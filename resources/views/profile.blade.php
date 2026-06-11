@extends('layouts.app')

@section('title', 'Public Listing Setup')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    
    {{-- Page Heading Header Block --}}
    <div class="text-left bg-white p-6 sm:p-8 rounded-2xl border border-slate-200/80 shadow-sm">
        <h1 class="text-2xl font-black text-[#0F2D5A] tracking-tight">Public Directory Settings</h1>
        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mt-1">
            Manage your marketing profile, verification credentials, and standard shop diagnostic rates.
        </p>
    </div>

    {{-- Core Profile Form Wrapper Node --}}
    <div class="bg-[#FFFFFF] rounded-[2.5rem] border-4 border-slate-900 shadow-xl p-6 sm:p-10 space-y-6">
        <form action="{{ route('profile.update') }}" method="POST" class="space-y-8 text-left">
            @csrf
            
            {{-- SECTION 1: GENERAL REGIONAL INFO --}}
            <div class="space-y-4">
                <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 pb-2">1. General Information</h3>
                
                <div>
                    <label class="block text-xs font-black text-[#3C3C4B] uppercase tracking-widest mb-2">Public Business Display Name</label>
                    <input type="text" name="business_name" value="{{ old('business_name', auth()->user()->business_name ?? auth()->user()->name) }}" required placeholder="e.g. Miller & Sons Handyman Services" class="w-full bg-[#F0F0F0] border-2 border-slate-200 rounded-xl text-[#3C3C3C] placeholder-slate-400 font-bold py-3.5 px-4 focus:border-[#0F2D5A] focus:bg-[#FFFFFF] focus:ring-0 focus:outline-none transition text-base">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-black text-[#3C3C4B] uppercase tracking-widest mb-2">Primary Trade Category</label>
                        <select name="specialty_id" required class="w-full bg-[#F0F0F0] border-2 border-slate-200 rounded-xl text-[#3C3C3C] font-bold py-3.5 px-4 focus:border-[#0F2D5A] focus:bg-[#FFFFFF] focus:ring-0 focus:outline-none transition text-base appearance-none">
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
                        <input type="tel" name="phone" value="{{ old('phone', auth()->user()->phone) }}" required placeholder="(555) 000-0000" class="w-full bg-[#F0F0F0] border-2 border-slate-200 rounded-xl text-[#3C3C3C] placeholder-slate-400 font-bold py-3.5 px-4 focus:border-[#0F2D5A] focus:bg-[#FFFFFF] focus:ring-0 focus:outline-none transition text-base">
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-4">
                    <div class="col-span-2">
                        <label class="block text-xs font-black text-[#3C3C4B] uppercase tracking-widest mb-2">Main City Served</label>
                        <input type="text" name="city" value="{{ old('city', auth()->user()->city) }}" required placeholder="e.g. Phoenix" class="w-full bg-[#F0F0F0] border-2 border-slate-200 rounded-xl text-[#3C3C3C] placeholder-slate-400 font-bold py-3.5 px-4 focus:border-[#0F2D5A] focus:bg-[#FFFFFF] focus:ring-0 focus:outline-none transition text-base">
                    </div>
                    <div>
                        <label class="block text-xs font-black text-[#3C3C4B] uppercase tracking-widest mb-2">State</label>
                        <input type="text" name="state" maxlength="2" value="{{ old('state', auth()->user()->state ?? 'AZ') }}" required placeholder="AZ" class="w-full bg-[#F0F0F0] border-2 border-slate-200 rounded-xl text-[#3C3C3C] placeholder-slate-400 font-bold py-3.5 px-4 text-center focus:border-[#0F2D5A] focus:bg-[#FFFFFF] focus:ring-0 focus:outline-none transition text-base uppercase">
                    </div>
                </div>
            </div>

            {{-- SECTION 2: TRUST COMPLIANCE VERIFICATION --}}
            <div class="space-y-4">
                <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 pb-2">2. Verification & Background</h3>
                
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-black text-[#3C3C4B] uppercase tracking-widest mb-2">License / Registration # (Optional)</label>
                        <input type="text" name="license_number" value="{{ old('license_number', auth()->user()->license_number) }}" placeholder="e.g. ROC #321455" class="w-full bg-[#F0F0F0] border-2 border-slate-200 rounded-xl text-[#3C3C3C] placeholder-slate-400 font-bold py-3.5 px-4 focus:border-[#0F2D5A] focus:bg-[#FFFFFF] focus:ring-0 focus:outline-none transition text-base">
                    </div>
                    <div>
                        <label class="block text-xs font-black text-[#3C3C4B] uppercase tracking-widest mb-2">Year Established</label>
                        <input type="number" min="1900" max="2026" name="established_year" value="{{ old('established_year', auth()->user()->established_year) }}" placeholder="e.g. 2012" class="w-full bg-[#F0F0F0] border-2 border-slate-200 rounded-xl text-[#3C3C3C] placeholder-slate-400 font-bold py-3.5 px-4 focus:border-[#0F2D5A] focus:bg-[#FFFFFF] focus:ring-0 focus:outline-none transition text-base">
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

            {{-- SECTION 3: MUNICIPAL MILEAGE RADIUS --}}
            <div class="space-y-4">
                <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 pb-2">3. Service Area & Travel Radius</h3>
                
                <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                    <div class="sm:col-span-1">
                        <label class="block text-xs font-black text-[#3C3C4B] uppercase tracking-widest mb-2">Max Travel Radius</label>
                        <select name="service_radius" class="w-full bg-[#F0F0F0] border-2 border-slate-200 rounded-xl text-[#3C3C3C] font-bold py-3.5 px-4 focus:border-[#0F2D5A] focus:bg-[#FFFFFF] focus:ring-0 focus:outline-none transition text-base appearance-none">
                            <option value="10" {{ old('service_radius', auth()->user()->service_radius) == 10 ? 'selected' : '' }}>10 Miles</option>
                            <option value="25" {{ old('service_radius', auth()->user()->service_radius ?? 25) == 25 ? 'selected' : '' }}>25 Miles</option>
                            <option value="50" {{ old('service_radius', auth()->user()->service_radius) == 50 ? 'selected' : '' }}>50 Miles</option>
                            <option value="100" {{ old('service_radius', auth()->user()->service_radius) == 100 ? 'selected' : '' }}>100 Miles</option>
                        </select>
                    </div>
                    <div class="sm:col-span-3">
                        <label class="block text-xs font-black text-[#3C3C4B] uppercase tracking-widest mb-2">Neighboring Cities / Towns Covered</label>
                        <input type="text" name="service_areas" value="{{ old('service_areas', auth()->user()->service_areas) }}" placeholder="e.g. Scottsdale, Mesa, Gilbert, Chandler, Tempe" class="w-full bg-[#F0F0F0] border-2 border-slate-200 rounded-xl text-[#3C3C3C] placeholder-slate-400 font-bold py-3.5 px-4 focus:border-[#0F2D5A] focus:bg-[#FFFFFF] focus:ring-0 focus:outline-none transition text-base">
                    </div>
                </div>
            </div>

            {{-- SECTION 4: OPERATIONAL RATES CONFIG (CPP CORE) --}}
            <div class="space-y-4">
                <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 pb-2">4. Estimating Rates & Settings</h3>
                
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-black text-[#3C3C4B] uppercase tracking-widest mb-2">Minimum Service Fee</label>
                        <div class="relative">
                            <span class="absolute left-4 top-3.5 font-bold text-slate-400">$</span>
                            <input type="number" min="0" name="minimum_service_fee" value="{{ old('minimum_service_fee', auth()->user()->minimum_service_fee) }}" placeholder="85" class="w-full bg-[#F0F0F0] border-2 border-slate-200 rounded-xl text-[#3C3C3C] placeholder-slate-400 font-bold py-3.5 pl-8 pr-4 focus:border-[#0F2D5A] focus:bg-[#FFFFFF] focus:ring-0 focus:outline-none transition text-base">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-black text-[#3C3C4B] uppercase tracking-widest mb-2">Hourly Shop Rate</label>
                        <div class="relative">
                            <span class="absolute left-4 top-3.5 font-bold text-slate-400">$</span>
                            <input type="number" min="0" name="hourly_rate" value="{{ old('hourly_rate', auth()->user()->hourly_rate) }}" placeholder="95" class="w-full bg-[#F0F0F0] border-2 border-slate-200 rounded-xl text-[#3C3C3C] placeholder-slate-400 font-bold py-3.5 pl-8 pr-4 focus:border-[#0F2D5A] focus:bg-[#FFFFFF] focus:ring-0 focus:outline-none transition text-base">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-black text-[#3C3C4B] uppercase tracking-widest mb-2">Standard Crew Size</label>
                        <input type="number" min="1" max="100" name="crew_size" value="{{ old('crew_size', auth()->user()->crew_size) }}" placeholder="2" class="w-full bg-[#F0F0F0] border-2 border-slate-200 rounded-xl text-[#3C3C3C] placeholder-slate-400 font-bold py-3.5 px-4 focus:border-[#0F2D5A] focus:bg-[#FFFFFF] focus:ring-0 focus:outline-none transition text-base">
                    </div>
                </div>
            </div>

            {{-- SECTION 5: MARKETING PUBLIC TEXT COPY --}}
            <div class="space-y-4">
                <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 pb-2">5. Search Directory Overview</h3>
                <div>
                    <label class="block text-xs font-black text-[#3C3C4B] uppercase tracking-widest mb-2">About Your Business (Bio Descriptions)</label>
                    <textarea name="bio" rows="5" placeholder="Briefly introduce your company, detail your specialized crew services, warranties, or project size history so customers find you on search engines..." class="w-full bg-[#F0F0F0] border-2 border-slate-200 rounded-xl text-[#3C3C3C] placeholder-slate-400 font-bold py-3.5 px-4 focus:border-[#0F2D5A] focus:bg-[#FFFFFF] focus:ring-0 focus:outline-none transition text-base leading-relaxed">{{ old('bio', auth()->user()->bio) }}</textarea>
                </div>
            </div>

            <button type="submit" class="w-full bg-[#0F2D5A] hover:bg-[#1E3C5A] text-[#FFFFFF] font-black text-sm uppercase tracking-wider py-4 rounded-xl shadow-md transition transform active:scale-95 border border-[#0F2D5A]">
                Save Profile Changes →
            </button>
        </form>
    </div>
</div>
@endsection