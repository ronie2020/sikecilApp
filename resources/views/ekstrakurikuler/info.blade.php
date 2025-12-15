<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Info Ekstrakurikuler - SiKecil</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .blob {
            position: absolute;
            filter: blur(40px);
            z-index: -1;
            opacity: 0.4;
            animation: float 10s infinite ease-in-out;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-20px); }
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 overflow-x-hidden">

    <!-- NAVBAR -->
    <nav class="fixed w-full z-50 bg-white/80 backdrop-blur-md border-b border-slate-100 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <a href="{{ route('home') }}" class="flex items-center gap-2 group">
                    <div class="bg-gradient-to-br from-emerald-500 to-teal-600 p-2 rounded-xl shadow-lg shadow-emerald-200 group-hover:scale-105 transition-transform">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    </div>
                    <span class="text-xl font-bold tracking-tight text-slate-900">SiKecil</span>
                </a>
                <div class="flex items-center gap-4">
                    @auth
                        <a href="{{ route('dashboard') }}" class="text-slate-600 font-bold hover:text-emerald-600 text-sm mr-4">Kembali ke Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="bg-emerald-600 text-white px-5 py-2.5 rounded-full text-sm font-bold shadow-lg hover:bg-emerald-700 transition-all">Masuk</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- HERO HEADER -->
    <header class="relative pt-32 pb-20 lg:pt-40 lg:pb-24 overflow-hidden bg-slate-900">
        <div class="absolute inset-0 bg-gradient-to-b from-slate-900/0 to-slate-900"></div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
            <span class="inline-block py-1 px-3 rounded-full bg-emerald-500/20 border border-emerald-500/50 text-emerald-300 text-xs font-bold tracking-wide uppercase mb-4" data-aos="fade-down">
                🚀 Kembangkan Bakatmu
            </span>
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-white tracking-tight mb-6" data-aos="fade-up">
                Temukan Minat & Bakat <br> di Luar Kelas
            </h1>
            <p class="text-lg text-slate-300 max-w-2xl mx-auto mb-10" data-aos="fade-up" data-aos-delay="100">
                Informasi lengkap mengenai jadwal, pembina, dan kegiatan ekstrakurikuler yang tersedia di sekolah kami.
            </p>
        </div>
    </header>

    <!-- MAIN CONTENT -->
    <section class="py-16 bg-slate-50 relative" x-data="{ filter: 'all' }">
        <div class="blob bg-emerald-200 w-96 h-96 rounded-full top-20 left-0 -translate-x-1/2"></div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            
            <!-- Filter Buttons -->
            <div class="flex flex-wrap justify-center gap-3 mb-12" data-aos="fade-up">
                <button @click="filter = 'all'" 
                        :class="filter === 'all' ? 'bg-emerald-600 text-white shadow-emerald-200' : 'bg-white text-slate-600 hover:bg-slate-50'"
                        class="px-6 py-2.5 rounded-full font-bold text-sm shadow-md transition-all transform hover:-translate-y-0.5">
                    Semua
                </button>
                <button @click="filter = 'olahraga'" 
                        :class="filter === 'olahraga' ? 'bg-emerald-600 text-white shadow-emerald-200' : 'bg-white text-slate-600 hover:bg-slate-50'"
                        class="px-6 py-2.5 rounded-full font-bold text-sm shadow-md transition-all transform hover:-translate-y-0.5">
                    ⚽ Olahraga
                </button>
                <button @click="filter = 'seni'" 
                        :class="filter === 'seni' ? 'bg-emerald-600 text-white shadow-emerald-200' : 'bg-white text-slate-600 hover:bg-slate-50'"
                        class="px-6 py-2.5 rounded-full font-bold text-sm shadow-md transition-all transform hover:-translate-y-0.5">
                    🎨 Seni & Kreatif
                </button>
                <button @click="filter = 'akademik'" 
                        :class="filter === 'akademik' ? 'bg-emerald-600 text-white shadow-emerald-200' : 'bg-white text-slate-600 hover:bg-slate-50'"
                        class="px-6 py-2.5 rounded-full font-bold text-sm shadow-md transition-all transform hover:-translate-y-0.5">
                    📚 Akademik & Teknologi
                </button>
            </div>

            <!-- Grid Ekskul Dinamis -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                
                @forelse($ekskuls as $item)
                    <!-- Item Card -->
                    <div x-show="filter === 'all' || filter === '{{ strtolower($item->kategori) }}'" 
                         class="bg-white rounded-3xl overflow-hidden shadow-sm border border-slate-100 hover:shadow-xl transition-all duration-300 group" 
                         data-aos="fade-up" x-transition.scale.origin.center>
                        
                        <div class="h-48 overflow-hidden relative bg-slate-200">
                            @if($item->foto)
                                <img src="{{ asset('storage/'.$item->foto) }}" alt="{{ $item->nama }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-slate-400">
                                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                            @endif
                            
                            <div class="absolute top-4 right-4 bg-white/90 backdrop-blur px-3 py-1 rounded-lg text-xs font-bold uppercase shadow-sm
                                {{ $item->kategori == 'olahraga' ? 'text-emerald-600' : ($item->kategori == 'seni' ? 'text-rose-500' : 'text-blue-600') }}">
                                {{ $item->kategori }}
                            </div>
                        </div>

                        <div class="p-6">
                            <h3 class="text-xl font-bold text-slate-900 mb-2">{{ $item->nama }}</h3>
                            <p class="text-slate-500 text-sm mb-4 line-clamp-2">{{ Str::limit($item->deskripsi, 80) }}</p>
                            
                            <div class="flex items-center gap-3 text-xs text-slate-500 mb-4 bg-slate-50 p-3 rounded-xl border border-slate-100">
                                <div class="flex items-center gap-1">
                                    <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    <span class="font-semibold">{{ $item->jadwal }}</span>
                                </div>
                                <div class="w-px h-4 bg-slate-300"></div>
                                <div class="flex items-center gap-1">
                                    <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                    <span class="font-semibold">{{ $item->pembina }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12">
                        <p class="text-slate-500 text-lg">Belum ada data kegiatan ekstrakurikuler.</p>
                    </div>
                @endforelse

            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="bg-slate-900 text-slate-300 py-12 border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p class="text-sm">&copy; {{ date('Y') }} SiKecil. Membangun Generasi Hebat.</p>
        </div>
    </footer>

    <script>
        AOS.init({ once: true, offset: 50, duration: 800 });
    </script>
</body>
</html>