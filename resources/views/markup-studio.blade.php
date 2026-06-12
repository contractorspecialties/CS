@extends('layouts.app')

@section('title', 'Media Markup Studio')

@section('content')
<div class="space-y-6 w-full min-w-0 max-w-full overflow-hidden" 
     x-data="canvasStudio()"
     @resize.window.debounce.150ms="handleViewportRotation()">
    
    {{-- SYSTEM FEEDBACK & ERROR DIAGNOSTIC PANEL --}}
    @if ($errors->any())
        <div class="bg-red-900 border-l-8 border-red-500 p-5 rounded-2xl text-left shadow-lg">
            <h4 class="text-sm font-black text-white uppercase tracking-wider">Server Upload Refusal Diagnostic</h4>
            <ul class="mt-2 text-xs font-bold text-red-200 list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- STUDIO SUB-HEADER --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-slate-900 text-white p-6 rounded-2xl border border-slate-800 shadow-xl text-left w-full min-w-0">
        <div>
            <span class="bg-[#FFC32D] text-slate-950 text-[9px] font-black uppercase tracking-widest px-2.5 py-1 rounded-md">Precision Mode</span>
            <h1 class="text-xl font-black tracking-tight mt-2">Media Markup Studio</h1>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mt-0.5">Annotating Proposal: <span class="text-[#FFC32D]">#EST-00{{ $estimate->id }}</span> for {{ $estimate->client_name }}</p>
        </div>
        <div class="flex gap-2 w-full sm:w-auto">
            <a href="{{ route('dashboard.estimates') }}" class="w-1/2 sm:w-auto bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-black uppercase tracking-wider px-5 py-3.5 rounded-xl text-center transition">
                Back to Log
            </a>
            <button @click="saveFlattenedImage()" 
                    :disabled="!imageLoaded || isSaving" 
                    :class="(!imageLoaded || isSaving) ? 'opacity-40 cursor-not-allowed bg-slate-700' : 'bg-emerald-600 hover:bg-emerald-500'" 
                    class="w-1/2 sm:w-auto text-white text-xs font-black uppercase tracking-wider px-6 py-3.5 rounded-xl text-center shadow-md transition transform active:scale-95 whitespace-nowrap">
                <span x-text="isSaving ? 'Uploading...' : 'Save as New Asset →'">Save as New Asset →</span>
            </button>
        </div>
    </div>

    {{-- MAIN EDITING ENGINE MATRICES --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start w-full min-w-0">
        
        {{-- LEFT COLUMN: CONTROL PANEL TOOLBAR --}}
        <div class="lg:col-span-3 bg-slate-900 border border-slate-800 rounded-[2rem] p-5 text-left text-white space-y-6 shadow-xl w-full min-w-0">
            
            {{-- 0. Capture New Photo Channel --}}
            <div class="space-y-2.5">
                <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest block">0. Capture or Import Photo</span>
                <div class="grid grid-cols-2 gap-2 text-xs font-black uppercase tracking-wider">
                    <div class="relative overflow-hidden bg-slate-800 hover:bg-slate-750 border border-slate-700 text-slate-200 rounded-xl p-3 text-center transition cursor-pointer shadow-sm">
                        📸 Live Lens
                        <input type="file" accept="image/*" capture="environment" @change="loadImageFromFile($event)" class="opacity-0 absolute inset-0 w-full h-full cursor-pointer z-20">
                    </div>
                    <div class="relative overflow-hidden bg-slate-800 hover:bg-slate-750 border border-slate-700 text-slate-200 rounded-xl p-3 text-center transition cursor-pointer shadow-sm">
                        📁 Gallery
                        <input type="file" accept="image/*" @change="loadImageFromFile($event)" class="opacity-0 absolute inset-0 w-full h-full cursor-pointer z-20">
                    </div>
                </div>
            </div>

            {{-- 1. Tool Selection Block (Added Text Tool Injection) --}}
            <div class="space-y-2.5">
                <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest block">1. Selected Tool</span>
                <div class="grid grid-cols-2 gap-2 text-xs font-black uppercase tracking-wider">
                    <button @click="tool = 'brush'" :class="tool === 'brush' ? 'bg-[#FFC32D] text-slate-950' : 'bg-slate-800 text-slate-300 hover:bg-slate-700'" class="p-3 rounded-xl flex items-center justify-center gap-2 transition">
                        <span>🖌️</span> Brush
                    </button>
                    <button @click="tool = 'text'" :class="tool === 'text' ? 'bg-[#FFC32D] text-slate-950' : 'bg-slate-800 text-slate-300 hover:bg-slate-700'" class="p-3 rounded-xl flex items-center justify-center gap-2 transition">
                        <span>🔤</span> Text
                    </button>
                    <button @click="tool = 'line'" :class="tool === 'line' ? 'bg-[#FFC32D] text-slate-950' : 'bg-slate-800 text-slate-300 hover:bg-slate-700'" class="p-3 rounded-xl flex items-center justify-center gap-2 transition">
                        <span>📏</span> Line
                    </button>
                    <button @click="tool = 'rect'" :class="tool === 'rect' ? 'bg-[#FFC32D] text-slate-950' : 'bg-slate-800 text-slate-300 hover:bg-slate-700'" class="p-3 rounded-xl flex items-center justify-center gap-2 transition">
                        <span>⬜</span> Box
                    </button>
                </div>
            </div>

            {{-- 2. Line Weight & Font Thickness Slider Component --}}
            <div class="space-y-2.5">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest" x-text="tool === 'text' ? '2. Font Sizing' : '2. Brush Thickness'">2. Brush Thickness</span>
                    <span class="text-xs font-mono font-bold text-[#FFC32D]" x-text="strokeSize + 'px'">4px</span>
                </div>
                <input type="range" min="2" max="25" step="1" x-model="strokeSize" class="w-full accent-[#FFC32D] bg-slate-800 h-2 rounded-lg cursor-pointer">
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

            {{-- 4. Dynamic Customer Proposal Visibility Toggle Control Box --}}
            <div class="space-y-2.5">
                <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest block">4. Client Link Access</span>
                <label class="flex items-center gap-3 bg-slate-800 hover:bg-slate-750 p-3.5 rounded-xl cursor-pointer select-none transition border border-slate-700/60">
                    <input type="checkbox" x-model="isPublic" class="rounded text-[#0F2D5A] border-slate-600 focus:ring-0 focus:ring-offset-0 bg-slate-900 accent-[#FFC32D] h-4 w-4">
                    <span class="text-xs font-bold text-slate-200">Show on Homeowner Portal</span>
                </label>
            </div>

            {{-- 5. History Cleansing Controls --}}
            <div class="pt-4 border-t border-slate-800">
                <button @click="clearCanvas()" class="w-full bg-rose-950/40 hover:bg-rose-900/60 border border-rose-900/50 text-rose-300 text-[10px] font-black uppercase tracking-wider py-3 rounded-xl transition text-center">
                    Reset Canvas
                </button>
            </div>
        </div>

        {{-- RIGHT COLUMN: WORKSPACE CONTAINER SHEET WITH HISTORIC CAROUSEL BAR --}}
        <div class="lg:col-span-9 space-y-4 w-full min-w-0">
            
            {{-- Interactive Multi-Image Proposal History Navigation Strip --}}
            @if($estimate->attachments->count() > 0)
                <div class="bg-slate-900 p-3 rounded-2xl border border-slate-800 text-left space-y-2">
                    <span class="text-[9px] font-black text-slate-500 uppercase tracking-widest block px-1">Estimate Proposal Photo Deck (Select Image to Edit)</span>
                    <div class="flex items-center gap-3 overflow-x-auto pb-1 scrollbar-thin">
                        @foreach($estimate->attachments as $idx => $attachment)
                            <button @click="switchActiveImage('{{ asset('storage/' . $attachment->file_path) }}')" 
                                    class="w-16 h-16 rounded-xl overflow-hidden border-2 bg-slate-950 flex-shrink-0 transition-all duration-200"
                                    :class="activePathSource === '{{ asset('storage/' . $attachment->file_path) }}' ? 'border-[#FFC32D] scale-105 shadow-md' : 'border-slate-700 hover:border-slate-500 opacity-60 hover:opacity-100'">
                                <img src="{{ asset('storage/' . $attachment->file_path) }}" alt="Proposal asset preview square" class="w-full h-full object-cover">
                            </button>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="bg-slate-950 border border-slate-800 rounded-[2.5rem] p-3 sm:p-6 shadow-xl flex items-center justify-center overflow-hidden h-[55vh] max-h-[60vh] relative w-full min-w-0">
                <canvas id="studioCanvas"
                        @pointerdown="startDrawing($event)"
                        @pointermove="draw($event)"
                        @pointerup="stopDrawing()"
                        @pointerleave="stopDrawing()"
                        class="shadow-2xl rounded-2xl max-w-full max-h-full h-auto object-contain cursor-crosshair touch-none select-none block"
                        x-show="imageLoaded">
                </canvas>

                {{-- Empty Placeholder Prompt --}}
                <div class="text-center text-slate-500 space-y-2 max-w-sm px-4" x-show="!imageLoaded">
                    <span class="text-4xl block">📷</span>
                    <h4 class="text-sm font-black text-slate-400 uppercase tracking-wider">No Image Selected</h4>
                    <p class="text-xs font-bold text-slate-600">Select an existing photo from the top deck array, use "Live Lens" to capture a new view, or click "Gallery" to import.</p>
                </div>
            </div>
        </div>
    </div>
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
        isPublic: true,
        isSaving: false,
        startX: 0,
        startY: 0,
        snapshot: null,
        imageLoaded: false,
        activeImageSource: null,
        activePathSource: '',
        colors: ['#FFC32D', '#FF3B30', '#34C759', '#007AFF', '#AF52DE', '#FFFFFF', '#000000'],
        
        init() {
            this.canvas = document.getElementById('studioCanvas');
            this.ctx = this.canvas.getContext('2d');
            
            @if($estimate->attachments->count() > 0)
                this.switchActiveImage('{{ asset('storage/' . $estimate->attachments->last()->file_path) }}');
            @endif
        },

        switchActiveImage(urlPath) {
            if (!urlPath) return;
            this.activePathSource = urlPath;
            const img = new Image();
            img.onload = () => {
                this.activeImageSource = img;
                this.processAndRenderImage(img);
            };
            img.src = urlPath;
        },

        loadImageFromFile(e) {
            const file = e.target.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = (event) => {
                const img = new Image();
                img.onload = () => {
                    this.activeImageSource = img;
                    this.activePathSource = ''; // Clears sync highlight for fresh additions
                    this.processAndRenderImage(img);
                };
                img.src = event.target.result;
            };
            reader.readAsDataURL(file);
        },

        processAndRenderImage(img) {
            let maxWidth = 1200;
            let maxHeight = 1200;
            let width = img.naturalWidth || 800;
            let height = img.naturalHeight || 600;

            if (width > maxWidth || height > maxHeight) {
                if (width > height) {
                    height = Math.round((height * maxWidth) / width);
                    width = maxWidth;
                } else {
                    width = Math.round((width * maxHeight) / height);
                    height = maxHeight;
                }
            }

            this.canvas.width = width;
            this.canvas.height = height;
            this.ctx.drawImage(img, 0, 0, width, height);
            this.imageLoaded = true;
        },

        handleViewportRotation() {
            if (!this.imageLoaded || !this.activeImageSource) return;
            
            const cacheCanvas = document.createElement('canvas');
            cacheCanvas.width = this.canvas.width;
            cacheCanvas.height = this.canvas.height;
            const cacheCtx = cacheCanvas.getContext('2d');
            cacheCtx.drawImage(this.canvas, 0, 0);
            
            this.canvas.style.width = '100%';
            this.ctx.putImageData(cacheCtx.getImageData(0, 0, cacheCanvas.width, cacheCanvas.height), 0, 0);
        },

        startDrawing(e) {
            if(!this.imageLoaded || this.isSaving) return;
            
            const rect = this.canvas.getBoundingClientRect();
            this.startX = (e.clientX - rect.left) * (this.canvas.width / rect.width);
            this.startY = (e.clientY - rect.top) * (this.canvas.height / rect.height);
            
            this.ctx.strokeStyle = this.strokeColor;
            this.ctx.fillStyle = this.strokeColor;
            this.ctx.lineWidth = this.strokeSize;
            this.ctx.lineCap = 'round';
            this.ctx.lineJoin = 'round';
            
            // Integrated Canvas Text Tool Intervention Matrix
            if (this.tool === 'text') {
                const inputContent = prompt('Enter your annotation text:');
                if (inputContent && inputContent.trim() !== '') {
                    // Scales typography size bounding rules cleanly matching slider px models
                    const computedFontSize = Math.round(parseInt(this.strokeSize) * 2.5) + 12;
                    this.ctx.font = 'black ' + computedFontSize + 'px Plus Jakarta Sans, Arial, sans-serif';
                    this.ctx.fillText(inputContent, this.startX, this.startY);
                }
                return;
            }

            this.isDrawing = true;
            if (this.tool === 'brush') {
                this.ctx.beginPath();
                this.ctx.moveTo(this.startX, this.startY);
            } else {
                this.snapshot = this.ctx.getImageData(0, 0, this.canvas.width, this.canvas.height);
            }
        },

        draw(e) {
            if (!this.isDrawing || !this.imageLoaded || this.isSaving || this.tool === 'text') return;
            
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
                    this.processAndRenderImage(this.activeImageSource);
                }
            }
        },

        saveFlattenedImage() {
            if(!this.imageLoaded || this.isSaving) return;
            this.isSaving = true;
            
            try {
                this.canvas.toBlob((blob) => {
                    if (!blob) {
                        alert('Canvas image generation failed.');
                        this.isSaving = false;
                        return;
                    }
                    
                    const formData = new FormData();
                    formData.append('_token', '{{ csrf_token() }}');
                    formData.append('markup_image', blob, 'markup.jpg');
                    formData.append('is_public', this.isPublic ? '1' : '0');
                    
                    fetch('{{ route('estimates.markup.store', $estimate->id) }}', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Server upload failure status code.');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.redirect) {
                            window.location.href = data.redirect;
                        } else {
                            alert('Upload caught but redirection route missing.');
                            this.isSaving = false;
                        }
                    })
                    .catch(error => {
                        console.error(error);
                        alert('Upload failed. Ensure server upload limits match and try again.');
                        this.isSaving = false;
                    });
                }, 'image/jpeg', 0.85);
            } catch(error) {
                console.error(error);
                this.isSaving = false;
            }
        }
    }));
});
</script>
@endsection