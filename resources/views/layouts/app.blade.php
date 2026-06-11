<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full scroll-smooth bg-[#F0F0F0]">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Contractor Workspace') | Contractor Specialties</title>
    
    {{-- CSS & Alpine.js Core Frameworks --}}
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
<body class="h-full text-[#3C3C3C] antialiased" x-data="{ mobileMenuOpen: false, userMenuOpen: false }">

    {{-- 1. MOBILE SLIDE-OUT MENU DRAWER (Hidden on Desktop) --}}
    <div x-show="mobileMenuOpen" class="relative z-50 lg:hidden" style="display: none;" role="dialog" aria-modal="true">
        {{-- Background Blur Overlay --}}
        <div x-show="mobileMenuOpen" 
             x-transition:enter="transition-opacity ease-linear duration-300" 
             x-transition:enter-start="opacity-0" 
             x-transition:enter-end="opacity-100" 
             x-transition:leave="transition-opacity ease-linear duration-300" 
             x-transition:leave-start="opacity-100" 
             x-transition:leave-end="opacity-0" 
             class="fixed inset-0 bg-slate-950/60 backdrop-blur-sm"></div>

        <div class="fixed inset-0 flex">
            {{-- Drawer Content Box --}}
            <div x-show="mobileMenuOpen" 
                 x-transition:enter="transition ease-in-out duration-300 transform" 
                 x-transition:enter-start="-translate-x-full" 
                 x-transition:enter-end="translate-x-0" 
                 x-transition:leave="transition ease-in-out duration-300 transform" 
                 x-transition:leave-start="translate-x-0" 
                 x-transition:leave-end="-translate-x-full" 
                 @click.away="mobileMenuOpen = false"
                 class="relative flex w-full max-w-xs flex-1 flex-col bg-slate-900 pt-5 pb-4">
                
                {{-- Close Button --}}
                <div class="absolute top-0 right-0 -mr-12 pt-4">
                    <button type="button" @click="mobileMenuOpen = false" class="ml-1 flex h-10 w-10 items-center justify-center rounded-xl bg-slate-900 text-white focus:outline-none">
                        <span class="text-xl font-black">×</span>
                    </button>
                </div>

                {{-- Mobile Drawer Logo Header --}}
                <div class="flex flex-shrink-0 items-center px-6 border-b border-white/10 pb-5">
                    <img src="{{ asset('images/CS-logo-horizontal-750.webp') }}" alt="Contractor Specialties" class="h-10 w-auto object-contain rounded-lg">
                </div>
                
                {{-- Mobile Navigation Link Set --}}
                <div class="mt-6 h-0 flex-1 overflow-y-auto px-4 space-y-2">
                    <a href="/dashboard" class="flex items-center gap-4 text-base font-bold text-white bg-white/10 rounded-2xl py-4 px-5 transition">
                        <span class="text-xl">📊</span> Command Center
                    </a>
                    <a href="/dashboard/estimates" class="flex items-center gap-4 text-base font-bold text-slate-300 hover:text-white hover:bg-white/5 rounded-2xl py-4 px-5 transition">
                        <span class="text-xl">📝</span> Project Estimates
                    </a>
                    <a href="{{ route('dashboard.invoices') }}" class="flex items-center gap-4 text-base font-bold text-slate-300 hover:text-white hover:bg-white/5 rounded-2xl py-4 px-5 transition">
                        <span class="text-xl">💰</span> Invoices
                    </a>
                    <a href="{{ route('dashboard.scheduler') }}" class="flex items-center gap-4 text-base font-bold text-slate-300 hover:text-white hover:bg-white/5 rounded-2xl py-4 px-5 transition">
                        <span class="text-xl">📅</span> Crew Scheduler
                    </a>
                    <a href="/dashboard/profile" class="flex items-center gap-4 text-base font-bold text-slate-300 hover:text-white hover:bg-white/5 rounded-2xl py-4 px-5 transition">
                        <span class="text-xl">⚙️</span> Public Listing Setup
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- 2. DESKTOP SIDEBAR NAVIGATION PANEL (Hidden on Mobile) --}}
    <div class="hidden lg:fixed lg:inset-y-0 lg:flex lg:w-72 lg:flex-col lg:border-r lg:border-slate-800 lg:bg-slate-900 lg:pt-6 lg:pb-4">
        {{-- Desktop Corporate Identity --}}
        <div class="flex flex-shrink-0 items-center px-8 border-b border-white/5 pb-6">
            <img src="{{ asset('images/CS-logo-horizontal-750.webp') }}" alt="Contractor Specialties" class="h-12 w-auto object-contain rounded-xl">
        </div>
        
        {{-- Desktop Navigation Links --}}
        <div class="mt-8 flex h-0 flex-1 flex-col overflow-y-auto px-4 justify-between">
            <nav class="space-y-1.5">
                <a href="/dashboard" class="flex items-center gap-4 text-sm font-black uppercase tracking-wider text-white bg-white/10 rounded-xl py-3.5 px-5 transition">
                    <span class="text-lg">📊</span> Command Center
                </a>
                <a href="/dashboard/estimates" class="flex items-center gap-4 text-sm font-black uppercase tracking-wider text-slate-400 hover:text-white hover:bg-white/5 rounded-xl py-3.5 px-5 transition">
                    <span class="text-lg">📝</span> Project Estimates
                </a>
                <a href="{{ route('dashboard.invoices') }}" class="flex items-center gap-4 text-sm font-black uppercase tracking-wider text-slate-400 hover:text-white hover:bg-white/5 rounded-xl py-3.5 px-5 transition">
                    <span class="text-lg">💰</span> Invoices
                </a>
                <a href="{{ route('dashboard.scheduler') }}" class="flex items-center gap-4 text-sm font-black uppercase tracking-wider text-slate-400 hover:text-white hover:bg-white/5 rounded-xl py-3.5 px-5 transition">
                    <span class="text-lg">📅</span> Crew Scheduler
                </a>
                <a href="/dashboard/profile" class="flex items-center gap-4 text-sm font-black uppercase tracking-wider text-slate-400 hover:text-white hover:bg-white/5 rounded-xl py-3.5 px-5 transition">
                    <span class="text-lg">⚙️</span> Public Listing Setup
                </a>
            </nav>

            {{-- Profile Identity Footprint Section inside Desktop Nav Footer --}}
            <div class="border-t border-white/5 pt-4 mb-2 flex items-center justify-between px-2">
                <div class="text-left">
                    <p class="text-xs font-black text-white truncate max-w-[160px]">{{ auth()->user()->name }}</p>
                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-wider mt-0.5">Account ID: #{{ auth()->user()->id }}</p>
                </div>
                <form method="POST" action="/logout">
                    @csrf
                    <button type="submit" class="text-[10px] font-black uppercase tracking-widest text-red-400 hover:text-red-300">Exit</button>
                </form>
            </div>
        </div>
    </div>

    {{-- 3. MAIN WORKSPACE DISPLAY FRAME --}}
    <div class="flex flex-col lg:pl-72 min-h-screen">
        
        {{-- GLOBAL INTERACTION BAR: Top Navigation Header --}}
        <header class="sticky top-0 z-40 flex h-20 flex-shrink-0 items-center justify-between bg-white border-b border-[#F0F0F0] px-4 sm:px-6 lg:px-8 shadow-sm">
            
            {{-- Hamburger Activation Toggle Button (Mobile Only) --}}
            <button type="button" @click="mobileMenuOpen = true" class="rounded-xl border border-slate-200 p-2.5 text-slate-600 lg:hidden hover:bg-slate-50 transition active:scale-95">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                </svg>
            </button>

            {{-- Space Filler for Mobile Alignment, Badge Holder for Desktop --}}
            <div class="hidden sm:flex items-center gap-3">
                <span class="text-xs font-black text-emerald-600 uppercase tracking-widest bg-emerald-50 border border-emerald-200 px-3 py-1.5 rounded-xl flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span> Profile Operations Live
                </span>
            </div>

            {{-- Right Aligned Global Account Triggers --}}
            <div class="flex items-center gap-4">
                <div class="relative">
                    <button @click="userMenuOpen = !userMenuOpen" @click.away="userMenuOpen = false" class="w-10 h-10 rounded-xl bg-[#0F2D5A] text-[#FFFFFF] font-black text-sm flex items-center justify-center border-2 border-slate-900 transition active:scale-95">
                        {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                    </button>
                    <div x-show="userMenuOpen" x-transition class="absolute right-0 mt-2 w-48 bg-[#FFFFFF] rounded-xl shadow-xl border border-[#F0F0F0] py-2 z-50" style="display: none;">
                        <span class="block px-4 py-2 text-[10px] font-black text-slate-400 uppercase tracking-wider border-b border-[#F0F0F0] mb-1">Options</span>
                        <form method="POST" action="/logout" class="block w-full">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 text-xs font-black text-red-500 hover:bg-red-50 transition uppercase tracking-widest">Log Out</button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        {{-- DYNAMIC INNER VIEW REPLACEMENT WINDOW --}}
        <main class="flex-1 py-6 sm:py-10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                @yield('content')
            </div>
        </main>

    </div>

</body>
</html>