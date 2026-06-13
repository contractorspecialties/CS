@extends('layouts.app')

@section('title', 'Client CRM Directory')

@section('content')
<div class="space-y-6 w-full min-w-0 text-left" 
     x-data="crmEngine({ initialClients: {{ json_encode($clients) }} })">
    
    {{-- CRM BRANDING HEADER --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-slate-900 text-white p-6 rounded-2xl border border-slate-800 shadow-xl w-full min-w-0">
        <div>
            <span class="bg-[#FFC32D] text-slate-950 text-[9px] font-black uppercase tracking-widest px-2.5 py-1 rounded-md">Customer Hub</span>
            <h1 class="text-xl font-black tracking-tight mt-2">Client CRM Directory</h1>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mt-0.5">Centralized client accounts, communication logs, and lifetime value histories.</p>
        </div>
        <div class="flex items-center gap-3">
            <div class="bg-slate-800 border border-slate-700 rounded-xl px-4 py-2.5 text-right">
                <span class="text-[9px] font-black text-slate-400 uppercase tracking-wider block">Total Managed Accounts</span>
                <span class="text-base font-black text-[#FFC32D] font-mono" x-text="clients.length">0</span>
            </div>
        </div>
    </div>

    {{-- SEARCH & FILTRATION DECK --}}
    <div class="bg-white border border-slate-200 p-4 rounded-2xl shadow-sm flex flex-col sm:flex-row items-center gap-3 w-full">
        <div class="relative w-full sm:flex-1">
            <span class="absolute inset-y-0 left-3 flex items-center text-slate-400 pointer-events-none text-xs">🔍</span>
            <input type="text" x-model="searchQuery" placeholder="Search customer directory by name, email, or phone number..." class="w-full bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold py-3 pl-9 pr-4 focus:bg-white focus:border-[#0F2D5A] focus:outline-none transition">
        </div>
    </div>

    {{-- CLIENT ACCOUNT DIRECTORY GRID --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 w-full min-w-0">
        <template x-for="client in filteredClients()" :key="client.id">
            <div class="bg-white border-2 border-slate-200/80 rounded-3xl p-5 shadow-sm space-y-4 hover:border-slate-900 transition flex flex-col justify-between">
                
                {{-- ACCOUNT TOP CARD: IDENTITY --}}
                <div class="flex items-start justify-between gap-3">
                    <div class="min-w-0">
                        <div class="flex items-center gap-2 flex-wrap">
                            <h3 class="text-base font-black text-slate-900 tracking-tight truncate max-w-[200px]" x-text="client.name"></h3>
                            <span class="text-[8px] font-black bg-slate-100 text-slate-600 border border-slate-200 uppercase tracking-widest px-1.5 py-0.5 rounded" x-text="'ID: #' + client.id"></span>
                        </div>
                        <p class="text-[11px] font-bold text-slate-400 truncate mt-0.5" x-text="client.email || 'No email profile recorded'"></p>
                    </div>
                    
                    {{-- LIFETIME VALUATION BADGE --}}
                    <div class="text-right flex-shrink-0 bg-emerald-50 border border-emerald-100 rounded-xl px-3 py-1.5">
                        <span class="text-[8px] font-black text-emerald-800 uppercase tracking-widest block">Lifetime Spent</span>
                        <span class="text-xs font-black font-mono text-emerald-600 block mt-0.5" x-text="formatCurrency(client.ltv_cents / 100)"></span>
                    </div>
                </div>

                {{-- ACCOUNT MID SECTION: WORK VOLUMES --}}
                <div class="bg-slate-50 border border-slate-100 rounded-xl p-3 grid grid-cols-2 gap-2 text-center">
                    <div class="text-left pl-1">
                        <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest block">Total Proposals</span>
                        <span class="text-xs font-black text-slate-800 mt-0.5 block" x-text="client.estimates_count + ' Quotes'"></span>
                    </div>
                    <div class="text-left border-l border-slate-200 pl-3">
                        <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest block">Recent Project</span>
                        <span class="text-xs font-black text-slate-800 mt-0.5 block truncate max-w-[130px]" x-text="client.latest_project"></span>
                    </div>
                </div>

                {{-- COMMUNICATIONS DECK ACTIONS --}}
                <div class="flex items-center gap-2 pt-1">
                    <a :href="client.phone ? 'tel:' + client.phone : '#'" 
                       :class="client.phone ? 'bg-[#0F2D5A] hover:bg-[#1E3C5A] text-white' : 'bg-slate-100 text-slate-300 pointer-events-none'"
                       class="flex-1 text-[10px] font-black uppercase tracking-wider py-2.5 rounded-xl transition text-center block shadow-sm">
                        📞 Call Client
                    </a>
                    <a :href="client.email ? 'mailto:' + client.email : '#'" 
                       :class="client.email ? 'border-2 border-slate-900 text-slate-900 hover:bg-slate-50' : 'border border-slate-200 text-slate-300 pointer-events-none'"
                       class="flex-1 text-[10px] font-black uppercase tracking-wider py-2 px-2.5 rounded-xl transition text-center block">
                        ✉️ Email Link
                    </a>
                </div>

            </div>
        </template>

        {{-- DIRECTORY EMPTY STATE --}}
        <div class="col-span-1 md:col-span-2 border-4 border-dashed border-slate-200 rounded-[2rem] p-12 text-center" 
             x-show="filteredClients().length === 0">
            <span class="text-4xl block mb-2">👥</span>
            <h4 class="text-base font-black text-[#0F2D5A]">No Matching Client Accounts Found</h4>
            <p class="text-xs text-slate-400 font-bold max-w-sm mx-auto mt-1">Generate project estimates or billing invoices to compile your client profile entries automatically.</p>
        </div>
    </div>

</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('crmEngine', (config) => ({
        searchQuery: '',
        clients: config.initialClients || [],

        formatCurrency(val) {
            return new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(val);
        },

        filteredClients() {
            if (!this.searchQuery.trim()) return this.clients;
            const q = this.searchQuery.toLowerCase().trim();
            return this.clients.filter(c => 
                c.name.toLowerCase().includes(q) || 
                (c.email && c.email.toLowerCase().includes(q)) || 
                (c.phone && c.phone.includes(q))
            );
        }
    }));
});
</script>
@endsection