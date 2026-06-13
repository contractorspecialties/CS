@extends('layouts.app')

@section('title', 'Today\'s Work Orders')

@section('content')
<div class="w-full max-w-md mx-auto px-2 py-3 space-y-4 text-left"
     x-data="workerCockpit()">
    
    {{-- FIELD HEADER --}}
    <div class="bg-slate-900 text-white p-4 rounded-2xl border border-slate-800 shadow-md flex items-center justify-between">
        <div>
            <span class="text-[9px] bg-[#FFC32D] text-slate-950 font-black uppercase tracking-widest px-2 py-0.5 rounded-md">Field Active</span>
            <h1 class="text-base font-black tracking-tight mt-1">My Daily Schedule</h1>
            <p class="text-[10px] text-slate-400 font-bold uppercase mt-0.5">{{ \Carbon\Carbon::now()->format('F d, Y') }}</p>
        </div>
        <div class="text-right">
            <span class="text-[9px] font-black text-slate-400 uppercase tracking-wider block">Assigned To</span>
            <span class="text-xs font-black text-white truncate max-w-[120px] block">👷 {{ auth()->user()->name }}</span>
        </div>
    </div>

    {{-- LOOP THROUGH DISPATCHED WORK ORDERS --}}
    @forelse($appointments as $appt)
        <div class="bg-white rounded-2xl border-2 border-slate-900 p-4 shadow-md space-y-3.5 relative overflow-hidden">
            
            {{-- TIME & WORK ORDER DECK --}}
            <div class="flex items-center justify-between border-b border-slate-100 pb-2.5">
                <div class="flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span class="text-[11px] font-black text-slate-900 uppercase tracking-wide">
                        Arrival Time: {{ $appt->scheduled_start_at->format('h:i A') }}
                    </span>
                </div>
                <span class="text-[9px] font-mono font-black text-slate-400 bg-slate-50 px-2 py-0.5 rounded-md border border-slate-100">
                    ID #{{ $appt->id }}
                </span>
            </div>

            {{-- JOB SPECIFIC PARAMETERS --}}
            <div>
                <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest block">Job Description & Scope</span>
                <h3 class="text-sm font-black text-slate-900 tracking-tight mt-0.5 leading-snug">
                    {{ $appt->estimate->project_title }}
                </h3>
                <p class="text-[11px] font-bold text-slate-500 mt-1 leading-relaxed">
                    {{ $appt->estimate->project_description ?? 'No extra project instructions provided by manager.' }}
                </p>
            </div>

            {{-- LOGISTICS DECK (ADDRESS LINK) --}}
            <div class="bg-slate-50 rounded-xl p-2.5 border border-slate-100 flex items-center justify-between gap-3">
                <div class="min-w-0">
                    <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest block">Job Site Location</span>
                    <p class="text-xs font-black text-slate-800 truncate mt-0.5">
                        {{ $appt->estimate->client_name }}
                    </p>
                </div>
                <a href="https://maps.google.com/?q={{ urlencode($appt->estimate->client_name) }}" 
                   target="_blank" 
                   class="bg-[#0F2D5A] hover:bg-[#1E3C5A] text-white text-[10px] font-black uppercase tracking-wider px-3 py-2 rounded-lg flex items-center gap-1 flex-shrink-0 transition">
                    🗺️ Open Map
                </a>
            </div>

            {{-- BACKGROUND SITE CONCURRENCY CHECK (SHARED DRIVEWAY ALERTS) --}}
            @if(isset($sharedSites[$appt->id]))
                <div class="flex items-center gap-2 bg-amber-50 border border-amber-200 p-2.5 rounded-xl">
                    <span class="text-sm flex-shrink-0">⚠️</span>
                    <p class="text-[10px] font-bold text-amber-900 leading-tight">
                        <span class="font-black uppercase tracking-wide block text-amber-950">Shared Site Warning:</span>
                        Another trade team (<span class="font-black text-slate-900">{{ $sharedSites[$appt->id]->crew->name }}</span>) is assigned to this house layout today. Coordinate vehicles cleanly.
                    </p>
                </div>
            @endif

            {{-- PROGRESS CHECKLIST --}}
            @if($appt->checkpoints->isNotEmpty())
                <div class="space-y-2 border-t border-b border-slate-100 py-3">
                    <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest block">Required Job Steps</span>
                    <div class="space-y-1.5">
                        @foreach($appt->checkpoints as $check)
                            <label class="flex items-start gap-2.5 text-xs font-bold text-slate-700 cursor-pointer select-none">
                                <input type="checkbox" 
                                       class="rounded text-[#0F2D5A] focus:ring-0 bg-slate-100 border-slate-300 h-4 w-4 mt-0.5 accent-[#FFC32D]"
                                       {{ $check->is_completed ? 'checked' : '' }}
                                       @change="toggleCheckpoint({{ $check->id }}, $event.target.checked)">
                                <span :class="{'line-through text-slate-300': {{ $check->is_completed ? 'true' : 'false' }} }" class="leading-tight pt-0.5">
                                    {{ $check->title }}
                                </span>
                            </label>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- THE FINANCIAL FIREWALL WALLET WIDGET --}}
            <div class="bg-slate-900 rounded-xl p-3 flex items-center justify-between text-white border border-slate-800">
                <div>
                    <span class="text-[8px] font-black text-[#FFC32D] uppercase tracking-widest block">My Payout For This Job</span>
                    <span class="text-[10px] font-bold text-slate-400 block mt-0.5">Paid out once manager approves photos</span>
                </div>
                <div class="text-right flex-shrink-0">
                    <span class="text-base font-black text-[#FFC32D] font-mono tracking-tight">
                        {{ $appt->formatted_payout }}
                    </span>
                </div>
            </div>

            {{-- HIGH-VELOCITY IMAGE CAPTURE PORT --}}
            <form action="{{ route('worker.upload-photo', $appt->id) }}" method="POST" enctype="multipart/form-data" class="pt-1">
                @csrf
                <label class="w-full bg-[#0F2D5A] hover:bg-[#1E3C5A] text-white text-xs font-black uppercase tracking-widest py-3 rounded-xl transition text-center block shadow-sm cursor-pointer select-none transform active:scale-[0.99]">
                    📸 Take Job Site Photo
                    <input type="file" name="photo" accept="image/*" capture="environment" class="hidden" @change="$制造form.submit()">
                </label>
            </form>

        </div>
    @empty
        <div class="border-2 border-dashed border-slate-300 bg-white rounded-2xl p-10 text-center text-slate-400 space-y-2">
            <span class="text-3xl block">📋</span>
            <h4 class="text-xs font-black text-slate-800 uppercase tracking-wider">No Scheduled Jobs</h4>
            <p class="text-[10px] font-bold text-slate-400 leading-relaxed">Your routing sequence is clear today. Check back if the manager assigns a new work order card.</p>
        </div>
    @endforelse

</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('workerCockpit', () => ({
        toggleCheckpoint(checkpointId, isChecked) {
            fetch(`/worker/checkpoint/${checkpointId}/toggle`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ completed: isChecked })
            })
            .then(response => response.json())
            .catch(error => {
                console.error('Checklist update failure:', error);
                alert('Connection lost: Step progress could not save to server.');
            });
        }
    }));
});
</script>
@endsection