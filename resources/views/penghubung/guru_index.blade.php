<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilih Siswa - Buku Penghubung</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <style> body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #F8FAFC; } </style>
</head>
<body class="bg-slate-50 min-h-screen text-slate-800">

    <!-- Navbar -->
    <nav class="bg-white border-b border-slate-200 sticky top-0 z-30">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center gap-3">
                    <div class="bg-indigo-600 text-white p-2 rounded-xl shadow-lg shadow-indigo-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                    </div>
                    <div>
                        <h1 class="font-bold text-lg text-slate-900 leading-tight">Buku Penghubung</h1>
                        <p class="text-xs text-slate-500 font-medium">Pilih siswa untuk memulai chat</p>
                    </div>
                </div>
                <a href="{{ url('/dashboard') }}" class="text-sm font-medium text-slate-500 hover:text-indigo-600 transition-colors flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-slate-50">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Dashboard
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="max-w-3xl mx-auto px-4 py-8" x-data="{ search: '' }">
        
        <!-- Search Bar -->
        <div class="relative mb-6">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <input type="text" x-model="search" class="w-full bg-white border border-slate-200 text-slate-700 text-sm rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent block pl-11 p-4 shadow-sm transition-all placeholder-slate-400" placeholder="Cari nama siswa...">
        </div>

        <!-- List Siswa -->
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden min-h-[300px]">
            <div class="divide-y divide-slate-100">
                @forelse($listSiswa as $s)
                <a href="{{ url('/buku-penghubung?siswa_id=' . $s->id) }}" 
                   class="block p-4 hover:bg-slate-50 transition-colors group"
                   x-show="search === '' || '{{ strtolower($s->name) }}'.includes(search.toLowerCase())">
                   
                    <div class="flex items-center gap-4">
                        <div class="relative">
                            <div class="h-12 w-12 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-600 font-bold text-lg group-hover:bg-indigo-100 transition-colors">
                                {{ substr($s->name, 0, 1) }}
                            </div>
                        </div>
                        
                        <div class="flex-1 min-w-0">
                            <h3 class="text-sm font-bold text-slate-900 group-hover:text-indigo-600 transition-colors">{{ $s->name }}</h3>
                            <p class="text-xs text-slate-500 flex items-center gap-2">
                                <span class="bg-slate-100 px-1.5 py-0.5 rounded text-[10px] font-mono">{{ $s->nip_nis }}</span>
                                <span class="text-slate-400">• Kelas {{ $s->kelas->nama_kelas ?? '-' }}</span>
                            </p>
                        </div>

                        <div class="text-slate-300 group-hover:text-indigo-500 transition-colors">
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
</body>
</html>