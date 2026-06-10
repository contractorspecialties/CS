<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ auth()->user()->is_gc ? 'Contractor Command Deck' : 'Business Dashboard' }} | Contractor Specialties</title>
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
                    {{ auth()->user()->is_gc ? 'General Contractor Account' : 'Subcontractor Hub' }}
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
                            Account ID: #{{ auth()->user()->id }}
                        </span>
                        <form method="POST" action="/logout" class="block w-full">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 text-xs font-black text-red-500 hover:bg-red-50 transition uppercase tracking-widest">
                                Log Out
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </header>

    {{-- MAIN CONTENT INTERFACE --}}
    <main class="py-8 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            {{-- SYSTEM NOTIFICATION BANNERS --}}
            @if (session('status'))
                <div class="bg-slate-950 border-l-8 border-[#FFD22D] p-5 rounded-2xl text-left shadow-md">
                    <p class="text-[10px] font-black text-[#FFD22D] uppercase tracking-widest">Notice</p>
                    <p class="text-sm font-bold text-[#FFFFFF] mt-1 leading-snug">{{ session('status') }}</p>
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-xl text-left shadow-sm">
                    <p class="text-xs font-black text-red-700 uppercase tracking-wider">Please Fix the Following Errors</p>
                    <p class="text-sm font-bold text-red-600 mt-0.5">{{ $errors->first() }}</p>
                </div>
            @endif

            @if(auth()->user()->is_gc)
                {{-- ========================================================================= --}}
                {{-- GENERAL CONTRACTOR DASHBOARD PANEL                                        --}}
                {{-- ========================================================================= --}}
                
                {{-- WELCOME BANNER --}}
                <div class="bg-slate-900 text-[#FFFFFF] p-8 rounded-[2rem] border-4 border-slate-950 shadow-xl relative overflow-hidden">
                    <div class="absolute inset-0 opacity-5 bg-[radial-gradient(#F0F0F0_1px,transparent_1px)] [background-size:16px_16px] pointer-events-none"></div>
                    <div class="relative z-10 space-y-1">
                        <span class="text-[#FFD22D] text-[10px] font-black uppercase tracking-widest bg-slate-800 px-3 py-1 rounded-md">GC Management Deck</span>
                        <h1 class="text-3xl font-black tracking-tight pt-2">Welcome Back, {{ auth()->user()->name }}</h1>
                        <p class="text-slate-400 font-bold text-sm">Track your subcontractors, manage compliance paperwork, and verify credentials in real-time.</p>
                    </div>
                </div>

                {{-- STATISTICS TILES --}}
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="bg-[#FFFFFF] p-6 rounded-2xl border-2 border-[#F0F0F0] shadow-sm flex items-center justify-between">
                        <div>
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block">Active Subcontractor Roster</span>
                            <span class="text-2xl font-black text-[#0F2D5A] block mt-1">14 Tradesmen</span>
                        </div>
                        <span class="text-2xl bg-slate-100 p-3 rounded-xl">🏗️</span>
                    </div>
                    <div class="bg-[#FFFFFF] p-6 rounded-2xl border-2 border-[#F0F0F0] shadow-sm flex items-center justify-between">
                        <div>
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block">Insurance Compliance</span>
                            <span class="text-2xl font-black text-emerald-600 block mt-1">100% Verified</span>
                        </div>
                        <span class="text-2xl bg-emerald-50 text-emerald-600 p-3 rounded-xl">🛡️</span>
                    </div>
                    <div class="bg-[#FFFFFF] p-6 rounded-2xl border-2 border-[#F0F0F0] shadow-sm flex items-center justify-between">
                        <div>
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block">Current Active Projects</span>
                            <span class="text-2xl font-black text-[#0F2D5A] block mt-1">3 Jobs</span>
                        </div>
                        <span class="text-2xl bg-slate-100 p-3 rounded-xl">📋</span>
                    </div>
                </div>

                {{-- SUBCONTRACTOR ROSTER TABLES --}}
                <div class="bg-[#FFFFFF] rounded-[2.5rem] border-4 border-slate-900 shadow-2xl p-6 sm:p-8 space-y-6">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-[#F0F0F0] pb-4">
                        <div>
                            <h2 class="text-xl font-black text-[#0F2D5A] tracking-tight">Subcontractor Roster & Compliance Tracking</h2>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mt-0.5">Keep track of active general liability insurance and worker documentation lookup history</p>
                        </div>
                        <button class="bg-[#0F2D5A] hover:bg-[#1E3C5A] text-[#FFFFFF] text-xs font-black uppercase tracking-wider px-4 py-3 rounded-xl transition active:scale-95">
                            + Look Up New Contractor
                        </button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-slate-200 text-[10px] font-black text-slate-400 uppercase tracking-widest bg-slate-50">
                                    <th class="py-4 px-4">Subcontractor Name</th>
                                    <th class="py-4 px-4">Trade Specialty</th>
                                    <th class="py-4 px-4">Insurance Status</th>
                                    <th class="py-4 px-4 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm font-bold divide-y divide-slate-100">
                                <tr>
                                    <td class="py-4 px-4">
                                        <p class="text-slate-900 font-black">Miller & Sons Handyman Services</p>
                                        <p class="text-xs text-slate-400">miller.sons@gmail.com</p>
                                    </td>
                                    <td class="py-4 px-4 text-slate-600">🛠️ Carpentry & General Repairs</td>
                                    <td class="py-4 px-4"><span class="bg-emerald-50 border border-emerald-200 text-emerald-700 text-[10px] uppercase font-black tracking-wider px-2.5 py-1 rounded-md">✓ Insured & Active</span></td>
                                    <td class="py-4 px-4 text-right"><button class="text-xs font-black text-[#0F2D5A] hover:underline uppercase tracking-wider">Request Update</button></td>
                                </tr>
                                <tr>
                                    <td class="py-4 px-4">
                                        <p class="text-slate-900 font-black">Bolt Electric Loops</p>
                                        <p class="text-xs text-slate-400">marcus@boltelectric.com</p>
                                    </td>
                                    <td class="py-4 px-4 text-slate-600">⚡ High-Voltage Systems</td>
                                    <td class="py-4 px-4"><span class="bg-emerald-50 border border-emerald-200 text-emerald-700 text-[10px] uppercase font-black tracking-wider px-2.5 py-1 rounded-md">✓ Insured & Active</span></td>
                                    <td class="py-4 px-4 text-right"><button class="text-xs font-black text-[#0F2D5A] hover:underline uppercase tracking-wider">Request Update</button></td>
                                </tr>
                                <tr>
                                    <td class="py-4 px-4">
                                        <p class="text-slate-900 font-black">Jack's Lawns Platform</p>
                                        <p class="text-xs text-slate-400">jack@jackslawns.com</p>
                                    </td>
                                    <td class="py-4 px-4 text-slate-600">🌱 Commercial Grounds Management</td>
                                    <td class="py-4 px-4"><span class="bg-amber-50 border border-amber-200 text-amber-700 text-[10px] uppercase font-black tracking-wider px-2.5 py-1 rounded-md">⚠ Pending Document Review</span></td>
                                    <td class="py-4 px-4 text-right"><button class="text-xs font-black text-amber-600 hover:underline uppercase tracking-wider">View Audit Details</button></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            @else
                {{-- ========================================================================= --}}
                {{-- STANDARD SUBCONTRACTOR / TRADESMAN DASHBOARD PANEL                        --}}
                {{-- ========================================================================= --}}
                
                {{-- WELCOME BANNER --}}
                <div class="bg-[#0F2D5A] text-[#FFFFFF] p-8 rounded-[2rem] border-4 border-slate-900 shadow-xl relative overflow-hidden">
                    <div class="absolute inset-0 opacity-5 bg-[radial-gradient(#F0F0F0_1px,transparent_1px)] [background-size:16px_16px] pointer-events-none"></div>
                    <div class="relative z-10 space-y-1">
                        <span class="bg-[#FFC32D] text-[#0F2D5A] text-[10px] font-black uppercase tracking-widest px-3 py-1 rounded-md">Contractor Hub</span>
                        <h1 class="text-3xl font-black tracking-tight pt-2">Welcome to Your Trade Workspace</h1>
                        <p class="text-[#F0F0F0]/80 font-bold text-sm">Manage your public business profile, show up in local searches, and track your listing performance.</p>
                    </div>
                </div>

                {{-- PUBLIC DIRECTORY LINK STATUS CODES --}}
                @if(auth()->user()->slug && auth()->user()->specialty)
                    <div class="bg-[#FFFFFF] border-2 border-slate-200 p-5 rounded-2xl flex flex-col sm:flex-row sm:items-center justify-between gap-4 shadow-sm">
                        <div class="text-left">
                            <span class="text-[9px] font-black text-emerald-600 uppercase tracking-widest bg-emerald-50 border border-emerald-200 px-2 py-0.5 rounded">Live Listing</span>
                            <h4 class="text-sm font-black text-[#0F2D5A] mt-1 tracking-tight">Your Public Directory Profile is Live Online:</h4>
                            <p class="text-xs font-bold text-slate-400 mt-0.5">Homeowners and search engines can now find your profile page.</p>
                        </div>
                        <a href="/pros/{{ auth()->user()->specialty->slug }}/{{ auth()->user()->slug }}" target="_blank" class="bg-slate-900 hover:bg-slate-800 text-[#FFC32D] text-xs font-black uppercase tracking-wider px-4 py-3 rounded-xl border border-slate-950 transition text-center whitespace-nowrap">
                            View Live Profile Page ↗
                        </a>
                    </div>
                @else
                    <div class="bg-amber-50 border-2 border-amber-200 p-5 rounded-2xl flex flex-col sm:flex-row sm:items-center justify-between gap-4 shadow-sm">
                        <div class="text-left">
                            <span class="text-[9px] font-black text-amber-700 uppercase tracking-widest bg-amber-100/50 px-2 py-0.5 rounded">Listing Incomplete</span>
                            <h4 class="text-sm font-black text-[#0F2D5A] mt-1 tracking-tight">Your Public Listing is Currently Invisible.</h4>
                            <p class="text-xs font-bold text-amber-600 mt-0.5">Fill out the form below to create your public listing page and start showing up in local searches.</p>
                        </div>
                    </div>
                @endif

                {{-- GRID FOR PROFILE BUILDER & OPERATIONS AREA --}}
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                    
                    {{-- COMPONENT LEFT: DYNAMIC ONBOARDING DATA-CAPTURE FORM (FOOTPRINT: 7/12) --}}
                    <div class="lg:col-span-7 bg-[#FFFFFF] rounded-[2.5rem] border-4 border-slate-900 shadow-2xl p-6 sm:p-8 space-y-6">
                        <div>
                            <h2 class="text-xl font-black text-[#0F2D5A] tracking-tight">Set Up Your Public Listing Profile</h2>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mt-0.5">Enter your business details below to customize your search page directory entry</p>
                        </div>

                        <form action="{{ route('profile.update') }}" method="POST" class="space-y-6 text-left">
                            @csrf
                            
                            {{-- SECTION 1: CORE BUSINESS DETAILS --}}
                            <div class="space-y-4">
                                <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 pb-2">1. General Information</h3>
                                
                                <div>
                                    <label class="block text-xs font-black text-[#3C3C4B] uppercase tracking-widest mb-2">Public Business Display Name</label>
                                    <input type="text" name="business_name" value="{{ old('business_name', auth()->user()->business_name ?? auth()->user()->name) }}" required placeholder="e.g. Miller & Sons Handyman Services" class="w-full bg-[#F0F0F0] border-2 border-slate-200 rounded-xl text-[#3C3C3C] placeholder-slate-400 font-bold py-3.5 px-4 focus:border-[#1E3C5A] focus:bg-[#FFFFFF] focus:ring-0 focus:outline-none transition text-base">
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-black text-[#3C3C4B] uppercase tracking-widest mb-2">Primary Trade Category</label>
                                        <select name="specialty_id" required class="w-full bg-[#F0F0F0] border-2 border-slate-200 rounded-xl text-[#3C3C3C] font-bold py-3.5 px-4 focus:border-[#1E3C5A] focus:bg-[#FFFFFF] focus:ring-0 focus:outline-none transition text-base appearance-none">
                                            <option value="" disabled {{ is_null(auth()->user()->specialty_id) ? 'selected' : '' }}>Select Your Focus...</option>
                                            @foreach($specialties as $specialty)
                                                <option value="{{ $specialty->id }}" {{ old('specialty_id', auth()->user()->specialty_id) == $specialty->id ? 'selected' : '' }}>
                                                    {{ $specialty->icon }} {{ $specialty->name }} ({{ ucfirst($specialty->operational_type) }} Focus)
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-black text-[#3C3C4B] uppercase tracking-widest mb-2">Business Phone Line</label>
                                        <input type="tel" name="phone" value="{{ old('phone', auth()->user()->phone) }}" required placeholder="(555) 000-0000" class="w-full bg-[#F0F0F0] border-2 border-slate-200 rounded-xl text-[#3C3C3C] placeholder-slate-400 font-bold py-3.5 px-4 focus:border-[#1E3C5A] focus:bg-[#FFFFFF] focus:ring-0 focus:outline-none transition text-base">
                                    </div>
                                </div>

                                <div class="grid grid-cols-3 gap-4">
                                    <div class="col-span-2">
                                        <label class="block text-xs font-black text-[#3C3C4B] uppercase tracking-widest mb-2">Main City Served</label>
                                        <input type="text" name="city" value="{{ old('city', auth()->user()->city) }}" required placeholder="e.g. Phoenix" class="w-full bg-[#F0F0F0] border-2 border-slate-200 rounded-xl text-[#3C3C3C] placeholder-slate-400 font-bold py-3.5 px-4 focus:border-[#1E3C5A] focus:bg-[#FFFFFF] focus:ring-0 focus:outline-none transition text-base">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-black text-[#3C3C4B] uppercase tracking-widest mb-2">State</label>
                                        <input type="text" name="state" maxlength="2" value="{{ old('state', auth()->user()->state ?? 'AZ') }}" required placeholder="AZ" class="w-full bg-[#F0F0F0] border-2 border-slate-200 rounded-xl text-[#3C3C3C] placeholder-slate-400 font-bold py-3.5 px-4 text-center focus:border-[#1E3C5A] focus:bg-[#FFFFFF] focus:ring-0 focus:outline-none transition text-base uppercase">
                                    </div>
                                </div>
                            </div>

                            {{-- SECTION 2: TRUST & CREDENTIALS TIER --}}
                            <div class="space-y-4 pt-2">
                                <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 pb-2">2. Verification & Background</h3>
                                
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                    <div class="sm:col-span-2">
                                        <label class="block text-xs font-black text-[#3C3C4B] uppercase tracking-widest mb-2">License / Registration # (Optional)</label>
                                        <input type="text" name="license_number" value="{{ old('license_number', auth()->user()->license_number) }}" placeholder="e.g. ROC #321455" class="w-full bg-[#F0F0F0] border-2 border-slate-200 rounded-xl text-[#3C3C3C] placeholder-slate-400 font-bold py-3.5 px-4 focus:border-[#1E3C5A] focus:bg-[#FFFFFF] focus:ring-0 focus:outline-none transition text-base">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-black text-[#3C3C4B] uppercase tracking-widest mb-2">Year Established</label>
                                        <input type="number" min="1900" max="2026" name="established_year" value="{{ old('established_year', auth()->user()->established_year) }}" placeholder="e.g. 2012" class="w-full bg-[#F0F0F0] border-2 border-slate-200 rounded-xl text-[#3C3C3C] placeholder-slate-400 font-bold py-3.5 px-4 focus:border-[#1E3C5A] focus:bg-[#FFFFFF] focus:ring-0 focus:outline-none transition text-base">
                                    </div>
                                </div>

                                <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200 flex items-center justify-between gap-4">
                                    <div class="text-left">
                                        <label for="is_insured" class="block text-sm font-black text-[#0F2D5A] cursor-pointer select-none">General Liability Insurance Verification</label>
                                        <p class="text-xs text-slate-400 font-bold mt-0.5">Check this box if your company is currently bonded, insured, or licensed to operate locally.</p>
                                    </div>
                                    <input type="checkbox" id="is_insured" name="is_insured" value="1" {{ old('is_insured', auth()->user()->is_insured) ? 'checked' : '' }} class="w-6 h-6 rounded border-slate-300 text-[#0F2D5A] focus:ring-[#0F2D5A] cursor-pointer">
                                </div>
                            </div>

                            {{-- SECTION 3: BIO DIRECTORY COPY --}}
                            <div class="space-y-4 pt-2">
                                <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 pb-2">3. Search Directory Overview</h3>
                                <div>
                                    <label class="block text-xs font-black text-[#3C3C4B] uppercase tracking-widest mb-2">About Your Business (Bio Descriptions)</label>
                                    <textarea name="bio" rows="4" placeholder="Briefly introduce your company, detail your specialized crew services, warranties, or project size history so customers find you on search engines..." class="w-full bg-[#F0F0F0] border-2 border-slate-200 rounded-xl text-[#3C3C3C] placeholder-slate-400 font-bold py-3.5 px-4 focus:border-[#1E3C5A] focus:bg-[#FFFFFF] focus:ring-0 focus:outline-none transition text-base leading-relaxed">{{ old('bio', auth()->user()->bio) }}</textarea>
                                </div>
                            </div>

                            <button type="submit" class="w-full bg-[#0F2D5A] hover:bg-[#1E3C5A] text-[#FFFFFF] font-black text-sm uppercase tracking-wider py-4 rounded-xl shadow-md transition transform active:scale-95 border border-[#0F2D5A]">
                                Publish Public Directory Profile →
                            </button>
                        </form>
                    </div>

                    {{-- COMPONENT RIGHT: TELEMETRY & PREMIUM METER CONSOLE (FOOTPRINT: 5/12) --}}
                    <div class="lg:col-span-5 space-y-6">
                        
                        {{-- DIRECTORY TRAFFIC SUMMARY --}}
                        <div class="bg-[#FFFFFF] rounded-3xl border-2 border-[#F0F0F0] p-6 shadow-sm space-y-4">
                            <h3 class="text-xs font-black text-[#0F2D5A] uppercase tracking-widest">Public Search Performance</h3>
                            <div class="grid grid-cols-2 gap-4 text-center">
                                <div class="bg-[#F0F0F0] p-4 rounded-xl border border-slate-200/60">
                                    <span class="font-black text-2xl text-[#0F2D5A] block">0</span>
                                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Profile Views</span>
                                </div>
                                <div class="bg-[#F0F0F0] p-4 rounded-xl border border-slate-200/60">
                                    <span class="font-black text-2xl text-emerald-600 block">0</span>
                                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Customer Phone Leads</span>
                                </div>
                            </div>
                        </div>

                        {{-- PREMIUM TOOL SUITE PROMPTS (CPP MONETIZATION GATES) --}}
                        <div class="bg-[#FFFFFF] rounded-3xl border-2 border-[#F0F0F0] p-6 shadow-sm space-y-4">
                            <div class="flex items-center gap-3">
                                <span class="w-2.5 h-2.5 rounded-full bg-[#FFC32D] animate-pulse"></span>
                                <h4 class="text-sm font-black text-[#0F2D5A] uppercase tracking-wider">Premium Business Tools Preview (Contractor Profit Pro)</h4>
                            </div>
                            <p class="text-xs font-bold text-slate-500 leading-relaxed">
                                Your current free account includes your public directory listing. Soon, you'll be able to manage your entire business directly from this screen. We are bringing over premium tools for professional estimating, job scheduling, invoice tracking, and fast client credit card or bank payouts.
                            </p>
                            <button disabled class="w-full bg-[#F0F0F0] text-slate-400 font-black text-xs uppercase tracking-widest py-3.5 rounded-xl border border-slate-200 cursor-not-allowed text-center">
                                Business Tool Suite Loading...
                            </button>
                        </div>

                    </div>

                </div>
            @endif

        </div>
    </main>

</body>
</html>