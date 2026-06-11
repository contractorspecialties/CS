@extends('layouts.app')

@section('title', 'Command Center')

@section('content')
<div class="space-y-8 text-left">

    {{-- 1. HERO WELCOME HEADER --}}
    <div class="bg-[#0F2D5A] text-[#FFFFFF] p-6 sm:p-8 rounded-[2rem] border-4 border-slate-900 shadow-xl relative overflow-hidden">
        <div class="absolute inset-0 opacity-5 bg-[radial-gradient(#F0F0F0_1px,transparent_1px)] [background-size:16px_16px] pointer-events-none"></div>
        <div class="relative z-10 space-y-1">
            <span class="bg-[#FFC32D] text-[#0F2D5A] text-[9px] font-black uppercase tracking-widest px-2.5 py-1 rounded-md">Command Center</span>
            <h1 class="text-2xl sm:text-3xl font-black tracking-tight pt-2">Welcome Back, {{ auth()->user()->name }}</h1>
            <p class="text-[#F0F0F0]/80 font-bold text-xs sm:text-sm"> Evolving your workspace into the Contractor Profit Pro software platform.</p>
        </div>
    </div>

    {{-- REAL-TIME TELEMETRY ALERT FEED CENTER --}}
    @if($actionAlerts->count() > 0)
        <div class="space-y-3">
            <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest pl-1">⚡ Operations Requiring Attention</h3>
            <div class="grid grid-cols-1 gap-3">
                @foreach($actionAlerts as $alert)
                    @if($alert->status === 'approved')
                        <div class="bg-white border-l-4 border-emerald-500 rounded-xl p-4 sm:p-5 shadow-sm flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                            <div class="space-y-1">
                                <span class="bg-emerald-50 text-emerald-700 text-[9px] font-black uppercase tracking-wider px-2 py-0.5 rounded border border-emerald-200">Proposal Approved</span>
                                <h4 class="text-sm font-black text-slate-900">{{ $alert->client_name }} signed off on <span class="text-[#0F2D5A]">"{{ $alert->project_title }}"</span></h4>
                                @if($alert->customer_notes)
                                    <p class="text-xs text-slate-500 font-medium italic mt-1 bg-slate-50 p-2 rounded-lg border border-slate-100">"{{ $alert->customer_notes }}"</p>
                                @endif
                            </div>
                            <div class="flex items-center gap-2 self-end sm:self-center">
                                <form action="{{ route('estimates.convert', $alert->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-black uppercase tracking-wider py-2.5 px-4 rounded-xl transition shadow-sm whitespace-nowrap">
                                        💵 Bill Job
                                    </button>
                                </form>
                                <a href="{{ route('dashboard.estimates') }}" class="bg-slate-100 hover:bg-slate-200 border border-slate-200 text-slate-700 text-xs font-black uppercase tracking-wider py-2.5 px-3.5 rounded-xl transition">
                                    Manage
                                </a>
                            </div>
                        </div>
                    @elseif($alert->status === 'declined')
                        <div class="bg-white border-l-4 border-rose-500 rounded-xl p-4 sm:p-5 shadow-sm flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                            <div class="space-y-1">
                                <span class="bg-rose-50 text-rose-700 text-[9px] font-black uppercase tracking-wider px-2 py-0.5 rounded border border-rose-200">Revision Requested</span>
                                <h4 class="text-sm font-black text-slate-900">{{ $alert->client_name }} declined proposal for <span class="text-slate-600">"{{ $alert->project_title }}"</span></h4>
                                @if($alert->customer_notes)
                                    <p class="text-xs text-slate-500 font-medium italic mt-1 bg-slate-50 p-2 rounded-lg border border-slate-100">"{{ $alert->customer_notes }}"</p>
                                @endif
                            </div>
                            <a href="{{ route('dashboard.estimates') }}" class="bg-slate-900 hover:bg-slate-800 text-[#FFC32D] text-xs font-black uppercase tracking-wider py-2.5 px-5 rounded-xl transition self-end sm:self-center whitespace-nowrap">
                                Adjust Estimate
                            </a>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    @endif

    {{-- 2. DYNAMIC WORKSPACE SHORTCUT TILES (Mobile Touch Targets) --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        
        {{-- Profile Listing Redirect Card --}}
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col justify-between items-start gap-4">
            <div class="flex items-center gap-3">
                <span class="text-2xl bg-blue-50 p-2.5 rounded-xl">⚙️</span>
                <div>
                    <h4 class="text-sm font-black text-[#0F2D5A] tracking-tight">Public Directory</h4>
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mt-0.5">Marketing Profile</p>
                </div>
            </div>
            <a href="{{ route('dashboard.profile') }}" class="w-full bg-slate-50 hover:bg-slate-100 border border-slate-200 text-slate-700 font-black text-xs uppercase tracking-wider py-3 rounded-xl text-center block transition active:scale-[0.98]">
                Edit Listing Settings
            </a>
        </div>

        {{-- Estimating Suite Redirect Card --}}
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col justify-between items-start gap-4">
            <div class="flex items-center gap-3">
                <span class="text-2xl bg-amber-50 text-amber-600 p-2.5 rounded-xl">📝</span>
                <div>
                    <h4 class="text-sm font-black text-[#0F2D5A] tracking-tight">CPP Tool Core</h4>
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mt-0.5">Project Estimates</p>
                </div>
            </div>
            <a href="{{ route('dashboard.estimates') }}" class="w-full bg-[#0F2D5A] hover:bg-[#1E3C5A] text-white font-black text-xs uppercase tracking-wider py-3 rounded-xl text-center block transition active:scale-[0.98] shadow-sm">
                Open Estimating Suite
            </a>
        </div>

        {{-- Public Preview Link Status block --}}
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col justify-between items-start gap-4 sm:col-span-2 lg:col-span-1">
            <div class="flex items-center gap-3">
                <span class="text-2xl bg-emerald-50 text-emerald-600 p-2.5 rounded-xl">↗</span>
                <div>
                    <h4 class="text-sm font-black text-[#0F2D5A] tracking-tight">Live Web Address</h4>
                    <p class="text-[11px] font-bold text-emerald-600 uppercase tracking-wider mt-0.5">Search Engine Ready</p>
                </div>
            </div>
            @if(auth()->user()->slug && auth()->user()->specialty)
                <a href="/pros/{{ auth()->user()->specialty->slug }}/{{ auth()->user()->slug }}" target="_blank" class="w-full bg-slate-900 hover:bg-slate-800 text-[#FFC32D] font-black text-xs uppercase tracking-wider py-3 rounded-xl text-center block transition active:scale-[0.98]">
                    View Live Profile Page
                </a>
            @else
                <span class="w-full bg-amber-50 border border-amber-200 text-amber-800 font-bold text-xs rounded-xl py-3 text-center block">
                    ⚠ Complete Form to Launch Page
                </span>
            @endif
        </div>
    </div>

    {{-- 3. RECENT ACTIVITY SNAPSHOT AREA --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        
        {{-- Recent Estimates Snippet (Footprint: 7/12) --}}
        <div class="lg:col-span-7 bg-white rounded-[2.5rem] border-4 border-slate-900 p-5 sm:p-6 shadow-md space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="text-sm font-black text-[#0F2D5A] uppercase tracking-wider">Recent Project Estimates</h3>
                <a href="{{ route('dashboard.estimates') }}" class="text-xs font-black text-[#0F2D5A] hover:underline uppercase tracking-wider">View All</a>
            </div>

            <div class="space-y-2.5">
                @if($recentEstimates->count() > 0)
                    @foreach($recentEstimates as $estimate)
                        <div class="bg-slate-50 border border-slate-200/60 p-4 rounded-xl flex items-center justify-between hover:border-slate-300 transition">
                            <div>
                                <h4 class="text-xs font-black text-slate-900 tracking-tight">{{ $estimate->client_name }}</h4>
                                <p class="text-[11px] font-bold text-slate-400 mt-0.5">{{ Str::limit($estimate->project_title, 36) }}</p>
                            </div>
                            <div class="text-right">
                                <span class="text-xs font-black text-[#0F2D5A] block">${{ number_format($estimate->total_cents / 100, 2) }}</span>
                                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block mt-0.5">{{ $estimate->created_at->format('M d') }}</span>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="border-2 border-dashed border-slate-200 rounded-2xl p-6 text-center">
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider py-2">No quotes generated yet.</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Platform Onboarding Guide Task List (Footprint: 5/12) --}}
        <div class="lg:col-span-5 bg-white rounded-3xl border border-slate-200/80 p-5 sm:p-6 shadow-sm space-y-4">
            <h3 class="text-sm font-black text-[#0F2D5A] uppercase tracking-wider border-b border-slate-100 pb-3">Operational Checklists</h3>
            
            <div class="space-y-3.5">
                <div class="flex items-start gap-3">
                    <span class="text-emerald-500 font-black text-base mt-0.5">✓</span>
                    <div>
                        <h4 class="text-xs font-black text-slate-800 tracking-tight">Provision Directory Account</h4>
                        <p class="text-[11px] text-slate-400 font-bold">Onboarding credentials verified live against server endpoints.</p>
                    </div>
                </div>

                <div class="flex items-start gap-3">
                    <span class="{{ auth()->user()->specialty_id ? 'text-emerald-500' : 'text-slate-300' }} font-black text-base mt-0.5">✓</span>
                    <div>
                        <h4 class="text-xs font-black text-slate-800 tracking-tight">Configure Business Settings</h4>
                        <p class="text-[11px] text-slate-400 font-bold">Default minimum calls and shop pricing hours loaded into workspace memory.</p>
                    </div>
                </div>

                <div class="flex items-start gap-3">
                    <span class="{{ $recentEstimates->count() > 0 ? 'text-emerald-500' : 'text-slate-300' }} font-black text-base mt-0.5">✓</span>
                    <div>
                        <h4 class="text-xs font-black text-slate-800 tracking-tight">Issue First Project Proposal</h4>
                        <p class="text-[11px] text-slate-400 font-bold">Utilize the embedded field calculator matrix to push pricing logs live.</p>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection