<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Crew;
use App\Models\Appointment;
use App\Models\Checkpoint;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class WorkerController extends Controller
{
    /**
     * Render today's streamlined route cockpit for the worker phone view.
     */
    public function index()
    {
        $today = Carbon::today();

        // Find the worker's crew track or automatically grab the owner shell fallback
        $crew = Crew::where('user_id', Auth::id())->first();

        if (!$crew) {
            $crew = Crew::create([
                'user_id' => Auth::id(),
                'name' => 'Owner Shell',
                'is_active' => true
            ]);
        }

        // Gather all work orders assigned to this crew track for today
        $appointments = Appointment::where('crew_id', $crew->id)
            ->whereDate('scheduled_start_at', $today)
            ->with(['estimate', 'checkpoints'])
            ->orderBy('scheduled_start_at', 'asc')
            ->get();

        // Concurrency Scan Engine: Find driveway overlaps at the same address today
        $sharedSites = [];
        
        foreach ($appointments as $appt) {
            $overlap = Appointment::where('estimate_id', $appt->estimate_id)
                ->where('crew_id', '!=', $crew->id)
                ->whereDate('scheduled_start_at', $today)
                ->with('crew')
                ->first();

            if ($overlap) {
                // Map the shared site alert data to this specific appointment card
                $sharedSites[$appt->id] = $overlap;
            }
        }

        return view('worker.dashboard', compact('appointments', 'sharedSites'));
    }

    /**
     * Update checkbox status records instantly from the job site checklist.
     */
    public function toggleCheckpoint(Request $request, $id)
    {
        $validated = $request->validate([
            'completed' => 'required|boolean'
        ]);

        $checkpoint = Checkpoint::findOrFail($id);
        
        // Ensure the worker has security rights to modify this step record
        $appointment = $checkpoint->appointment;
        $crew = Crew::where('user_id', Auth::id())->first();

        if ($appointment->crew_id !== $crew->id) {
            return response()->json(['success' => false, 'message' => 'Security Error: Unauthorized operation.'], 403);
        }

        $checkpoint->update([
            'is_completed' => $validated['completed']
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Job milestone step successfully updated.'
        ]);
    }

    /**
     * Process high-velocity on-site camera captures directly into the project attachments file.
     */
    public function uploadPhoto(Request $request, $id)
    {
        $request->validate([
            'photo' => 'required|image|max:12288' // Generous 12MB ceiling for high-res phone cameras
        ]);

        $appointment = Appointment::findOrFail($id);
        $crew = Crew::where('user_id', Auth::id())->first();

        if ($appointment->crew_id !== $crew->id) {
            abort(403, 'Security Error: Unauthorized operation.');
        }

        if ($request->hasFile('photo')) {
            // Store file securely inside the public disk using explicit directory parameters
            $path = $request->file('photo')->store('attachments', 'public');

            // Wire the asset path to the parent estimate model using the system's core trait mechanism
            $appointment->estimate->attachments()->create([
                'file_path' => $path,
                'is_visible_to_client' => true, // Automatically post to the homeowner portal view deck
                'uploaded_by_worker_id' => Auth::id()
            ]);
        }

        return redirect()->back()->with('success', 'Job site photo recorded successfully.');
    }
}