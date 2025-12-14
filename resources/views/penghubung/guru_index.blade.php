<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilih Siswa - Buku Penghubung</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <style> 
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #F8FAFC; } 
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-slate-50 min-h-screen text-slate-800" x-data="{ sidebarOpen: false }">

    <div class="flex h-screen overflow-hidden">
        
        <!-- SIDEBAR -->
        @include('components.sidebar')

        <!-- Mobile Overlay -->
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

        <!-- MAIN CONTENT -->
        <div class="flex-1 flex flex-col h-screen overflow-hidden relative">
            
            <!-- Navbar / Header -->
            <nav class="bg-white border-b border-slate-200 sticky top-0 z-30 shrink-0">
                <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16 items-center">
                        <div class="flex items-center gap-3">
                            <!-- Hamburger (Mobile) -->
                            <button @click="sidebarOpen = !sidebarOpen" class="md:hidden p-2 -ml-2 text-slate-500 hover:text-emerald-600 rounded-lg hover:bg-slate-100 transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                            </button>

                            <div class="bg-emerald-600 text-white p-2 rounded-xl shadow-lg shadow-emerald-200 hidden sm:block">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                            </div>
                            <div>
                                <h1 class="font-bold text-lg text-slate-900 leading-tight">Buku Penghubung</h1>
                                <p class="text-xs text-slate-500 font-medium">Pilih siswa untuk memulai chat</p>
                            </div>
                        </div>
                        
                        <!-- Breadcrumb / Status -->
                        <div class="hidden sm:flex items-center gap-2 px-3 py-1 bg-slate-50 rounded-lg border border-slate-100">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                            <span class="text-xs font-medium text-slate-500">Guru Mode</span>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Scrollable Content -->
            <div class="flex-1 overflow-y-auto bg-slate-50 p-4 sm:p-8" x-data="{ search: '' }">
                <div class="max-w-3xl mx-auto">
                    
                    <!-- Search Bar -->
                    <div class="relative mb-6">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                        <input type="text" x-model="search" class="w-full bg-white border border-slate-200 text-slate-700 text-sm rounded-2xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent block pl-11 p-4 shadow-sm transition-all placeholder-slate-400" placeholder="Cari nama siswa...">
                    </div>

                    <!-- List Siswa -->
                    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden min-h-[300px]">
                        <div class="divide-y divide-slate-100">
                            @forelse($listSiswa as $s)
                            
                            <!-- LOGIKA NOTIFIKASI: Hitung pesan belum dibaca -->
                            @php
                                $unreadCount = \App\Models\BukuPenghubung::where('siswa_id', $s->id)
                                                ->where('pengirim_id', '!=', Auth::id()) // Pesan dari siswa/ortu
                                                ->where('is_read', 0)
                                                ->count();
                            @endphp

                            <a href="{{ url('/buku-penghubung?siswa_id=' . $s->id) }}" 
                               class="block p-4 hover:bg-emerald-50/30 transition-colors group relative"
                               x-show="search === '' || '{{ strtolower($s->name) }}'.includes(search.toLowerCase())">
                               
                                <div class="flex items-center gap-4">
                                    <div class="relative">
                                        <div class="h-12 w-12 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-600 font-bold text-lg group-hover:bg-emerald-100 transition-colors border border-emerald-100">
                                            {{ substr($s->name, 0, 1) }}
                                        </div>
                                    </div>
                                    
                                    <div class="flex-1 min-w-0">
                                        <div class="flex justify-between items-center mb-1">
                                            <h3 class="text-sm font-bold text-slate-900 group-hover:text-emerald-700 transition-colors">{{ $s->name }}</h3>
                                            
                                            <!-- BADGE NOTIFIKASI -->
                                            @if($unreadCount > 0)
                                                <span class="bg-rose-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full shadow-sm animate-pulse">
                                                    {{ $unreadCount }} Pesan Baru
                                                </span>
                                            @endif
                                        </div>
                                        <p class="text-xs text-slate-500 flex items-center gap-2">
                                            <span class="bg-slate-100 px-1.5 py-0.5 rounded text-[10px] font-mono text-slate-500">{{ $s->nip_nis }}</span>
                                            <span class="text-slate-400">• Kelas {{ $s->kelas->nama_kelas ?? '-' }}</span>
                                        </p>
                                    </div>

                                    <div class="text-slate-300 group-hover:text-emerald-500 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                    </div>
                                </div>
                            </a>
                            @empty
                            <div class="p-12 text-center text-slate-500">
                                <p>Belum ada data siswa.</p>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>