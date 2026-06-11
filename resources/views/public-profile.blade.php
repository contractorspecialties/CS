<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    {{-- SEO META TITLES: Trade + City + Business Name --}}
    <title>{{ $contractor->business_name }} | {{ $contractor->specialty->name }} in {{ $contractor->city }}, {{ $contractor->state }}</title>
    <meta name="description" content="{{ Str::limit($contractor->bio, 160) }}">

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;700;800&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .glass-card { background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(12px); }
    </style>
</head>
<body class="bg-[#F8F9FA] text-[#1A202C] antialiased">

    {{-- PUBLIC HEADER --}}
    <nav class="bg-white/80 backdrop-blur-md sticky top-0 z-50 border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            <a href="/" class="transition active:scale-95">
                <img src="{{ asset('images/CS-logo-horizontal-750.webp') }}" alt="Contractor Specialties" class="h-10 w-auto">
            </a>
            <div class="hidden sm:flex items-center gap-6">
                <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Verified Trade Directory</span>
                <a href="/login" class="text-xs font-black uppercase tracking-wider text-[#0F2D5A] hover:underline">Contractor Login</a>
            </div>
        </div>
    </nav>

    <main class="py-8 lg:py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- HERO SECTION: IDENTITY & TRUST ANCHORS --}}
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                
                <div class="lg:col-span-8 space-y-8">
                    
                    {{-- MAIN BUSINESS HEADER --}}
                    <div class="bg-white rounded-[2.5rem] p-8 lg:p-12 shadow-xl shadow-slate-200/50 border border-slate-100 relative overflow-hidden">
                        <div class="absolute top-0 right-0 p-8">
                            <span class="text-5xl opacity-10">{{ $contractor->specialty->icon }}</span>
                        </div>

                        <div class="relative z-10 space-y-4">
                            <div class="flex flex-wrap items-center gap-3">
                                <span class="bg-emerald-50 text-emerald-700 text-[10px] font-black uppercase tracking-widest px-3 py-1.5 rounded-lg border border-emerald-100 flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span> Verified Profile
                                </span>
                                <span class="bg-slate-100 text-slate-600 text-[10px] font-black uppercase tracking-widest px-3 py-1.5 rounded-lg">
                                    {{ $contractor->specialty->name }}
                                </span>
                            </div>

                            <h1 class="text-4xl lg:text-6xl font-black text-[#0F2D5A] leading-[1.1] tracking-tight">
                                {{ $contractor->business_name }}
                            </h1>

                            <div class="flex items-center gap-2 text-slate-500 font-bold">
                                <svg class="w-5 h-5 text-[#FFC32D]" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path></svg>
                                <span>Based in {{ $contractor->city }}, {{ $contractor->state }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- BUSINESS OVERVIEW / BIO --}}
                    <div class="bg-white rounded-[2.5rem] p-8 lg:p-12 border border-slate-100 shadow-sm">
                        <h2 class="text-xs font-black text-slate-400 uppercase tracking-[0.2em] mb-6">About the Business</h2>
                        <div class="text-lg text-slate-600 leading-relaxed font-medium">
                            {!! nl2br(e($contractor->bio)) !!}
                        </div>
                    </div>

                    {{-- SERVICE AREA FOOTPRINT --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-white p-8 rounded-[2rem] border border-slate-100">
                            <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-4">Travel Radius</h3>
                            <div class="flex items-baseline gap-2">
                                <span class="text-4xl font-black text-[#0F2D5A]">{{ $contractor->service_radius ?? '25' }}</span>
                                <span class="text-lg font-bold text-slate-400">Miles from {{ $contractor->city }}</span>
                            </div>
                        </div>
                        <div class="bg-white p-8 rounded-[2rem] border border-slate-100">
                            <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-4">Cities & Towns Served</h3>
                            <p class="text-slate-600 font-bold leading-relaxed">
                                {{ $contractor->service_areas ?? 'All surrounding areas in ' . $contractor->state }}
                            </p>
                        </div>
                    </div>
                </div>

                {{-- SIDEBAR: QUICK ACTION & TRUST DATA --}}
                <div class="lg:col-span-4 space-y-6">
                    
                    {{-- CONTACT CARD --}}
                    <div class="bg-[#0F2D5A] rounded-[2.5rem] p-8 text-white shadow-2xl sticky top-28">
                        <h3 class="text-xs font-black text-[#FFC32D] uppercase tracking-[0.2em] mb-6">Connect with Pro</h3>
                        
                        <div class="space-y-6">
                            <a href="tel:{{ $contractor->phone }}" class="block group">
                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1">Direct Line</span>
                                <span class="text-2xl font-black group-hover:text-[#FFC32D] transition tracking-tight">{{ $contractor->phone }}</span>
                            </a>

                            <div class="pt-6 border-t border-white/10">
                                <button class="w-full bg-[#FFC32D] hover:bg-[#FFD22D] text-[#0F2D5A] font-black text-sm uppercase tracking-wider py-4 rounded-2xl shadow-lg transition transform active:scale-95">
                                    Request Project Estimate
                                </button>
                                <p class="text-[10px] text-center text-slate-400 font-bold mt-4 uppercase tracking-widest">Typical response: Under 2 hours</p>
                            </div>
                        </div>

                        {{-- VERIFICATION BADGES --}}
                        <div class="mt-12 space-y-4">
                            <div class="flex items-center gap-4 bg-white/5 p-4 rounded-2xl border border-white/10">
                                <span class="text-2xl">🛡️</span>
                                <div>
                                    <p class="text-xs font-black uppercase tracking-wider">Insurance Verified</p>
                                    <p class="text-[10px] text-slate-400 font-bold">{{ $contractor->is_insured ? 'General Liability Policy Active' : 'Status: Pending Verification' }}</p>
                                </div>
                            </div>
                            
                            @if($contractor->license_number)
                            <div class="flex items-center gap-4 bg-white/5 p-4 rounded-2xl border border-white/10">
                                <span class="text-2xl">📜</span>
                                <div>
                                    <p class="text-xs font-black uppercase tracking-wider">Credentials</p>
                                    <p class="text-[10px] text-slate-400 font-bold">{{ $contractor->license_number }}</p>
                                </div>
                            </div>
                            @endif

                            <div class="flex items-center gap-4 bg-white/5 p-4 rounded-2xl border border-white/10">
                                <span class="text-2xl">🏗️</span>
                                <div>
                                    <p class="text-xs font-black uppercase tracking-wider">Market Tenure</p>
                                    <p class="text-[10px] text-slate-400 font-bold">Established {{ $contractor->established_year ?? 'Verified Member' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- PRICING TRANSPARENCY (PREVIEW FOR CPP) --}}
                    <div class="bg-slate-900 rounded-[2rem] p-8 text-white">
                        <h3 class="text-xs font-black text-slate-500 uppercase tracking-widest mb-6">Standard Rates</h3>
                        <div class="space-y-4">
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-bold text-slate-400">Service Call Fee</span>
                                <span class="text-lg font-black text-[#FFC32D]">${{ $contractor->minimum_service_fee ?? '85' }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-bold text-slate-400">Hourly Shop Rate</span>
                                <span class="text-lg font-black text-[#FFC32D]">${{ $contractor->hourly_rate ?? '95' }}</span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </main>

    <footer class="bg-white border-t border-slate-200 py-12 mt-12">
        <div class="max-w-7xl mx-auto px-4 text-center space-y-4">
            <img src="{{ asset('images/CS-logo-horizontal-750.webp') }}" alt="CS" class="h-8 w-auto mx-auto grayscale opacity-50">
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">© 2026 Contractor Specialties | Part of the CPP Network</p>
        </div>
    </footer>

</body>
</html>