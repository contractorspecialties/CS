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

        // Use a database transaction to ensure either EVERYTHING saves perfectly, or nothing does
        DB::transaction(function () use ($validated) {
            $subtotalCents = 0;

            // 1. Pre-calculate totals and handle decimal-to-cents conversion
            $processedItems = [];
            foreach ($validated['items'] as $item) {
                $unitPriceCents = (int) round($item['unit_price'] * 100);
                $lineTotalCents = $unitPriceCents * (int) $item['quantity'];
                $subtotalCents += $lineTotalCents;

                $processedItems[] = [
                    'description' => $item['description'],
                    'item_type' => 'labor', // Defaulting to labor for base engine validation
                    'quantity' => $item['quantity'],
                    'unit_price_cents' => $unitPriceCents,
                    'total_price_cents' => $lineTotalCents,
                ];
            }

            // For V1, we'll keep tax at 0% unless you want to add a tax multiplier configuration row later
            $totalCents = $subtotalCents; 

            // 2. Create the parent Estimate record
            $estimate = Estimate::create([
                'user_id' => Auth::id(),
                'client_name' => $validated['client_name'],
                'client_email' => $validated['client_email'],
                'project_title' => $validated['project_title'],
                'subtotal_cents' => $subtotalCents,
                'tax_cents' => 0,
                'total_cents' => $totalCents,
                'status' => 'draft',
                'secure_token' => Str::random(40),
            ]);

            // 3. Save all child line items bound to this estimate ID
            foreach ($processedItems as $processedItem) {
                $estimate->items()->create($processedItem);
            }
        });

        return redirect()->route('dashboard')->with('status', 'Estimate has been successfully generated and saved to your workspace records!');
    }
}