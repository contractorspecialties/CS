<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Contractor Workspace | Contractor Specialties</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-[#F0F0F0] text-[#3C3C3C] antialiased">

    {{-- NAV MAIN NAVBAR --}}
    <header class="bg-[#FFFFFF] border-b border-[#F0F0F0] h-20 flex items-center shadow-sm">
        <div class="max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 flex justify-between items-center">
            <img src="{{ asset('images/CS-logo-horizontal-750.webp') }}" alt="Contractor Specialties" class="h-10 w-auto object-contain">
            <span class="text-xs font-black text-emerald-600 uppercase tracking-widest bg-emerald-50 border border-emerald-200 px-3 py-1 rounded-full">
                ● Profile Active
            </span>
        </div>
    </header>

    {{-- PORTAL HUB CONTAINER --}}
    <main class="py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 space-y-8">
            
            {{-- WELCOME BANNER NODE --}}
            <div class="bg-[#0F2D5A] text-[#FFFFFF] p-8 rounded-[2rem] border-4 border-slate-900 shadow-xl relative overflow-hidden">
                <div class="absolute inset-0 opacity-5 bg-[radial-gradient(#F0F0F0_1px,transparent_1px)] [background-size:16px_16px] pointer-events-none"></div>
                <h1 class="text-2xl sm:text-3xl font-black tracking-tight">Welcome to Your Trade Workspace</h1>
                <p class="text-[#F0F0F0]/80 font-bold text-sm mt-1">Manage your public presence and client messaging parameters dynamically.</p>
            </div>

            {{-- CORE SAAS COMPONENT BLOCKS (CPP PREVIEW TRACKS) --}}
            <div class="bg-[#FFFFFF] rounded-[2.5rem] border-4 border-slate-900 shadow-2xl p-6 sm:p-8 space-y-6">
                <div>
                    <h2 class="text-xl font-black text-[#0F2D5A] tracking-tight">Active Directory Link Information</h2>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mt-0.5">Your data footprint on the public routing matrix</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="bg-[#F0F0F0] p-5 rounded-2xl border border-slate-200">
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1">Business Operator</span>
                        <p class="font-black text-base text-[#0F2D5A]">{{ auth()->user()->name }}</p>
                        <p class="text-xs font-bold text-slate-500 mt-0.5">{{ auth()->user()->email }}</p>
                    </div>
                    <div class="bg-[#F0F0F0] p-5 rounded-2xl border border-slate-200">
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1">Company Entity Namespace</span>
                        <p class="font-black text-base text-[#0F2D5A]">{{ auth()->user()->business_name ?? 'Pending Registration Updates' }}</p>
                    </div>
                </div>

                <div class="border-t border-[#F0F0F0] pt-6 space-y-4">
                    <div class="flex items-center gap-3">
                        <span class="w-2 h-2 rounded-full bg-[#FFC32D] animate-pulse"></span>
                        <h3 class="text-sm font-black text-[#0F2D5A] uppercase tracking-wider">Premium Operations Suite Pending (CPP)</h3>
                    </div>
                    <p class="text-sm font-bold text-slate-500 leading-relaxed">
                        Your account is currently active on our complimentary public directory tier. High-velocity tools for generating client estimates, managing subcontractor networks, and accepting direct bank payouts are initializing behind the scenes.
                    </p>
                </div>
            </div>

        </div>
    </main>

</body>
</html>