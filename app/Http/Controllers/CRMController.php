<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use Illuminate\Support\Facades\Auth;

class CRMController extends Controller
{
    /**
     * Render the centralized CRM customer account directory tracking array.
     */
    public function index()
    {
        // Query accounts matching the current contractor, computing critical CPP financial metrics instantly
        $clients = Client::where('user_id', Auth::id())
            ->withCount('estimates')
            ->withSum(['invoices as ltv_cents' => function ($query) {
                $query->where('status', 'paid');
            }], 'total_cents')
            ->with(['estimates' => function ($query) {
                $query->latest()->take(1); // Grabs the most recent project description asset string
            }])
            ->get()
            ->map(function ($client) {
                // Flatten structural relations down for lightweight JSON hydration arrays
                $latestEstimate = $client->estimates->first();
                $client->latest_project = $latestEstimate ? $latestEstimate->project_title : 'No Active Tasks';
                $client->ltv_cents = $client->ltv_cents ?? 0;
                return $client;
            });

        return view('crm', compact('clients'));
    }
}