<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class InvoiceController extends Controller
{
    /**
     * Render the core tracking panel for all contractor invoices.
     */
    public function index()
    {
        $invoices = Invoice::where('user_id', Auth::id())
            ->where('status', '!=', 'archived')
            ->latest()
            ->get();

        return view('invoices', compact('invoices'));
    }

    /**
     * Update an outstanding draft invoice's high-level customer details before issuance.
     */
    public function update(Request $request, $id)
    {
        $invoice = Invoice::where('user_id', Auth::id())
            ->where('id', $id)
            ->where('status', 'draft')
            ->firstOrFail();

        $validated = $request->validate([
            'client_name' => 'required|string|max:255',
            'client_email' => 'nullable|email|max:255',
            'project_title' => 'required|string|max:255',
        ]);

        $invoice->update($validated);

        return redirect()->route('dashboard.invoices')->with('status', 'Invoice draft details successfully updated.');
    }

    /**
     * Mark an active invoice as paid to settle account receivables balances.
     */
    public function markAsPaid($id)
    {
        $invoice = Invoice::where('user_id', Auth::id())->where('id', $id)->firstOrFail();
        
        $invoice->update([
            'status' => 'paid',
            'amount_paid_cents' => $invoice->total_cents
        ]);

        return redirect()->route('dashboard.invoices')->with('status', 'Invoice log marked successfully as Paid.');
    }

    /**
     * Push a target invoice log safely to an archived state.
     */
    public function archive($id)
    {
        $invoice = Invoice::where('user_id', Auth::id())->where('id', $id)->firstOrFail();
        $invoice->update(['status' => 'archived']);

        return redirect()->route('dashboard.invoices')->with('status', 'Invoice record successfully archived.');
    }

    /**
     * Permanently delete a specified invoice profile log.
     */
    public function destroy($id)
    {
        $invoice = Invoice::where('user_id', Auth::id())->where('id', $id)->firstOrFail();
        $invoice->delete();

        return redirect()->route('dashboard.invoices')->with('status', 'Invoice permanently purged from system memory.');
    }
}