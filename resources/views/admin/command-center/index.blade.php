<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>HQ Command Center | Contractor Specialties</title>
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
                <a href="/" class="block transition active:scale-95">
                    <img src="{{ asset('images/CS-logo-horizontal-750.webp') }}" alt="Contractor Specialties" class="h-14 w-auto object-contain">
                </a>
                <span class="bg-slate-900 text-[#FFD22D] text-[10px] font-black uppercase tracking-widest px-3 py-1 rounded-md">
                    HQ Control Core
                </span>
            </div>
            <div class="hidden sm:flex items-center gap-4">
                <span class="text-[#0F2D5A] font-black text-xs uppercase tracking-widest bg-[#F0F0F0] px-4 py-2 rounded-full border border-slate-200 shadow-sm">
                    System Live • {{ now()->format('H:i:s T') }}
                </span>
            </div>
        </div>
    </header>

    {{-- CORE WORKSPACE CONTENT PANEL --}}
    <main class="py-8 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            {{-- PAGE TITLE --}}
            <div>
                <h2 class="font-black text-3xl md:text-4xl text-[#0F2D5A] tracking-tight leading-none">
                    HQ Command Center
                </h2>
                <p class="text-slate-500 font-bold text-sm uppercase tracking-widest mt-2">Global System Telemetry & Tenant Control</p>
            </div>

            {{-- LIVE ACTION PERFORMANCE NOTIFICATIONS --}}
            @if (session('status'))
                <div class="bg-slate-900 border-l-8 border-[#FFD22D] p-6 rounded-r-2xl shadow-md">
                    <p class="font-black text-base text-[#FFFFFF]">{{ session('status') }}</p>
                </div>
            @endif

            {{-- MACRO TELEMETRY PULSE CARDS --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
                
                <div class="bg-[#FFFFFF] rounded-2xl p-6 border-2 border-[#F0F0F0] shadow-sm flex flex-col justify-between">
                    <span class="text-xs font-black text-slate-400 uppercase tracking-widest block mb-2">Total Tenants</span>
                    <div class="flex items-baseline gap-2">
                        <span class="text-4xl font-black text-[#0F2D5A]">{{ $systemMetrics['total_contractors'] }}</span>
                        <span class="text-xs font-bold text-slate-400">Registered</span>
                    </div>
                </div>

                <div class="bg-[#FFFFFF] rounded-2xl p-6 border-2 border-[#F0F0F0] shadow-sm flex flex-col justify-between">
                    <span class="text-xs font-black text-slate-400 uppercase tracking-widest block mb-2">Workspace Breakdown</span>
                    <div class="flex items-center gap-4">
                        <div>
                            <span class="text-2xl font-black text-[#1E3C5A]">{{ $systemMetrics['active_gcs'] }}</span>
                            <span class="text-[10px] font-black text-orange-600 uppercase tracking-wider block">GC Hubs</span>
                        </div>
                        <div class="border-l-2 border-[#F0F0F0] h-8"></div>
                        <div>
                            <span class="text-2xl font-black text-slate-700">{{ $systemMetrics['standard_subs'] }}</span>
                            <span class="text-[10px] font-black text-slate-500 uppercase tracking-wider block">Subs</span>
                        </div>
                    </div>
                </div>

                <div class="bg-[#FFFFFF] rounded-2xl p-6 border-2 border-[#F0F0F0] shadow-sm flex flex-col justify-between">
                    <span class="text-xs font-black text-slate-400 uppercase tracking-widest block mb-2">SaaS Activity Pulse</span>
                    <div class="space-y-1">
                        <p class="text-sm font-bold text-[#3C3C3C]">
                            <span class="font-black text-[#0F2D5A]">{{ $systemMetrics['total_quotes_sent'] }}</span> Proposals Sent
                        </p>
                        <p class="text-sm font-bold text-[#3C3C3C]">
                            <span class="font-black text-[#0F2D5A]">{{ $systemMetrics['total_appointments'] }}</span> Logged Tasks
                        </p>
                    </div>
                </div>

                <div class="bg-[#FFFFFF] rounded-2xl p-6 border-2 border-slate-900 shadow-sm flex flex-col justify-between"
                     style="background-color: {{ $systemMetrics['restricted_nodes'] > 0 ? '#FFF5F5' : '#FFFFFF' }}">
                    <span class="text-xs font-black text-slate-400 uppercase tracking-widest block mb-2">Circuit Breakers</span>
                    <div class="flex items-baseline gap-2">
                        <span class="text-4xl font-black {{ $systemMetrics['restricted_nodes'] > 0 ? 'text-red-600' : 'text-[#0F2D5A]' }}">
                            {{ $systemMetrics['restricted_nodes'] }}
                        </span>
                        <span class="text-xs font-black uppercase tracking-wider text-slate-400">Suspended</span>
                    </div>
                </div>

            </div>

            {{-- MASTER CLIENT TELEMETRY TRACK TABLE --}}
            <div class="bg-[#FFFFFF] rounded-[2rem] border-4 border-slate-900 shadow-xl overflow-hidden">
                
                <div class="bg-slate-900 px-8 py-5 flex items-center justify-between">
                    <h3 class="text-[#FFFFFF] font-black text-lg tracking-tight">Active Tenant Registry Matrix</h3>
                    <span class="text-slate-400 text-xs font-black uppercase tracking-widest">Real-time DB Connection</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b-2 border-[#F0F0F0] bg-slate-50 text-[11px] font-black text-[#3C3C4B] uppercase tracking-widest">
                                <th class="py-4 px-6">Operational Node</th>
                                <th class="py-4 px-6">Contact Vector</th>
                                <th class="py-4 px-6">Workspace Mode</th>
                                <th class="py-4 px-6 text-center">SaaS Metrics (CPP)</th>
                                <th class="py-4 px-6 text-right">Status / Core Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y-2 divide-[#F0F0F0] font-medium text-sm text-[#3C3C3C]">
                            @forelse($clients as $client)
                                <tr class="hover:bg-slate-50/80 transition-colors">
                                    
                                    {{-- Node details --}}
                                    <td class="py-5 px-6">
                                        <p class="font-black text-base text-[#0F2D5A]">{{ $client->business_name ?? 'Unconfigured Shop' }}</p>
                                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mt-0.5">Joined {{ $client->created_at->format('M Y') }}</p>
                                    </td>

                                    {{-- Contact handles --}}
                                    <td class="py-5 px-6">
                                        <p class="font-bold text-slate-800">{{ $client->name }}</p>
                                        <p class="text-xs font-bold text-slate-500 mt-0.5">{{ $client->email }}</p>
                                    </td>

                                    {{-- Workspace validation mode indicators --}}
                                    <td class="py-5 px-6">
                                        @if($client->is_gc)
                                            <span class="bg-orange-100 text-orange-800 text-[10px] font-black px-2.5 py-1 rounded uppercase tracking-widest border border-orange-200">
                                                General Contractor
                                            </span>
                                        @else
                                            <span class="bg-[#F0F0F0] text-slate-700 text-[10px] font-black px-2.5 py-1 rounded uppercase tracking-widest border border-slate-200">
                                                Standard Sub
                                            </span>
                                        @endif
                                    </td>

                                    {{-- High-Visibility SaaS Usage Metrics counters --}}
                                    <td class="py-5 px-6">
                                        <div class="flex items-center justify-center gap-4 text-xs font-bold text-slate-500">
                                            <div class="text-center" title="Linked Clients">
                                                <span class="font-black text-sm text-[#0F2D5A] block">{{ $client->clients_count }}</span>
                                                <span class="text-[9px] uppercase tracking-wider text-slate-400">Roster</span>
                                            </div>
                                            <div class="text-center" title="Proposals Generated">
                                                <span class="font-black text-sm text-[#0F2D5A] block">0</span>
                                                <span class="text-[9px] uppercase tracking-wider text-slate-400">Docs</span>
                                            </div>
                                            <div class="text-center" title="Logged Tasks">
                                                <span class="font-black text-sm text-[#0F2D5A] block">0</span>
                                                <span class="text-[9px] uppercase tracking-wider text-slate-400">Tasks</span>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Circuit Breaker & Deep Audit links --}}
                                    <td class="py-5 px-6 text-right">
                                        <div class="flex items-center justify-end gap-3">
                                            <a href="{{ route('admin.command-center.client.show', $client->id) }}" class="inline-block bg-[#F0F0F0] hover:bg-slate-200 text-[#0F2D5A] font-black text-xs uppercase tracking-widest px-4 py-2.5 rounded-xl transition">
                                                Audit Node
                                            </a>
                                            
                                            <form action="{{ route('admin.command-center.client.toggle', $client->id) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" 
                                                        onclick="return confirm('Alter operational availability states for {{ $client->business_name }}?')"
                                                        class="inline-block font-black text-xs uppercase tracking-widest px-4 py-2.5 rounded-xl transition border-2"
                                                        style="{{ $client->is_restricted ? 'background-color: #EF4444; color: #FFFFFF; border-color: #DC2626;' : 'background-color: #FFFFFF; color: #EF4444; border-color: #FEE2E2;' }}">
                                                    {{ $client->is_restricted ? 'Unsuspend' : 'Suspend' }}
                                                </button>
                                            </form>
                                        </div>
                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-12 text-center font-bold text-slate-400 text-base">
                                        No tenant profiles registered inside the current directory database cluster.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>

        </div>
    </main>

</body>
</html>