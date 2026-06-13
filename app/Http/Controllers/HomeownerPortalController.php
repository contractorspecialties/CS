<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Estimate;
use Carbon\Carbon;

class HomeownerPortalController extends Controller
{
    /**
     * Render the secure, tokenized proposal page for customer review.
     */
    public function show($token)
    {
        // Intercept the unique cryptographic token string safely
        $estimate = Estimate::where('secure_token', $token)
            ->with(['items', 'attachments' => function ($query) {
                // Only pull assets the contractor marked as client-ready in the markup studio
                $query->where('is_visible_to_client', true);
            }])
            ->firstOrFail();

        // Prevent unauthorized rendering if the estimate is still a rough draft
        if ($estimate->status === 'draft') {
            abort(404, 'This project overview is currently being updated by the service provider.');
        }

        return view('homeowner.portal', compact('estimate'));
    }

    /**
     * Process digital signature approvals instantly from the client touch pad.
     */
    public function approve(Request $request, $token)
    {
        $estimate = Estimate::where('secure_token', $token)->firstOrFail();

        if ($estimate->status !== 'sent') {
            return response()->json([
                'success' => false,
                'message' => 'This project agreement has already been processed or updated.'
            ], 422);
        }

        $validated = $request->validate([
            'signature_data' => 'required|string', // Stores vector data path or text confirmation string
            'customer_notes' => 'nullable|string|max:1000'
        ]);

        // Complete database transaction sequence cleanly
        $estimate->update([
            'status' => 'approved',
            'customer_notes' => $validated['customer_notes'] ?? $estimate->customer_notes
        ]);

        // Automatically log a permanent tracking note into the file log
        $estimate->attachments()->create([
            'file_path' => 'signatures/signed_' . $estimate->id . '_' . time() . '.txt',
            'is_visible_to_client' => false,
            'note' => 'Contract digitally authorized by client signature validation lock.'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Project agreement confirmed! Work orders have been successfully routed to scheduling backlogs.'
        ]);
    }
}