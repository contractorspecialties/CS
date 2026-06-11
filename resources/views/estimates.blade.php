@extends('layouts.app')

@section('title', 'Project Estimates')

@section('content')
{{-- Encapsulate the entire workspace state cleanly so it doesn't leak into the app shell layout --}}
<div class="space-y-6" x-data="{ estimateModalOpen: false }">
    
    {{-- PAGE HEADER INTERFACE --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 sm:p-8 rounded-2xl border border-slate-200/80 shadow-sm text-left">
        <div>
            <h1 class="text-2xl font-black text-[#0F2D5A] tracking-tight">Project Estimates</h1>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mt-1">
                Draft, issue, and manage client project proposals directly from the field.
            </p>
        </div>
        <button @click="estimateModalOpen = true" class="w-full sm:w-auto bg-[#0F2D5A] hover:bg-[#1E3C5A] text-white text-sm font-black uppercase tracking-wider px-6 py-4 rounded-xl transition transform active:scale-95 shadow-md whitespace-nowrap text-center">
            + Create New Estimate
        </button>
    </div>

    {{-- REAL-TIME PIPELINE TELEMETRY COUNTERS --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-center">
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm text-left flex items-center justify-between">
            <div>
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block">Draft Proposals</span>
                <span class="text-2xl font-black text-[#0F2D5A] block mt-1">
                    ${{ number_format($estimates->where('status', 'draft')->sum('total_cents') / 100, 2) }}
                </span>
            </div>
            <span class="text-xl bg-slate-100 p-3 rounded-xl">📁</span>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm text-left flex items-center justify-between">
            <div>
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block">Awaiting Review</span>
                <span class="text-2xl font-black text-amber-600 block mt-1">
                    ${{ number_format($estimates->where('status', 'sent')->sum('total_cents') / 100, 2) }}
                </span>
            </div>
            <span class="text-xl bg-amber-50 text-amber-600 p-3 rounded-xl">⏳</span>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm text-left flex items-center justify-between">
            <div>
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block">Approved Pipeline</span>
                <span class="text-2xl font-black text-emerald-600 block mt-1">
                    ${{ number_format($estimates->where('status', 'approved')->sum('total_cents') / 100, 2) }}
                </span>
            </div>
            <span class="text-xl bg-emerald-50 text-emerald-600 p-3 rounded-xl">💸</span>
        </div>
    </div>

    {{-- ESTIMATES ROSTER CONTAINER --}}
    <div class="bg-[#FFFFFF] rounded-[2.5rem] border-4 border-slate-900 shadow-xl p-6 sm:p-8 space-y-6">
        <div>
            <h2 class="text-lg font-black text-[#0F2D5A] tracking-tight text-left">Quote Log & Historic Records</h2>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mt-0.5 text-left">Select any logged row sequence to verify details, monitor statuses, or copy links</p>
        </div>

        @if($estimates->count() > 0)
            {{-- DESKTOP ROW MODE TABLE LAYOUT (Hidden on Mobile) --}}
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-200 text-[10px] font-black text-slate-400 uppercase tracking-widest bg-slate-50">
                            <th class="py-4 px-4">Client Contact</th>
                            <th class="py-4 px-4">Project Title</th>
                            <th class="py-4 px-4">Status Tag</th>
                            <th class="py-4 px-4">Quote Total</th>
                            <th class="py-4 px-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm font-bold divide-y divide-slate-100">
                        @foreach($estimates as $estimate)
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="py-4 px-4">
                                    <p class="text-slate-900 font-black">{{ $estimate->client_name }}</p>
                                    <p class="text-xs text-slate-400">{{ $estimate->client_email ?? 'No email supplied' }}</p>
                                </td>
                                <td class="py-4 px-4 text-slate-600 font-medium">
                                    {{ $estimate->project_title }}
                                </td>
                                <td class="py-4 px-4">
                                    @if($estimate->status === 'draft')
                                        <span class="bg-slate-100 border border-slate-200 text-slate-700 text-[9px] uppercase font-black tracking-wider px-2.5 py-1 rounded-md">Draft</span>
                                    @elseif($estimate->status === 'sent')
                                        <span class="bg-amber-50 border border-amber-200 text-amber-700 text-[9px] uppercase font-black tracking-wider px-2.5 py-1 rounded-md">Sent</span>
                                    @elseif($estimate->status === 'approved')
                                        <span class="bg-emerald-50 border border-emerald-200 text-emerald-700 text-[9px] uppercase font-black tracking-wider px-2.5 py-1 rounded-md">Approved</span>
                                    @else
                                        <span class="bg-red-50 border border-red-200 text-red-700 text-[9px] uppercase font-black tracking-wider px-2.5 py-1 rounded-md">{{ $estimate->status }}</span>
                                    @endif
                                </td>
                                <td class="py-4 px-4 text-base font-black text-[#0F2D5A]">
                                    ${{ number_format($estimate->total_cents / 100, 2) }}
                                </td>
                                <td class="py-4 px-4 text-right">
                                    <a href="{{ route('estimates.public.show', $estimate->secure_token) }}" target="_blank" class="inline-block bg-slate-100 hover:bg-slate-200 border border-slate-200 text-slate-700 text-xs font-black uppercase tracking-wider py-2 px-3.5 rounded-lg transition active:scale-95">
                                        View Proposal ↗
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- MOBILE STACKED TOUCH TARGET CARDS (Hidden on Desktop) --}}
            <div class="block md:hidden space-y-3">
                @foreach($estimates as $estimate)
                    <a href="{{ route('estimates.public.show', $estimate->secure_token) }}" target="_blank" class="block bg-white border-2 border-slate-200/80 p-5 rounded-2xl text-left space-y-3 shadow-sm active:bg-slate-50 transition">
                        <div class="flex items-center justify-between">
                            <div>
                                <h4 class="text-base font-black text-slate-900 tracking-tight">{{ $estimate->client_name }}</h4>
                                <p class="text-xs font-bold text-slate-400">{{ $estimate->project_title }}</p>
                            </div>
                            <span class="text-base font-black text-[#0F2D5A]">
                                ${{ number_format($estimate->total_cents / 100, 2) }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between pt-2 border-t border-slate-100">
                            <span class="text-[10px] text-slate-400 font-bold">{{ $estimate->created_at->format('M d, Y') }}</span>
                            @if($estimate->status === 'draft')
                                <span class="bg-slate-100 border border-slate-200 text-slate-700 text-[8px] uppercase font-black tracking-wider px-2.5 py-1 rounded-md">Draft</span>
                            @elseif($estimate->status === 'sent')
                                <span class="bg-amber-50 border border-amber-200 text-amber-700 text-[8px] uppercase font-black tracking-wider px-2.5 py-1 rounded-md">Sent</span>
                            @elseif($estimate->status === 'approved')
                                <span class="bg-emerald-50 border border-emerald-200 text-emerald-700 text-[8px] uppercase font-black tracking-wider px-2.5 py-1 rounded-md">Approved</span>
                            @else
                                <span class="bg-red-50 border border-red-200 text-red-700 text-[8px] uppercase font-black tracking-wider px-2.5 py-1 rounded-md">{{ $estimate->status }}</span>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            {{-- EMPTY LOG FALLBACK PANEL --}}
            <div class="border-4 border-dashed border-slate-200 rounded-[2rem] p-10 text-center">
                <span class="text-4xl block mb-2">📋</span>
                <h4 class="text-base font-black text-[#0F2D5A]">No Estimates Generated Yet</h4>
                <p class="text-xs text-slate-400 font-bold max-w-sm mx-auto mt-1">Tap the button above to draft your first itemized invoice or service contract quote.</p>
            </div>
        @endif
    </div>

    {{-- INTERACTIVE COMPONENT DIALOG OVERLAY: CREATE ESTIMATE MODAL --}}
    <div x-show="estimateModalOpen" 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/60 backdrop-blur-sm"
         x-transition
         style="display: none;">
        
        <div class="bg-white rounded-[2.5rem] border-4 border-slate-900 w-full max-w-2xl max-h-[90vh] overflow-y-auto shadow-2xl p-6 sm:p-8 space-y-6"
             @click.away="estimateModalOpen = false">
            
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div class="text-left">
                    <h3 class="text-xl font-black text-[#0F2D5A] tracking-tight">Generate New Project Estimate</h3>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mt-0.5">Create and issue professional estimates instantly</p>
                </div>
                <button @click="estimateModalOpen = false" class="w-8 h-8 rounded-lg bg-slate-100 text-slate-500 font-black text-sm flex items-center justify-center hover:bg-slate-200">×</button>
            </div>

            <form action="{{ route('estimates.store') }}" method="POST" class="space-y-6 text-left" 
                  x-data="{ 
                      items: [
                          { description: 'Standard Service Call Out Fee', type: 'labor', quantity: 1, unit_price: {{ auth()->user()->minimum_service_fee ?? 85 }} }
                      ],
                      addItem() {
                          this.items.push({ description: 'Additional Labor / Materials Description', type: 'labor', quantity: 1, unit_price: {{ auth()->user()->hourly_rate ?? 95 }} });
                      },
                      removeItem(index) {
                          if (this.items.length > 1) this.items.splice(index, 1);
                      },
                      calculateTotal() {
                          let total = 0;
                          this.items.forEach(item => {
                              total += (item.quantity * item.unit_price);
                          });
                          return total;
                      }
                  }">
                @csrf

                <div class="space-y-4">
                    <h4 class="text-xs font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 pb-1">1. Customer Information</h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Client Full Name</label>
                            <input type="text" name="client_name" required placeholder="e.g. Walter White" class="w-full bg-[#F0F0F0] border-2 border-slate-200 rounded-xl text-sm font-bold py-3 px-4 focus:border-[#0F2D5A] focus:bg-white focus:outline-none focus:ring-0 transition">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Client Email Address</label>
                            <input type="email" name="client_email" placeholder="e.g. walter@gmail.com" class="w-full bg-[#F0F0F0] border-2 border-slate-200 rounded-xl text-sm font-bold py-3 px-4 focus:border-[#0F2D5A] focus:bg-white focus:outline-none focus:ring-0 transition">
                        </div>
                    </div>
                </div>

                <div class="space-y-4">
                    <h4 class="text-xs font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 pb-1">2. Project Scope</h4>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Project Title Name</label>
                        <input type="text" name="project_title" required placeholder="e.g. Master Bedroom Ceiling Fan Installation" class="w-full bg-[#F0F0F0] border-2 border-slate-200 rounded-xl text-sm font-bold py-3 px-4 focus:border-[#0F2D5A] focus:bg-white focus:outline-none focus:ring-0 transition">
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-1">
                        <h4 class="text-xs font-black text-slate-400 uppercase tracking-widest">3. Line Items</h4>
                        <button type="button" @click="addItem()" class="text-[10px] font-black text-[#0F2D5A] hover:underline uppercase tracking-wider">+ Add Row Item</button>
                    </div>

                    <div class="space-y-3">
                        <template x-for="(item, index) in items" :key="index">
                            <div class="grid grid-cols-12 gap-2 items-center bg-slate-50 p-3 rounded-xl border border-slate-200/70">
                                <div class="col-span-12 sm:col-span-6">
                                    <input type="text" :name="'items[' + index + '][description]'" x-model="item.description" placeholder="Item Description" required class="w-full bg-white border border-slate-200 rounded-lg text-xs font-bold py-2.5 px-3 focus:outline-none focus:border-[#0F2D5A]">
                                </div>
                                <div class="col-span-4 sm:col-span-2">
                                    <input type="number" min="1" :name="'items[' + index + '][quantity]'" x-model.number="item.quantity" placeholder="Qty" required class="w-full bg-white border border-slate-200 rounded-lg text-xs font-bold py-2.5 px-2 text-center focus:outline-none focus:border-[#0F2D5A]">
                                </div>
                                <div class="col-span-6 sm:col-span-3 relative">
                                    <span class="absolute left-2.5 top-2.5 text-xs font-bold text-slate-400">$</span>
                                    <input type="number" min="0" :name="'items[' + index + '][unit_price]'" x-model.number="item.unit_price" placeholder="Rate" required class="w-full bg-white border border-slate-200 rounded-lg text-xs font-bold py-2.5 pl-5 pr-2 focus:outline-none focus:border-[#0F2D5A]">
                                </div>
                                <div class="col-span-2 sm:col-span-1 text-center">
                                    <button type="button" @click="removeItem(index)" class="text-red-500 font-black text-sm hover:text-red-700">×</button>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <div class="bg-slate-900 rounded-2xl p-5 text-white flex items-center justify-between">
                    <div>
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block">Calculated Invoice Total</span>
                        <p class="text-xs font-bold text-slate-500 mt-0.5">Calculated in real-time from active configurations</p>
                    </div>
                    <div class="text-right">
                        <span class="text-2xl font-black text-[#FFC32D]" x-text="'$' + calculateTotal().toFixed(2)">$0.00</span>
                    </div>
                </div>

                <div class="flex gap-4 pt-2">
                    <button type="button" @click="estimateModalOpen = false" class="w-1/3 bg-slate-100 hover:bg-slate-200 text-slate-600 font-black text-xs uppercase tracking-wider py-4 rounded-xl transition">
                        Cancel
                    </button>
                    <button type="submit" class="w-2/3 bg-[#0F2D5A] hover:bg-[#1E3C5A] text-white font-black text-xs uppercase tracking-wider py-4 rounded-xl shadow-md transition transform active:scale-95">
                        Save and Issue Estimate →
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection