@extends('layouts.app')

@section('title', 'Profit & Margin Calculator')

@section('content')
<div class="space-y-6 w-full max-w-4xl mx-auto text-left" 
     x-data="profitCalculator()">
    
    {{-- CALCULATOR HEADER --}}
    <div class="bg-slate-900 text-white p-6 rounded-2xl border border-slate-800 shadow-xl">
        <span class="bg-[#FFC32D] text-slate-950 text-[9px] font-black uppercase tracking-widest px-2.5 py-1 rounded-md">Job Costing Tool</span>
        <h1 class="text-xl font-black tracking-tight mt-2">CPP Profit & Margin Calculator</h1>
        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mt-0.5">Calculate real job costs, break-even limits, and accurate target retail pricing structures.</p>
    </div>

    {{-- INTERACTIVE WORKSPACE GRID --}}
    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6 items-start">
        
        {{-- LEFT COLUMN: HARD COST INPUTS (Takes up 2/5 space) --}}
        <div class="lg:col-span-2 bg-white border border-slate-200 p-5 rounded-3xl shadow-sm space-y-4">
            <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 pb-2">1. Input Job Costs</h3>
            
            {{-- MATERIAL COSTS INPUT --}}
            <div class="space-y-1">
                <label class="block text-[11px] font-black text-slate-700 uppercase tracking-wide">Total Material Cost ($)</label>
                <input type="number" x-model.number="costs.materials" @input="calculate()" class="w-full bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono font-black p-3 focus:bg-white focus:border-[#0F2D5A] focus:outline-none transition" placeholder="0.00">
            </div>

            {{-- LABOR HOURLY CALCULATION DECK --}}
            <div class="p-3 bg-slate-50 rounded-xl border border-slate-100 space-y-3">
                <span class="text-[9px] font-black text-slate-400 uppercase tracking-wider block">Estimated Project Labor</span>
                <div class="grid grid-cols-2 gap-2">
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-slate-500">Total Hours</label>
                        <input type="number" x-model.number="costs.laborHours" @input="calculate()" class="w-full bg-white border border-slate-200 rounded-lg text-xs font-mono p-2 focus:outline-none focus:border-[#0F2D5A]" placeholder="0">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-slate-500">Rate Per Hour ($)</label>
                        <input type="number" x-model.number="costs.laborRate" @input="calculate()" class="w-full bg-white border border-slate-200 rounded-lg text-xs font-mono p-2 focus:outline-none focus:border-[#0F2D5A]" placeholder="25.00">
                    </div>
                </div>
            </div>

            {{-- COMPANY OVERHEAD LOAD MULTIPLIER --}}
            <div class="space-y-1">
                <div class="flex justify-between items-center">
                    <label class="block text-[11px] font-black text-slate-700 uppercase tracking-wide">Company Overhead Allocation</label>
                    <span class="text-xs font-mono font-black text-slate-500" x-text="costs.overhead + '%'">15%</span>
                </div>
                <p class="text-[10px] font-bold text-slate-400 leading-none mb-1">Percentage of revenue needed to keep office lights on.</p>
                <input type="range" min="0" max="50" step="1" x-model.number="costs.overhead" @input="calculate()" class="w-full h-2 bg-slate-100 rounded-lg appearance-none cursor-pointer accent-[#0F2D5A]">
            </div>

            {{-- DESIRED NET NET PROFIT MARGIN --}}
            <div class="space-y-1 pt-2 border-t border-slate-100">
                <div class="flex justify-between items-center">
                    <label class="block text-[11px] font-black text-[#0F2D5A] uppercase tracking-wide">Target Net Profit Margin</label>
                    <span class="text-xs font-mono font-black text-[#0F2D5A]" x-text="costs.targetMargin + '%'">30%</span>
                </div>
                <p class="text-[10px] font-bold text-slate-400 leading-none mb-1">Pure profit leftover after labor, materials, and overhead are paid.</p>
                <input type="range" min="5" max="75" step="1" x-model.number="costs.targetMargin" @input="calculate()" class="w-full h-2 bg-slate-100 rounded-lg appearance-none cursor-pointer accent-[#FFC32D]">
            </div>
        </div>

        {{-- RIGHT COLUMN: REAL-TIME MARGIN ANALYTICS GENERATOR (Takes up 3/5 space) --}}
        <div class="lg:col-span-3 space-y-4">
            
            {{-- THE CORE PRICE QUOTE TARGET MATRIX CARD --}}
            <div class="bg-slate-900 text-white p-6 rounded-[2rem] shadow-xl border border-slate-800 flex items-center justify-between relative overflow-hidden">
                <div class="space-y-1">
                    <span class="text-[9px] font-black text-[#FFC32D] uppercase tracking-widest block">Required Minimum Price Quote</span>
                    <h2 class="text-3xl font-black font-mono tracking-tight text-[#FFC32D]" x-text="results.sellingPriceFormatted">$0.00</h2>
                    <p class="text-[11px] font-bold text-slate-400">Quote this amount to guarantee your target profit parameters match up.</p>
                </div>
                <div class="text-right hidden sm:block">
                    <span class="text-[9px] font-black text-slate-500 uppercase tracking-wider block">Gross Markup Required</span>
                    <span class="text-lg font-black font-mono block text-white mt-1" x-text="results.markupPercent + '%'">0%</span>
                </div>
            </div>

            {{-- SECONDARY METRICS BREAKDOWN BOARD --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                
                {{-- BREAK EVEN BENCHMARK CONTAINER --}}
                <div class="bg-white border border-slate-200 p-4 rounded-2xl shadow-sm text-left flex items-center justify-between">
                    <div>
                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block">Job Production Break-Even</span>
                        <span class="text-lg font-black font-mono text-slate-800 block mt-1" x-text="results.breakEvenFormatted">$0.00</span>
                        <p class="text-[10px] font-bold text-slate-400 mt-0.5">Total hard execution cost baseline.</p>
                    </div>
                </div>

                {{-- NET NET PROFIT SUM VALUATION --}}
                <div class="bg-white border border-slate-200 p-4 rounded-2xl shadow-sm text-left flex items-center justify-between">
                    <div>
                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block">Take-Home Profit Dollars</span>
                        <span class="text-lg font-black font-mono text-emerald-600 block mt-1" x-text="results.netProfitFormatted">$0.00</span>
                        <p class="text-[10px] font-bold text-emerald-700/70 mt-0.5">Pure company cash retention value.</p>
                    </div>
                </div>
            </div>

            {{-- DETAILED COST METRIC PIE PERCENTAGE DISPLAY METER --}}
            <div class="bg-white border border-slate-200 p-5 rounded-2xl shadow-sm space-y-3 text-xs font-bold">
                <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Revenue Allocation Visual Strip</h4>
                
                <div class="h-6 w-full rounded-xl overflow-hidden flex text-white text-[10px] font-black text-center shadow-inner">
                    <div class="bg-red-500 transition-all duration-300 flex items-center justify-center" :style="'width: ' + chart.materials + '%'" x-show="chart.materials > 5" x-text="'Mat: ' + chart.materials + '%'"></div>
                    <div class="bg-blue-500 transition-all duration-300 flex items-center justify-center" :style="'width: ' + chart.labor + '%'" x-show="chart.labor > 5" x-text="'Lab: ' + chart.labor + '%'"></div>
                    <div class="bg-amber-500 transition-all duration-300 flex items-center justify-center" :style="'width: ' + chart.overhead + '%'" x-show="chart.overhead > 5" x-text="'OH: ' + chart.overhead + '%'"></div>
                    <div class="bg-emerald-500 transition-all duration-300 flex items-center justify-center" :style="'width: ' + costs.targetMargin + '%'" x-show="costs.targetMargin > 5" x-text="'Profit: ' + costs.targetMargin + '%'"></div>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 pt-2 text-[10px] text-slate-500 font-bold uppercase tracking-wide border-t border-slate-50">
                    <div class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded bg-red-500 block"></span> Materials</div>
                    <div class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded bg-blue-500 block"></span> Direct Labor</div>
                    <div class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded bg-amber-500 block"></span> Company Overhead</div>
                    <div class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded bg-emerald-500 block"></span> Net Profit</div>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('profitCalculator', () => ({
        costs: {
            materials: 450,
            laborHours: 12,
            laborRate: 30,
            overhead: 15,
            targetMargin: 25
        },
        results: {
            sellingPriceFormatted: '$0.00',
            breakEvenFormatted: '$0.00',
            netProfitFormatted: '$0.00',
            markupPercent: 0
        },
        chart: {
            materials: 0,
            labor: 0,
            overhead: 0
        },

        init() {
            this.calculate();
        },

        formatCurrency(val) {
            return new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(val);
        },

        calculate() {
            const matCost = parseFloat(this.costs.materials) || 0;
            const laborCost = (parseFloat(this.costs.laborHours) || 0) * (parseFloat(this.costs.laborRate) || 0);
            const hardCosts = matCost + laborCost;

            // Mathematical Equation to calculate Required Selling Price based on Margin constraints:
            // Price = Hard Costs / (1 - (Overhead% + TargetMargin%))
            const divisor = 1 - ((this.costs.overhead / 100) + (this.costs.targetMargin / 100));
            
            let sellingPrice = 0;
            if (divisor > 0) {
                sellingPrice = hardCosts / divisor;
            } else {
                sellingPrice = hardCosts / 0.1; // Safety threshold to prevent infinite mathematical loops
            }

            const overheadCost = sellingPrice * (this.costs.overhead / 100);
            const breakEvenPrice = hardCosts + overheadCost;
            const netProfitDollars = sellingPrice * (this.costs.targetMargin / 100);

            // Calculate Required Markup percentage relative to hard deployment production baselines
            let markup = 0;
            if (hardCosts > 0) {
                markup = ((sellingPrice - hardCosts) / hardCosts) * 100;
            }

            // Bind values cleanly to view metrics
            this.results.sellingPriceFormatted = this.formatCurrency(sellingPrice);
            this.results.breakEvenFormatted = this.formatCurrency(breakEvenPrice);
            this.results.netProfitFormatted = this.formatCurrency(netProfitDollars);
            this.results.markupPercent = Math.round(markup);

            // Compile chart proportions safely
            if (sellingPrice > 0) {
                this.chart.materials = Math.round((matCost / sellingPrice) * 100);
                this.chart.labor = Math.round((laborCost / sellingPrice) * 100);
                this.chart.overhead = Math.round((overheadCost / sellingPrice) * 100);
            } else {
                this.chart.materials = 0;
                this.chart.labor = 0;
                this.chart.overhead = 0;
            }
        }
    }));
});
</script>
@endsection