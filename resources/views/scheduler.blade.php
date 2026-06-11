@extends('layouts.app')

@section('title', 'Crew Scheduler')

@section('content')
<div class="space-y-6" x-data="{ slotModalOpen: false }">
    
    {{-- SYSTEM FEEDBACK NOTIFICATIONS --}}
    @if (session('status'))
        <div class="bg-slate-900 border-l-8 border-[#FFC32D] p-5 rounded-2xl text-left shadow-sm">
            <p class="text-sm font-bold text-white leading-snug">{{ session('status') }}</p>
        </div>
    @endif

    {{-- PAGE HEADER INTERFACE --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 sm:p-8 rounded-2xl border border-slate-200/80 shadow-sm text-left">
        <div>
            <h1 class="text-2xl font-black text-[#0F2D5A] tracking-tight">Crew Dispatch Scheduler</h1>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mt-1">
                Allocate approved customer proposals directly to calendar dispatch windows.
            </p>
        </div>
        <button @click="slotModalOpen = true" class="w-full sm:w-auto bg-[#0F2D5A] hover:bg-[#1E3C5A] text-white text-sm font-black uppercase tracking-wider px-6 py-4 rounded-xl transition transform active:scale-95 shadow-md whitespace-nowrap text-center">
            + Schedule New Work Order
        </button>
    </div>

    {{-- MAIN TIMELINE WORKSPACE LOG BLOCK --}}
    <div class="bg-[#FFFFFF] rounded-[2.5rem] border-4 border-slate-900 shadow-xl p-6 sm:p-8 space-y-6">
        <div>
            <h2 class="text-lg font-black text-[#0F2D5A] tracking-tight text-left">Operational Dispatch Agenda</h2>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mt-0.5 text-left">Vertical micro-targets prioritize visibility during field management operations</p>
        </div>

        @if($schedules->count() > 0)
            <div class="relative border-l-4 border-slate-200 ml-4 py-2 space-y-6 text-left">
                @foreach($schedules as $schedule)
                    <div class="relative pl-6 sm:pl-8">
                        {{-- Absolute node target indicator points --}}
                        <div class="absolute -left-[10px] top-1.5 w-4 h-4 rounded-full border-4 border-white bg-slate-900 shadow-sm"></div>
                        
                        <div class="bg-slate-50 hover:bg-slate-100/70 border border-slate-200/80 p-4 sm:p-5 rounded-2xl flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 transition shadow-sm">
                            <div class="space-y-1">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span class="bg-[#0F2D5A] text-white text-[9px] font-black uppercase tracking-wider px-2 py-0.5 rounded">
                                        📅 {{ \Carbon\Carbon::parse($schedule->scheduled_date)->format('M d, Y') }}
                                    </span>
                                    @if($schedule->start_time)
                                        <span class="bg-slate-200 text-slate-700 text-[9px] font-black uppercase tracking-wider px-2 py-0.5 rounded">
                                            ⏰ {{ $schedule->start_time }}
                                        </span>
                                    @endif
                                </div>
                                <h4 class="text-base font-black text-slate-900 tracking-tight pt-1">
                                    {{ $schedule->client_name }} — <span class="text-slate-600 font-medium">{{ $schedule->project_title }}</span>
                                </h4>
                                @if($schedule->crew_notes)
                                    <p class="text-xs text-slate-400 font-bold mt-1 max-w-xl">Notes: <span class="text-slate-500 font-medium font-mono bg-white px-1.5 py-0.5 rounded border border-slate-100">{{ $schedule->crew_notes }}</span></p>
                                @endif
                            </div>

                            <form action="{{ route('schedules.destroy', $schedule->id) }}" method="POST" onsubmit="return confirm('Clear this dispatch entry from the timeline calendar?');" class="self-end sm:self-center">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-white hover:bg-red-50 border border-slate-200 hover:border-red-200 text-slate-400 hover:text-red-500 text-xs font-black uppercase tracking-wider py-2 px-3 rounded-xl transition shadow-sm">
                                    Remove Block
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="border-4 border-dashed border-slate-200 rounded-[2rem] p-10 text-center">
                <span class="text-4xl block mb-2">📅</span>
                <h4 class="text-base font-black text-[#0F2D5A]">No Scheduled Work Blocks</h4>
                <p class="text-xs text-slate-400 font-bold max-w-sm mx-auto mt-1">Tap the button above to lock in crew dispatches or link approved project parameters directly onto execution target windows.</p>
            </div>
        @endif
    </div>

    {{-- INTERACTIVE CONTAINER DIALOG OVERLAY: ALLOCATION MODAL --}}
    <div x-show="slotModalOpen" 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/60 backdrop-blur-sm"
         x-transition
         style="display: none;">
        
        <div class="bg-white rounded-[2.5rem] border-4 border-slate-900 w-full max-w-lg max-h-[90vh] overflow-y-auto shadow-2xl p-6 sm:p-8 space-y-6"
             @click.away="slotModalOpen = false">
            
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div class="text-left">
                    <h3 class="text-xl font-black text-[#0F2D5A] tracking-tight">Reserve Dispatch Slot</h3>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mt-0.5">Deploy crews and project specs to custom calendar dates</p>
                </div>
                <button @click="slotModalOpen = false" class="w-8 h-8 rounded-lg bg-slate-100 text-slate-500 font-black text-sm flex items-center justify-center hover:bg-slate-200">×</button>
            </div>

            <form action="{{ route('schedules.store') }}" method="POST" class="space-y-5 text-left"
                  x-data="{ 
                      backlog: {{ json_encode($approvedBacklog->map(function($e){ return ['name' => $e->client_name, 'title' => $e->project_title]; })) }},
                      selectJob(e) {
                          let idx = e.target.value;
                          if(idx !== '') {
                              this.$refs.clientName.value = this.backlog[idx].name;
                              this.$refs.projectTitle.value = this.backlog[idx].title;
                          }
                      }
                  }">
                @csrf

                @if($approvedBacklog->count() > 0)
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Link Approved Job Pipeline (Optional)</label>
                        <select @change="selectJob($event)" class="w-full bg-[#F0F0F0] border-2 border-slate-200 rounded-xl text-sm font-bold py-3 px-4 focus:border-[#0F2D5A] focus:bg-white focus:outline-none appearance-none">
                            <option value="">Select a pre-approved proposal reference...</option>
                            @foreach($approvedBacklog as $index => $backlogItem)
                                <option value="{{ $index }}">{{ $backlogItem->client_name }} — {{ Str::limit($backlogItem->project_title, 30) }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Client Full Name</label>
                    <input type="text" name="client_name" x-ref="clientName" required placeholder="Customer billing name reference" class="w-full bg-[#F0F0F0] border-2 border-slate-200 rounded-xl text-sm font-bold py-3 px-4 focus:border-[#0F2D5A] focus:bg-white focus:outline-none focus:ring-0 transition">
                </div>

                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Project Scope Title</label>
                    <input type="text" name="project_title" x-ref="projectTitle" required placeholder="e.g. Living Room Drywall Restoration" class="w-full bg-[#F0F0F0] border-2 border-slate-200 rounded-xl text-sm font-bold py-3 px-4 focus:border-[#0F2D5A] focus:bg-white focus:outline-none focus:ring-0 transition">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Target Work Date</label>
                        <input type="date" name="scheduled_date" required class="w-full bg-[#F0F0F0] border-2 border-slate-200 rounded-xl text-sm font-bold py-3 px-4 focus:border-[#0F2D5A] focus:bg-white focus:outline-none focus:ring-0 transition">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Arrival Window Window</label>
                        <input type="text" name="start_time" placeholder="e.g. 8:00 AM - 10:00 AM" class="w-full bg-[#F0F0F0] border-2 border-slate-200 rounded-xl text-sm font-bold py-3 px-4 focus:border-[#0F2D5A] focus:bg-white focus:outline-none focus:ring-0 transition">
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Crew Service Instructions / Notes</label>
                    <textarea name="crew_notes" rows="3" placeholder="Materials, gateway lock combinations, paint codes, or specific truck details needed by field crews..." class="w-full bg-[#F0F0F0] border-2 border-slate-200 rounded-xl text-sm font-bold py-3 px-4 focus:border-[#0F2D5A] focus:bg-white focus:outline-none focus:ring-0 transition"></textarea>
                </div>

                <div class="flex gap-4 pt-2">
                    <button type="button" @click="slotModalOpen = false" class="w-1/3 bg-slate-100 hover:bg-slate-200 text-slate-600 font-black text-xs uppercase tracking-wider py-4 rounded-xl transition">
                        Cancel
                    </button>
                    <button type="submit" class="w-2/3 bg-[#0F2D5A] hover:bg-[#1E3C5A] text-white font-black text-xs uppercase tracking-wider py-4 rounded-xl shadow-md transition transform active:scale-95">
                        Confirm Allocation Slot →
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection