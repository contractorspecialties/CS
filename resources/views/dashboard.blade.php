<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ auth()->user()->is_gc ? 'Orchestrator Grid' : 'Trade Workspace' }} | Contractor Specialties</title>
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
<body class="bg-[#F0F0F0] text-[#3C3C3C] antialiased" x-data="{ userMenuOpen: false }">

    {{-- MASTER NAVIGATION BAR --}}
    <header class="bg-[#FFFFFF] border-b border-[#F0F0F0] sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-24 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="/dashboard" class="block transition active:scale-95">
                    <img src="{{ asset('images/CS-logo-horizontal-750.webp') }}" alt="Contractor Specialties" class="h-14 w-auto object-contain">
                </a>
                <span class="bg-slate-900 text-[#FFD22D] text-[10px] font-black uppercase tracking-widest px-3 py-1 rounded-md hidden sm:inline-block">
                    {{ auth()->user()->is_gc ? 'GC Core Node' : 'Subcontractor Hub' }}
                </span>
            </div>
            
            <div class="flex items-center gap-4 relative">
                <span class="text-xs font-black text-emerald-600 uppercase tracking-widest bg-emerald-50 border border-emerald-200 px-3 py-1.5 rounded-xl flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span> Profile Active
                </span>

                {{-- USER ACCOUNT DROPDOWN --}}
                <div class="relative">
                    <button @click="userMenuOpen = !userMenuOpen" @click.away="userMenuOpen = false" class="w-10 h-10 rounded-xl bg-[#0F2D5A] text-[#FFFFFF] font-black text-sm flex items-center justify-center border-2 border-slate-900 transition active:scale-95">
                        {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                    </button>
                    <div x-show="userMenuOpen" x-transition class="absolute right-0 mt-2 w-48 bg-[#FFFFFF] rounded-xl shadow-xl border border-[#F0F0F0] py-2 z-50" style="display: none;">
                        <span class="block px-4 py-2 text-[10px] font-black text-slate-400 uppercase tracking-wider border-b border-[#F0F0F0] mb-1">
                            Node: #{{ auth()->user()->id }}
                        </span>
                        <form method="POST" action="/logout" class="block w-full">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 text-xs font-black text-red-500 hover:bg-red-50 transition uppercase tracking-widest">
                                Terminate Session
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </header>

    {{-- SYSTEM CONTROL GRID CONTROLLER --}}
    <main class="py-8 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            @if(auth()->user()->is_gc)
                {{-- ========================================================================= --}}
                {{-- GENERAL CONTRACTOR MULTI-TENANT CONSOLE TIER: "THE ORCHESTRATOR GRID"      --}}
                {{-- ========================================================================= --}}
                
                {{-- WELCOME COMPONENT FRAME --}}
                <div class="bg-slate-900 text-[#FFFFFF] p-8 rounded-[2rem] border-4 border-slate-950 shadow-xl relative overflow-hidden">
                    <div class="absolute inset-0 opacity-5 bg-[radial-gradient(#F0F0F0_1px,transparent_1px)] [background-size:16px_16px] pointer-events-none"></div>
                    <div class="relative z-10 space-y-1">
                        <span class="text-[#FFD22D] text-[10px] font-black uppercase tracking-widest bg-slate-800 px-3 py-1 rounded-md">The Orchestrator Matrix</span>
                        <h1 class="text-3xl font-black tracking-tight pt-2">Welcome Back, {{ auth()->user()->name }}</h1>
                        <p class="text-slate-400 font-bold text-sm">Monitor independent sub-tier compliance parameters, track milestones, and query specialty credentials live.</p>
                    </div>
                </div>

                {{-- CORE TELEMETRY METRIC SUMMARY TILES --}}
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="bg-[#FFFFFF] p-6 rounded-2xl border-2 border-[#F0F0F0] shadow-sm flex items-center justify-between">
                        <div>
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block">Monitored Sub Roster</span>
                            <span class="text-2xl font-black text-[#0F2D5A] block mt-1">14 Tradesmen</span>
                        </div>
                        <span class="text-2xl bg-slate-100 p-3 rounded-xl">🏗️</span>
                    </div>
                    <div class="bg-[#FFFFFF] p-6 rounded-2xl border-2 border-[#F0F0F0] shadow-sm flex items-center justify-between">
                        <div>
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block">Compliance Health</span>
                            <span class="text-2xl font-black text-emerald-600 block mt-1">100% Verified</span>
                        </div>
                        <span class="text-2xl bg-emerald-50 text-emerald-600 p-3 rounded-xl">🛡️</span>
                    </div>
                    <div class="bg-[#FFFFFF] p-6 rounded-2xl border-2 border-[#F0F0F0] shadow-sm flex items-center justify-between">
                        <div>
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block">Active Project Pools</span>
                            <span class="text-2xl font-black text-[#0F2D5A] block mt-1">3 Pipelines</span>
                        </div>
                        <span class="text-2xl bg-slate-100 p-3 rounded-xl">📋</span>
                    </div>
                </div>

                {{-- ROSTER OVERSIGHT TRACKING DATAGRID --}}
                <div class="bg-[#FFFFFF] rounded-[2.5rem] border-4 border-slate-900 shadow-2xl p-6 sm:p-8 space-y-6">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-[#F0F0F0] pb-4">
                        <div>
                            <h2 class="text-xl font-black text-[#0F2D5A] tracking-tight">Independent Subcontractor Compliance Ledger</h2>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mt-0.5">Real-time status tracking for general liability certificates and worker documentation lookups</p>
                        </div>
                        <button class="bg-[#0F2D5A] hover:bg-[#1E3C5A] text-[#FFFFFF] text-xs font-black uppercase tracking-wider px-4 py-3 rounded-xl transition active:scale-95">
                            + Query New Specialty Node
                        </button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-slate-200 text-[10px] font-black text-slate-400 uppercase tracking-widest bg-slate-50">
                                    <th class="py-4 px-4">Subcontractor Operator</th>
                                    <th class="py-4 px-4">Trade Focus Namespace</th>
                                    <th class="py-4 px-4">Liability Verification Status</th>
                                    <th class="py-4 px-4 text-right">Actions Matrix</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm font-bold divide-y divide-slate-100">
                                <tr>
                                    <td class="py-4 px-4">
                                        <p class="text-slate-900 font-black">Miller & Sons Handyman Services</p>
                                        <p class="text-xs text-slate-400">miller.sons@gmail.com</p>
                                    </td>
                                    <td class="py-4 px-4 text-slate-600">🛠️ Carpentry & General Repairs</td>
                                    <td class="py-4 px-4"><span class="bg-emerald-50 border border-emerald-200 text-emerald-700 text-[10px] uppercase font-black tracking-wider px-2.5 py-1 rounded-md">✓ Active Verified</span></td>
                                    <td class="py-4 px-4 text-right"><button class="text-xs font-black text-[#0F2D5A] hover:underline uppercase tracking-wider">Request Update</button></td>
                                </tr>
                                <tr>
                                    <td class="py-4 px-4">
                                        <p class="text-slate-900 font-black">Bolt Electric Loops</p>
                                        <p class="text-xs text-slate-400">marcus@boltelectric.com</p>
                                    </td>
                                    <td class="py-4 px-4 text-slate-600">⚡ High-Voltage Infrastructure</td>
                                    <td class="py-4 px-4"><span class="bg-emerald-50 border border-emerald-200 text-emerald-700 text-[10px] uppercase font-black tracking-wider px-2.5 py-1 rounded-md">✓ Active Verified</span></td>
                                    <td class="py-4 px-4 text-right"><button class="text-xs font-black text-[#0F2D5A] hover:underline uppercase tracking-wider">Request Update</button></td>
                                </tr>
                                <tr>
                                    <td class="py-4 px-4">
                                        <p class="text-slate-900 font-black">Jack's Lawns Platform</p>
                                        <p class="text-xs text-slate-400">jack@jackslawns.com</p>
                                    </td>
                                    <td class="py-4 px-4 text-slate-600">🌱 Organic Grounds Management</td>
                                    <td class="py-4 px-4"><span class="bg-amber-50 border border-amber-200 text-amber-700 text-[10px] uppercase font-black tracking-wider px-2.5 py-1 rounded-md">⚠ Pending Manual Review</span></td>
                                    <td class="py-4 px-4 text-right"><button class="text-xs font-black text-amber-600 hover:underline uppercase tracking-wider">Audit Log Trace</button></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            @else
                {{-- ========================================================================= --}}
                {{-- STANDARD TRADESMAN/SUBCONTRACTOR REVENUE LAYER TIERS                      --}}
                {{-- ========================================================================= --}}
                
                {{-- WELCOME COMPONENT FRAME --}}
                <div class="bg-[#0F2D5A] text-[#FFFFFF] p-8 rounded-[2rem] border-4 border-slate-900 shadow-xl relative overflow-hidden">
                    <div class="absolute inset-0 opacity-5 bg-[radial-gradient(#F0F0F0_1px,transparent_1px)] [background-size:16px_16px] pointer-events-none"></div>
                    <div class="relative z-10 space-y-1">
                        <span class="bg-[#FFC32D] text-[#0F2D5A] text-[10px] font-black uppercase tracking-widest px-3 py-1 rounded-md">Independent Operator Hub</span>
                        <h1 class="text-3xl font-black tracking-tight pt-2">Welcome to Your Trade Workspace</h1>
                        <p class="text-[#F0F0F0]/80 font-bold text-sm">Manage your local public visibility profile footprints and pipeline notifications dynamically.</p>
                    </div>
                </div>

                {{-- SUB-TIER PERFORMANCE CARD SHEETS --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-8 items-start">
                    
                    {{-- COMPONENT LEFT: CORE PARAMETERS --}}
                    <div class="bg-[#FFFFFF] rounded-[2.5rem] border-4 border-slate-900 shadow-2xl p-6 sm:p-8 space-y-6">
                        <div>
                            <h2 class="text-xl font-black text-[#0F2D5A] tracking-tight">Active Directory Configuration</h2>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mt-0.5">Your network lookup parameters on our public routing matrix</p>
                        </div>

                        <div class="space-y-4">
                            <div class="bg-[#F0F0F0] p-5 rounded-2xl border border-slate-200">
                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1">Authenticated Account Operator</span>
                                <p class="font-black text-base text-[#0F2D5A]">{{ auth()->user()->name }}</p>
                                <p class="text-xs font-bold text-slate-500 mt-0.5">{{ auth()->user()->email }}</p>
                            </div>
                            
                            <div class="bg-[#F0F0F0] p-5 rounded-2xl border border-slate-200">
                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1">Carrier Network Target (SMS Auth Path)</span>
                                <p class="font-black text-base text-[#0F2D5A]">{{ auth()->user()->phone ?? 'No Communications Line Provisioned' }}</p>
                            </div>
                        </div>

                        <div class="bg-slate-50 rounded-2xl border border-slate-200 p-4 text-xs font-bold text-slate-500 leading-relaxed">
                            💡 Want to customize your theme shades or map an external custom domain routing rule to your listing? Drop a line to <strong>support@contractorspecialties.com</strong> and our command center admin deck will bind the parameter adjustments directly to your entity block!
                        </div>
                    </div>

                    {{-- COMPONENT RIGHT: TELEMETRY & PREMIUM METER CONSOLE (CPP PIPELINE BLOCKERS) --}}
                    <div class="space-y-6">
                        
                        {{-- DIRECTORY ENGAGEMENT TELEMETRY --}}
                        <div class="bg-[#FFFFFF] rounded-3xl border-2 border-[#F0F0F0] p-6 shadow-sm space-y-4">
                            <h3 class="text-xs font-black text-[#0F2D5A] uppercase tracking-widest">Public Funnel Engagement Traffic</h3>
                            <div class="grid grid-cols-2 gap-4 text-center">
                                <div class="bg-[#F0F0F0] p-4 rounded-xl border border-slate-200/60">
                                    <span class="font-black text-2xl text-[#0F2D5A] block">0</span>
                                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Directory Views</span>
                                </div>
                                <div class="bg-[#F0F0F0] p-4 rounded-xl border border-slate-200/60">
                                    <span class="font-black text-2xl text-emerald-600 block">0</span>
                                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Direct Leads</span>
                                </div>
                            </div>
                        </div>

                        {{-- PREMIUM CORE SAAS EXTENSION BLOCKS --}}
                        <div class="bg-[#FFFFFF] rounded-3xl border-2 border-[#F0F0F0] p-6 shadow-sm space-y-4">
                            <div class="flex items-center gap-3">
                                <span class="w-2.5 h-2.5 rounded-full bg-[#FFC32D] animate-pulse"></span>
                                <h4 class="text-sm font-black text-[#0F2D5A] uppercase tracking-wider">Premium Operations Suite Pending (CPP)</h4>
                            </div>
                            <p class="text-xs font-bold text-slate-500 leading-relaxed">
                                Your current workspace is locked on our complimentary public directory funnel tier. High-velocity automated tool modules for generating professional estimates, building programmatic subcontractor rosters, tracking invoice timelines, and accepting instant client bank payouts are initializing behind the scenes.
                            </p>
                            <button disabled class="w-full bg-[#F0F0F0] text-slate-400 font-black text-xs uppercase tracking-widest py-3.5 rounded-xl border border-slate-200 cursor-not-allowed text-center">
                                Advanced SaaS Dashboard Modules Initializing...
                            </button>
                        </div>

                    </div>

                </div>
            @endif

        </div>
    </main>

</body>
</html>