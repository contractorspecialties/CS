<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Estimate;
use App\Models\Invoice;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class EstimateController extends Controller
{
    /**
     * Store a newly created estimate, compile its line items, and email it to the client.
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

        $estimate = null;

        // Line 32: The transaction block opens with a curly brace
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
        }); // Line 65: Closed perfectly with a curly brace and parenthesis

        if ($estimate && $estimate->client_email) {
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
                    ->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'))
                    ->html($emailHtmlBody);
            });
        }

        return redirect()->route('dashboard.estimates')->with('status', 'Estimate has been successfully generated, logged, and emailed to your client!');
    }

    /**
     * Evolve an authorized estimate directly into a formal tracking invoice with one touch.
     */
    public function convertToInvoice($id)
    {
        $estimate = Estimate::where('user_id', Auth::id())
            ->where('id', $id)
            ->with('items')
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
}