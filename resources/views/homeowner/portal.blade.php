<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50 scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Project Proposal | Contractor Specialties</title>
    
    {{-- Core Client Presentation Assets --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="h-full text-slate-800 antialiased bg-slate-50" x-data="portalApproval({
    submitUrl: '{{ route('estimates.public.status', $estimate->secure_token) }}',
    csrf: '{{ csrf_token() }}'
})">

    {{-- MAIN CONSUMER SHELL CONTAINER --}}
    <div class="max-w-2xl mx-auto px-4 py-6 sm:py-10 space-y-6 text-left">
        
        {{-- PROPOSAL HERO BRANDING HEADER --}}
        <div class="bg-slate-900 text-white p-6 rounded-[2rem] shadow-xl border border-slate-800 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <span class="bg-[#FFC32D] text-slate-950 text-[9px] font-black uppercase tracking-widest px-2.5 py-1 rounded-md">Official Proposal</span>
                <h1 class="text-xl font-black tracking-tight mt-2">{{ $estimate->project_title }}</h1>
                <p class="text-xs font-bold text-slate-400 mt-0.5">Prepared by: <span class="text-white">{{ $estimate->user->name }}</span></p>
            </div>
            <div class="sm:text-right flex-shrink-0">
                <span class="text-[9px] font-black text-slate-400 uppercase tracking-wider block">Status</span>
                <span class="inline-block mt-1 px-3 py-1 text-xs font-black uppercase rounded-lg border tracking-wide"
                      :class="{
                          'bg-amber-500/10 border-amber-500/30 text-amber-400': status === 'sent',
                          'bg-emerald-500/10 border-emerald-500/30 text-emerald-400': status === 'approved'
                      }"
                      x-text="statusText">
                    {{ strtoupper($estimate->status) }}
                </span>
            </div>
        </div>

        {{-- CLIENT PROFILE INFORMATION BLOCK --}}
        <div class="bg-white border border-slate-200 p-5 rounded-2xl shadow-sm space-y-1">
            <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block">Customer Details</span>
            <h3 class="text-sm font-black text-slate-900">{{ $estimate->client_name }}</h3>
            @if($estimate->client_phone)<p class="text-xs font-bold text-slate-500">📞 {{ $estimate->client_phone }}</p>@endif
            @if($estimate->client_email)<p class="text-xs font-bold text-slate-500">✉️ {{ $estimate->client_email }}</p>@endif
        </div>

        {{-- INTERACTIVE VISUAL SCOPE OF WORK GALLERY --}}
        @if($estimate->attachments->isNotEmpty())
            <div class="bg-white border border-slate-200 p-5 rounded-2xl shadow-sm space-y-3">
                <div>
                    <h3 class="text-sm font-black text-slate-900 tracking-tight">Project Visual Scope</h3>
                    <p class="text-[11px] font-bold text-slate-400 mt-0.5">Review the marked-up photos detailing the execution parameters for your property.</p>
                </div>
                
                {{-- PHOTO ROLL GRID ASSEMBLY --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-1">
                    @foreach($estimate->attachments as $photo)
                        <div class="border border-slate-200 rounded-xl overflow-hidden shadow-sm bg-slate-50 group relative">
                            <img src="{{ asset('storage/' . $photo->file_path) }}" alt="Job parameters markup" class="w-full h-48 object-cover object-center">
                            @if($photo->note)
                                <div class="p-2 bg-slate-900/90 text-white text-[10px] font-bold leading-normal">
                                    {{ $photo->note }}
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- CLEAN ACCOUNTING LINE ITEMS BREAKDOWN TABLE --}}
        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
            <div class="p-5 border-b border-slate-100">
                <h3 class="text-sm font-black text-slate-900 tracking-tight">Project Pricing & Items</h3>
            </div>
            
            <div class="divide-y divide-slate-100 px-5">
                @foreach($estimate->items as $item)
                    <div class="py-3.5 flex items-start justify-between gap-4 text-xs">
                        <div class="min-w-0">
                            <h4 class="font-black text-slate-900 truncate">{{ $item->description }}</h4>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wide mt-0.5">Qty: {{ $item->quantity }} • Type: {{ $item->item_type }}</p>
                        </div>
                        <span class="font-mono font-black text-slate-800 flex-shrink-0">
                            ${{ number_format(($item->total_price_cents) / 100, 2) }}
                        </span>
                    </div>
                @endforeach
            </div>

            {{-- TOTAL CASH SUMMATION TIER --}}
            <div class="bg-slate-900 p-5 text-white flex items-center justify-between">
                <div>
                    <span class="text-[10px] font-black text-[#FFC32D] uppercase tracking-widest block">Total Guaranteed Investment</span>
                    <span class="text-[11px] font-bold text-slate-400 block mt-0.5">All local tax inclusions are calculated below</span>
                </div>
                <div class="text-right">
                    <span class="text-xl font-black font-mono text-[#FFC32D]">
                        ${{ number_format($estimate->total_cents / 100, 2) }}
                    </span>
                </div>
            </div>
        </div>

        {{-- INTERACTIVE ONE-TOUCH APPROVAL PANEL --}}
        <template x-if="status === 'sent'">
            <div class="bg-white border-2 border-slate-900 rounded-[2rem] p-5 sm:p-6 shadow-xl space-y-4">
                <div class="border-b border-slate-100 pb-3">
                    <h3 class="text-sm font-black text-[#0F2D5A] tracking-tight">Authorize Project Agreement</h3>
                    <p class="text-[11px] font-bold text-slate-400 mt-0.5">Review the terms above, apply your digital signature below, and click confirm to seal the agreement.</p>
                </div>

                {{-- FEEDBACK NOTES INPUT --}}
                <div class="space-y-1.5">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Optional Revision Notes / Gate Codes</label>
                    <textarea x-model="formData.customer_notes" rows="2" placeholder="e.g. Leave gate unlocked for the lawn crew, beware of dog..." class="w-full bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold py-2.5 px-3 focus:bg-white focus:border-[#0F2D5A] focus:outline-none transition resize-none"></textarea>
                </div>

                {{-- DIGITAL SIGNATURE BOX MAT --}}
                <div class="space-y-1.5">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Sign with Touchscreen or Mouse</label>
                    <div class="border-2 border-dashed border-slate-300 rounded-xl overflow-hidden bg-slate-50 relative h-36">
                        <canvas id="sigPad" 
                                @mousedown="startDrawing($event)"
                                @mousemove="draw($event)"
                                @mouseup="stopDrawing()"
                                @touchstart="startDrawingMobile($event)"
                                @touchmove="drawMobile($event)"
                                @touchend="stopDrawing()"
                                class="absolute inset-0 w-full h-full cursor-crosshair z-10"></canvas>
                        <div x-show="!hasSigned" class="absolute inset-0 flex items-center justify-center text-slate-300 font-bold text-xs pointer-events-none select-none z-0">
                            ✍️ Sign Here
                        </div>
                        <button type="button" @click="clearPad()" class="absolute bottom-2 right-2 z-20 text-[9px] font-black bg-slate-200 hover:bg-slate-300 text-slate-600 uppercase tracking-wider px-2 py-1 rounded transition">
                            Clear
                        </button>
                    </div>
                </div>

                {{-- FIRE TRIGGER SUBMIT BUTTON --}}
                <button type="button" 
                        @click="submitApproval()"
                        :disabled="submitting || !hasSigned"
                        class="w-full text-white text-xs font-black uppercase tracking-widest py-4 rounded-xl transition text-center block shadow-md shadow-slate-200 select-none transform active:scale-[0.99] disabled:opacity-40 disabled:scale-100 disabled:cursor-not-allowed"
                        :class="hasSigned ? 'bg-[#0F2D5A] hover:bg-[#1E3C5A]' : 'bg-slate-400'">
                    <span x-text="submitting ? 'Processing Authorization...' : 'Authorize & Schedule Work →'"></span>
                </button>
            </div>
        </template>

        {{-- SUCCESS CARD FOOTPRINT IF ALREADY COMPLETE --}}
        <template x-if="status === 'approved'">
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-900 rounded-[2rem] p-6 text-center space-y-2 shadow-sm">
                <span class="text-3xl block">🎉</span>
                <h3 class="text-sm font-black uppercase tracking-wide text-emerald-950">Proposal Authorized Successfully</h3>
                <p class="text-xs font-bold text-emerald-700 max-w-sm mx-auto leading-relaxed">
                    Thank you! Your agreement signature lock has been successfully stamped. The team has been dispatched and scheduled into our live operations grid.
                </p>
            </div>
        </template>

    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('portalApproval', (config) => ({
        submitUrl: config.submitUrl,
        csrf: config.csrf,
        status: '{{ $estimate->status }}',
        hasSigned: false,
        submitting: false,
        canvas: null,
        ctx: null,
        isDrawing: false,
        formData: {
            signature_data: '',
            customer_notes: ''
        },

        init() {
            this.$nextTick(() => {
                this.setupCanvas();
            });
        },

        get statusText() {
            return this.status === 'sent' ? 'Pending Review' : 'Active Approved';
        },

        setupCanvas() {
            this.canvas = document.getElementById('sigPad');
            if (!this.canvas) return;
            
            this.ctx = this.canvas.getContext('2d');
            
            // Handle high-resolution display padding scalings
            const rect = this.canvas.getBoundingClientRect();
            this.canvas.width = rect.width;
            this.canvas.height = rect.height;
            
            this.ctx.strokeStyle = '#0F2D5A';
            this.ctx.lineWidth = 3;
            this.ctx.lineCap = 'round';
        },

        getPos(e) {
            const rect = this.canvas.getBoundingClientRect();
            return { x: e.clientX - rect.left, y: e.clientY - rect.top };
        },

        getTouchPos(e) {
            const rect = this.canvas.getBoundingClientRect();
            return { x: e.touches[0].clientX - rect.left, y: e.touches[0].top - rect.top };
        },

        startDrawing(e) {
            this.isDrawing = true;
            const pos = this.getPos(e);
            this.ctx.beginPath();
            this.ctx.moveTo(pos.x, pos.y);
            this.hasSigned = true;
        },

        draw(e) {
            if (!this.isDrawing) return;
            const pos = this.getPos(e);
            this.ctx.lineTo(pos.x, pos.y);
            this.ctx.stroke();
        },

        startDrawingMobile(e) {
            e.preventDefault();
            this.isDrawing = true;
            const pos = this.getTouchPos(e);
            this.ctx.beginPath();
            this.ctx.moveTo(pos.x, pos.y);
            this.hasSigned = true;
        },

        drawMobile(e) {
            e.preventDefault();
            if (!this.isDrawing) return;
            const pos = this.getTouchPos(e);
            this.ctx.lineTo(pos.x, pos.y);
            this.ctx.stroke();
        },

        stopDrawing() {
            this.isDrawing = false;
        },

        clearPad() {
            this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);
            this.hasSigned = false;
            this.formData.signature_data = '';
        },

        submitApproval() {
            this.submitting = true;
            this.formData.signature_data = this.canvas.toDataURL(); // Extracts signature lines smoothly as a text vector string

            fetch(this.submitUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrf,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify(this.formData)
            })
            .then(response => {
                if (!response.ok) return response.json().then(err => { throw err; });
                return response.json();
            })
            .then(data => {
                this.status = 'approved';
                this.submitting = false;
            })
            .catch(error => {
                console.error(error);
                alert(error.message || 'Signature storage failed due to a network connection timeout.');
                this.submitting = false;
            });
        }
    }));
});
</script>
</body>
</html>