<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Crew;
use App\Models\Appointment;
use App\Models\Estimate;
use App\Models\RecurrenceTemplate;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DispatchController extends Controller
{
    /**
     * Render the visual dispatch board, blending single jobs and repeating schedules.
     */
    public function index(Request $request)
    {
        // Set the active week view window (defaults to today)
        $viewDate = $request->input('date') ? Carbon::parse($request->input('date')) : Carbon::today();
        $startOfWeek = $viewDate->copy()->startOfWeek(Carbon::MONDAY);
        $endOfWeek = $viewDate->copy()->endOfWeek(Carbon::SUNDAY);

        // 1. Fetch active crew tracks for this business owner
        $crews = Crew::where('user_id', Auth::id())
            ->where('is_active', true)
            ->get();

        // Automatically set up an "Owner Shell" if they are a solo operator
        if ($crews->isEmpty()) {
            $defaultCrew = Crew::create([
                'user_id' => Auth::id(),
                'name' => 'Owner Shell',
                'is_active' => true
            ]);
            $crews = collect([$defaultCrew]);
        }

        $crewIds = $crews->pluck('id');

        // 2. Pull standard, one-off jobs scheduled for this week
        $singleAppointments = Appointment::whereIn('crew_id', $crewIds)
            ->whereBetween('scheduled_start_at', [$startOfWeek, $endOfWeek])
            ->with(['estimate', 'crew'])
            ->get();

        // 3. Pull all repeating schedule templates assigned to these crews
        $recurrenceTemplates = RecurrenceTemplate::whereIn('crew_id', $crewIds)
            ->where('start_date', '<=', $endOfWeek)
            ->where(function ($query) use ($startOfWeek) {
                $query->whereNull('end_date')->orWhere('end_date', '>=', $startOfWeek);
            })
            ->with(['estimate', 'exceptions'])
            ->get();

        // 4. The Projection Engine: Loop through the week and calculate repeating slots
        $projectedAppointments = collect();

        for ($date = $startOfWeek->copy(); $date->lte($endOfWeek); $date->addDay()) {
            // ISO day of week format: 1 = Monday, 2 = Tuesday, etc.
            $currentDayOfWeek = $date->isoweekday(); 

            foreach ($recurrenceTemplates as $template) {
                if ($template->day_of_week === $currentDayOfWeek) {
                    
                    // Check if the manager logged a change or skip for this specific date
                    $exception = $template->exceptions
                        ->where('original_date', $date->format('Y-m-d'))
                        ->first();

                    // If it's a skip, drop it from the timeline projection completely
                    if ($exception && $exception->exception_type === 'skip') {
                        continue;
                    }

                    // Determine the start and end times for this specific occurrence
                    $start = $date->copy()->setTime(8, 0); // Default to an 8:00 AM start parameter
                    $end = $date->copy()->setTime(10, 0);

                    if ($exception && $exception->exception_type === 'reschedule') {
                        $start = $exception->rescheduled_start_at;
                        $end = $exception->rescheduled_end_at;
                    }

                    // Build a temporary runtime object matching our layout parameters
                    $projectedAppointments->push((object)[
                        'id' => 'recurring_' . $template->id . '_' . $date->format('Ymd'),
                        'estimate_id' => $template->estimate_id,
                        'crew_id' => $template->crew_id,
                        'formatted_payout' => '$' . number_format($template->payout_cents / 100, 2),
                        'scheduled_start_at' => $start,
                        'scheduled_end_at' => $end,
                        'estimate' => $template->estimate,
                        'is_recurring' => true
                    ]);
                }
            }
        }

        // 5. Combine single jobs and repeating projections into one master list group
        $allAppointments = collect($singleAppointments)
            ->concat($projectedAppointments)
            ->groupBy('crew_id');

        // 6. Gather unscheduled backlog bucket (approved quotes with no calendar tracking yet)
        $backlogEstimates = Estimate::where('user_id', Auth::id())
            ->where('status', 'approved')
            ->whereDoesntHave('appointments')
            ->whereDoesntHave('recurrenceTemplates')
            ->get();

        return view('dispatch.index', compact(
            'crews', 
            'allAppointments', 
            'backlogEstimates', 
            'viewDate', 
            'startOfWeek', 
            'endOfWeek'
        ));
    }

    /**
     * Handle drag-and-drop allocations from the board interface.
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

        $estimate = Estimate::where('user_id', Auth::id())->where('id', $validated['estimate_id'])->firstOrFail();
        $crew = Crew::where('user_id', Auth::id())->where('id', $validated['crew_id'])->firstOrFail();

        $payoutCents = $request->filled('payout_rate') ? (int) round($validated['payout_rate'] * 100) : 0;

        Appointment::create([
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
            'message' => 'Job successfully scheduled and assigned to ' . $crew->name
        ]);
    }
}