<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Estimate;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EstimateController extends Controller
{
    /**
     * Store a newly created estimate and its line items in the database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_name' => 'required|string|max:255',
            'client_email' => 'nullable|email|max:255',
            'project_title' => 'required|string|max:255',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string|max:255',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($validated) {
            $subtotalCents = 0;
            $processedItems = [];

            foreach ($validated['items'] as $item) {
                $unitPriceCents = (int) round($item['unit_price'] * 100);
                $lineTotalCents = $unitPriceCents * (int) $item['quantity'];
                $subtotalCents += $lineTotalCents;

                $processedItems[] = [
                    'description' => $item['description'],
                    'item_type' => 'labor',
                    'quantity' => $item['quantity'],
                    'unit_price_cents' => $unitPriceCents,
                    'total_price_cents' => $lineTotalCents,
                ];
            }

            $estimate = Estimate::create([
                'user_id' => Auth::id(),
                'client_name' => $validated['client_name'],
                'client_email' => $validated['client_email'],
                'project_title' => $validated['project_title'],
                'subtotal_cents' => $subtotalCents,
                'tax_cents' => 0,
                'total_cents' => $subtotalCents,
                'status' => 'draft',
                'secure_token' => Str::random(40),
            ]);

            foreach ($processedItems as $processedItem) {
                $estimate->items()->create($processedItem);
            }
        ]);

        return redirect()->route('dashboard.estimates')->with('status', 'Estimate has been successfully generated and saved to your workspace records!');
    }

    /**
     * Render the passwordless proposal page for the homeowner.
     */
    public function showPublic($token)
    {
        // Fetch estimate alongside its itemized lines and parent company records
        $estimate = Estimate::with(['items', 'user.specialty'])
            ->where('secure_token', $token)
            ->firstOrFail();

        return view('public-estimate', compact('estimate'));
    }

    /**
     * Handle incoming homeowner status approvals or change requests.
     */
    public function updateStatus(Request $request, $token)
    {
        $estimate = Estimate::where('secure_token', $token)->firstOrFail();

        $validated = $request->validate([
            'action' => 'required|in:approve,decline'
        ]);

        $newStatus = ($validated['action'] === 'approve') ? 'approved' : 'declined';
        
        $estimate->update([
            'status' => $newStatus
        ]);

        $message = ($newStatus === 'approved') 
            ? 'Thank you! You have successfully approved this proposal. Your contractor has been notified and will coordinate next steps.' 
            : 'You have marked this proposal as declined. If adjustments are needed, please contact your service technician directly.';

        return redirect()->back()->with('status', $message);
    }
}