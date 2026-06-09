<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Audit Node | HQ Command Center</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <link rel="icon" type="image/webp" sizes="32x32" href="{{ asset('images/CS-Square.webp') }}">
    <link rel="icon" type="image/webp" sizes="192x192" href="{{ asset('images/CS-Square.webp') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/CS-Square.webp') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-[#F0F0F0] text-[#3C3C3C] antialiased">

    {{-- MASTER ADMIN HEADER --}}
    <header class="bg-[#FFFFFF] border-b border-[#F0F0F0] sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-24 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="/admin/command-center" class="block transition active:scale-95">
                    <img src="{{ asset('images/CS-logo-horizontal-750.webp') }}" alt="Contractor Specialties" class="h-14 w-auto object-contain">
                </a>
                <span class="bg-slate-900 text-[#FFD22D] text-[10px] font-black uppercase tracking-widest px-3 py-1 rounded-md">
                    HQ Control Core
                </span>
            </div>
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.command-center.index') }}" class="text-xs font-black text-slate-500 hover:text-[#0F2D5A] uppercase tracking-widest transition flex items-center gap-1 bg-[#F0F0F0] px-4 py-2 rounded-xl border border-slate-200">
                    &larr; Return to Master Deck
                </a>
            </div>
        </div>
    </header>

    {{-- CORE AUDIT PANEL --}}
    <main class="py-8 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            {{-- HEADER ROW --}}
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="font-black text-3xl text-[#0F2D5A] tracking-tight leading-tight">
                        Audit Node: {{ $client->business_name ?? 'Unconfigured Shop' }}
                    </h2>
                    <p class="text-slate-500 font-bold text-sm uppercase tracking-widest mt-1">Granular Client Workspace Oversight</p>
                </div>
                <div>
                    <form action="{{ route('admin.command-center.client.toggle', $client->id) }}" method="POST">
                        @csrf
                        <button type="submit" 
                                class="w-full sm:w-auto inline-block font-black text-xs uppercase tracking-widest px-5 py-3 rounded-xl transition border-2 shadow-sm text-center"
                                style="{{ $client->is_restricted ? 'background-color: #EF4444; color: #FFFFFF; border-color: #DC2626;' : 'background-color: #FFFFFF; color: #EF4444; border-color: #FEE2E2;' }}">
                            {{ $client->is_restricted ? 'Unsuspend Node Access' : 'Trigger Immediate Suspension' }}
                        </button>
                    </form>
                </div>
            </div>

            {{-- LIVE NOTIFICATION TOAST BARS --}}
            @if (session('status'))
                <div class="bg-slate-900 border-l-8 border-[#FFD22D] p-6 rounded-r-2xl shadow-md">
                    <p class="font-black text-base text-[#FFFFFF]">{{ session('status') }}</p>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                
                {{-- LEFT CORE PANEL: STYLING OVERRIDES & IDENTITY PARAMETERS (7/12) --}}
                <div class="lg:col-span-7 bg-[#FFFFFF] rounded-[2rem] border-4 border-slate-900 shadow-xl p-6 sm:p-8 space-y-6">
                    <div>
                        <h3 class="text-xl font-black text-[#0F2D5A] tracking-tight">Visual Theme Configuration Overrides</h3>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mt-1">Directly manipulates directory link attributes and solo site templates</p>
                    </div>

                    <form action="{{ route('admin.command-center.client.update', $client->id) }}" method="POST" class="space-y-5">
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

                {{-- RIGHT CORE PANEL: EXTENDED WORKSPACE METRICS (5/12) --}}
                <div class="lg:col-span-5 space-y-6">
                    
                    {{-- CONTACT PROFILE CARD --}}
                    <div class="bg-[#FFFFFF] rounded-3xl border-2 border-[#F0F0F0] p-6 shadow-sm space-y-4">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl bg-slate-100 border border-slate-200 flex items-center justify-center font-black text-lg text-[#0F2D5A]">
                                {{ strtoupper(substr($client->name, 0, 2)) }}
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
                                <span class="font-black text-xl text-[#0F2D5A] block">{{ $client->clients_count }}</span>
                                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Roster</span>
                            </div>
                            <div class="bg-[#F0F0F0] p-3 rounded-xl border border-slate-200/60">
                                <span class="font-black text-xl text-[#0F2D5A] block">0</span>
                                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Estimates</span>
                            </div>
                            <div class="bg-[#F0F0F0] p-3 rounded-xl border border-slate-200/60">
                                <span class="font-black text-xl text-[#0F2D5A] block">0</span>
                                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Tasks</span>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </main>

</body>
</html>