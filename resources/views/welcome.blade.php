<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Contractor Specialties | Find Verified Local Trade Experts</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-[#FFFFFF] text-[#3C3C3C] antialiased">

    {{-- MODERN STICKY NAVIGATION HEADER --}}
    <header class="sticky top-0 z-50 bg-[#FFFFFF]/90 backdrop-blur-md border-b border-[#F0F0F0] transition-all">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            <div class="flex items-center gap-3">
                {{-- High-Authority Shield Logo Mark --}}
                <div class="w-10 h-10 bg-[#1E3C5A] rounded-xl flex items-center justify-center shadow-md">
                    <svg class="w-5 h-5 text-[#FFD22D]" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944a11.954 11.954 0 007.834 3.056 10.03 10.03 0 01-1.63 5.485c-.443.623-.933 1.2-1.464 1.725A11.921 11.921 0 0110 16.206 11.92 11.92 0 014.26 12.21a10.03 10.03 0 01-1.63-5.485c-.443-.623-.42-1.144-.464-1.726zM10 14.426a9.927 9.927 0 004.81-3.328 8.026 8.026 0 01-1.341 4.295A9.946 9.946 0 0110 17.185a9.946 9.946 0 01-3.469-1.792 8.026 8.026 0 01-1.34-4.295 9.927 9.927 0 004.81 3.328z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <span class="text-xl font-black text-[#0F2D5A] tracking-tight uppercase">Contractor<span class="text-[#FFD22D]">Specialties</span></span>
            </div>
            
            <nav class="hidden md:flex items-center space-x-8 font-bold text-sm text-[#3C3C4B]">
                <a href="#specialties" class="hover:text-[#1E3C5A] transition">Browse Trades</a>
                <a href="#verification-process" class="hover:text-[#1E3C5A] transition">Verification Standards</a>
                <a href="#pro-portal" class="bg-[#0F2D5A] hover:bg-[#1E3C5A] text-[#FFFFFF] px-5 py-2.5 rounded-xl transition shadow-md border border-[#0F2D5A]">
                    Contractor Portal
                </a>
            </nav>
        </div>
    </header>

    {{-- HERO SECTION: SEGREGATED CONVERSION MULTI-SPLIT --}}
    <section class="relative bg-[#0F2D5A] overflow-hidden pt-12 pb-20 lg:pt-20 lg:pb-28">
        {{-- Structural background visual grids --}}
        <div class="absolute inset-0 opacity-10 pointer-events-none bg-[radial-gradient(#F0F0F0_1px,transparent_1px)] [background-size:16px_16px]"></div>
        <div class="absolute -top-40 -right-40 w-96 h-96 bg-[#1E3C5A] rounded-full blur-3xl opacity-50 pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-8 items-center">
                
                {{-- CONSUMER HOOK SIDE --}}
                <div class="lg:col-span-7 space-y-6 text-center lg:text-left">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#1E3C5A] border border-slate-700 text-[#FFD22D] text-xs font-black uppercase tracking-widest">
                        ⚡ Zero Broker Fees • Direct Contact
                    </div>
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black text-[#FFFFFF] tracking-tight leading-none">
                        Find Verified, Local <span class="text-[#FFD22D]">Trade Specialists</span> Near You.
                    </h1>
                    <p class="text-[#F0F0F0] text-lg sm:text-xl font-medium max-w-2xl mx-auto lg:mx-0 leading-relaxed">
                        Skip the automated project brokers. Connect directly with verified local subcontractors and specialized service crews who are actively operating in your neighborhood.
                    </p>

                    {{-- CONSUMER QUICK LIVE SEARCH GRID CONTAINER --}}
                    <div class="pt-4 max-w-xl mx-auto lg:mx-0">
                        <form action="#" class="bg-[#FFFFFF] p-2 rounded-2xl shadow-2xl border-2 border-slate-800 flex flex-col sm:flex-row gap-2">
                            <div class="flex-1 flex items-center px-3 gap-2">
                                <svg class="w-5 h-5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                <input type="text" placeholder="What trade do you need? (e.g. Electrician)" class="w-full text-base font-bold text-[#3C3C3C] placeholder-slate-400 bg-transparent border-none focus:ring-0 focus:outline-none py-3">
                            </div>
                            <button type="submit" class="bg-[#FFC32D] hover:bg-[#FFD22D] text-[#0F2D5A] font-black uppercase tracking-wider text-sm px-6 py-4 rounded-xl shadow transition active:scale-95 whitespace-nowrap">
                                Find Experts
                            </button>
                        </form>
                    </div>
                </div>

                {{-- CONTRACTOR "TROJAN HORSE" ACQUISITION CORE CARD --}}
                <div id="pro-portal" class="lg:col-span-5">
                    <div class="bg-[#FFFFFF] rounded-[2rem] border-4 border-slate-900 shadow-2xl p-6 sm:p-8 relative overflow-hidden">
                        <div class="absolute top-0 right-0 bg-[#FFD22D] text-[#0F2D5A] text-[9px] font-black uppercase tracking-widest px-4 py-1 rounded-bl-xl shadow-sm">
                            Pro Placement Node
                        </div>
                        
                        <h3 class="text-2xl font-black text-[#0F2D5A] tracking-tight mb-2">Are You a Local Contractor?</h3>
                        <p class="text-[#3C3C4B] font-bold text-sm leading-relaxed mb-6">
                            Claim your specialized regional profile directory slot to verify your trade authority and unlock friction-free direct leads.
                        </p>

                        {{-- FRICTIONLESS PROTOCOL LOOP (SMS INTAKE) --}}
                        <form action="#" class="space-y-4">
                            <div>
                                <label class="block text-xs font-black text-[#3C3C4B] uppercase tracking-widest mb-2">Company / Business Name</label>
                                <input type="text" required placeholder="e.g. Apex Electrical Contractors" class="w-full bg-[#F0F0F0] border-2 border-slate-200 rounded-xl text-[#3C3C3C] placeholder-slate-400 font-bold py-3.5 px-4 focus:border-[#1E3C5A] focus:bg-[#FFFFFF] focus:ring-0 focus:outline-none transition text-base">
                            </div>

                            <div>
                                <label class="block text-xs font-black text-[#3C3C4B] uppercase tracking-widest mb-2">Your Mobile Communications Line</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none font-black text-slate-400">+1</div>
                                    <input type="tel" required placeholder="(555) 000-0000" class="w-full bg-[#F0F0F0] border-2 border-slate-200 rounded-xl text-[#3C3C3C] placeholder-slate-400 font-bold py-3.5 pl-10 pr-4 focus:border-[#1E3C5A] focus:bg-[#FFFFFF] focus:ring-0 focus:outline-none transition text-base">
                                </div>
                            </div>

                            <button type="submit" class="w-full bg-[#0F2D5A] hover:bg-[#1E3C5A] text-[#FFFFFF] font-black text-base uppercase tracking-wider py-4 rounded-xl shadow-lg transition transform active:scale-95 border border-[#0F2D5A]">
                                Secure Instant Profile Slot &rarr;
                            </button>
                        </form>

                        <div class="mt-6 pt-4 border-t border-[#F0F0F0] flex items-center justify-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span>
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Instant Activation Protocol Live</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- AUTHORITY LOGO BAR STACK --}}
    <section class="bg-[#F0F0F0] py-6 border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p class="text-[11px] font-black text-slate-400 uppercase tracking-[0.2em]">Verified Registry Operations Structural Mapping</p>
        </div>
    </section>

    {{-- PROGRAMMATIC SPECIALTY MATRIX GRID --}}
    <section id="specialties" class="py-20 lg:py-28 bg-[#FFFFFF]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
            <div class="text-center max-w-3xl mx-auto space-y-3">
                <h2 class="text-3xl sm:text-4xl font-black text-[#0F2D5A] tracking-tight">Programmatic Trade Directories</h2>
                <div class="w-16 h-1.5 bg-[#FFC32D] mx-auto rounded-full"></div>
                <p class="text-[#3C3C4B] font-bold text-base sm:text-lg">Select a specialized trade directory sector below to crawl localized service operators.</p>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 sm:gap-6">
                @php
                    $trades = [
                        ['name' => 'General Contracting', 'icon' => '🏗️', 'count' => 'Verified Operators'],
                        ['name' => 'Electrical Systems', 'icon' => '⚡', 'count' => 'Precision Crew Nodes'],
                        ['name' => 'Precision Plumbing', 'icon' => '💧', 'count' => 'Emergency Handlers'],
                        ['name' => 'Roofing & Exterior', 'icon' => '🏠', 'count' => 'Structural Experts'],
                        ['name' => 'HVAC & Mechanical', 'icon' => '❄️', 'count' => 'Climate Control Units'],
                        ['name' => 'Masonry & Concrete', 'icon' => '🧱', 'count' => 'Foundation Masters'],
                        ['name' => 'Painting & Finishing', 'icon' => '🎨', 'count' => 'Detail Operators'],
                        ['name' => 'Excavation & Site', 'icon' => '🚜', 'count' => 'Heavy Mach Operations']
                    ];
                @endphp

                @foreach($trades as $trade)
                    <a href="#" class="group bg-[#FFFFFF] border-2 border-[#F0F0F0] rounded-2xl p-6 transition-all hover:border-[#1E3C5A] hover:shadow-xl hover:-translate-y-1 block text-left">
                        <div class="text-4xl mb-4 transform group-hover:scale-110 transition-transform duration-200">{{ $trade['icon'] }}</div>
                        <h4 class="font-black text-lg text-[#0F2D5A] group-hover:text-[#1E3C5A] transition-colors leading-tight mb-1">{{ $trade['name'] }}</h4>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">{{ $trade['count'] }}</p>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    {{-- TRUST AND STRUCTURAL STANDARDS STACK --}}
    <section id="verification-process" class="py-20 lg:py-28 bg-[#F0F0F0] border-t border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-16">
            <div class="text-center max-w-2xl mx-auto space-y-3">
                <span class="text-xs font-black text-[#1E3C5A] bg-[#1E3C5A]/10 border border-[#1E3C5A]/20 px-4 py-1.5 rounded-full uppercase tracking-widest">Platform Safeguards</span>
                <h2 class="text-3xl sm:text-4xl font-black text-[#0F2D5A] tracking-tight">How We Eradicate Friction</h2>
                <p class="text-[#3C3C4B] font-bold text-base">We strip out the hidden fees, project brokers, and middle-tier markups completely.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                {{-- Point 1 --}}
                <div class="bg-[#FFFFFF] p-8 rounded-3xl border border-slate-200 shadow-sm space-y-4">
                    <div class="w-12 h-12 rounded-xl bg-[#1E3C5A] text-[#FFD22D] flex items-center justify-center shadow-inner">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    </div>
                    <h3 class="text-xl font-black text-[#0F2D5A]">Verified Registries Only</h3>
                    <p class="text-sm font-bold text-[#3C3C4B] leading-relaxed">
                        Every contractor must pass a structural validation sequence mapping their identity to active state operational nodes before gaining visibility indicators.
                    </p>
                </div>

                {{-- Point 2 --}}
                <div class="bg-[#FFFFFF] p-8 rounded-3xl border border-slate-200 shadow-sm space-y-4">
                    <div class="w-12 h-12 rounded-xl bg-[#1E3C5A] text-[#FFD22D] flex items-center justify-center shadow-inner">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                    </div>
                    <h3 class="text-xl font-black text-[#0F2D5A]">Direct Line Connectivity</h3>
                    <p class="text-sm font-bold text-[#3C3C4B] leading-relaxed">
                        We never hide phone numbers or block direct emails. Homeowners communicate straight with the source to guarantee pure procurement speed.
                    </p>
                </div>

                {{-- Point 3 --}}
                <div class="bg-[#FFFFFF] p-8 rounded-3xl border border-slate-200 shadow-sm space-y-4">
                    <div class="w-12 h-12 rounded-xl bg-[#1E3C5A] text-[#FFD22D] flex items-center justify-center shadow-inner">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <h3 class="text-xl font-black text-[#0F2D5A]">Guaranteed Heartbeats</h3>
                    <p class="text-sm font-bold text-[#3C3C4B] leading-relaxed">
                        Dead entries are purged dynamically. Our system runs constant communication tests to guarantee every listed trade asset is answering calls.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- TROJAN HORSE BOTTOM LEAD ACQUISITION FOOTER --}}
    <section class="bg-[#0F2D5A] py-16 lg:py-20 relative overflow-hidden">
        <div class="absolute -bottom-20 -left-20 w-80 h-80 bg-[#1E3C5A] rounded-full blur-3xl opacity-40 pointer-events-none"></div>
        
        <div class="max-w-5xl mx-auto px-4 text-center space-y-8 relative z-10">
            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-black text-[#FFFFFF] tracking-tight max-w-3xl mx-auto leading-none">
                Stop Letting High-Margin Local Projects Slide to Competitors.
            </h2>
            <p class="text-[#F0F0F0] text-base sm:text-lg font-bold max-w-xl mx-auto opacity-90">
                Secure your regional directory block parameters right now. It takes 30 seconds to lock down your node before your territory fills up.
            </p>

            <div class="max-w-md mx-auto">
                <form action="#" class="flex flex-col sm:flex-row gap-3">
                    <input type="tel" required placeholder="Enter mobile number..." class="w-full rounded-xl border-2 border-slate-700 bg-[#FFFFFF] py-3.5 px-4 font-bold text-[#3C3C3C] text-center focus:ring-0 focus:outline-none focus:border-[#FFD22D] text-base">
                    <button type="submit" class="w-full sm:w-auto bg-[#FFC32D] hover:bg-[#FFD22D] text-[#0F2D5A] font-black uppercase tracking-wider text-sm py-4 px-8 rounded-xl shadow transition active:scale-95 whitespace-nowrap">
                        Claim Slot Now
                    </button>
                </form>
            </div>
        </div>
    </section>

    {{-- FOOTER MAPPING --}}
    <footer class="bg-[#FFFFFF] border-t border-[#F0F0F0] py-8 text-center text-xs font-bold text-slate-400 uppercase tracking-widest">
        <div class="max-w-7xl mx-auto px-4 flex flex-col sm:flex-row justify-between items-center gap-4">
            <p>&copy; {{ date('Y') }} Contractor Specialties. All rights reserved.</p>
            <div class="flex space-x-6">
                <a href="#" class="hover:text-[#0F2D5A]">Privacy Policy</a>
                <a href="#" class="hover:text-[#0F2D5A]">Terms of Service</a>
            </div>
        </div>
    </footer>

</body>
</html>