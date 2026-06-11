@extends('layouts.app')

@section('title', 'Media Markup Studio')

@section('content')
<div class="space-y-6" x-data="canvasStudio()">
    
    {{-- STUDIO SUB-HEADER --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-slate-900 text-white p-6 rounded-2xl border border-slate-800 shadow-xl text-left">
        <div>
            <span class="bg-[#FFC32D] text-slate-950 text-[9px] font-black uppercase tracking-widest px-2.5 py-1 rounded-md">Precision Mode</span>
            <h1 class="text-xl font-black tracking-tight mt-2">Media Markup Studio</h1>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mt-0.5">Annotating Proposal: <span class="text-[#FFC32D]">#EST-00{{ $estimate->id }}</span> for {{ $estimate->client_name }}</p>
        </div>
        <div class="flex gap-2 w-full sm:w-auto">
            <a href="{{ route('dashboard.estimates') }}" class="w-1/2 sm:w-auto bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-black uppercase tracking-wider px-5 py-3.5 rounded-xl text-center transition">
                Cancel
            </a>
            <button @click="saveFlattenedImage()" ::disabled="!imageLoaded" :class="!imageLoaded ? 'opacity-40 cursor-not-allowed bg-slate-700' : 'bg-emerald-600 hover:bg-emerald-500'" class="w-1/2 sm:w-auto text-white text-xs font-black uppercase tracking-wider px-6 py-3.5 rounded-xl text-center shadow-md transition transform active:scale-95 whitespace-nowrap">
                Save & Attach Asset →
            </button>
        </div>
    </div>

    {{-- MAIN EDITING ENGINE MATRICES --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        
        {{-- LEFT COLUMN: CONTROL PANEL TOOLBAR --}}
        <div class="lg:col-span-3 bg-slate-900 border border-slate-800 rounded-[2rem] p-5 text-left text-white space-y-6 shadow-xl">
            
            {{-- 0. Image Source Selector --}}
            <div class="space-y-2.5">
                <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest block">Select or Snap Photo</span>
                <label class="w-full bg-slate-800 hover:bg-slate-700 border border-slate-700 hover:border-slate-600 text-slate-300 rounded-xl p-3 text-xs font-black uppercase tracking-wider block text-center cursor-pointer transition">
                    📸 Choose Site Image
                    <input type="file" accept="image/*" @change="loadImageFromFile($event)" class="hidden">
                </label>
            </div>

            {{-- 1. Tool Selection Block --}}
            <div class="space-y-2.5">
                <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest block">1. Selected Tool</span>
                <div class="grid grid-cols-2 gap-2 text-xs font-black uppercase tracking-wider">
                    <button @click="tool = 'brush'" :class="tool === 'brush' ? 'bg-[#FFC32D] text-slate-950' : 'bg-slate-800 text-slate-300 hover:bg-slate-700'" class="p-3 rounded-xl flex items-center justify-center gap-2 transition">
                        <span>专️</span> Brush
                    </button>
                    <button @click="tool = 'line'" :class="tool === 'line' ? 'bg-[#FFC32D] text-slate-950' : 'bg-slate-800 text-slate-300 hover:bg-slate-700'" class="p-3 rounded-xl flex items-center justify-center gap-2 transition">
                        <span>📏</span> Line
                    </button>
                    <button @click="tool = 'rect'" :class="tool === 'rect' ? 'bg-[#FFC32D] text-slate-950' : 'bg-slate-800 text-slate-300 hover:bg-slate-700'" class="p-3 rounded-xl flex items-center justify-center gap-2 transition">
                        <span>⬜</span> Box
                    </button>
                    <button @click="tool = 'circle'" :class="tool === 'circle' ? 'bg-[#FFC32D] text-slate-950' : 'bg-slate-800 text-slate-300 hover:bg-slate-700'" class="p-3 rounded-xl flex items-center justify-center gap-2 transition">
                        <span>⭕</span> Circle
                    </button>
                </div>
            </div>

            {{-- 2. Line Weight Thickness Slider Component --}}
            <div class="space-y-2.5">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest">2. Brush Thickness</span>
                    <span class="text-xs font-mono font-bold text-[#FFC32D]" x-text="strokeSize + 'px'">4px</span>
                </div>
                <input type="range" min="2" max="20" step="1" x-model="strokeSize" class="w-full accent-[#FFC32D] bg-slate-800 h-2 rounded-lg cursor-pointer">
            </div>

            {{-- 3. Dynamic Color Matrix Swatches --}}
            <div class="space-y-2.5">
                <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest block">3. High-Vis Color</span>
                <div class="grid grid-cols-4 gap-2">
                    <template x-for="color in colors">
                        <button @click="strokeColor = color" 
                                :style="{ backgroundColor: color }" 
                                :class="strokeColor === color ? 'ring-4 ring-white scale-105' : 'hover:scale-105'" 
                                class="h-10 rounded-xl transition transform duration-100 relative">
                        </button>
                    </template>
                </div>
            </div>

            {{-- 4. History Cleansing Controls --}}
            <div class="pt-4 border-t border-slate-800">
                <button @click="clearCanvas()" class="w-full bg-rose-950/40 hover:bg-rose-900/60 border border-rose-900/50 text-rose-300 text-[10px] font-black uppercase tracking-wider py-3 rounded-xl transition text-center">
                    Reset Canvas
                </button>
            </div>
        </div>

        {{-- RIGHT COLUMN: VISUAL ENGINE WORKSPACE SHEET --}}
        <div class="lg:col-span-9 bg-slate-950 border border-slate-800 rounded-[2.5rem] p-4 sm:p-6 shadow-xl flex items-center justify-center overflow-auto min-h-[500px] relative">
            
            <canvas id="studioCanvas"
                    @pointerdown="startDrawing($event)"
                    @pointermove="draw($event)"
                    @pointerup="stopDrawing()"
                    @pointerleave="stopDrawing()"
                    class="shadow-2xl rounded-2xl max-w-full h-auto cursor-crosshair touch-none"
                    x-show="imageLoaded">
            </canvas>

            {{-- Empty Placeholder Prompt --}}
            <div class="text-center text-slate-500 space-y-2 max-w-sm" x-show="!imageLoaded">
                <span class="text-4xl block">📷</span>
                <h4 class="text-sm font-black text-slate-400 uppercase tracking-wider">No Image Loaded Yet</h4>
                <p class="text-xs font-bold text-slate-600">Tap "Choose Site Image" on the sidebar panel to snap or upload a field photo to configure the workspace.</p>
            </div>

        </div>
    </div>

    {{-- ASSET PAYLOAD SHIPPING FRAME --}}
    <form id="markupForm" action="{{ route('estimates.markup.store', $estimate->id) }}" method="POST" class="hidden">
        @csrf
        <input type="hidden" id="payload_base64" name="markup_image">
    </form>

</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('canvasStudio', () => ({
        canvas: null,
        ctx: null,
        tool: 'brush',
        strokeColor: '#FFC32D',
        strokeSize: 4,
        isDrawing: false,
        startX: 0,
        startY: 0,
        snapshot: null,
        imageLoaded: false,
        activeImageSource: null,
        colors: ['#FFC32D', '#FF3B30', '#34C759', '#007AFF', '#AF52DE', '#FFFFFF', '#000000'],
        
        init() {
            this.canvas = document.getElementById('studioCanvas');
            this.ctx = this.canvas.getContext('2d');
        },

        loadImageFromFile(e) {
            const file = e.target.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = (event) => {
                const img = new Image();
                img.onload = () => {
                    this.activeImageSource = img;
                    this.canvas.width = img.naturalWidth || 800;
                    this.canvas.height = img.naturalHeight || 600;
                    this.ctx.drawImage(img, 0, 0, this.canvas.width, this.canvas.height);
                    this.imageLoaded = true;
                };
                img.src = event.target.result;
            };
            reader.readAsDataURL(file);
        },

        startDrawing(e) {
            if(!this.imageLoaded) return;
            this.isDrawing = true;
            
            const rect = this.canvas.getBoundingClientRect();
            this.startX = (e.clientX - rect.left) * (this.canvas.width / rect.width);
            this.startY = (e.clientY - rect.top) * (this.canvas.height / rect.height);
            
            this.ctx.strokeStyle = this.strokeColor;
            this.ctx.lineWidth = this.strokeSize;
            this.ctx.lineCap = 'round';
            this.ctx.lineJoin = 'round';
            
            if (this.tool === 'brush') {
                this.ctx.beginPath();
                this.ctx.moveTo(this.startX, this.startY);
            } else {
                this.snapshot = this.ctx.getImageData(0, 0, this.canvas.width, this.canvas.height);
            }
        },

        draw(e) {
            if (!this.isDrawing || !this.imageLoaded) return;
            
            const rect = this.canvas.getBoundingClientRect();
            const currentX = (e.clientX - rect.left) * (this.canvas.width / rect.width);
            const currentY = (e.clientY - rect.top) * (this.canvas.height / rect.height);
            
            if (this.tool === 'brush') {
                this.ctx.lineTo(currentX, currentY);
                this.ctx.stroke();
            } else {
                this.ctx.putImageData(this.snapshot, 0, 0);
                this.ctx.beginPath();
                
                if (this.tool === 'line') {
                    this.ctx.moveTo(this.startX, this.startY);
                    this.ctx.lineTo(currentX, currentY);
                } 
                else if (this.tool === 'rect') {
                    this.ctx.strokeRect(this.startX, this.startY, currentX - this.startX, currentY - this.startY);
                } 
                else if (this.tool === 'circle') {
                    let radius = Math.sqrt(Math.pow(this.startX - currentX, 2) + Math.pow(this.startY - currentY, 2));
                    this.ctx.arc(this.startX, this.startY, radius, 0, 2 * Math.PI);
                }
                
                this.ctx.stroke();
            }
        },

        stopDrawing() {
            this.isDrawing = false;
        },

        clearCanvas() {
            if(confirm('Reset all layout marks and edits on this image?')) {
                if (this.activeImageSource) {
                    this.ctx.drawImage(this.activeImageSource, 0, 0, this.canvas.width, this.canvas.height);
                }
            }
        },

        saveFlattenedImage() {
            if(!this.imageLoaded) return;
            
            const dataUrl = this.canvas.toDataURL('image/webp', 0.85);
            document.getElementById('payload_base64').value = dataUrl;
            document.getElementById('markupForm').submit();
        }
    }));
});
</script>
@endsection