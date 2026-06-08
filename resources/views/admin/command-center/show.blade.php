<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.command-center.index') }}" class="text-sm font-black text-slate-400 hover:text-[#0F2D5A] uppercase tracking-widest transition">
                        &larr; Back To Deck
                    </a>
                </div>
                <h2 class="font-black text-3xl text-[#0F2D5A] tracking-tight leading-tight mt-2">
                    Audit Node: {{ $client->business_name ?? 'Unconfigured Shop' }}
                </h2>
            </div>
            <div>
                <form action="{{ route('admin.command-center.client.toggle', $client->id) }}" method="POST">
                    @csrf
                    <button type="submit" 
                            class="inline-block font-black text-xs uppercase tracking-widest px-5 py-3 rounded-xl transition border-2 shadow-sm"
                            style="{{ $client->is_restricted ? 'background-color: #EF4444; color: #FFFFFF; border-color: #DC2626;' : 'background-color: #FFFFFF; color: #EF4444; border-color: #FEE2E2;' }}">
                        {{ $client->is_restricted ? 'Unsuspend Node Access' : 'Trigger Immediate Suspension' }}
                    </button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            {{-- LIVE NOTIFICATION TOAST BARS --}}
            @if (session('status'))
                <div class="bg-slate-900 border-l-8 border-[#FFD22D] p-6 rounded-r-2xl shadow-md">
                    <p class="font-black text-base text-[#FFFFFF]">{{ session('status') }}</p>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                
                {{-- LEFT CORE PANEL: STYLING OVERRIDES & IDENTITY PARAMETERS (FOOTPRINT: 7/12) --}}
                <div class="lg:col-span-7 bg-[#FFFFFF] rounded-[2rem] border-4 border-slate-900 shadow-xl p-6 sm:p-8 space-y-6">
                    <div>
                        <h3 class="text-xl font-black text-[#0F2D5A] tracking-tight">Visual Theme Configuration Overrides</h3>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mt-1">Directly manipulates directory link attributes and solo site templates</p>
                    </div>

                    <form action="{{ route('admin.command-center.client.theme', $client->id) }}" method="POST" class="space-y-5">
                        @csrf
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-black text-[#3C3C4B] uppercase tracking-widest mb-2">Target Hex Theme Color</label>
                                <div class="flex gap-2">
                                    <input type="color" name="theme_color" value="{{ $themePayload['theme_color'] }}" class="w-12 h-12 rounded-xl bg-[#F0F0F0] border-2 border-slate-200 p-1 cursor-pointer">
                                    <input type="text" value="{{ $themePayload['theme_color'] }}" readonly class="w-full bg-[#F0F0F0] border-2 border-slate-200 rounded-xl text-slate-500 font-bold px-4 py-3 text-sm">
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-black text-[#3C3C4B] uppercase tracking-widest mb-2">External Custom Domain Routing</label>
                                <input type="url" name="company_website" value="{{ $themePayload['company_website'] }}" placeholder="https://clientdomain.com" class="w-full bg-[#F0F0F0] border-2 border-slate-200 rounded-xl text-[#3C3C3C] placeholder-slate-400 font-bold py-3 px-4 text-sm focus:border-[#1E3C5A] focus:bg-[#FFFFFF] focus:outline-none transition">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-black text-[#3C3C4B] uppercase tracking-widest mb-2">Corporate Brand Slogan String</label>
                            <input type="text" name="slogan" value="{{ $themePayload['slogan'] }}" placeholder="e.g. Precision Electrical Engineering Loops" class="w-full bg-[#F0F0F0] border-2 border-slate-200 rounded-xl text-[#3C3C3C] placeholder-slate-400 font-bold py-3.5 px-4 text-sm focus:border-[#1E3C5A] focus:bg-[#FFFFFF] focus:outline-none transition">
                        </div>

                        <div class="pt-2">
                            <button type="submit" class="w-full bg-[#0F2D5A] hover:bg-[#1E3C5A] text-[#FFFFFF] font-black text-sm uppercase tracking-wider py-4 rounded-xl shadow transition transform active:scale-95 border border-[#0F2D5A]">
                                Commit Profile Property Adjustments &rarr;
                            </button>
                        </div>
                    </form>
                </div>

                {{-- RIGHT CORE PANEL: EXTENDED WORKSPACE METRICS (FOOTPRINT: 5/12) --}}
                <div class="lg:col-span-5 space-y-6">
                    
                    {{-- CONTACT PROFILE CARD --}}
                    <div class="bg-[#FFFFFF] rounded-3xl border-2 border-[#F0F0F0] p-6 shadow-sm space-y-4">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl bg-slate-100 border border-slate-200 flex items-center justify-center font-black text-lg text-[#0F2D5A]">
                                {{ uppercase(substr($client->name, 0, 2)) }}
                            </div>
                            <div>
                                <h4 class="font-black text-lg text-slate-900 leading-none">{{ $client->name }}</h4>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mt-1">Primary Operator Node</p>
                            </div>
                        </div>
                        <div class="border-t border-[#F0F0F0] pt-4 space-y-2 text-xs font-bold text-[#3C3C4B]">
                            <p><span class="text-slate-400 uppercase tracking-wider block mb-0.5">Email Communications Vector</span> {{ $client->email }}</p>
                            <p><span class="text-slate-400 uppercase tracking-wider block mb-0.5">Account Token Authority</span> ID #{{ $client->id }}</p>
                        </div>
                    </div>

                    {{-- PREMIUM SAAS SUITE APP HEALTH OVERVIEW --}}
                    <div class="bg-[#FFFFFF] rounded-3xl border-2 border-[#F0F0F0] p-6 shadow-sm space-y-4">
                        <div>
                            <h4 class="font-black text-sm text-[#0F2D5A] uppercase tracking-widest">SaaS Workspace Performance (CPP)</h4>
                            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mt-0.5">Metered runtime variables tracked from mobile application wrapped frames</p>
                        </div>
                        
                        <div class="grid grid-cols-3 gap-2 text-center">
                            <div class="bg-[#F0F0F0] p-3 rounded-xl border border-slate-200/60">
                                <span class="font-black text-xl text-[#0F2D5A] block">{{ $client->clients->count() }}</span>
                                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Roster</span>
                            </div>
                            <div class="bg-[#F0F0F0] p-3 rounded-xl border border-slate-200/60">
                                <span class="font-black text-xl text-[#0F2D5A] block">{{ $client->quotes->count() }}</span>
                                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Estimates</span>
                            </div>
                            <div class="bg-[#F0F0F0] p-3 rounded-xl border border-slate-200/60">
                                <span class="font-black text-xl text-[#0F2D5A] block">{{ $client->appointments->count() }}</span>
                                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Tasks</span>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </div>
</x-app-layout>