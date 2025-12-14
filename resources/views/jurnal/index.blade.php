<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jurnal Kegiatan - SiKecil</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #F8FAFC; }
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .glass-effect { background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px); }
        .animate-card { animation: fadeInScale 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards; opacity: 0; }
        @keyframes fadeInScale { 0% { opacity: 0; transform: scale(0.95) translateY(10px); } 100% { opacity: 1; transform: scale(1) translateY(0); } }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased min-h-screen" x-data="{ sidebarOpen: false }">

    <div class="flex h-screen overflow-hidden">
        
        <!-- SIDEBAR IMPLEMENTATION -->
        <!-- Memanggil sidebar dari folder resources/views/components -->
        @include('components.sidebar')

        <!-- Mobile Overlay (Untuk menutup sidebar saat diklik di luar pada mobile) -->
        <div x-show="sidebarOpen" 
             @click="sidebarOpen = false"
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-slate-900/50 z-40 md:hidden" x-cloak>
        </div>

        <!-- MAIN CONTENT WRAPPER -->
        <div class="flex-1 flex flex-col h-screen overflow-hidden relative w-full">
            
            <!-- Top Header (Updated) -->
            <header class="sticky top-0 z-30 w-full glass-effect border-b border-slate-200 shadow-sm transition-all duration-300 shrink-0">
                <div class="px-4 sm:px-6 lg:px-8">
                    <div class="flex items-center justify-between h-16">
                        <div class="flex items-center gap-4">
                            
                            <!-- Hamburger Menu Button (Mobile Only) -->
                            <button @click="sidebarOpen = !sidebarOpen" class="md:hidden p-2 -ml-2 text-slate-500 hover:text-emerald-600 rounded-lg hover:bg-slate-100 transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                            </button>

                            <!-- Title Section -->
                            <div class="flex items-center gap-3">
                                <div class="bg-emerald-600 text-white p-2 rounded-lg shadow-emerald-200 shadow-lg hidden sm:block">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                </div>
                                <div>
                                    <h1 class="text-base sm:text-lg font-bold text-slate-900 leading-none">Timeline Jurnal</h1>
                                    <p class="text-[10px] sm:text-xs text-slate-500 font-medium mt-1">Aktivitas Pembelajaran Terbaru</p>
                                </div>
                            </div>
                        </div>

                        <!-- Action Button -->
                        <div>
                            <a href="{{ route('jurnal.create') }}" class="flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-xl text-sm font-semibold transition-all shadow-md hover:shadow-lg hover:-translate-y-0.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                <span class="hidden sm:inline">Buat Jurnal</span>
                            </a>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Scrollable Main Area -->
            <main class="flex-1 overflow-y-auto overflow-x-hidden p-4 sm:p-6 lg:p-8 space-y-8 bg-slate-50">
                
                <!-- Flash Message -->
                @if(session('success'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl flex items-center justify-between shadow-sm max-w-5xl mx-auto w-full">
                    <span class="font-medium text-sm">{{ session('success') }}</span>
                    <button @click="show = false">&times;</button>
                </div>
                @endif

                <div class="max-w-5xl mx-auto w-full space-y-8">
                    <!-- Filter & Stats -->
                    <div class="flex flex-col md:flex-row justify-between items-end md:items-center gap-4">
                        <div class="flex gap-4 overflow-x-auto w-full md:w-auto pb-2 md:pb-0">
                            <div class="bg-white px-4 py-3 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-3 min-w-[150px]">
                                <div class="p-2 bg-teal-50 text-teal-600 rounded-lg"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg></div>
                                <div><p class="text-[10px] font-bold text-slate-400 uppercase">Total</p><p class="text-lg font-bold text-slate-800">{{ $jurnals->total() ?? 0 }}</p></div>
                            </div>
                        </div>

                        <div class="bg-white p-1 rounded-xl border border-slate-200 shadow-sm flex">
                            <a href="{{ route('jurnal.index') }}" 
                               class="px-4 py-2 text-sm font-semibold rounded-lg transition-colors {{ request('filter') != 'me' ? 'bg-emerald-50 text-emerald-700 shadow-sm' : 'text-slate-500 hover:text-slate-700 hover:bg-slate-50' }}">
                               Semua
                            </a>
                            <a href="{{ route('jurnal.index', ['filter' => 'me']) }}" 
                               class="px-4 py-2 text-sm font-semibold rounded-lg transition-colors {{ request('filter') == 'me' ? 'bg-emerald-50 text-emerald-700 shadow-sm' : 'text-slate-500 hover:text-slate-700 hover:bg-slate-50' }}">
                               Jurnal Saya
                            </a>
                        </div>
                    </div>

                    <!-- Timeline Content -->
                    <div class="relative space-y-6">
                        <div class="absolute left-6 top-0 bottom-0 w-0.5 bg-slate-200 hidden md:block"></div>

                        @forelse($jurnals as $index => $jurnal)
                        <div class="relative pl-0 md:pl-16 animate-card" style="animation-delay: {{ $index * 100 }}ms">
                            <!-- Dot (Green) -->
                            <div class="absolute left-[21px] top-6 w-3 h-3 bg-white border-[3px] border-emerald-500 rounded-full z-10 hidden md:block shadow-sm"></div>

                            <!-- Card -->
                            <article class="bg-white rounded-2xl border border-slate-100 shadow-sm hover:shadow-lg hover:border-emerald-100 transition-all duration-300 overflow-visible group">
                                <div class="p-5 flex items-center justify-between border-b border-slate-50 bg-slate-50/50 rounded-t-2xl">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center text-white font-bold text-sm shadow-md ring-2 ring-white">
                                            {{ substr($jurnal->user->name ?? 'G', 0, 1) }}
                                        </div>
                                        <div>
                                            <h3 class="text-sm font-bold text-slate-900">{{ $jurnal->user->name ?? 'Guru' }}</h3>
                                            <div class="flex items-center gap-2 text-xs text-slate-500">
                                                <span class="font-medium text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded">Kelas {{ $jurnal->kelas->nama_kelas ?? '-' }}</span>
                                                <span>•</span>
                                                <span>{{ $jurnal->created_at->locale('id')->isoFormat('D MMM, HH:mm') }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Action Menu -->
                                     <div class="flex items-center gap-2">
                                        <span class="hidden sm:inline-block px-3 py-1 text-xs font-bold uppercase tracking-wide text-slate-500 bg-slate-100 rounded-full border border-slate-200">
                                            {{ $jurnal->mata_pelajaran }}
                                        </span>
                                        @if(Auth::id() === $jurnal->user_id || Auth::user()->role === 'admin')
                                            <a href="{{ route('jurnal.edit', $jurnal->id) }}" class="p-2 text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg">✏️</a>
                                        @endif
                                     </div>
                                </div>
                                
                                <div class="p-5">
                                    <h4 class="font-bold text-lg text-slate-800 mb-2 group-hover:text-emerald-600 transition-colors">{{ $jurnal->materi_pokok }}</h4>
                                    <p class="whitespace-pre-line text-slate-600 text-sm">{{ $jurnal->deskripsi_kegiatan }}</p>
                                </div>

                                @if($jurnal->foto_bukti)
                                <div class="px-5 pb-5">
                                    <img src="{{ asset('storage/' . $jurnal->foto_bukti) }}" class="w-full h-56 object-cover rounded-xl border border-slate-100">
                                </div>
                                @endif
                            </article>
                        </div>
                        @empty
                        <div class="text-center py-16 bg-white rounded-3xl border border-slate-200 border-dashed">
                            <p class="text-slate-500">Tidak ada jurnal ditemukan</p>
                        </div>
                        @endforelse

                        <div class="pt-6">
                            {{ $jurnals->withQueryString()->links() }} 
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>