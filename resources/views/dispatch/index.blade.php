@extends('layouts.app')

@section('title', 'Dispatch Command Matrix')

@section('content')
<div class="space-y-6 w-full min-w-0 max-w-full overflow-hidden text-left" 
     x-data="dispatchMatrix({
         assignUrl: '{{ route('dashboard.dispatch.assign') }}',
         csrf: '{{ csrf_token() }}'
     })">
    
    {{-- PIPELINE HEADER TRACK --}}
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 bg-slate-900 text-white p-6 rounded-2xl border border-slate-800 shadow-xl w-full min-w-0">
        <div>
            <span class="bg-[#FFC32D] text-slate-950 text-[9px] font-black uppercase tracking-widest px-2.5 py-1 rounded-md">Live Command Center</span>
            <h1 class="text-xl font-black tracking-tight mt-2">Visual Dispatch Matrix</h1>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mt-0.5">
                Week Tracking Profile: <span class="text-[#FFC32D]">{{ $startOfWeek->format('M d, Y') }} — {{ $endOfWeek->format('M d, Y') }}</span>
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

    {{-- INTERACTIVE MATRIX GRID CONTAINER --}}
    <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 items-start w-full min-w-0">
        
        {{-- LEFT COLUMN: THE UNASSIGNED BACKLOG MAGNET BUCKET (3 Cols) --}}
        <div class="xl:col-span-3 bg-slate-100 border-2 border-slate-200 rounded-[2rem] p-5 space-y-4 shadow-inner w-full min-w-0 min-h-[60vh]">
            <div>
                <h3 class="text-sm font-black text-[#0F2D5A] uppercase tracking-wider">Unscheduled Backlog</h3>
                <p class="text-[11px] font-bold text-slate-400 mt-0.5">Approved estimates waiting for dispatch placement. Drag any card onto a crew time track.</p>
            </div>

            <div class="space-y-3 max-h-[65vh] overflow-y-auto pr-1">
                @forelse($backlogEstimates as $backlog)
                    <div class="bg-white border-2 border-slate-950 p-4 rounded-xl shadow-sm cursor-grab active:cursor-grabbing hover:scale-[1.02] transition transform duration-150 relative select-none group"
                         draggable="true"
                         @dragstart="handleDragStart($event, {{ $backlog->id }}, '{{ addslashes($backlog->project_title) }}')">
                        <span class="absolute top-2 right-2 text-slate-300 group-hover:text-slate-500 transition text-xs">⋮⋮</span>
                        <span class="bg-emerald-50 text-emerald-700 text-[8px] font-black uppercase tracking-widest px-2 py-0.5 rounded">Approved</span>
                        <h4 class="text-xs font-black text-slate-900 mt-2 tracking-tight line-clamp-1">{{ $backlog->project_title }}</h4>
                        <p class="text-[10px] font-bold text-slate-500 mt-0.5">👤 {{ $backlog->client_name }}</p>
                        <p class="text-xs font-black text-[#0F2D5A] mt-2">${{ number_format($backlog->total_cents / 100, 2) }}</p>
                    </div>
                @empty
                    <div class="border-2 border-dashed border-slate-300 rounded-xl p-6 text-center text-slate-400 space-y-1.5">
                        <span class="text-xl block">🎉</span>
                        <h5 class="text-xs font-black uppercase">Backlog Clear</h5>
                        <p class="text-[10px] font-bold text-slate-400">All winning project metrics have been successfully allocated to operational lanes.</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- RIGHT COLUMN: THE CREW TRACK TIMELINE BOARD WITH EXPLICIT TIME AXIS (9 Cols) --}}
        <div class="xl:col-span-9 bg-white border-4 border-slate-900 rounded-[2.5rem] p-4 sm:p-6 shadow-xl overflow-hidden w-full min-w-0">
            <div class="overflow-x-auto">
                <div class="min-w-[850px] flex items-start gap-4">
                    
                    {{-- FIXED TIMELINE HOUR LABEL STRIP --}}
                    <div class="w-20 flex-shrink-0 pt-16 space-y-0 text-right pr-2">
                        @foreach(['08:00 AM' => '8 AM', '10:00 AM' => '10 AM', '12:00 PM' => '12 PM', '02:00 PM' => '2 PM', '04:00 PM' => '4 PM', '06:00 PM' => '6 PM'] as $rawTime => $displayHour)
                            <div class="h-24 flex items-start justify-end border-t border-dashed border-slate-200 pt-1">
                                <span class="text-[10px] font-black font-mono text-slate-400 uppercase tracking-wider">{{ $displayHour }}</span>
                            </div>
                        @endforeach
                    </div>

                    {{-- CREW LANES CONTAINER GRID (Inline CSS styles bypass dynamic Tailwind compilation hazards safely) --}}
                    <div class="flex-1" style="display: grid; grid-template-columns: repeat({{ max(1, $crews->count()) }}, minmax(0, 1fr)); gap: 1rem;">
                        @foreach($crews as $crew)
                            {{-- INDIVIDUAL CREW LANE TRACK TRACKER --}}
                            <div class="space-y-4">
                                {{-- COLUMN TOP HEAD --}}
                                <div class="bg-slate-900 text-white p-3.5 rounded-xl flex items-center justify-between shadow-sm">
                                    <span class="text-xs font-black uppercase tracking-wider truncate mr-2">Worker: {{ $crew->name }}</span>
                                    <span class="text-[9px] bg-[#FFC32D] text-slate-950 font-black px-1.5 py-0.5 rounded uppercase">Lane</span>
                                </div>

                                {{-- INTERACTIVE DROP ZONE BLOCK MAP --}}
                                <div class="bg-slate-50 border-2 border-dashed border-slate-200 rounded-2xl p-3 min-h-[58vh] space-y-3 transition-colors duration-200 relative"
                                     :class="dragOverCrewId == {{ $crew->id }} ? 'bg-amber-50 border-amber-400 border-solid' : ''"
                                     @dragover.prevent="handleDragOver($event, {{ $crew->id }})"
                                     @dragleave="handleDragLeave()"
                                     @drop="handleDrop($event, {{ $crew->id }})">
                                    
                                    {{-- BACKGROUND ROW GUIDELINES MATCHING HOUR TRACK SECTIONS --}}
                                    <div class="absolute inset-0 p-3 space-y-0 pointer-events-none z-0">
                                        @for($i = 0; $i < 6; $i++)
                                            <div class="h-24 border-b border-dashed border-slate-200/60"></div>
                                        @endfor
                                    </div>

                                    {{-- RENDER EXISTING INTERCEPTED APPOINTMENTS --}}
                                    <div class="relative z-10 space-y-3">
                                        @if(isset($appointments[$crew->id]))
                                            @foreach($appointments[$crew->id] as $appt)
                                                <div class="bg-white border-2 border-slate-200 p-3.5 rounded-xl shadow-sm text-left relative group hover:border-slate-950 transition">
                                                    <div class="flex items-center justify-between gap-2">
                                                        <span class="text-[8px] bg-slate-900 text-[#FFC32D] font-black uppercase tracking-widest px-1.5 py-0.5 rounded">
                                                            {{ $appt->scheduled_start_at->format('D h:i A') }}
                                                        </span>
                                                        <span class="text-[9px] font-mono font-bold text-slate-500">
                                                            {{ $appt->formatted_payout }}
                                                        </span>
                                                    </div>
                                                    <h4 class="text-xs font-black text-slate-900 mt-2 tracking-tight line-clamp-2">{{ $appt->estimate->project_title }}</h4>
                                                    <p class="text-[10px] font-bold text-slate-400 mt-0.5 truncate">📍 {{ $appt->estimate->client_name }}</p>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="py-24 text-center text-slate-300 pointer-events-none select-none">
                                                <span class="text-xl block">📥</span>
                                                <span class="text-[9px] font-black uppercase tracking-wider block mt-1">Lane Available</span>
                                            </div>
                                        @endif
                                    </div>

                                </div>
                            </div>
                        @endforeach
                    </div>

                </div>
            </div>
        </div>

    </div>

    {{-- PROGRAMMATIC ASSIGNMENT MODAL POPUP DIALOG --}}
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