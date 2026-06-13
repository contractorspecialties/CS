<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Estimate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class HomeownerPortalController extends Controller
{
    /**
     * Render the secure, tokenized proposal page for customer review.
     */
    public function show($token)
    {
        // Intercept the unique token string safely
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
        $estimate = Estimate::where('secure_token', $token)->with('user')->firstOrFail();

        if ($estimate->status !== 'sent') {
            return response()->json([
                'success' => false,
                'message' => 'This project agreement has already been processed or updated.'
            ], 422);
        }

        $validated = $request->validate([
            'signature_data' => 'required|string', 
            'customer_notes' => 'nullable|string|max:1000'
        ]);

        // Complete database transaction sequence cleanly
        $estimate->update([
            'status' => 'approved',
            'customer_notes' => $validated['customer_notes'] ?? $estimate->customer_notes
        ]);

        // Stamping explicit owner parameters to comply with core database column tracking
        $estimate->attachments()->create([
            'user_id' => $estimate->user_id, // Resolves constraint 1364 unknown column default crash
            'file_path' => 'signatures/signed_' . $estimate->id . '_' . time() . '.txt',
            'is_visible_to_client' => false,
            'note' => 'Contract digitally authorized by client signature validation lock.'
        ]);

        // AUTOMATED DUAL EMAIL DISPATCH CHANNELS
        try {
            $contractorEmail = $estimate->user->email;
            $clientEmail = $estimate->client_email;
            $projectTitle = $estimate->project_title;
            $totalAmount = '$' . number_format($estimate->total_cents / 100, 2);

            Mail::send([], [], function ($message) use ($contractorEmail, $clientEmail, $projectTitle, $totalAmount, $estimate) {
                $message->to($contractorEmail);
                
                // Route CC to client folder track if email variable parameter exists
                if (!empty($clientEmail)) {
                    $message->cc($clientEmail);
                }

                $message->subject('Job Approved: ' . $projectTitle)
                    ->html('
                        <div style="font-family: sans-serif; padding: 24px; color: #334155; max-width: 550px; margin: 0 auto; background-color: #f8fafc; border-radius: 16px; border: 1px solid #e2e8f0;">
                            <span style="background-color: #dcfce7; color: #15803d; font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; padding: 4px 8px; border-radius: 6px;">Contract Sealed</span>
                            <h2 style="color: #0f172a; margin-top: 12px; margin-bottom: 4px; font-size: 20px; font-weight: 800; letter-spacing: -0.02em;">Proposal Approved Automatically</h2>
                            <p style="font-size: 13px; margin-top: 0; color: #64748b; font-weight: 500;">A digital signature lock has been successfully verified.</p>
                            
                            <div style="background-color: #ffffff; padding: 16px; border-radius: 12px; border: 1px solid #e2e8f0; margin-top: 20px; margin-bottom: 20px; text-align: left;">
                                <p style="font-size: 13px; margin: 4px 0;"><strong style="color: #0f172a;">Customer:</strong> ' . htmlspecialchars($estimate->client_name) . '</p>
                                <p style="font-size: 13px; margin: 4px 0;"><strong style="color: #0f172a;">Project Description:</strong> ' . htmlspecialchars($projectTitle) . '</p>
                                <p style="font-size: 13px; margin: 4px 0;"><strong style="color: #0f172a;">Total Investment:</strong> ' . $totalAmount . '</p>
                            </div>

                            <p style="font-size: 13px; line-height: 1.5; color: #475569;">This project has been added into your dispatch backlog bucket and is ready to be allocated onto your live worker schedule matrix boards.</p>
                            
                            <hr style="border: none; border-top: 1px solid #e2e8f0; margin: 24px 0;">
                            <p style="font-size: 10px; color: #94a3b8; text-align: center; margin: 0; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em;">Automated Message Via Contractor Specialties Engine</p>
                        </div>
                    ');
            });
        } catch (\Exception $e) {
            // Guard background transactions; protect client conversion sequence if mail channels jam
            Log::error('Automated job acceptance email dispatch failure tracking: ' . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'Project agreement confirmed! Work orders have been successfully routed to scheduling backlogs.'
        ]);
    }
}