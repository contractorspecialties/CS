@extends('layouts.app')

@section('title', 'Billing Invoices')

@section('content')
<div class="space-y-6">
    
    {{-- SYSTEM FEEDBACK NOTIFICATIONS --}}
    @if (session('status'))
        <div class="bg-slate-900 border-l-8 border-[#FFC32D] p-5 rounded-2xl text-left shadow-sm">
            <p class="text-sm font-bold text-white leading-snug">{{ session('status') }}</p>
        </div>
    @endif

    {{-- PAGE HEADER INTERFACE --}}
    <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200/80 shadow-sm text-left">
        <h1 class="text-2xl font-black text-[#0F2D5A] tracking-tight">Billing Invoices</h1>
        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mt-1">
            Track customer balances, finalize outstanding work orders, and log field collection processing events.
        </p>
    </div>

    {{-- REAL-TIME OUTSTANDING LIQUIDITY COUNTERS --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-center">
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm text-left flex items-center justify-between">
            <div>
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block">Unissued Drafts</span>
                <span class="text-2xl font-black text-slate-600 block mt-1">
                    ${{ number_format($invoices->where('status', 'draft')->sum('total_cents') / 100, 2) }}
                </span>
            </div>
            <span class="text-xl bg-slate-100 p-3 rounded-xl">✏️</span>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm text-left flex items-center justify-between">
            <div>
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block">Receivables Pipeline</span>
                <span class="text-2xl font-black text-amber-600 block mt-1">
                    ${{ number_format($invoices->where('status', 'sent')->sum('total_cents') / 100, 2) }}
                </span>
            </div>
            <span class="text-xl bg-amber-50 text-amber-600 p-3 rounded-xl">⏳</span>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm text-left flex items-center justify-between">
            <div>
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block">Total Collected YTD</span>
                <span class="text-2xl font-black text-emerald-600 block mt-1">
                    ${{ number_format($invoices->where('status', 'paid')->sum('amount_paid_cents') / 100, 2) }}
                </span>
            </div>
            <span class="text-xl bg-emerald-50 text-emerald-600 p-3 rounded-xl">💰</span>
        </div>
    </div>

    {{-- INVOICES ROSTER CONTAINER --}}
    <div class="bg-[#FFFFFF] rounded-[2.5rem] border-4 border-slate-900 shadow-xl p-6 sm:p-8 space-y-6">
        <div>
            <h2 class="text-lg font-black text-[#0F2D5A] tracking-tight text-left">Accounts Receivable Ledger</h2>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mt-0.5 text-left">Log payments, archive closed accounts, or purge structural tracking mistakes securely</p>
        </div>

        @if($invoices->count() > 0)
            {{-- DESKTOP VIEW LEDGER (Hidden on Mobile) --}}
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-200 text-[10px] font-black text-slate-400 uppercase tracking-widest bg-slate-50">
                            <th class="py-4 px-4">Client Contact</th>
                            <th class="py-4 px-4">Project Scope</th>
                            <th class="py-4 px-4">Status</th>
                            <th class="py-4 px-4">Total Value</th>
                            <th class="py-4 px-4 text-right">Operations Matrix</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm font-bold divide-y divide-slate-100">
                        @foreach($invoices as $invoice)
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="py-4 px-4">
                                    <p class="text-slate-900 font-black">{{ $invoice->client_name }}</p>
                                    <p class="text-xs text-slate-400">{{ $invoice->client_email ?? 'No email profile' }}</p>
                                </td>
                                <td class="py-4 px-4 text-slate-600 font-medium">
                                    {{ $invoice->project_title }}
                                </td>
                                <td class="py-4 px-4">
                                    @if($invoice->status === 'draft')
                                        <span class="bg-slate-100 border border-slate-200 text-slate-700 text-[9px] uppercase font-black tracking-wider px-2.5 py-1 rounded-md">Draft</span>
                                    @elseif($invoice->status === 'sent')
                                        <span class="bg-amber-50 border border-amber-200 text-amber-700 text-[9px] uppercase font-black tracking-wider px-2.5 py-1 rounded-md">Awaiting Payment</span>
                                    @elseif($invoice->status === 'paid')
                                        <span class="bg-emerald-50 border border-emerald-200 text-emerald-700 text-[9px] uppercase font-black tracking-wider px-2.5 py-1 rounded-md">Paid</span>
                                    @else
                                        <span class="bg-red-50 border border-red-200 text-red-700 text-[9px] uppercase font-black tracking-wider px-2.5 py-1 rounded-md">{{ $invoice->status }}</span>
                                    @endif
                                </td>
                                <td class="py-4 px-4 text-base font-black text-[#0F2D5A]">
                                    ${{ number_format($invoice->total_cents / 100, 2) }}
                                </td>
                                <td class="py-4 px-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        @if($invoice->status !== 'paid')
                                            <form action="{{ route('invoices.paid', $invoice->id) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 text-emerald-700 text-xs font-black uppercase tracking-wider py-2 px-3 rounded-lg transition">
                                                    ✓ Settle Balance
                                                </button>
                                            </form>
                                        @endif

                                        <form action="{{ route('invoices.archive', $invoice->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" title="Archive Record" class="bg-slate-50 hover:bg-slate-100 border border-slate-200 text-slate-500 text-xs font-black py-2 px-3 rounded-lg transition">
                                                📁
                                            </button>
                                        </form>

                                        <form action="{{ route('invoices.destroy', $invoice->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to permanently delete this invoice record?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" title="Delete Record" class="bg-red-50 hover:bg-red-100 border border-red-200 text-red-600 text-xs font-black py-2 px-3 rounded-lg transition">
                                                🗑
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- MOBILE VIEW STACKED TOUCH TARGET CARDS (Hidden on Desktop) --}}
            <div class="block md:hidden space-y-3">
                @foreach($invoices as $invoice)
                    <div class="bg-white border-2 border-slate-200/80 p-5 rounded-2xl text-left space-y-4 shadow-sm">
                        <div class="flex items-center justify-between">
                            <div>
                                <h4 class="text-base font-black text-slate-900 tracking-tight">{{ $invoice->client_name }}</h4>
                                <p class="text-xs font-bold text-slate-400">{{ $invoice->project_title }}</p>
                            </div>
                            <span class="text-base font-black text-[#0F2D5A]">
                                ${{ number_format($invoice->total_cents / 100, 2) }}
                            </span>
                        </div>

                        <div class="flex items-center justify-between pt-3 border-t border-slate-100 gap-2 flex-wrap">
                            <div class="flex items-center gap-2">
                                @if($invoice->status === 'draft')
                                    <span class="bg-slate-100 border border-slate-200 text-slate-700 text-[8px] uppercase font-black tracking-wider px-2.5 py-1 rounded-md">Draft</span>
                                @elseif($invoice->status === 'sent')
                                    <span class="bg-amber-50 border border-amber-200 text-amber-700 text-[8px] uppercase font-black tracking-wider px-2.5 py-1 rounded-md">Sent</span>
                                @elseif($invoice->status === 'paid')
                                    <span class="bg-emerald-50 border border-emerald-200 text-emerald-700 text-[8px] uppercase font-black tracking-wider px-2.5 py-1 rounded-md">Paid</span>
                                @endif
                                <span class="text-[10px] text-slate-400 font-bold">{{ $invoice->created_at->format('M d') }}</span>
                            </div>

                            <div class="flex items-center gap-2">
                                @if($invoice->status !== 'paid')
                                    <form action="{{ route('invoices.paid', $invoice->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="bg-emerald-700 text-white text-xs font-black uppercase tracking-wider py-2 px-3 rounded-xl transition">
                                            Settle
                                        </button>
                                    </form>
                                @endif

                                <form action="{{ route('invoices.archive', $invoice->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="bg-slate-100 border border-slate-200 text-slate-400 py-2 px-2.5 rounded-xl transition">
                                        📁
                                    </button>
                                </form>

                                <form action="{{ route('invoices.destroy', $invoice->id) }}" method="POST" onsubmit="return confirm('Delete this invoice?');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-50 border border-red-100 text-red-500 py-2 px-2.5 rounded-xl transition">
                                        🗑
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="border-4 border-dashed border-slate-200 rounded-[2rem] p-10 text-center">
                <span class="text-4xl block mb-2">💵</span>
                <h4 class="text-base font-black text-[#0F2D5A]">No Invoices Generated Yet</h4>
                <p class="text-xs text-slate-400 font-bold max-w-sm mx-auto mt-1">Convert an approved estimate row to begin collecting balances and managing accounts receivable metrics.</p>
            </div>
        @endif
    </div>
</div>
@endsection