<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gateway Core | Contractor Specialties Secure Access</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-[#F0F0F0] text-[#3C3C3C] antialiased flex flex-col justify-between min-h-screen">

    <div class="flex-grow flex items-center justify-center px-4 sm:px-6 lg:px-8 py-12">
        <div class="max-w-md w-full space-y-8 bg-[#FFFFFF] p-8 sm:p-10 rounded-[2.5rem] border-4 border-slate-900 shadow-2xl relative overflow-hidden">
            
            <div class="absolute top-0 inset-x-0 h-2 bg-gradient-to-r from-[#1E3C5A] via-[#0F2D5A] to-[#FFD22D]"></div>

            {{-- BRAND IDENTITY CONTAINER --}}
            <div class="text-center space-y-2">
                <a href="/" class="inline-block transition active:scale-95">
                    <img src="{{ asset('images/CS-Square.webp') }}" alt="Contractor Specialties" class="h-20 w-20 mx-auto object-cover rounded-2xl shadow-md border border-[#F0F0F0]">
                </a>
                <h2 class="text-2xl font-black text-[#0F2D5A] tracking-tight uppercase pt-2">
                    Gateway <span class="text-[#FFC32D]">Core</span>
                </h2>
                <p class="text-xs font-black text-slate-400 uppercase tracking-widest">Frictionless Security Protocol Active</p>
            </div>

            {{-- RESPONSE NOTIFICATION STATUS BARS --}}
            @if (session('status'))
                <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-r-xl">
                    <p class="text-xs font-bold text-emerald-800 leading-relaxed">{{ session('status') }}</p>
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r-xl">
                    <p class="text-xs font-bold text-red-800 leading-relaxed">{{ $errors->first() }}</p>
                </div>
            @endif

            {{-- INTAKE WORKSPACE ROUTE FORM --}}
            <form action="{{ route('login.send') }}" method="POST" class="mt-8 space-y-6">
                @csrf
                <div>
                    <label for="email" class="block text-xs font-black text-[#3C3C4B] uppercase tracking-widest mb-2">Registered Communications Line</label>
                    <input id="email" name="email" type="email" autocomplete="email" required 
                           placeholder="name@company.com" 
                           class="w-full bg-[#F0F0F0] border-2 border-slate-200 rounded-xl text-[#3C3C3C] placeholder-slate-400 font-bold py-3.5 px-4 focus:border-[#1E3C5A] focus:bg-[#FFFFFF] focus:ring-0 focus:outline-none transition text-base">
                </div>

                <div>
                    <button type="submit" class="w-full bg-[#0F2D5A] hover:bg-[#1E3C5A] text-[#FFFFFF] font-black text-sm uppercase tracking-wider py-4 rounded-xl shadow-lg transition transform active:scale-95 border border-[#0F2D5A]">
                        Transmit Magic Entry Link &rarr;
                    </button>
                </div>
            </form>

            <div class="pt-4 border-t border-[#F0F0F0] text-center">
                <a href="/" class="text-xs font-black text-slate-400 hover:text-[#0F2D5A] uppercase tracking-widest transition">
                    &larr; Abort Protocol & Return Home
                </a>
            </div>

        </div>
    </div>

    {{-- FOOTER LEGAL --}}
    <footer class="py-6 text-center text-[10px] font-black text-slate-400 uppercase tracking-widest bg-[#FFFFFF] border-t border-[#F0F0F0]">
        &copy; {{ date('Y') }} Contractor Specialties. Protected Workspace Hub.
    </footer>

</body>
</html>