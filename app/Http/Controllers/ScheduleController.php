<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Models\Estimate;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{
    /**
     * Fetch active work backlogs and calendar allocations to compile the layout arrays.
     */
    public function index()
    {
        // Gather scheduled job instances
        $schedules = Schedule::where('user_id', Auth::id())
            ->orderBy('scheduled_date', 'asc')
            ->get();

        // Gather approved but un-scheduled contract proposals to load select option boxes
        $approvedBacklog = Estimate::where('user_id', Auth::id())
            ->where('status', 'approved')
            ->get();

        return view('scheduler', compact('schedules', 'approvedBacklog'));
    }

    /**
     * Map a pipeline project entry to a real-world dispatch slot entry.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_name' => 'required|string|max:255',
            'project_title' => 'required|string|max:255',
            'scheduled_date' => 'required|date',
            'start_time' => 'nullable|string|max:50',
            'crew_notes' => 'nullable|string|max:1000',
        ]);

        Schedule::create([
            'user_id' => Auth::id(),
            'client_name' => $validated['client_name'],
            'project_title' => $validated['project_title'],
            'scheduled_date' => $validated['scheduled_date'],
            'start_time' => $validated['start_time'],
            'crew_notes' => $validated['crew_notes'],
            'status' => 'scheduled'
        ]);

        return redirect()->route('dashboard.scheduler')->with('status', 'Success! Job allocation slot reserved on your operational dispatch logs.');
    }

    /**
     * Remove a calendar slot row completely from active view states.
     */
    public function destroy($id)
    {
        $schedule = Schedule::where('user_id', Auth::id())->where('id', $id)->firstOrFail();
        $schedule->delete();

        return redirect()->route('dashboard.scheduler')->with('status', 'Dispatch slot removed cleanly from current schedule records.');
    }
}