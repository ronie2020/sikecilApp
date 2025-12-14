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
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background-color: #F8FAFC;
        }
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .glass-effect {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
        }
        @keyframes fadeInScale {
            0% { opacity: 0; transform: scale(0.95) translateY(10px); }
            100% { opacity: 1; transform: scale(1) translateY(0); }
        }
        .animate-card {
            animation: fadeInScale 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            opacity: 0; 
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased min-h-screen">

    <!-- Top Navigation -->
    <header class="sticky top-0 z-40 w-full glass-effect border-b border-slate-200 shadow-sm transition-all duration-300">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center gap-4">
                    <a href="{{ route('dashboard') }}" class="flex items-center justify-center w-10 h-10 rounded-xl text-slate-500 hover:bg-slate-100 hover:text-indigo-600 transition-all group">
                        <svg class="w-5 h-5 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    </a>
                    <div class="hidden sm:block w-px h-6 bg-slate-200"></div>
                    <div class="flex items-center gap-3">
                        <div class="bg-indigo-600 text-white p-2 rounded-lg shadow-indigo-200 shadow-lg hidden sm:block">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        </div>
                        <div>
                            <h1 class="text-base sm:text-lg font-bold text-slate-900 leading-none">Timeline Jurnal</h1>
                            <p class="text-[10px] sm:text-xs text-slate-500 font-medium mt-1">Aktivitas Pembelajaran Terbaru</p>
                        </div>
                    </div>
                </div>
                <div>
                    <a href="{{ route('jurnal.create') }}" class="flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-xl text-sm font-semibold transition-all shadow-md hover:shadow-lg hover:-translate-y-0.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        <span class="hidden sm:inline">Buat Jurnal</span>
                        <span class="sm:hidden">Baru</span>
                    </a>
                </div>
            </div>
        </div>
    </header>

    <main class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
        
        <!-- Flash Message -->
        @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-3">
                <div class="bg-emerald-100 p-1.5 rounded-full"><svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg></div>
                <span class="font-medium text-sm">{{ session('success') }}</span>
            </div>
            <button @click="show = false" class="text-emerald-500 hover:text-emerald-700"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
        </div>
        @endif

        <!-- Filter & Stats -->
        <div class="flex flex-col md:flex-row justify-between items-end md:items-center gap-4">
            <!-- Simple Stats -->
            <div class="flex gap-4 overflow-x-auto w-full md:w-auto pb-2 md:pb-0">
                <div class="bg-white px-4 py-3 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-3 min-w-[150px]">
                    <div class="p-2 bg-blue-50 text-blue-600 rounded-lg"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg></div>
                    <div><p class="text-[10px] font-bold text-slate-400 uppercase">Total</p><p class="text-lg font-bold text-slate-800">{{ $jurnals->total() ?? 0 }}</p></div>
                </div>
            </div>

            <!-- Filter Buttons (Functional) -->
            <div class="bg-white p-1 rounded-xl border border-slate-200 shadow-sm flex">
                <a href="{{ route('jurnal.index') }}" 
                   class="px-4 py-2 text-sm font-semibold rounded-lg transition-colors {{ request('filter') != 'me' ? 'bg-indigo-50 text-indigo-700 shadow-sm' : 'text-slate-500 hover:text-slate-700 hover:bg-slate-50' }}">
                   Semua
                </a>
                <a href="{{ route('jurnal.index', ['filter' => 'me']) }}" 
                   class="px-4 py-2 text-sm font-semibold rounded-lg transition-colors {{ request('filter') == 'me' ? 'bg-indigo-50 text-indigo-700 shadow-sm' : 'text-slate-500 hover:text-slate-700 hover:bg-slate-50' }}">
                   Jurnal Saya
                </a>
            </div>
        </div>

        <!-- Timeline Content -->
        <div class="relative space-y-6">
            <div class="absolute left-6 top-0 bottom-0 w-0.5 bg-slate-200 hidden md:block"></div>

            @forelse($jurnals as $index => $jurnal)
            <div class="relative pl-0 md:pl-16 animate-card" style="animation-delay: {{ $index * 100 }}ms">
                <!-- Dot -->
                <div class="absolute left-[21px] top-6 w-3 h-3 bg-white border-[3px] border-indigo-500 rounded-full z-10 hidden md:block shadow-sm"></div>

                <!-- Card -->
                <article class="bg-white rounded-2xl border border-slate-100 shadow-sm hover:shadow-lg hover:border-indigo-100 transition-all duration-300 overflow-visible group">
                    
                    <!-- Card Header -->
                    <div class="p-5 flex items-center justify-between border-b border-slate-50 bg-slate-50/50 rounded-t-2xl">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-sm shadow-md ring-2 ring-white">
                                {{ substr($jurnal->user->name ?? 'G', 0, 1) }}
                            </div>
                            <div>
                                <h3 class="text-sm font-bold text-slate-900">{{ $jurnal->user->name ?? 'Guru' }}</h3>
                                <div class="flex items-center gap-2 text-xs text-slate-500">
                                    <span class="font-medium text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded">Kelas {{ $jurnal->kelas->nama_kelas ?? '-' }}</span>
                                    <span>•</span>
                                    <span>{{ $jurnal->created_at->locale('id')->isoFormat('D MMM, HH:mm') }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Action Menu (Only for owner or admin) -->
                        <div class="flex items-center gap-2">
                             <!-- Badge Mapel -->
                             <span class="hidden sm:inline-block px-3 py-1 text-xs font-bold uppercase tracking-wide text-slate-500 bg-slate-100 rounded-full border border-slate-200">
                                {{ $jurnal->mata_pelajaran }}
                            </span>

                            @if(Auth::id() === $jurnal->user_id || Auth::user()->role === 'admin')
                            <div x-data="{ open: false }" class="relative">
                                <button @click="open = !open" class="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-lg transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path></svg>
                                </button>
                                <div x-show="open" @click.outside="open = false" x-transition class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-xl border border-slate-100 z-50 overflow-hidden">
                                    <a href="{{ route('jurnal.edit', $jurnal->id) }}" class="block px-4 py-3 text-sm text-slate-700 hover:bg-slate-50 hover:text-indigo-600 font-medium">✏️ Edit Jurnal</a>
                                    <form action="{{ route('jurnal.destroy', $jurnal->id) }}" method="POST" onsubmit="return confirm('Hapus jurnal ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="w-full text-left px-4 py-3 text-sm text-rose-600 hover:bg-rose-50 font-medium border-t border-slate-50">🗑️ Hapus</button>
                                    </form>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="p-5">
                        <h4 class="font-bold text-lg text-slate-800 mb-2 group-hover:text-indigo-600 transition-colors">{{ $jurnal->materi_pokok }}</h4>
                        <div class="prose prose-sm prose-slate max-w-none text-slate-600 leading-relaxed">
                            <p class="whitespace-pre-line">{{ $jurnal->deskripsi_kegiatan }}</p>
                        </div>
                    </div>

                    <!-- Media Attachment -->
                    @if($jurnal->foto_bukti)
                    <div class="px-5 pb-5" x-data="{ open: false }">
                        <div @click="open = true" class="relative group/image cursor-pointer overflow-hidden rounded-xl border border-slate-100">
                            <div class="absolute inset-0 bg-slate-900/0 group-hover/image:bg-slate-900/10 transition-colors z-10"></div>
                            <img src="{{ asset('storage/' . $jurnal->foto_bukti) }}" class="w-full h-56 object-cover transform group-hover/image:scale-105 transition-transform duration-500">
                            <div class="absolute bottom-3 right-3 bg-black/60 backdrop-blur-sm text-white text-xs px-2 py-1 rounded-md opacity-0 group-hover/image:opacity-100 transition-opacity z-20">
                                Perbesar
                            </div>
                        </div>
                        <!-- Modal Lightbox -->
                        <div x-show="open" x-transition.opacity @keydown.escape.window="open = false" class="fixed inset-0 z-[60] flex items-center justify-center bg-black/90 backdrop-blur-sm p-4">
                            <button @click="open = false" class="absolute top-4 right-4 text-white/70 hover:text-white"><svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                            <img @click.outside="open = false" src="{{ asset('storage/' . $jurnal->foto_bukti) }}" class="max-w-full max-h-[90vh] rounded-lg shadow-2xl animate-scale-in">
                        </div>
                    </div>
                    @endif
                </article>
            </div>
            @empty
            <div class="text-center py-16 bg-white rounded-3xl border border-slate-200 border-dashed">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-slate-50 rounded-full mb-4 text-3xl">📝</div>
                <h3 class="text-lg font-bold text-slate-700">Tidak ada jurnal ditemukan</h3>
                <p class="text-slate-500 mt-1 text-sm">Coba ubah filter atau buat jurnal baru.</p>
            </div>
            @endforelse

            <!-- Pagination (Penting untuk kinerja) -->
            <div class="pt-6">
                {{ $jurnals->withQueryString()->links() }} 
                <!-- Pastikan Anda menggunakan Tailwind Pagination di Laravel AppServiceProvider -->
            </div>
        </div>
    </main>
</body>
</html>