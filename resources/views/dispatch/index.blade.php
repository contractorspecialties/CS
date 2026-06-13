@extends('layouts.app')

@section('title', 'Dispatch Command Matrix')

@section('content')
<div class="space-y-6 w-full min-w-0 max-w-full overflow-hidden text-left" 
     x-data="dispatchMatrix({
         assignUrl: '{{ route('dashboard.dispatch.assign') }}',
         csrf: '{{ csrf_token() }}'
     })">
    
    {{-- SYSTEM CONTROL & PIPELINE HEADER --}}
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 bg-slate-900 text-white p-6 rounded-2xl border border-slate-800 shadow-xl w-full min-w-0">
        <div>
            <span class="bg-[#FFC32D] text-slate-950 text-[9px] font-black uppercase tracking-widest px-2.5 py-1 rounded-md">Live Command Center</span>
            <h1 class="text-xl font-black tracking-tight mt-2">Visual Dispatch Matrix</h1>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mt-0.5">
                Week View: <span class="text-[#FFC32D]">{{ $startOfWeek->format('M d, Y') }} — {{ $endOfWeek->format('M d, Y') }}</span>
            </p>
        </div>
        <div class="flex items-center gap-2 w-full lg:w-auto">
            <a href="?date={{ $viewDate->copy()->subWeek()->format('Y-m-d') }}" class="bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-black uppercase tracking-wider px-4 py-3 rounded-xl transition text-center flex-1 lg:flex-none">
                ← Last Week
            </a>
            <a href="?date={{ \Carbon\Carbon::today()->format('Y-m-d') }}" class="bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-black uppercase tracking-wider px-4 py-3 rounded-xl transition text-center">
                Today
            </a>
            <a href="?date={{ $viewDate->copy()->addWeek()->format('Y-m-d') }}" class="bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-black uppercase tracking-wider px-4 py-3 rounded-xl transition text-center flex-1 lg:flex-none">
                Next Week →
            </a>
        </div>
    </div>

    {{-- MAIN INTERACTIVE ENGINE SPLIT --}}
    <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 items-start w-full min-w-0">
        
        {{-- LEFT COLUMN: UNSCHEDULED BACKLOG PANEL --}}
        <div class="xl:col-span-3 bg-slate-50 border border-slate-200/80 rounded-[2rem] p-5 space-y-4 shadow-sm w-full min-w-0">
            <div>
                <h3 class="text-xs font-black text-[#0F2D5A] uppercase tracking-wider">Unscheduled Backlog</h3>
                <p class="text-[11px] font-bold text-slate-400 mt-1 leading-relaxed">Approved estimates awaiting execution. Drag any magnet card onto a crew time track lane.</p>
            </div>

            <div class="space-y-3 max-h-[60vh] overflow-y-auto pr-1">
                @forelse($backlogEstimates as $backlog)
                    <div class="bg-white border-2 border-slate-900 p-4 rounded-xl shadow-sm cursor-grab active:cursor-grabbing hover:border-[#0F2D5A] hover:scale-[1.01] transition transform duration-150 relative select-none group"
                         draggable="true"
                         @dragstart="handleDragStart($event, {{ $backlog->id }}, '{{ addslashes($backlog->project_title) }}')">
                        <span class="absolute top-2.5 right-3 text-slate-300 group-hover:text-slate-500 transition text-xs font-black">⋮⋮</span>
                        <span class="bg-emerald-50 border border-emerald-200 text-emerald-700 text-[8px] font-black uppercase tracking-widest px-2 py-0.5 rounded-md">Approved</span>
                        <h4 class="text-xs font-black text-slate-900 mt-2.5 tracking-tight line-clamp-1">{{ $backlog->project_title }}</h4>
                        <p class="text-[10px] font-bold text-slate-400 mt-0.5">👤 {{ $backlog->client_name }}</p>
                        <div class="border-t border-slate-100 mt-3 pt-2 flex items-center justify-between">
                            <span class="text-[9px] font-black text-slate-400 uppercase tracking-wider">Estimated Valve</span>
                            <span class="text-xs font-black text-[#0F2D5A]">${{ number_format($backlog->total_cents / 100, 2) }}</span>
                        </div>
                    </div>
                @empty
                    <div class="border-2 border-dashed border-slate-200 rounded-xl p-8 text-center text-slate-400 space-y-1.5 bg-white">
                        <span class="text-2xl block">🎉</span>
                        <h5 class="text-xs font-black text-slate-700 uppercase tracking-wide">Backlog Clear</h5>
                        <p class="text-[10px] font-bold text-slate-400 leading-relaxed">All active projects have been allocated to field crews.</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- RIGHT COLUMN: WORKER TIMELINE MATRIX --}}
        <div class="xl:col-span-9 bg-white border border-slate-200 shadow-xl rounded-[2.5rem] p-5 sm:p-6 overflow-hidden w-full min-w-0">
            <div class="overflow-x-auto scrollbar-thin">
                <div class="min-w-[850px] space-y-0">
                    
                    {{-- ROW A: UNIFIED HEADER BAR MATRIX (Aligns column heads flush with content tracks) --}}
                    <div class="flex items-center gap-4 border-b-2 border-slate-900 pb-3 mb-4">
                        {{-- Dead corner spacing block mirroring the exact width of the hour ticker --}}
                        <div class="w-16 flex-shrink-0"></div>
                        
                        {{-- Crew Labels Horizontal Spreader --}}
                        <div class="flex-1" style="display: grid; grid-template-columns: repeat({{ max(1, $crews->count()) }}, minmax(0, 1fr)); gap: 1rem;">
                            @foreach($crews as $crew)
                                <div class="bg-slate-900 text-white px-4 py-3 rounded-xl flex items-center justify-between shadow-sm border border-slate-800">
                                    <span class="text-xs font-black uppercase tracking-wider truncate">👷 {{ $crew->name }}</span>
                                    <span class="text-[8px] bg-[#FFC32D] text-slate-950 font-black px-1.5 py-0.5 rounded-md uppercase tracking-wider">Active</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- ROW B: TIMELINE BOARD MATRIX BODY --}}
                    <div class="flex items-start gap-4 relative">
                        
                        {{-- VERTICAL HOUR TIME MARKERS AXIS --}}
                        <div class="w-16 flex-shrink-0 space-y-0 text-right select-none">
                            @foreach(['08:00 AM' => '8 AM', '10:00 AM' => '10 AM', '12:00 PM' => '12 PM', '02:00 PM' => '2 PM', '04:00 PM' => '4 PM', '06:00 PM' => '6 PM'] as $rawTime => $displayHour)
                                <div class="h-24 flex items-start justify-end pr-2 pt-0.5">
                                    <span class="text-[10px] font-black font-mono text-slate-400 uppercase tracking-widest">{{ $displayHour }}</span>
                                </div>
                            @endforeach
                        </div>

                        {{-- DYNAMIC FIELD CONTENT CREW TRACK OVERLAY GRID --}}
                        <div class="flex-1 relative" style="display: grid; grid-template-columns: repeat({{ max(1, $crews->count()) }}, minmax(0, 1fr)); gap: 1rem;">
                            
                            {{-- HORIZONTAL GUIDELINES MATRIX RUNNING CONTINUOUSLY UNDER LANE PACKS --}}
                            <div class="absolute inset-0 space-y-0 pointer-events-none z-0">
                                @for($i = 0; $i < 6; $i++)
                                    <div class="h-24 border-t border-dashed border-slate-200/80"></div>
                                @endfor
                            </div>

                            @foreach($crews as $crew)
                                {{-- DRAG & DROP INTERACTIVE LANE TARGET CHANNELS --}}
                                <div class="min-h-[576px] rounded-2xl transition-all duration-200 z-10 p-1 bg-transparent border-2 border-transparent"
                                     :class="dragOverCrewId == {{ $crew->id }} ? 'bg-amber-50/40 border-amber-400 border-solid shadow-inner rounded-2xl' : ''"
                                     @dragover.prevent="handleDragOver($event, {{ $crew->id }})"
                                     @dragleave="handleDragLeave()"
                                     @drop="handleDrop($event, {{ $crew->id }})">
                                    
                                    {{-- INTERCEPTED APPOINTMENT CONTAINER LAYER --}}
                                    <div class="space-y-3">
                                        @if(isset($appointments[$crew->id]))
                                            @foreach($appointments[$crew->id] as $appt)
                                                <div class="bg-white border-2 border-slate-200/90 p-3.5 rounded-xl shadow-sm text-left relative group hover:border-[#0F2D5A] transition duration-150 bg-white/95">
                                                    <div class="flex items-center justify-between gap-2">
                                                        <span class="text-[8px] bg-slate-900 text-[#FFC32D] font-black uppercase tracking-widest px-1.5 py-0.5 rounded">
                                                            {{ $appt->scheduled_start_at->format('D h:i A') }}
                                                        </span>
                                                        <span class="text-[9px] font-mono font-black text-slate-500 bg-slate-100 px-1.5 py-0.5 rounded">
                                                            {{ $appt->formatted_payout }}
                                                        </span>
                                                    </div>
                                                    <h4 class="text-xs font-black text-slate-900 mt-2.5 tracking-tight line-clamp-2 leading-snug">{{ $appt->estimate->project_title }}</h4>
                                                    <p class="text-[10px] font-bold text-slate-400 mt-0.5 truncate">📍 {{ $appt->estimate->client_name }}</p>
                                                </div>
                                            @endforeach
                                        @else
                                            {{-- Invisible placeholder tracking alignment frame when lane is empty --}}
                                            <div class="py-24 text-center text-slate-300 pointer-events-none select-none opacity-40 group">
                                                <span class="text-lg block group-hover:scale-110 transition duration-150">📥</span>
                                                <span class="text-[8px] font-black uppercase tracking-widest block mt-1">Available Track</span>
                                            </div>
                                        @endif
                                    </div>

                                </div>
                            @endforeach

                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- PROGRAMMATIC ASSIGNMENT PARAMETER MODAL --}}
    <div x-show="modalOpen" 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/60 backdrop-blur-sm"
         x-transition
         style="display: none;">
        
        <div class="bg-white rounded-[2rem] border-4 border-slate-900 w-full max-w-md shadow-2xl p-6 space-y-5"
             @click.away="modalOpen = false">
            
            <div class="border-b border-slate-100 pb-3 text-left">
                <h3 class="text-base font-black text-[#0F2D5A] tracking-tight">Configure Route Parameters</h3>
                <p class="text-xs font-bold text-slate-400 mt-0.5">Allocating: <span class="text-slate-900 font-black" x-text="draggedTitle"></span></p>
            </div>

            <div class="space-y-4 text-left">
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Scheduled Deployment Start</label>
                    <input type="datetime-local" x-model="formData.start_time" class="w-full bg-[#F0F0F0] border-2 border-slate-200 rounded-xl text-xs font-bold py-2.5 px-3 focus:bg-white focus:border-[#0F2D5A] focus:outline-none transition">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Scheduled Completion End</label>
                    <input type="datetime-local" x-model="formData.end_time" class="w-full bg-[#F0F0F0] border-2 border-slate-200 rounded-xl text-xs font-bold py-2.5 px-3 focus:bg-white focus:border-[#0F2D5A] focus:outline-none transition">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Subcontractor / Crew Payout Rate ($)</label>
                    <div class="relative">
                        <span class="absolute left-3 top-2.5 text-xs font-bold text-slate-400">$</span>
                        <input type="number" step="0.01" min="0" placeholder="e.g. 250.00" x-model="formData.payout_rate" class="w-full bg-[#F0F0F0] border-2 border-slate-200 rounded-xl text-xs font-bold py-2.5 pl-7 pr-3 focus:bg-white focus:border-[#0F2D5A] focus:outline-none transition">
                    </div>
                </div>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="button" @click="modalOpen = false" class="w-1/3 bg-slate-100 hover:bg-slate-200 text-slate-600 font-black text-xs uppercase tracking-wider py-3 rounded-xl transition">
                    Cancel
                </button>
                <button type="button" @click="submitAssignment()" class="w-2/3 bg-[#0F2D5A] hover:bg-[#1E3C5A] text-white font-black text-xs uppercase tracking-wider py-3 rounded-xl shadow-md transition transform active:scale-95">
                    Confirm Dispatch →
                </button>
            </div>
        </div>
    </div>

</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('dispatchMatrix', (config) => ({
        assignUrl: config.assignUrl,
        csrf: config.csrf,
        draggedEstimateId: null,
        draggedTitle: '',
        dragOverCrewId: null,
        modalOpen: false,
        formData: {
            estimate_id: '',
            crew_id: '',
            start_time: '',
            end_time: '',
            payout_rate: ''
        },

        handleDragStart(e, id, title) {
            this.draggedEstimateId = id;
            this.draggedTitle = title;
            e.dataTransfer.effectAllowed = 'move';
        },

        handleDragOver(e, crewId) {
            this.dragOverCrewId = crewId;
        },

        handleDragLeave() {
            this.dragOverCrewId = null;
        },

        handleDrop(e, crewId) {
            this.dragOverCrewId = null;
            if (!this.draggedEstimateId) return;

            this.formData.estimate_id = this.draggedEstimateId;
            this.formData.crew_id = crewId;
            
            const now = new Date();
            const twoHoursOut = new Date(now.getTime() + (2 * 60 * 60 * 1000));
            
            this.formData.start_time = now.toISOString().slice(0, 16);
            this.formData.end_time = twoHoursOut.toISOString().slice(0, 16);
            this.formData.payout_rate = '';

            this.modalOpen = true;
        },

        submitAssignment() {
            fetch(this.assignUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrf,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify(this.formData)
            })
            .then(response => {
                if (!response.ok) return response.json().then(err => { throw err; });
                return response.json();
            })
            .then(data => {
                this.modalOpen = false;
                alert(data.message);
                window.location.reload();
            })
            .catch(error => {
                console.error(error);
                alert(error.message || 'Dispatch allocation failed due to an unknown database conflict.');
            });
        }
    }));
});
</script>
@endsection