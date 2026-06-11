<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Project Proposal | {{ $estimate->user->business_name ?? $estimate->user->name }}</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght=400;500;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="text-slate-800 antialiased min-h-full py-8 sm:py-16">

    <div class="max-w-3xl mx-auto px-4 sm:px-6">
        
        {{-- SYSTEM FEEDBACK NOTIFICATIONS --}}
        @if (session('status'))
            <div class="mb-8 bg-slate-900 border-l-8 border-[#FFC32D] p-5 rounded-2xl text-left shadow-sm">
                <p class="text-sm font-bold text-white leading-snug">{{ session('status') }}</p>
            </div>
        @endif

        {{-- MAIN INVOICE/PROPOSAL CONTAINER SHEET --}}
        <div class="bg-white rounded-[2.5rem] border border-slate-200/80 shadow-xl p-6 sm:p-12 space-y-8">
            
            {{-- LOGO & METADATA OVERVIEW HEADER --}}
            <div class="flex flex-col sm:flex-row justify-between items-start gap-6 border-b border-slate-100 pb-8 text-left">
                <div class="space-y-2">
                    <span class="bg-slate-900 text-white text-[9px] font-black uppercase tracking-widest px-2.5 py-1 rounded-md">
                        {{ $estimate->user->specialty->name ?? 'Verified Pro' }}
                    </span>
                    <h1 class="text-2xl font-black text-slate-900 tracking-tight">
                        {{ $estimate->user->business_name ?? $estimate->user->name }}
                    </h1>
                    <p class="text-xs font-bold text-slate-400">Phone: {{ $estimate->user->phone }}</p>
                </div>
                <div class="sm:text-right space-y-1">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Document Registry</p>
                    <p class="text-lg font-black text-slate-900">Proposal #EST-00{{ $estimate->id }}</p>
                    <p class="text-xs font-bold text-slate-400">Issued: {{ $estimate->created_at->format('M d, Y') }}</p>
                </div>
            </div>

            {{-- CLIENT PROFILE SNAPSHOT --}}
            <div class="bg-slate-50 p-6 rounded-2xl border border-slate-200/60 text-left">
                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-2">Prepared Exclusively For</span>
                <h3 class="text-base font-black text-slate-900 tracking-tight">{{ $estimate->client_name }}</h3>
                <p class="text-xs font-medium text-slate-500 mt-0.5">Project Scope: <span class="font-bold text-slate-700">{{ $estimate->project_title }}</span></p>
            </div>

            {{-- ITEMIZED COST METRIC ROWS TABLE --}}
            <div class="space-y-4 text-left">
                <h4 class="text-xs font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 pb-2">Itemized Breakdown of Services</h4>
                
                <div class="divide-y divide-slate-100">
                    @foreach($estimate->items as $item)
                        <div class="py-4 flex justify-between items-start gap-4">
                            <div>
                                <h5 class="text-sm font-black text-slate-800 tracking-tight">{{ $item->description }}</h5>
                                <p class="text-xs font-bold text-slate-400 mt-0.5">
                                    Qty {{ $item->quantity }} × ${{ number_format($item->unit_price_cents / 100, 2) }}
                                </p>
                            </div>
                            <span class="text-sm font-black text-slate-900">
                                ${{ number_format($item->total_price_cents / 100, 2) }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- HIGH-OCTANE VISUAL WORKSPACE GALLERY ATTACHMENTS --}}
            @if($estimate->attachments->where('is_public', true)->count() > 0)
                <div class="space-y-4 text-left pt-2">
                    <h4 class="text-xs font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 pb-2">Project Specifications & Visual Layouts</h4>
                    <div class="grid grid-cols-1 gap-6">
                        @foreach($estimate->attachments->where('is_public', true) as $attachment)
                            <div class="group relative bg-slate-950 rounded-2xl overflow-hidden border border-slate-200 shadow-md transition-all duration-300">
                                <div class="absolute top-3 left-3 z-10 bg-slate-900/80 backdrop-blur-md px-3 py-1 rounded-md border border-slate-700">
                                    <span class="text-[9px] text-[#FFC32D] font-black uppercase tracking-wider">Annotated Field Capture</span>
                                </div>
                                <img src="{{ asset('storage/' . $attachment->file_path) }}" 
                                     alt="Project markup specification asset entry" 
                                     class="w-full h-auto max-h-[500px] object-contain mx-auto block">
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- FINANCIAL RUNNING SUMMARY DECK --}}
            <div class="border-t border-slate-100 pt-6 flex flex-col items-end text-right space-y-2">
                <div class="flex justify-between w-full max-w-xs text-sm font-bold text-slate-400">
                    <span>Subtotal</span>
                    <span class="text-slate-800">${{ number_format($estimate->subtotal_cents / 100, 2) }}</span>
                </div>
                <div class="flex justify-between w-full max-w-xs text-sm font-bold text-slate-400">
                    <span>Tax & Surcharges</span>
                    <span class="text-slate-800">$0.00</span>
                </div>
                <div class="flex justify-between w-full max-w-xs pt-3 border-t border-slate-200 text-slate-900">
                    <span class="text-base font-black">Grand Total</span>
                    <span class="text-2xl font-black text-[#0F2D5A]">${{ number_format($estimate->total_cents / 100, 2) }}</span>
                </div>
            </div>

            {{-- HISTORIC NOTE DISPLAY BLOCK (Shows after submission) --}}
            @if($estimate->customer_notes)
                <div class="bg-slate-50 p-5 rounded-2xl border border-slate-200/60 text-left space-y-1">
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block">Submitted Client Comments</span>
                    <p class="text-sm font-medium text-slate-600 leading-relaxed italic">"{{ $estimate->customer_notes }}"</p>
                </div>
            @endif

            {{-- CALM, PREMIUM DECISION MANAGEMENT BAR --}}
            <div class="pt-6 border-t border-slate-100 text-left space-y-6">
                
                @if($estimate->status === 'draft' || $estimate->status === 'sent')
                    <form action="{{ route('estimates.public.status', $estimate->secure_token) }}" method="POST" class="space-y-6">
                        @csrf
                        
                        {{-- Unaggressive Input Box Overlay --}}
                        <div class="space-y-1.5">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Optional Notes, Corrections, or Scheduling Requests</label>
                            <textarea id="customer_notes" name="customer_notes" rows="3" placeholder="Need any dynamic adjustments? Have a specific scheduling preference? Add your notes here before responding..." class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold py-3 px-4 focus:outline-none focus:border-slate-400 focus:bg-white transition leading-relaxed placeholder-slate-400"></textarea>
                        </div>

                        <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-2">
                            <p class="text-xs text-slate-400 font-bold max-w-xs leading-normal">
                                Reviewing and accepting locks in your project specifications and logs execution initialization routines.
                            </p>
                            <div class="flex items-center gap-4 w-full sm:w-auto justify-end">
                                <button type="submit" name="action" value="decline" class="text-xs font-black text-slate-400 hover:text-red-500 uppercase tracking-wider transition px-4 py-3">
                                    Decline
                                </button>
                                <button type="submit" name="action" value="approve" class="w-full sm:w-auto bg-emerald-700 hover:bg-emerald-800 text-white font-black text-xs uppercase tracking-widest py-4 px-8 rounded-xl shadow-md transition transform active:scale-95 text-center whitespace-nowrap">
                                    Review & Accept Proposal →
                                </button>
                            </div>
                        </div>
                    </form>
                @else
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                        <div class="max-w-md">
                            @if($estimate->status === 'approved' || $estimate->status === 'invoiced')
                                <span class="bg-emerald-50 border border-emerald-200 text-emerald-700 text-[10px] uppercase font-black tracking-wider px-3 py-1 rounded-md block w-max">✓ Proposal Authorized</span>
                                <p class="text-xs text-slate-400 font-bold mt-2">This schedule contract was signed and locked into production scheduling.</p>
                            @elseif($estimate->status === 'declined')
                                <span class="bg-red-50 border border-red-200 text-red-700 text-[10px] uppercase font-black tracking-wider px-3 py-1 rounded-md block w-max">Proposal Declined</span>
                                <p class="text-xs text-slate-400 font-bold mt-2">You marked this arrangement as declined. Contact your specialist for a revised copy.</p>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

        </div>

        {{-- SECURE BACKUP FOOTER CAP --}}
        <div class="mt-8 text-center space-y-2">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Secured & Provided via the Contractor Specialties Network</p>
            <button onclick="window.print()" class="text-xs font-bold text-slate-400 hover:text-slate-600 underline transition">Print or Save Invoice Copy</button>
        </div>

    </div>

</body>
</html>