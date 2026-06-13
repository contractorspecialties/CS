@extends('layouts.app')

@section('title', 'Recurring Service Contracts')

@section('content')
<div class="space-y-6 w-full min-w-0 text-left" x-data="{ 
    searchQuery: '', 
    statusFilter: 'active',
    templates: [
        { id: 1, client_name: 'Walter White', project_title: 'Weekly Bi-Perimeter Property Cut', frequency: 'weekly', total_cycles: 26, completed_cycles: 6, billing_per_visit: 75.00, status: 'active', next_run: '2026-06-16' },
        { id: 2, client_name: 'Sarah Jenkins', project_title: 'Bi-Weekly Full Home Turn Down Clean', frequency: 'bi-weekly', total_cycles: 12, completed_cycles: 4, billing_per_visit: 160.00, status: 'active', next_run: '2026-06-23' },
        { id: 3, client_name: 'Gustavo Fring', project_title: 'Monthly Commercial HVAC Service Check', frequency: 'monthly', total_cycles: 12, completed_cycles: 12, billing_per_visit: 350.00, status: 'completed', next_run: 'Finished' },
        { id: 4, client_name: 'Hank Schrader', project_title: 'Weekly Lawn Edge & Fertilizer Feed', frequency: 'weekly', total_cycles: 20, completed_cycles: 11, billing_per_visit: 90.00, status: 'paused', next_run: 'Suspended' }
    ],
    filteredTemplates() {
        return this.templates.filter(t => {
            const matchesSearch = t.client_name.toLowerCase().includes(this.searchQuery.toLowerCase()) || 
                                  t.project_title.toLowerCase().includes(this.searchQuery.toLowerCase());
            const matchesStatus = this.statusFilter === 'all' || t.status === this.statusFilter;
            return matchesSearch && matchesStatus;
        });
    }
}">

    {{-- RECURRING OPERATIONS HEADER --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-slate-900 text-white p-6 rounded-2xl border border-slate-800 shadow-xl w-full min-w-0">
        <div>
            <span class="bg-[#FFC32D] text-slate-950 text-[9px] font-black uppercase tracking-widest px-2.5 py-1 rounded-md">Automated Pipelines</span>
            <h1 class="text-xl font-black tracking-tight mt-2">Recurring Service Contracts</h1>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mt-0.5">Manage automated repeating dispatch templates, active service lifecycles, and subscription limits.</p>
        </div>
        <div class="flex items-center gap-3">
            <div class="bg-slate-800 border border-slate-700 rounded-xl px-4 py-2.5 text-right">
                <span class="text-[9px] font-black text-slate-400 uppercase tracking-wider block">Projected Weekly Run</span>
                <span class="text-base font-black text-emerald-400 font-mono">$1,235.00</span>
            </div>
        </div>
    </div>

    {{-- SEARCH AND SEGMENTED STATUS FILTERS --}}
    <div class="bg-white border border-slate-200 p-4 rounded-2xl shadow-sm flex flex-col md:flex-row items-center justify-between gap-4 w-full">
        <div class="relative w-full md:flex-1">
            <span class="absolute inset-y-0 left-3 flex items-center text-slate-400 pointer-events-none text-xs">🔍</span>
            <input type="text" x-model="searchQuery" placeholder="Search recurring contracts by customer or description..." class="w-full bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold py-3 pl-9 pr-4 focus:bg-white focus:border-[#0F2D5A] focus:outline-none transition">
        </div>
        
        <div class="flex bg-slate-100 p-1 rounded-xl w-full md:w-auto self-stretch md:self-auto">
            <button @click="statusFilter = 'active'" :class="statusFilter === 'active' ? 'bg-white shadow-sm text-slate-900 font-black' : 'text-slate-500 font-bold'" class="flex-1 md:flex-none text-[10px] uppercase tracking-wider px-4 py-2 rounded-lg transition">Active</button>
            <button @click="statusFilter = 'paused'" :class="statusFilter === 'paused' ? 'bg-white shadow-sm text-slate-900 font-black' : 'text-slate-500 font-bold'" class="flex-1 md:flex-none text-[10px] uppercase tracking-wider px-4 py-2 rounded-lg transition">Paused</button>
            <button @click="statusFilter = 'completed'" :class="statusFilter === 'completed' ? 'bg-white shadow-sm text-slate-900 font-black' : 'text-slate-500 font-bold'" class="flex-1 md:flex-none text-[10px] uppercase tracking-wider px-4 py-2 rounded-lg transition">Completed</button>
            <button @click="statusFilter = 'all'" :class="statusFilter === 'all' ? 'bg-white shadow-sm text-slate-900 font-black' : 'text-slate-500 font-bold'" class="flex-1 md:flex-none text-[10px] uppercase tracking-wider px-4 py-2 rounded-lg transition">All</button>
        </div>
    </div>

    {{-- RECURRING CONTRACTS GRID MATRIX --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 w-full min-w-0">
        <template x-for="item in filteredTemplates()" :key="item.id">
            <div class="bg-white border-2 border-slate-200/80 rounded-3xl p-5 shadow-sm space-y-4 hover:border-slate-900 transition flex flex-col justify-between">
                
                {{-- TOP HEADER: CLIENT + STATUS IDENTITY --}}
                <div class="flex items-start justify-between gap-3">
                    <div class="min-w-0 text-left">
                        <h3 class="text-base font-black text-slate-900 tracking-tight truncate" x-text="item.client_name"></h3>
                        <p class="text-xs font-bold text-slate-500 mt-0.5 truncate" x-text="item.project_title"></p>
                    </div>
                    <span class="text-[9px] font-black uppercase tracking-wider px-2.5 py-1 rounded-md border"
                          :class="{
                              'bg-emerald-50 border-emerald-200 text-emerald-700': item.status === 'active',
                              'bg-amber-50 border-amber-200 text-amber-700': item.status === 'paused',
                              'bg-slate-100 border-slate-200 text-slate-600': item.status === 'completed'
                          }" x-text="item.status"></span>
                </div>

                {{-- LIFECYCLE METRIC PROGRESS CHART STRIP --}}
                <div class="space-y-1 text-left">
                    <div class="flex items-center justify-between text-[10px] font-bold text-slate-400 uppercase tracking-wide">
                        <span>Contract Iteration Completion</span>
                        <span class="font-mono font-black text-slate-700" x-text="item.completed_cycles + ' / ' + item.total_cycles + ' Stops'"></span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-2.5 overflow-hidden border border-slate-200/40">
                        <div class="bg-[#0F2D5A] h-2.5 rounded-full transition-all duration-500" 
                             :style="'width: ' + ((item.completed_cycles / item.total_cycles) * 100) + '%'"></div>
                    </div>
                </div>

                {{-- VISIT AND RUN METRIC BLOCKS --}}
                <div class="bg-slate-50 border border-slate-100 rounded-xl p-3 grid grid-cols-3 gap-2 text-center text-xs font-bold">
                    <div class="text-left pl-1">
                        <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest block">Frequency</span>
                        <span class="text-slate-800 uppercase mt-0.5 block truncate" x-text="item.frequency"></span>
                    </div>
                    <div class="text-left border-l border-slate-200 pl-3">
                        <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest block">Rate / Visit</span>
                        <span class="text-slate-900 font-mono font-black mt-0.5 block" x-text="'$' + item.billing_per_visit.toFixed(2)"></span>
                    </div>
                    <div class="text-left border-l border-slate-200 pl-3">
                        <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest block">Next Generation</span>
                        <span class="text-slate-600 font-mono mt-0.5 block truncate" x-text="item.next_run"></span>
                    </div>
                </div>

                {{-- MANAGEMENT MATRIX OPERATIONS INTERACTIVE ACTION BAR --}}
                <div class="flex items-center gap-2 pt-1">
                    <button x-show="item.status === 'active'" @click="item.status = 'paused'; item.next_run = 'Suspended'" class="flex-1 bg-amber-500 hover:bg-amber-600 text-white text-[10px] font-black uppercase tracking-wider py-3 rounded-xl transition shadow-sm">
                        ⏸ Pause Contract
                    </button>
                    <button x-show="item.status === 'paused'" @click="item.status = 'active'; item.next_run = '2026-06-16'" class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white text-[10px] font-black uppercase tracking-wider py-3 rounded-xl transition shadow-sm">
                        ▶ Resume Service
                    </button>
                    <button class="border-2 border-slate-900 text-slate-900 hover:bg-slate-50 text-[10px] font-black uppercase tracking-wider py-2.5 px-4 rounded-xl transition">
                        ✏️ Adjust Loop
                    </button>
                </div>

            </div>
        </template>

        {{-- EMPTY ROSTER FALLBACK SCREEN --}}
        <div class="col-span-1 lg:col-span-2 border-4 border-dashed border-slate-200 rounded-[2rem] p-12 text-center" 
             x-show="filteredTemplates().length === 0">
            <span class="text-4xl block mb-2">🔄</span>
            <h4 class="text-base font-black text-[#0F2D5A]">No Matching Recurring Subscriptions Found</h4>
            <p class="text-xs text-slate-400 font-bold max-w-sm mx-auto mt-1">Issue itemized estimates with recurring configurations toggled active to initialize repeating pipeline accounts here.</p>
        </div>
    </div>

</div>
@endsection