<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Contractor Specialties | Find Local Pros You Can Actually Reach</title>
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
<body class="bg-[#FFFFFF] text-[#3C3C3C] antialiased" x-data="{ openMenu: null, mobileMenuOpen: false }">

    {{-- EXPANDED NAV BAR WITH CONNECTED BUTTON CONTROLS & HAMBURGER SYSTEM --}}
    <header class="sticky top-0 z-50 bg-[#FFFFFF]/95 backdrop-blur-md border-b border-[#F0F0F0]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-28 flex items-center justify-between relative">
            
            {{-- BRAND LOGO SLOT --}}
            <div class="flex items-center">
                <a href="/" class="block transition active:scale-95 py-1">
                    <img src="{{ asset('images/CS-logo-horizontal-750.webp') }}" alt="Contractor Specialties" class="h-20 w-auto object-contain">
                </a>
            </div>
            
            {{-- DESKTOP: CONNECTED BUTTON GROUP NAVIGATION SYSTEM --}}
            <nav class="hidden md:flex items-center bg-[#F0F0F0] p-1.5 rounded-2xl border border-slate-200/60 shadow-sm" @mouseleave="openMenu = null">
                
                {{-- CONNECTED BUTTON 1: TRADES DROP --}}
                <div class="relative flex items-center" @mouseenter="openMenu = 'trades'">
                    <button class="px-5 py-3 rounded-xl text-sm font-black transition flex items-center gap-1.5 outline-none"
                            :class="openMenu === 'trades' ? 'bg-[#FFFFFF] text-[#0F2D5A] shadow-sm' : 'text-[#3C3C4B] hover:bg-[#FFFFFF]/60 hover:text-[#1E3C5A]'">
                        Browse Trades
                        <svg class="w-4 h-4 transition-transform duration-200" :class="{'rotate-180': openMenu === 'trades'}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                    </button>

                    {{-- MEGA DROP PANEL --}}
                    <div x-show="openMenu === 'trades'" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 translate-y-2"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 translate-y-2"
                         style="display: none;"
                         class="absolute top-[calc(100%+12px)] -right-12 w-[540px] bg-[#FFFFFF] rounded-2xl shadow-2xl border border-[#F0F0F0] p-6 grid grid-cols-2 gap-6">
                        
                        <div class="space-y-3">
                            <h4 class="text-xs font-black text-[#0F2D5A] uppercase tracking-widest border-b border-[#F0F0F0] pb-2">Structural Crews</h4>
                            <a href="#specialties" class="block text-sm font-bold hover:text-[#1E3C5A] text-slate-600 transition">🏗️ General Contractors</a>
                            <a href="#specialties" class="block text-sm font-bold hover:text-[#1E3C5A] text-slate-600 transition">🏠 Roofers & Exterior</a>
                            <a href="#specialties" class="block text-sm font-bold hover:text-[#1E3C5A] text-slate-600 transition">🧱 Masonry & Concrete</a>
                        </div>
                        <div class="space-y-3">
                            <h4 class="text-xs font-black text-[#0F2D5A] uppercase tracking-widest border-b border-[#F0F0F0] pb-2">Specialty Trades</h4>
                            <a href="#specialties" class="block text-sm font-bold hover:text-[#1E3C5A] text-slate-600 transition">⚡ Electricians</a>
                            <a href="#specialties" class="block text-sm font-bold hover:text-[#1E3C5A] text-slate-600 transition">💧 Plumbers</a>
                            <a href="#specialties" class="block text-sm font-bold hover:text-[#1E3C5A] text-slate-600 transition">❄️ HVAC Techs</a>
                        </div>
                    </div>
                </div>

                {{-- CONNECTED BUTTON 2: STANDARDS --}}
                <div class="relative flex items-center" @mouseenter="openMenu = 'why-us'">
                    <button class="px-5 py-3 rounded-xl text-sm font-black transition flex items-center gap-1.5 outline-none"
                            :class="openMenu === 'why-us' ? 'bg-[#FFFFFF] text-[#0F2D5A] shadow-sm' : 'text-[#3C3C4B] hover:bg-[#FFFFFF]/60 hover:text-[#1E3C5A]'">
                        Why Us
                        <svg class="w-4 h-4 transition-transform duration-200" :class="{'rotate-180': openMenu === 'why-us'}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                    </button>

                    <div x-show="openMenu === 'why-us'" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 translate-y-2"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 translate-y-2"
                         style="display: none;"
                         class="absolute top-[calc(100%+12px)] left-1/2 -translate-x-1/2 w-[300px] bg-[#FFFFFF] rounded-2xl shadow-2xl border border-[#F0F0F0] p-3 space-y-1">
                        <a href="#why-us" class="block p-2.5 rounded-xl hover:bg-[#F0F0F0] transition">
                            <p class="text-sm font-black text-[#0F2D5A]">Direct Line Protocols</p>
                            <p class="text-xs text-slate-400 font-bold mt-0.5">Absolute bypass of broker fees.</p>
                        </a>
                        <a href="#why-us" class="block p-2.5 rounded-xl hover:bg-[#F0F0F0] transition">
                            <p class="text-sm font-black text-[#0F2D5A]">Active System Heartbeat</p>
                            <p class="text-xs text-slate-400 font-bold mt-0.5">Dead numbers are dropped live.</p>
                        </a>
                    </div>
                </div>

                {{-- CONNECTED BUTTON 3 & 4: ROUTE PATHS --}}
                <a href="#contractor-growth" class="px-5 py-3 rounded-xl text-sm font-black text-[#3C3C4B] hover:bg-[#FFFFFF]/60 hover:text-[#1E3C5A] transition">For Tradesmen</a>
                <a href="#gc-tools" class="px-5 py-3 rounded-xl text-sm font-black text-[#3C3C4B] hover:bg-[#FFFFFF]/60 hover:text-[#1E3C5A] transition">For GCs</a>
                
                {{-- CONNECTED ACTION CTA HIGHLIGHT --}}
                <div class="pl-1">
                    <a href="#contractor-signup" class="block bg-[#0F2D5A] hover:bg-[#1E3C5A] text-[#FFFFFF] px-6 py-3 rounded-xl transition shadow-sm font-black tracking-wide transform active:scale-95 border border-[#0F2D5A]">
                        Create Your Free Profile
                    </a>
                </div>
            </nav>

            {{-- MOBILE: ACTIVE HAMBURGER INTERACTION INTERFACE TRIGGER --}}
            <div class="flex items-center md:hidden">
                <button @click="mobileMenuOpen = !mobileMenuOpen" 
                        type="button" 
                        class="p-2.5 rounded-xl border border-slate-200 bg-[#F0F0F0] text-[#0F2D5A] hover:bg-slate-200 transition outline-none"
                        aria-label="Toggle Adaptive Operational Menu">
                    <svg class="w-6 h-6 transition-transform duration-200" :class="{'rotate-90': mobileMenuOpen}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h16"/>
                        <path x-show="mobileMenuOpen" x-cloak stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        {{-- MOBILE NAVIGATION SLIDE DOWN DRAWER SYSTEM --}}
        <div x-show="mobileMenuOpen" 
             x-collapse
             style="display: none;" 
             class="md:hidden border-t border-[#F0F0F0] bg-[#FFFFFF] px-4 pt-4 pb-6 space-y-3 shadow-inner">
            <a href="#specialties" @click="mobileMenuOpen = false" class="block font-black text-base text-[#3C3C4B] hover:text-[#0F2D5A] px-4 py-2.5 rounded-xl hover:bg-[#F0F0F0] transition">Browse Trades</a>
            <a href="#why-us" @click="mobileMenuOpen = false" class="block font-black text-base text-[#3C3C4B] hover:text-[#0F2D5A] px-4 py-2.5 rounded-xl hover:bg-[#F0F0F0] transition">Why Us</a>
            <a href="#contractor-growth" @click="mobileMenuOpen = false" class="block font-black text-base text-[#3C3C4B] hover:text-[#0F2D5A] px-4 py-2.5 rounded-xl hover:bg-[#F0F0F0] transition">For Tradesmen</a>
            <a href="#gc-tools" @click="mobileMenuOpen = false" class="block font-black text-base text-[#3C3C4B] hover:text-[#0F2D5A] px-4 py-2.5 rounded-xl hover:bg-[#F0F0F0] transition">For GCs</a>
            <div class="pt-2 border-t border-[#F0F0F0]">
                <a href="#contractor-signup" @click="mobileMenuOpen = false" class="block w-full text-center bg-[#0F2D5A] text-[#FFFFFF] font-black uppercase tracking-wider py-4 rounded-xl shadow transition active:scale-95">
                    Create Your Free Profile
                </a>
            </div>
        </div>
    </header>

    {{-- HERO SECTION --}}
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

    {{-- SECTION: ARE YOU A LOCAL CONTRACTOR? --}}
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
                            A way for people to contact you directly
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-6">
                    <div class="bg-[#FFFFFF] border-4 border-slate-900 rounded-[2.5rem] shadow-2xl p-6 sm:p-8 space-y-6">
                        
                        {{-- SYSTEM FEEDBACK BROADCAST DISPATCH BARS --}}
                        @if (session('status'))
                            <div class="bg-slate-950 border-l-8 border-[#FFD22D] p-5 rounded-2xl text-left shadow-md">
                                <p class="text-[10px] font-black text-[#FFD22D] uppercase tracking-widest">Network Alert</p>
                                <p class="text-sm font-bold text-[#FFFFFF] mt-1 leading-snug">{{ session('status') }}</p>
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-xl text-left shadow-sm">
                                <p class="text-xs font-black text-red-700 uppercase tracking-wider">Validation Block</p>
                                <p class="text-sm font-bold text-red-600 mt-0.5">{{ $errors->first() }}</p>
                            </div>
                        @endif

                        <form action="{{ route('register') }}" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <label class="block text-xs font-black text-[#3C3C4B] uppercase tracking-widest mb-2">Business Name</label>
                                <input type="text" name="business_name" value="{{ old('business_name') }}" mercantile-node required placeholder="e.g. Miller & Sons Handyman Services" class="w-full bg-[#F0F0F0] border-2 border-slate-200 rounded-xl text-[#3C3C3C] placeholder-slate-400 font-bold py-3.5 px-4 focus:border-[#1E3C5A] focus:bg-[#FFFFFF] focus:ring-0 focus:outline-none transition text-base">
                            </div>

                            <div>
                                <label class="block text-xs font-black text-[#3C3C4B] uppercase tracking-widest mb-2">Email Address</label>
                                <input type="email" name="email" value="{{ old('email') }}" required placeholder="e.g. jack@jackslawns.com" class="w-full bg-[#F0F0F0] border-2 border-slate-200 rounded-xl text-[#3C3C3C] placeholder-slate-400 font-bold py-3.5 px-4 focus:border-[#1E3C5A] focus:bg-[#FFFFFF] focus:ring-0 focus:outline-none transition text-base">
                            </div>

                            <div>
                                <label class="block text-xs font-black text-[#3C3C4B] uppercase tracking-widest mb-2">Mobile Number</label>
                                <input type="tel" name="mobile_number" value="{{ old('mobile_number') }}" required placeholder="(555) 000-0000" class="w-full bg-[#F0F0F0] border-2 border-slate-200 rounded-xl text-[#3C3C3C] placeholder-slate-400 font-bold py-3.5 px-4 focus:border-[#1E3C5A] focus:bg-[#FFFFFF] focus:ring-0 focus:outline-none transition text-base">
                            </div>

                            <button type="submit" class="w-full bg-[#0F2D5A] hover:bg-[#1E3C5A] text-[#FFFFFF] font-black text-base uppercase tracking-wider py-4 rounded-xl shadow-md transition transform active:scale-95 border border-[#0F2D5A]">
                                Create My Free Profile →
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

    {{-- SECTION: BROWSE LOCAL TRADES --}}
    <section id="specialties" class="py-20 lg:py-24 bg-[#FFFFFF]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
            <div class="text-center max-w-3xl mx-auto space-y-4">
                <h2 class="text-3xl sm:text-4xl font-black text-[#0F2D5A] tracking-tight">Find the Right Person for the Job</h2>
                <p class="text-[#3C3C4B] font-bold text-lg max-w-2xl mx-auto">
                    Every trade. Every skill level. Every neighborhood.
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
                <p class="text-sm font-bold text-slate-400 uppercase tracking-widest italic">If they do the work, you’ll find them here.</p>
            </div>
        </div>
    </section>

    {{-- SECTION: WHY PEOPLE LIKE USING CONTRACTOR SPECIALTIES --}}
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
                        Call or message the pro right from their profile. No middlemen.
                    </p>
                </div>

                <div class="bg-[#FFFFFF] p-6 rounded-2xl border border-slate-200 space-y-3 shadow-sm">
                    <div class="text-2xl">🧍</div>
                    <h3 class="text-lg font-black text-[#0F2D5A]">Real People, Not Bots</h3>
                    <p class="text-sm font-bold text-[#3C3C4B] leading-relaxed">
                        Every single directory listing belongs to an actual human being who works in your area.
                    </p>
                </div>

                <div class="bg-[#FFFFFF] p-6 rounded-2xl border border-slate-200 space-y-3 shadow-sm">
                    <div class="text-2xl">🛡️</div>
                    <h3 class="text-lg font-black text-[#0F2D5A]">Credentials When They Matter</h3>
                    <p class="text-sm font-bold text-[#3C3C4B] leading-relaxed">
                        Some pros show licenses and insurance. Some don’t need them. Either way — you decide who’s right for your project.
                    </p>
                </div>

                <div class="bg-[#FFFFFF] p-6 rounded-2xl border border-slate-200 space-y-3 shadow-sm">
                    <div class="text-2xl">🔄</div>
                    <h3 class="text-lg font-black text-[#0F2D5A]">Active Listings Only</h3>
                    <p class="text-sm font-bold text-[#3C3C4B] leading-relaxed">
                        If someone stops responding, they stop showing up. Keeps things clean and frustration‑free.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- SECTION: FOR CONTRACTORS --}}
    <section id="contractor-growth" class="py-20 lg:py-24 bg-[#FFFFFF]">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-12">
            <div class="space-y-4">
                <span class="text-xs font-black text-[#0F2D5A] bg-[#FFC32D]/30 border border-[#FFC32D]/50 px-3 py-1 rounded-full uppercase tracking-widest inline-block">For Contractors: Grow at Your Own Pace</span>
                <h2 class="text-3xl sm:text-4xl lg:text-5xl font-black text-[#0F2D5A] tracking-tight">
                    Start with a Free Profile. Add Tools When You’re Ready.
                </h2>
                <p class="text-[#3C3C4B] font-bold text-lg max-w-2xl mx-auto leading-relaxed">
                    We don’t force subscriptions or upsells. Use the platform however it helps you:
                </p>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-6 gap-4 font-black text-xs text-[#0F2D5A] uppercase tracking-wider">
                <div class="bg-[#F0F0F0] p-4 rounded-xl border border-slate-200">🔍 Show up on Google</div>
                <div class="bg-[#F0F0F0] p-4 rounded-xl border border-slate-200">📱 Get direct calls</div>
                <div class="bg-[#F0F0F0] p-4 rounded-xl border border-slate-200">📝 Send estimates</div>
                <div class="bg-[#F0F0F0] p-4 rounded-xl border border-slate-200">📋 Track jobs</div>
                <div class="bg-[#F0F0F0] p-4 rounded-xl border border-slate-200">💰 Collect payments</div>
                <div class="bg-[#F0F0F0] p-4 rounded-xl border border-slate-200">⭐ Build your reputation</div>
            </div>

            <p class="text-[#3C3C4B] font-bold text-base max-w-xl mx-auto">
                Whether you’re part‑time, full‑time, or building something new — we’ve got your back.
            </p>
            
            <div>
                <a href="#contractor-signup" class="inline-block bg-[#0F2D5A] hover:bg-[#1E3C5A] text-[#FFFFFF] font-black uppercase tracking-wider text-sm px-8 py-4 rounded-xl shadow transition active:scale-95">
                    Claim Your Free Profile
                </a>
            </div>
        </div>
    </section>

    {{-- SECTION: FOR GENERAL CONTRACTORS --}}
    <section id="gc-tools" class="py-16 bg-[#F0F0F0] border-t border-b border-slate-200">
        <div class="max-w-4xl mx-auto px-4 text-center space-y-6">
            <span class="text-xs font-black text-[#FFFFFF] bg-[#3C3C3C] px-2.5 py-1 rounded uppercase tracking-widest inline-block">The Orchestrator Grid</span>
            <h2 class="text-3xl font-black text-[#0F2D5A] tracking-tight">Find Subs Without the Runaround</h2>
            <p class="text-[#3C3C4B] font-bold text-lg max-w-xl mx-auto">
                Search by trade, location, or skill. Request insurance docs in one click. Keep your projects moving. Simple tools. No fluff.
            </p>
            <div class="pt-2">
                <a href="#contractor-signup" class="text-sm font-black text-[#0F2D5A] hover:text-[#1E3C5A] uppercase tracking-widest underline decoration-2 decoration-[#FFC32D] transition-colors">
                    Access GC Search Hub &rarr;
                </a>
            </div>
        </div>
    </section>

    {{-- CLOSING SECTION --}}
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

    {{-- EXTENDED FUNCTIONAL FOOTER --}}
    <footer class="bg-[#FFFFFF] border-t border-[#F0F0F0] pt-16 pb-12 text-[#3C3C3C]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- PRIMARY FOOTER GRID --}}
            <div class="grid grid-cols-1 md:grid-cols-12 gap-y-10 gap-x-8 lg:gap-x-12 pb-12 border-b border-[#F0F0F0] items-start">
                
                {{-- COLUMN 1: BRANDING & LOGO (FOOTPRINT: 3/12) --}}
                <div class="md:col-span-3 space-y-4 text-left flex flex-col justify-start">
                    <div class="flex items-center">
                        <img src="{{ asset('images/CS-Square.webp') }}" alt="Contractor Specialties Brand Identity" class="h-16 w-16 object-cover rounded-2xl shadow-md border border-[#F0F0F0]">
                    </div>
                    <p class="font-medium text-sm text-[#3C3C4B] leading-relaxed">
                        Connecting local operators, specialized tradesmen, and project coordinators cleanly across modern community grids.
                    </p>
                </div>

                {{-- COLUMN 2: CONSUMER DIRECTORY NAVIGATION (FOOTPRINT: 3/12) --}}
                <div class="md:col-span-3 space-y-4 text-left">
                    <h4 class="text-xs font-black text-[#0F2D5A] uppercase tracking-[0.15em] mb-2">Find Specialists</h4>
                    <ul class="space-y-3 font-bold text-sm text-slate-500 tracking-wide">
                        <li><a href="#specialties" class="hover:text-[#1E3C5A] transition-colors duration-150 block">🏗️ Structural General Contracting</a></li>
                        <li><a href="#specialties" class="hover:text-[#1E3C5A] transition-colors duration-150 block">⚡ Local Electrical Systems</a></li>
                        <li><a href="#specialties" class="hover:text-[#1E3C5A] transition-colors duration-150 block">💧 Precision Plumbing Crews</a></li>
                        <li><a href="#specialties" class="hover:text-[#1E3C5A] transition-colors duration-150 block">🏠 Roofing & Exterior Assets</a></li>
                    </ul>
                </div>

                {{-- COLUMN 3: PLATFORM FEATURE UTILITIES (FOOTPRINT: 3/12) --}}
                <div class="md:col-span-3 space-y-4 text-left">
                    <h4 class="text-xs font-black text-[#0F2D5A] uppercase tracking-[0.15em] mb-2">Platform Capabilities</h4>
                    <ul class="space-y-3 font-bold text-sm text-slate-500 tracking-wide">
                        <li><a href="#contractor-growth" class="hover:text-[#1E3C5A] transition-colors duration-150 block">Outbound Estimating Tools</a></li>
                        <li><a href="#contractor-growth" class="hover:text-[#1E3C5A] transition-colors duration-150 block">Reputation Engine Loops</a></li>
                        <li><a href="#gc-tools" class="hover:text-[#1E3C5A] transition-colors duration-150 block">Insurance Registry Logs</a></li>
                        <li><a href="#contractor-growth" class="hover:text-[#1E3C5A] transition-colors duration-150 block">Secure Bank Payout Rails</a></li>
                    </ul>
                </div>

                {{-- COLUMN 4: HIGHLIGHTED TARGET CLIENT ACCESS PORTALS (FOOTPRINT: 3/12) --}}
                <div class="md:col-span-3 bg-[#F0F0F0] p-5 rounded-2xl border border-slate-200/80 shadow-sm space-y-4 text-left">
                    <h4 class="text-xs font-black text-[#0F2D5A] uppercase tracking-[0.15em] mb-1">Client Gateways</h4>
                    <ul class="space-y-3 font-bold text-sm tracking-wide">
                        <li><a href="#contractor-signup" class="text-[#0F2D5A] hover:text-[#1E3C5A] transition-colors duration-150 block font-black">✨ Sign Up Now</a></li>
                        <li><a href="/login" class="text-slate-500 hover:text-[#1E3C5A] transition-colors duration-150 block">Tradesman Dashboard</a></li>
                        <li><a href="/login" class="text-slate-500 hover:text-[#1E3C5A] transition-colors duration-150 block">General Contractor Hub</a></li>
                        <li><a href="/login" class="text-slate-500 hover:text-[#1E3C5A] transition-colors duration-150 block">Subcontractor Portal</a></li>
                    </ul>
                </div>

            </div>

            {{-- BOTTOM ROW LEGAL & REVENUE RIGHTS TRACK --}}
            <div class="pt-8 flex flex-col md:flex-row justify-between items-center gap-4 text-xs font-bold text-slate-400 uppercase tracking-widest">
                <p class="text-center md:text-left">&copy; {{ date('Y') }} Contractor Specialties. All rights reserved.</p>
                <div class="flex space-x-8">
                    <a href="#" class="hover:text-[#0F2D5A] transition-colors duration-150">Privacy Matrix</a>
                    <a href="#" class="hover:text-[#0F2D5A] transition-colors duration-150">Terms of Operation</a>
                </div>
            </div>

        </div>
    </footer>

</body>
</html>