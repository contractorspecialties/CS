<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/webp" href="{{ asset('images/CS-Square.webp') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/CS-Square.webp') }}">
    <title>Contractor Specialties | Find Local Pros You Can Actually Reach</title>
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

   {{-- EXPANDED MEGA-MENU READY NAVIGATION HEADER --}}
    <header class="sticky top-0 z-50 bg-[#FFFFFF]/95 backdrop-blur-md border-b border-[#F0F0F0]" x-data="{ openMenu: null }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-28 flex items-center justify-between relative">
            
            {{-- BRAND LOGO SLOT --}}
            <div class="flex items-center">
                <a href="/" class="block transition active:scale-95 py-2">
                    {{-- Scaled beautifully up to h-16 (64px) or use an arbitrary value like h-[70px] --}}
                    <img src="{{ asset('images/CS-logo-horizontal-750.webp') }}" alt="Contractor Specialties" class="h-16 w-auto object-contain">
                </a>
            </div>
            
            {{-- NAVIGATION CORE (WITH LIVE ANCHORS FOR MEGA MENUS) --}}
            <nav class="hidden md:flex items-center space-x-10 font-bold text-sm text-[#3C3C4B] h-full" @mouseleave="openMenu = null">
                
                {{-- ITEM 1: TRADES MEGA MENU TRIGGER --}}
                <div class="relative h-full flex items-center" @mouseenter="openMenu = 'trades'">
                    <button class="hover:text-[#1E3C5A] transition flex items-center gap-1 h-full border-b-2 border-transparent hover:border-[#1E3C5A] outline-none">
                        Browse Trades
                        <svg class="w-4 h-4 transition-transform duration-200" :class="{'rotate-180 text-[#1E3C5A]': openMenu === 'trades'}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                    </button>

                    {{-- BLUEPRINT MEGA MENU DROP PANEL A --}}
                    <div x-show="openMenu === 'trades'" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 translate-y-4"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 translate-y-4"
                         style="display: none;"
                         class="absolute top-full -right-20 w-[600px] bg-[#FFFFFF] rounded-2xl shadow-2xl border border-[#F0F0F0] p-6 grid grid-cols-2 gap-6 mt-0">
                        
                        <div class="space-y-3">
                            <h4 class="text-xs font-black text-[#0F2D5A] uppercase tracking-widest border-b border-[#F0F0F0] pb-2">Structural Trades</h4>
                            <a href="#specialties" class="block text-sm font-bold hover:text-[#1E3C5A] text-slate-600 transition">🏗️ General Contractors</a>
                            <a href="#specialties" class="block text-sm font-bold hover:text-[#1E3C5A] text-slate-600 transition">🏠 Roofing & Siding</a>
                            <a href="#specialties" class="block text-sm font-bold hover:text-[#1E3C5A] text-slate-600 transition">🧱 Masonry & Concrete</a>
                        </div>
                        <div class="space-y-3">
                            <h4 class="text-xs font-black text-[#0F2D5A] uppercase tracking-widest border-b border-[#F0F0F0] pb-2">Specialty Trades</h4>
                            <a href="#specialties" class="block text-sm font-bold hover:text-[#1E3C5A] text-slate-600 transition">⚡ Electrical Systems</a>
                            <a href="#specialties" class="block text-sm font-bold hover:text-[#1E3C5A] text-slate-600 transition">💧 Precision Plumbing</a>
                            <a href="#specialties" class="block text-sm font-bold hover:text-[#1E3C5A] text-slate-600 transition">❄️ HVAC Mechanical</a>
                        </div>
                    </div>
                </div>

                {{-- ITEM 2: VALUE UTILITIES DROPDOWN --}}
                <div class="relative h-full flex items-center" @mouseenter="openMenu = 'tools'">
                    <button class="hover:text-[#1E3C5A] transition flex items-center gap-1 h-full border-b-2 border-transparent hover:border-[#1E3C5A] outline-none">
                        Our Standards
                        <svg class="w-4 h-4 transition-transform duration-200" :class="{'rotate-180 text-[#1E3C5A]': openMenu === 'tools'}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                    </button>

                    {{-- BLUEPRINT MEGA MENU DROP PANEL B --}}
                    <div x-show="openMenu === 'tools'" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 translate-y-4"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 translate-y-4"
                         style="display: none;"
                         class="absolute top-full left-1/2 -translate-x-1/2 w-[320px] bg-[#FFFFFF] rounded-2xl shadow-2xl border border-[#F0F0F0] p-4 space-y-2 mt-0">
                        <a href="#why-us" class="block p-2.5 rounded-xl hover:bg-[#F0F0F0] transition group">
                            <p class="text-sm font-black text-[#0F2D5A]">Direct Connect Guarantee</p>
                            <p class="text-xs text-slate-400 font-bold mt-0.5">No brokers, markups, or phone shielding.</p>
                        </a>
                        <a href="#verification-process" class="block p-2.5 rounded-xl hover:bg-[#F0F0F0] transition group">
                            <p class="text-sm font-black text-[#0F2D5A]">Active Heartbeat Filter</p>
                            <p class="text-xs text-slate-400 font-bold mt-0.5">We systematically remove dead listings.</p>
                        </a>
                    </div>
                </div>

                <a href="#contractor-growth" class="hover:text-[#1E3C5A] transition h-full flex items-center border-b-2 border-transparent hover:border-[#1E3C5A]">For Tradesmen</a>
                <a href="#gc-tools" class="hover:text-[#1E3C5A] transition h-full flex items-center border-b-2 border-transparent hover:border-[#1E3C5A]">For GCs</a>
                
                {{-- PRIMARY CTA ACTION ANCHOR --}}
                <div class="h-full flex items-center pl-2">
                    <a href="#contractor-signup" class="bg-[#0F2D5A] hover:bg-[#1E3C5A] text-[#FFFFFF] px-6 py-3 rounded-xl transition shadow-md font-black tracking-wide transform active:scale-95 border border-[#0F2D5A]">
                        Join Free Profile
                    </a>
                </div>
            </nav>
        </div>
    </header>

    {{-- HERO SECTION: INSPIRATIONAL HUMAN CONVERSION BLOCK --}}
    <section class="relative bg-[#0F2D5A] text-[#FFFFFF] overflow-hidden pt-16 pb-24 lg:pt-28 lg:pb-32">
        <div class="absolute inset-0 opacity-5 bg-[radial-gradient(#F0F0F0_1px,transparent_1px)] [background-size:20px_20px] pointer-events-none"></div>
        <div class="absolute -top-48 -right-48 w-96 h-96 bg-[#1E3C5A] rounded-full blur-3xl opacity-40 pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="max-w-4xl mx-auto text-center space-y-8">
                <h1 class="text-4xl sm:text-5xl lg:text-7xl font-black tracking-tight leading-none">
                    Find Local Pros You Can <span class="text-[#FFD22D]">Actually Reach</span>.
                </h1>
                <p class="text-[#F0F0F0] text-xl sm:text-2xl font-medium max-w-3xl mx-auto leading-relaxed">
                    No hoops. No hidden numbers. No “we’ll call you back.” Just real people doing real work — right in your community.
                </p>
                
                <div class="flex flex-col sm:flex-row items-center justify-center gap-4 pt-4">
                    <a href="#specialties" class="w-full sm:w-auto bg-[#FFC32D] hover:bg-[#FFD22D] text-[#0F2D5A] font-black uppercase tracking-wider text-sm px-8 py-4 rounded-xl shadow-lg transition transform active:scale-95 text-center">
                        Find Local Experts
                    </a>
                    <a href="#contractor-signup" class="w-full sm:w-auto bg-[#1E3C5A] hover:bg-slate-800 text-[#FFFFFF] border-2 border-slate-700 font-black uppercase tracking-wider text-sm px-8 py-4 rounded-xl transition text-center">
                        Create Your Free Profile
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- SECTION: CONTRACTOR REGISTRATION AREA --}}
    <section id="contractor-signup" class="py-20 bg-[#FFFFFF] border-b border-[#F0F0F0]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                
                <div class="lg:col-span-6 space-y-6 text-left">
                    <span class="text-xs font-black text-[#1E3C5A] bg-[#F0F0F0] px-3 py-1.5 rounded-full uppercase tracking-widest inline-block">Are You a Local Contractor?</span>
                    <h2 class="text-3xl sm:text-4xl lg:text-5xl font-black text-[#0F2D5A] tracking-tight leading-tight">
                        Get a Free Profile That Helps You Get Found
                    </h2>
                    <p class="text-[#3C3C4B] font-bold text-lg leading-relaxed">
                        Whether you’re running a full crew or just getting started with a mower and a dream — you belong here.
                    </p>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                        <div class="flex items-center gap-3 font-bold text-[#3C3C3C]">
                            <span class="w-6 h-6 rounded-md bg-[#FFC32D]/20 text-[#0F2D5A] flex items-center justify-center text-xs font-black">✓</span>
                            A clean, simple web page
                        </div>
                        <div class="flex items-center gap-3 font-bold text-[#3C3C3C]">
                            <span class="w-6 h-6 rounded-md bg-[#FFC32D]/20 text-[#0F2D5A] flex items-center justify-center text-xs font-black">✓</span>
                            A spot in your local directory
                        </div>
                        <div class="flex items-center gap-3 font-bold text-[#3C3C3C]">
                            <span class="w-6 h-6 rounded-md bg-[#FFC32D]/20 text-[#0F2D5A] flex items-center justify-center text-xs font-black">✓</span>
                            A place to show your work
                        </div>
                        <div class="flex items-center gap-3 font-bold text-[#3C3C3C]">
                            <span class="w-6 h-6 rounded-md bg-[#FFC32D]/20 text-[#0F2D5A] flex items-center justify-center text-xs font-black">✓</span>
                            Direct contact channels
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-6">
                    <div class="bg-[#FFFFFF] border-4 border-slate-900 rounded-[2.5rem] shadow-2xl p-6 sm:p-8 space-y-6">
                        <form action="#" class="space-y-4">
                            <div>
                                <label class="block text-xs font-black text-[#3C3C4B] uppercase tracking-widest mb-2">Business Name</label>
                                <input type="text" required placeholder="e.g. Miller & Sons Handyman Services" class="w-full bg-[#F0F0F0] border-2 border-slate-200 rounded-xl text-[#3C3C3C] placeholder-slate-400 font-bold py-3.5 px-4 focus:border-[#1E3C5A] focus:bg-[#FFFFFF] focus:ring-0 focus:outline-none transition text-base">
                            </div>

                            <div>
                                <label class="block text-xs font-black text-[#3C3C4B] uppercase tracking-widest mb-2">Mobile Number</label>
                                <input type="tel" required placeholder="(555) 000-0000" class="w-full bg-[#F0F0F0] border-2 border-slate-200 rounded-xl text-[#3C3C3C] placeholder-slate-400 font-bold py-3.5 px-4 focus:border-[#1E3C5A] focus:bg-[#FFFFFF] focus:ring-0 focus:outline-none transition text-base">
                            </div>

                            <button type="submit" class="w-full bg-[#0F2D5A] hover:bg-[#1E3C5A] text-[#FFFFFF] font-black text-base uppercase tracking-wider py-4 rounded-xl shadow-md transition transform active:scale-95 border border-[#0F2D5A]">
                                Create My Free Profile &rarr;
                            </button>
                        </form>
                        <p class="text-center text-xs font-bold text-slate-400 tracking-wide uppercase">
                            Takes 30 seconds. No fees. No pressure.
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- SECTION: BROWSE LOCAL TRADES GRID --}}
    <section id="specialties" class="py-20 lg:py-24 bg-[#FFFFFF]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
            <div class="text-center max-w-3xl mx-auto space-y-4">
                <h2 class="text-3xl sm:text-4xl font-black text-[#0F2D5A] tracking-tight">Find the Right Person for the Job</h2>
                <p class="text-[#3C3C4B] font-bold text-lg max-w-2xl mx-auto">
                    Every trade. Every skill level. Every neighborhood. If they do the work, you’ll find them here.
                </p>
                <div class="w-12 h-1 bg-[#FFC32D] mx-auto rounded-full"></div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 sm:gap-6">
                @php
                    $trades = [
                        ['name' => 'General Contractors', 'icon' => '🏗️'],
                        ['name' => 'Electricians', 'icon' => '⚡'],
                        ['name' => 'Plumbers', 'icon' => '💧'],
                        ['name' => 'Roofers', 'icon' => '🏠'],
                        ['name' => 'HVAC Techs', 'icon' => '❄️'],
                        ['name' => 'Painters', 'icon' => '🎨'],
                        ['name' => 'Landscapers', 'icon' => '🌱'],
                        ['name' => 'Handymen', 'icon' => '🛠️']
                    ];
                @endphp

                @foreach($trades as $trade)
                    <a href="#" class="group bg-[#FFFFFF] border-2 border-[#F0F0F0] rounded-2xl p-6 transition-all hover:border-[#1E3C5A] hover:shadow-xl hover:-translate-y-1 block text-left">
                        <div class="text-3xl mb-3 transform group-hover:scale-110 transition-transform duration-200">{{ $trade['icon'] }}</div>
                        <h4 class="font-black text-base sm:text-lg text-[#0F2D5A] group-hover:text-[#1E3C5A] transition-colors leading-tight mb-1">{{ $trade['name'] }}</h4>
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-wider group-hover:text-orange-500 transition-colors">Browse Directory &rarr;</span>
                    </a>
                @endforeach
            </div>
            
            <div class="text-center pt-4">
                <p class="text-sm font-bold text-slate-400 uppercase tracking-widest italic">And everyone in between...</p>
            </div>
        </div>
    </section>

    {{-- SECTION: WHY PEOPLE LIKE USING US --}}
    <section id="why-us" class="py-20 lg:py-24 bg-[#F0F0F0] border-t border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-16">
            <div class="text-center max-w-2xl mx-auto space-y-3">
                <h2 class="text-3xl sm:text-4xl font-black text-[#0F2D5A] tracking-tight">Simple. Honest. Local.</h2>
                <p class="text-[#3C3C4B] font-bold text-base">A community-driven alternative built completely on transparency.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="bg-[#FFFFFF] p-6 rounded-2xl border border-slate-200 space-y-3 shadow-sm">
                    <div class="text-2xl">📞</div>
                    <h3 class="text-lg font-black text-[#0F2D5A]">Direct Contact</h3>
                    <p class="text-sm font-bold text-[#3C3C4B] leading-relaxed">
                        Call or message the pro right from their profile. No middlemen, no masked numbers, and no communications padding.
                    </p>
                </div>

                <div class="bg-[#FFFFFF] p-6 rounded-2xl border border-slate-200 space-y-3 shadow-sm">
                    <div class="text-2xl">🧍</div>
                    <h3 class="text-lg font-black text-[#0F2D5A]">Real People, Not Bots</h3>
                    <p class="text-sm font-bold text-[#3C3C4B] leading-relaxed">
                        Every single directory listing belongs to an actual human being who physically lives and operates in your regional area.
                    </p>
                </div>

                <div class="bg-[#FFFFFF] p-6 rounded-2xl border border-slate-200 space-y-3 shadow-sm">
                    <div class="text-2xl">🛡️</div>
                    <h3 class="text-lg font-black text-[#0F2D5A]">Credentials Optional</h3>
                    <p class="text-sm font-bold text-[#3C3C4B] leading-relaxed">
                        Some pros show active licenses and insurance. Some don’t need them. Either way — you decide who’s right for your project.
                    </p>
                </div>

                <div class="bg-[#FFFFFF] p-6 rounded-2xl border border-slate-200 space-y-3 shadow-sm">
                    <div class="text-2xl">🔄</div>
                    <h3 class="text-lg font-black text-[#0F2D5A]">Active Listings Only</h3>
                    <p class="text-sm font-bold text-[#3C3C4B] leading-relaxed">
                        If someone stops responding, they stop showing up on the board. Keeps the directory clean and completely frustration-free.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- SECTION: THE SOFT INTRO TO CPP SUITE VALUES --}}
    <section id="contractor-growth" class="py-20 lg:py-24 bg-[#FFFFFF]">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-12">
            <div class="space-y-4">
                <span class="text-xs font-black text-[#0F2D5A] bg-[#FFC32D]/30 border border-[#FFC32D]/50 px-3 py-1 rounded-full uppercase tracking-widest inline-block">For Contractors</span>
                <h2 class="text-3xl sm:text-4xl lg:text-5xl font-black text-[#0F2D5A] tracking-tight">
                    Start with a Free Profile. Add Tools When You’re Ready.
                </h2>
                <p class="text-[#3C3C4B] font-bold text-lg max-w-2xl mx-auto leading-relaxed">
                    We don’t force subscriptions, aggressive upsells, or locked contracts. Use the platform however it helps your workflow scale.
                </p>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-6 gap-4 font-black text-xs text-[#0F2D5A] uppercase tracking-wider">
                <div class="bg-[#F0F0F0] p-4 rounded-xl border border-slate-200">🔍 Show up on Google</div>
                <div class="bg-[#F0F0F0] p-4 rounded-xl border border-slate-200">📱 Get direct calls</div>
                <div class="bg-[#F0F0F0] p-4 rounded-xl border border-slate-200">📝 Send estimates</div>
                <div class="bg-[#F0F0F0] p-4 rounded-xl border border-slate-200">📋 Track jobs</div>
                <div class="bg-[#F0F0F0] p-4 rounded-xl border border-slate-200">💰 Collect payments</div>
                <div class="bg-[#F0F0F0] p-4 rounded-xl border border-slate-200">⭐ Build reputation</div>
            </div>

            <p class="text-[#3C3C4B] font-bold text-base max-w-xl mx-auto">
                Whether you’re part‑time, full‑time, or building something entirely new — we’ve got your back covered.
            </p>
            
            <div>
                <a href="#contractor-signup" class="inline-block bg-[#0F2D5A] hover:bg-[#1E3C5A] text-[#FFFFFF] font-black uppercase tracking-wider text-sm px-8 py-4 rounded-xl shadow transition active:scale-95">
                    Claim Your Free Profile
                </a>
            </div>
        </div>
    </section>

    {{-- SECTION: THE GENERAL CONTRACTOR SUB-HUB PREVIEW --}}
    <section id="gc-tools" class="py-16 bg-[#F0F0F0] border-t border-b border-slate-200">
        <div class="max-w-4xl mx-auto px-4 text-center space-y-6">
            <span class="text-xs font-black text-[#FFFFFF] bg-[#3C3C3C] px-2.5 py-1 rounded uppercase tracking-widest inline-block">The Orchestrator Grid</span>
            <h2 class="text-3xl font-black text-[#0F2D5A] tracking-tight">For General Contractors</h2>
            <p class="text-[#3C3C4B] font-bold text-lg max-w-xl mx-auto">
                Find Subs Without the Runaround. Search by trade, location, or skill. Request insurance docs in one click. Keep your projects moving. Simple tools. No fluff.
            </p>
            <div class="pt-2">
                <a href="#contractor-signup" class="text-sm font-black text-[#0F2D5A] hover:text-[#1E3C5A] uppercase tracking-widest underline decoration-2 decoration-[#FFC32D] transition-colors">
                    Access GC Search Hub &rarr;
                </a>
            </div>
        </div>
    </section>

    {{-- FINAL CONVERSION BANNER --}}
    <section class="bg-[#0F2D5A] text-[#FFFFFF] py-16 text-center relative overflow-hidden">
        <div class="max-w-4xl mx-auto px-4 space-y-6 relative z-10">
            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-black tracking-tight leading-none">
                Local Work Runs on Local People
            </h2>
            <p class="text-[#F0F0F0] text-lg sm:text-xl font-medium max-w-2xl mx-auto leading-relaxed">
                Contractor Specialties brings them together — homeowners, contractors, and GCs — all in one place. No pressure. No gimmicks. Just a better way to get things done.
            </p>
        </div>
    </section>

    {{-- EXTENDED FUNCTIONAL FOOTER WITH DYNAMIC NAVIGATION OPTIONS --}}
    <footer class="bg-[#FFFFFF] border-t border-[#F0F0F0] pt-16 pb-12 text-sm text-[#3C3C4B]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-12 gap-8 pb-12 border-b border-[#F0F0F0]">
            
            {{-- Live Square Logo Asset Mapping --}}
            <div class="md:col-span-4 space-y-4 text-left">
                <div class="flex items-center">
                    <img src="{{ asset('images/CS-Square.webp') }}" alt="Contractor Specialties" class="h-23 w-23 object-cover rounded-xl shadow-md border border-[#F0F0F0]">
                </div>
                <p class="font-bold text-xs text-slate-400 uppercase tracking-wider leading-relaxed">
                    Connecting local operators, trade specialists, and project coordinators cleanly across community grids.
                </p>
            </div>

            {{-- Navigation Array Panel A: Consumer Loops --}}
            <div class="md:col-span-3 space-y-3 text-left">
                <h4 class="font-black text-xs text-[#0F2D5A] uppercase tracking-widest">Find Specialists</h4>
                <ul class="font-bold space-y-2 text-slate-500 text-xs uppercase tracking-wider">
                    <li><a href="#specialties" class="hover:text-[#1E3C5A]">Mechanical Systems</a></li>
                    <li><a href="#specialties" class="hover:text-[#1E3C5A]">Structural Framing</a></li>
                    <li><a href="#specialties" class="hover:text-[#1E3C5A]">Precision Masonry</a></li>
                    <li><a href="#specialties" class="hover:text-[#1E3C5A]">Finishing Trades</a></li>
                </ul>
            </div>

            {{-- Navigation Array Panel B: ACTIVE CONTRACTOR OPERATIONS SUITE --}}
            <div class="md:col-span-3 space-y-3 text-left">
                <h4 class="font-black text-xs text-[#0F2D5A] uppercase tracking-widest">Contractor Workspace</h4>
                <ul class="font-bold space-y-2 text-slate-500 text-xs uppercase tracking-wider">
                    <li><a href="#contractor-signup" class="hover:text-[#1E3C5A]">Claim Directory Link</a></li>
                    <li><a href="#contractor-growth" class="hover:text-[#1E3C5A]">Estimate Outbound Modules</a></li>
                    <li><a href="#contractor-growth" class="hover:text-[#1E3C5A]">Reputation Integration</a></li>
                    <li><a href="#contractor-growth" class="hover:text-[#1E3C5A]">Financial Rail Payouts</a></li>
                </ul>
            </div>

            {{-- Navigation Array Panel C: GC Utilities --}}
            <div class="md:col-span-2 space-y-3 text-left">
                <h4 class="font-black text-xs text-[#0F2D5A] uppercase tracking-widest">GC Panel</h4>
                <ul class="font-bold space-y-2 text-slate-500 text-xs uppercase tracking-wider">
                    <li><a href="#gc-tools" class="hover:text-[#1E3C5A]">Sub-Tier Mapping</a></li>
                    <li><a href="#gc-tools" class="hover:text-[#1E3C5A]">Insurance Registry</a></li>
                </ul>
            </div>

        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8 flex flex-col sm:flex-row justify-between items-center gap-4 text-xs font-bold text-slate-400 uppercase tracking-widest">
            <p>&copy; {{ date('Y') }} Contractor Specialties. All rights reserved.</p>
            <div class="flex space-x-6">
                <a href="#" class="hover:text-[#0F2D5A]">Privacy Matrix</a>
                <a href="#" class="hover:text-[#0F2D5A]">Terms of Operation</a>
            </div>
        </div>
    </footer>

</body>
</html>