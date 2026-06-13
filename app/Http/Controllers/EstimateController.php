<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Estimate;
use App\Models\Invoice;
use App\Models\Attachment;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class EstimateController extends Controller
{
    /**
     * Store a newly created estimate and compile its line items.
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
            'photos' => 'nullable|array',
            'photos.*' => 'nullable|image|max:12288', // High-res phone cameras up to 12MB
        ]);

        $estimate = null;

        DB::transaction(function () use ($validated, &$estimate) {
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
                'status' => 'sent',
                'secure_token' => Str::random(40),
            ]);

            foreach ($processedItems as $processedItem) {
                $estimate->items()->create($processedItem);
            }
        });

        // Track file attachments if uploaded during the core creation step
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $file) {
                $storageFolder = 'attachments';
                $fileName = $storageFolder . '/' . Str::random(40) . '.' . $file->getClientOriginalExtension();
                
                Storage::disk('public')->putFileAs($storageFolder, $file, basename($fileName));

                $estimate->attachments()->create([
                    'user_id' => Auth::id(),
                    'file_path' => $fileName,
                    'file_type' => 'markup',
                    'is_visible_to_client' => true
                ]);
            }

            // Route cleanly to the canvas studio to apply precision markup lines over the images
            return redirect()->route('estimates.markup', $estimate->id)->with('status', 'Estimate drafted! Opening Markup Studio to apply project layout notes.');
        }

        // If there are no initial photos to draw on, refresh model keys and email the client instantly
        if ($estimate && $estimate->client_email) {
            $this->sendEstimateNotification($estimate);
        }

        return redirect()->route('dashboard.estimates')->with('status', 'Estimate has been successfully generated, logged, and emailed to your client!');
    }

    /**
     * Render the Media Markup Studio layout frame populated with parent quote identifiers.
     */
    public function showMarkup($id)
    {
        $estimate = Estimate::where('user_id', Auth::id())
            ->where('id', $id)
            ->with('attachments')
            ->firstOrFail();

        return view('markup-studio', compact('estimate'));
    }

    /**
     * Parse binary sketch configurations and append the marked-up file to the client proposal gallery.
     */
    public function storeMarkup(Request $request, $id)
    {
        $estimate = Estimate::where('user_id', Auth::id())->where('id', $id)->firstOrFail();

        $request->validate([
            'markup_image' => 'required|image|max:16384',
            'is_public' => 'nullable'
        ]);

        if ($request->hasFile('markup_image')) {
            $file = $request->file('markup_image');
            $storageFolder = 'attachments';
            $fileName = $storageFolder . '/' . Str::random(40) . '.jpg';

            Storage::disk('public')->putFileAs($storageFolder, $file, basename($fileName));

            $estimate->attachments()->create([
                'user_id' => Auth::id(),
                'file_path' => $fileName,
                'file_type' => 'markup',
                'is_visible_to_client' => true
            ]);

            // Fire the notification email now that the contractor has finished applying their visual notes
            if ($estimate->client_email) {
                $this->sendEstimateNotification($estimate);
            }

            session()->flash('status', 'Success! Marked-up photo added and proposal emailed to client.');

            return response()->json([
                'success' => true,
                'redirect' => route('dashboard.estimates')
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Error: Failed to process inbound drawing streams.'
        ], 422);
    }

    /**
     * Toggle client visibility permissions on specific media rows.
     */
    public function toggleAttachmentVisibility(Request $request, $id)
    {
        $attachment = Attachment::where('user_id', Auth::id())->where('id', $id)->firstOrFail();
        $attachment->update([
            'is_visible_to_client' => !$attachment->is_visible_to_client
        ]);

        return redirect()->back()->with('status', 'Photo visibility configuration updated successfully.');
    }

    /**
     * Evolve an authorized estimate directly into a formal tracking invoice with one touch.
     */
    public function convertToInvoice($id)
    {
        $estimate = Estimate::where('user_id', Auth::id())
            ->where('id', $id)
            ->with(['items', 'attachments'])
            ->firstOrFail();

        $invoice = null;

        DB::transaction(function () use ($estimate, &$invoice) {
            $invoice = Invoice::create([
                'user_id' => Auth::id(),
                'estimate_id' => $estimate->id,
                'client_name' => $estimate->client_name,
                'client_email' => $estimate->client_email,
                'project_title' => $estimate->project_title,
                'project_description' => $estimate->project_description,
                'subtotal_cents' => $estimate->subtotal_cents,
                'tax_cents' => $estimate->tax_cents,
                'total_cents' => $estimate->total_cents,
                'status' => 'draft',
                'secure_token' => Str::random(40),
            ]);

            foreach ($estimate->items as $item) {
                $invoice->items()->create([
                    'description' => $item->description,
                    'item_type' => $item->item_type,
                    'quantity' => $item->quantity,
                    'unit_price_cents' => $item->unit_price_cents,
                    'total_price_cents' => $item->total_price_cents,
                ]);
            }

            foreach ($estimate->attachments as $attachment) {
                $invoice->attachments()->create([
                    'user_id' => $attachment->user_id,
                    'file_path' => $attachment->file_path,
                    'file_type' => $attachment->file_type,
                    'is_visible_to_client' => $attachment->is_visible_to_client,
                    'note' => $attachment->note,
                ]);
            }

            $estimate->update(['status' => 'invoiced']);
        });

        return redirect()->route('dashboard.estimates')->with('status', 'Success! Proposal has been seamlessly converted into an active invoice record.');
    }

    /**
     * Push a target log sequence safely to an archived hidden status state.
     */
    public function archive($id)
    {
        $estimate = Estimate::where('user_id', Auth::id())->where('id', $id)->firstOrFail();
        $estimate->update(['status' => 'archived']);

        return redirect()->route('dashboard.estimates')->with('status', 'The project proposal record has been successfully moved to your history archives.');
    }

    /**
     * Purge a target proposal row sequence entirely from storage.
     */
    public function destroy($id)
    {
        $estimate = Estimate::where('user_id', Auth::id())->where('id', $id)->firstOrFail();
        $estimate->delete();

        return redirect()->route('dashboard.estimates')->with('status', 'The specified quote has been permanently deleted from your profile registry.');
    }

    /**
     * Render the passwordless proposal page for the homeowner.
     */
    public function showPublic($token)
    {
        $estimate = Estimate::with(['items', 'user.specialty', 'attachments'])
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
            'action' => 'required|in:approve,decline',
            'customer_notes' => 'nullable|string|max:2000'
        ]);

        $newStatus = ($validated['action'] === 'approve') ? 'approved' : 'declined';
        
        $estimate->update([
            'status' => $newStatus,
            'customer_notes' => $validated['customer_notes']
        ]);

        return redirect()->back()->with('status', 'Response registered successfully.');
    }

    /**
     * Unified clean notification dispatcher engine. Fires emails safely without locking UI transitions.
     */
    protected function sendEstimateNotification(Estimate $estimate)
    {
        // Explicitly load relations fresh to bypass active Eloquent cached memory strings
        $estimate->load('items', 'user');

        try {
            $contractorName = $estimate->user->business_name ?? $estimate->user->name;
            $publicReviewUrl = route('estimates.public.show', $estimate->secure_token);
            
            $itemRowsHtml = '';
            foreach ($estimate->items as $item) {
                $formattedLineTotal = number_format($item->total_price_cents / 100, 2);
                $itemRowsHtml .= '<tr>'
                    . '<td style="padding: 12px; border-bottom: 1px solid #E2E8F0; font-size: 14px; color: #2D3748;">'
                    . '<strong>' . e($item->description) . '</strong><br>'
                    . '<span style="font-size: 12px; color: #718096;">Qty ' . e($item->quantity) . '</span>'
                    . '</td>'
                    . '<td style="padding: 12px; border-bottom: 1px solid #E2E8F0; font-size: 14px; text-align: right; color: #1A202C; font-weight: bold;">'
                    . '$' . $formattedLineTotal
                    . '</td>'
                    . '</tr>';
            }

            $formattedGrandTotal = number_format($estimate->total_cents / 100, 2);

            $emailHtmlBody = '<div style="font-family: Arial, sans-serif; background-color: #F8FAFC; padding: 30px; text-align: center;">'
                . '<div style="max-width: 550px; margin: 0 auto; background: #FFFFFF; padding: 32px; border-radius: 20px; border: 1px solid #E2E8F0; text-align: left; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">'
                . '<span style="font-size: 10px; font-weight: 800; text-transform: uppercase; color: #718096; display: block; margin-bottom: 4px;">Project Estimate Reference</span>'
                . '<h3 style="color: #0F2D5A; font-size: 20px; font-weight: 800; margin: 0 0 16px 0;">New Proposal from ' . e($contractorName) . '</h3>'
                . '<p style="color: #4A5568; font-size: 14px; line-height: 1.5; margin-bottom: 20px;">'
                . 'Hello ' . e($estimate->client_name) . ', a digital project estimate has been prepared for your review regarding: <strong>' . e($estimate->project_title) . '</strong>.'
                . '</p>'
                . '<table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">'
                . '<thead>'
                . '<tr style="background-color: #F8FAFC;">'
                . '<th style="text-align: left; padding: 12px; font-size: 11px; font-weight: 800; text-transform: uppercase; color: #718096; border-bottom: 2px solid #E2E8F0;">Service Description</th>'
                . '<th style="text-align: right; padding: 12px; font-size: 11px; font-weight: 800; text-transform: uppercase; color: #718096; border-bottom: 2px solid #E2E8F0;">Total</th>'
                . '</tr>'
                . '</thead>'
                . '<tbody>'
                . $itemRowsHtml
                . '<tr>'
                . '<td style="padding: 16px 12px; font-size: 14px; font-weight: bold; color: #0F2D5A;">Grand Total</td>'
                . '<td style="padding: 16px 12px; font-size: 18px; font-weight: 900; color: #0F2D5A; text-align: right;">$' . $formattedGrandTotal . '</td>'
                . '</tr>'
                . '</tbody>'
                . '</table>'
                . '<div style="margin-top: 28px; text-align: center;">'
                . '<a href="' . e($publicReviewUrl) . '" style="display: inline-block; background-color: #0F2D5A; color: #FFFFFF; font-weight: bold; text-decoration: none; padding: 14px 28px; border-radius: 10px; font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px;">Review & Accept Proposal</a>'
                . '</div>'
                . '</div>'
                . '</div>';

            Mail::send([], [], function ($message) use ($estimate, $contractorName, $emailHtmlBody) {
                $message->to($estimate->client_email)
                    ->subject("Project Proposal from {$contractorName} | #EST-00{$estimate->id}")
                    ->html($emailHtmlBody);
            });
        } catch (\Exception $e) {
            Log::error('Frictionless estimate notification mailing exception caught: ' . $e->getMessage());
        }
    }
}