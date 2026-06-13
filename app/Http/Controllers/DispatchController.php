<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Crew;
use App\Models\Appointment;
use App\Models\Estimate;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DispatchController extends Controller
{
    /**
     * Render the Master visual Dispatch Calendar matrix interface grid.
     */
    public function index(Request $request)
    {
        // Establish timezone-aware boundaries for the active week view window
        $viewDate = $request->input('date') ? Carbon::parse($request->input('date')) : Carbon::today();
        $startOfWeek = $viewDate->copy()->startOfWeek(Carbon::MONDAY);
        $endOfWeek = $viewDate->copy()->endOfWeek(Carbon::SUNDAY);

        // Fetch crew assets mapping to the authenticated manager context
        $crews = Crew::where('user_id', Auth::id())
            ->where('is_active', true)
            ->get();

        // Handle the "Resource of One" automatic fallback wrapper seamlessly
        if ($crews->isEmpty()) {
            $defaultCrew = Crew::create([
                'user_id' => Auth::id(),
                'name' => 'Owner Shell',
                'is_active' => true
            ]);
            $crews = collect([$defaultCrew]);
        }

        // Gather all live appointments assigned to this business's crews within the active timeframe
        $appointments = Appointment::whereIn('crew_id', $crews->pluck('id'))
            ->whereBetween('scheduled_start_at', [$startOfWeek, $endOfWeek])
            ->with(['estimate', 'crew'])
            ->get()
            ->groupBy('crew_id');

        // Compile the unassigned backlog bucket: approved estimates that lack scheduled appointments
        $backlogEstimates = Estimate::where('user_id', Auth::id())
            ->where('status', 'approved')
            ->whereDoesntHave('appointments')
            ->get();

        return view('dispatch.index', compact(
            'crews', 
            'appointments', 
            'backlogEstimates', 
            'viewDate', 
            'startOfWeek', 
            'endOfWeek'
        ));
    }

    /**
     * Handle the asynchronous drag-and-drop allocation payloads sent via the Alpine frontend interface.
     */
    public function assign(Request $request)
    {
        $validated = $request->validate([
            'estimate_id' => 'required|exists:estimates,id',
            'crew_id' => 'required|exists:sc_crews,id',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'payout_rate' => 'nullable|numeric|min:0'
        ]);

        // Guard authentication multi-tenant access parameters explicitly
        $estimate = Estimate::where('user_id', Auth::id())->where('id', $validated['estimate_id'])->firstOrFail();
        $crew = Crew::where('user_id', Auth::id())->where('id', $validated['crew_id'])->firstOrFail();

        // Enforce the front-end collision checkpoint: verify the target crew is not double-booked
        $collisionExists = Appointment::where('crew_id', $crew->id)
            ->where(function ($query) use ($validated) {
                $query->whereBetween('scheduled_start_at', [$validated['start_time'], $validated['end_time']])
                      ->orWhereBetween('scheduled_end_at', [$validated['start_time'], $validated['end_time']]);
            })->exists();

        if ($collisionExists) {
            return response()->json([
                'success' => false, 
                'message' => 'Scheduling Conflict: This target crew is already assigned to a live operation within this time window.'
            ], 422);
        }

        // Convert the validated raw decimal input rate cleanly into atomic database cents
        $payoutCents = $request->filled('payout_rate') ? (int) round($validated['payout_rate'] * 100) : 0;

        // Stamp the formal independent field appointment work order into the registry
        $appointment = Appointment::create([
            'estimate_id' => $estimate->id,
            'crew_id' => $crew->id,
            'payout_type' => 'flat',
            'payout_cents' => $payoutCents,
            'status' => 'scheduled',
            'scheduled_start_at' => $validated['start_time'],
            'scheduled_end_at' => $validated['end_time']
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Work order successfully dispatched and allocated to ' . $crew->name
        ]);
    }
}