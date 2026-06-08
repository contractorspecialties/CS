<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class SuperAdminController extends Controller
{
    /**
     * Enforce administrative boundary protection checks.
     */
    protected function gatekeeper()
    {
        if (!auth()->check() || !auth()->user()->is_admin) {
            abort(403, 'Unauthorized System Access Protocol.');
        }
    }

    /**
     * Display the Master Telemetry Deck for all system tenants.
     */
    public function index()
    {
        $this->gatekeeper();

        // Only count relationships that actively exist in the database schema layer
        $clients = User::where('is_admin', false)
            ->withCount(['clients'])
            ->orderBy('created_at', 'desc')
            ->get();

        $systemMetrics = [
            'total_contractors'  => $clients->count(),
            'active_gcs'         => $clients->where('is_gc', true)->count(),
            'standard_subs'      => $clients->where('is_gc', false)->count(),
            'restricted_nodes'   => $clients->where('is_restricted', true)->count(),
            'total_quotes_sent'  => 0, // Safe static fallback placeholder
            'total_appointments' => 0, // Safe static fallback placeholder
        ];

        return view('admin.command-center.index', compact('clients', 'systemMetrics'));
    }

    /**
     * Deep-dive audit view for an isolated contractor entity node.
     */
    public function showClient($id)
    {
        $this->gatekeeper();

        $client = User::where('is_admin', false)
            ->with(['clients'])
            ->findOrFail($id);

        $themePayload = [
            'theme_color'     => $client->theme_color ?? '#0F2D5A',
            'company_website' => $client->company_website,
            'logo_path'       => $client->logo_path,
            'slogan'          => $client->slogan,
        ];

        return view('admin.command-center.show', compact('client', 'themePayload'));
    }

    /**
     * Universal Operational Circuit Breaker.
     */
    public function toggleStatus($id)
    {
        $this->gatekeeper();

        $client = User::findOrFail($id);
        $client->is_restricted = !$client->is_restricted;
        $client->save();

        $statusMessage = $client->is_restricted 
            ? "Operational routing for {$client->business_name} has been suspended immediately." 
            : "Operational routing for {$client->business_name} has been fully restored.";

        return redirect()->back()->with('status', $statusMessage);
    }

    /**
     * Live Sheet Styling Override Adjustments.
     */
    public function updateTheme(Request $request, $id)
    {
        $this->gatekeeper();

        $client = User::findOrFail($id);

        $validated = $request->validate([
            'theme_color'     => 'required|string|max:7',
            'slogan'          => 'nullable|string|max:255',
            'company_website' => 'nullable|url|max:255',
        ]);

        $client->update([
            'theme_color'     => $validated['theme_color'],
            'slogan'          => $validated['slogan'],
            'company_website' => $validated['company_website'],
        ]);

        return redirect()->back()->with('status', "Visual theme overrides committed successfully for {$client->business_name}.");
    }
}