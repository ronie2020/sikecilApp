<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - SiKecil</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style> 
        body { font-family: 'Plus Jakarta Sans', sans-serif; } 
    </style>
</head>
<body class="bg-slate-50 text-slate-800" x-data="{ sidebarOpen: false }">

    <div class="flex h-screen overflow-hidden">
        
        @include('components.sidebar')

        <!-- Mobile Overlay -->
        <div x-show="sidebarOpen" @click="sidebarOpen = false" x-transition.opacity class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-40 md:hidden"></div>

        <div class="flex-1 flex flex-col overflow-hidden relative">
            
            <!-- Header -->
            <header class="flex justify-between items-center py-4 px-6 bg-white/80 backdrop-blur-md border-b border-slate-200 z-30 sticky top-0">
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = !sidebarOpen" class="md:hidden text-slate-500 hover:bg-slate-100 p-2 rounded-lg transition-colors">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                    <div>
                        <h2 class="text-xl font-bold text-slate-800 leading-tight">Dashboard</h2>
                        <p class="text-xs text-slate-500 hidden sm:block">Ringkasan aktivitas hari ini</p>
                    </div>
                </div>
                
                <div class="flex items-center gap-3">
                    <div class="text-right hidden sm:block">
                        <div class="text-sm font-bold text-slate-800">{{ Auth::user()->name }}</div>
                        <span class="text-[10px] uppercase font-bold tracking-wider text-slate-500 bg-slate-100 px-2 py-0.5 rounded-full">{{ Auth::user()->role }}</span>
                    </div>
                    <div class="h-10 w-10 rounded-full bg-gradient-to-tr from-indigo-500 to-blue-500 flex items-center justify-center text-white font-bold shadow-md ring-2 ring-white/50">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-slate-50 p-4 sm:p-6 lg:p-8">
                
                <!-- Welcome Section -->
                <div class="mb-8">
                    <h1 class="text-2xl font-bold text-slate-900">Halo, {{ Auth::user()->name }}! 👋</h1>
                    <p class="text-slate-500 mt-1">Berikut adalah laporan terkini dari aktivitas sekolah.</p>
                </div>

                @if(session('success'))
                <div x-data="{ show: true }" x-show="show" x-transition class="bg-emerald-50 border border-emerald-200 text-emerald-800 p-4 mb-6 rounded-xl shadow-sm flex items-start justify-between">
                    <div class="flex gap-3">
                        <div class="bg-emerald-100 p-1 rounded-full text-emerald-600 h-fit">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <div>
                            <p class="font-bold text-sm">Berhasil!</p>
                            <p class="text-sm opacity-90">{{ session('success') }}</p>
                        </div>
                    </div>
                    <button @click="show = false" class="text-emerald-500 hover:text-emerald-700"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                </div>
                @endif

                <!-- STATISTIK CARDS (Responsive Grid) -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                    <!-- Component Card Statistik Sederhana -->
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 flex items-center justify-between hover:shadow-md transition-shadow">
                        <div>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Total Siswa</p>
                            <h4 class="text-3xl font-extrabold text-slate-800">{{ $totalSiswa }}</h4>
                        </div>
                        <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl">🎓</div>
                    </div>

                    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 flex items-center justify-between hover:shadow-md transition-shadow">
                        <div>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Hadir Hari Ini</p>
                            <h4 class="text-3xl font-extrabold text-emerald-600">{{ $hadir }}</h4>
                        </div>
                        <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl">✅</div>
                    </div>

                    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 flex items-center justify-between hover:shadow-md transition-shadow">
                        <div>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Izin / Sakit</p>
                            <h4 class="text-3xl font-extrabold text-amber-500">{{ $sakitIzin }}</h4>
                        </div>
                        <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl">📩</div>
                    </div>

                    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 flex items-center justify-between hover:shadow-md transition-shadow">
                        <div>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Tanpa Ket.</p>
                            <h4 class="text-3xl font-extrabold text-rose-500">{{ $alpa }}</h4>
                        </div>
                        <div class="w-12 h-12 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center text-xl">❌</div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    
                    <!-- TABEL AKTIVITAS (Left Column) -->
                    <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden flex flex-col h-full">
                        <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                            <h4 class="font-bold text-slate-800 flex items-center gap-2">
                                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Absensi Terkini
                            </h4>
                            <span class="text-[10px] font-bold text-slate-500 bg-white px-2 py-1 rounded border border-slate-200 shadow-sm">{{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMM') }}</span>
                        </div>
                        
                        <div class="overflow-x-auto flex-1">
                            <table class="w-full text-sm text-left">
                                <thead class="text-xs text-slate-500 uppercase bg-slate-50 border-b border-slate-100">
                                    <tr>
                                        <th class="px-6 py-3 font-bold">Nama Siswa</th>
                                        <th class="px-6 py-3 font-bold whitespace-nowrap">Jam Masuk</th>
                                        <th class="px-6 py-3 font-bold text-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @forelse($aktivitasTerbaru as $item)
                                    <tr class="hover:bg-slate-50/80 transition-colors">
                                        <td class="px-6 py-3.5">
                                            <div class="font-bold text-slate-700 text-sm">{{ $item->user->name ?? 'User Dihapus' }}</div>
                                            <div class="text-xs text-slate-400">{{ $item->user->kelas->nama_kelas ?? '-' }}</div>
                                        </td>
                                        <td class="px-6 py-3.5 text-slate-600 font-mono text-xs whitespace-nowrap">
                                            {{ $item->jam_masuk }} WIB
                                        </td>
                                        <td class="px-6 py-3.5 text-center">
                                            <!-- REFACTORED STATUS BADGES USING SWITCH -->
                                            @switch($item->status)
                                                @case('Hadir')
                                                    <span class="inline-flex items-center gap-1.5 bg-emerald-100 text-emerald-700 text-xs font-bold px-3 py-1 rounded-full border border-emerald-200 shadow-sm">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Hadir
                                                    </span>
                                                    @break
                                                @case('Sakit')
                                                    <span class="inline-flex items-center gap-1.5 bg-amber-100 text-amber-700 text-xs font-bold px-3 py-1 rounded-full border border-amber-200">
                                                        🤒 Sakit
                                                    </span>
                                                    @break
                                                @case('Izin')
                                                    <span class="inline-flex items-center gap-1.5 bg-blue-100 text-blue-700 text-xs font-bold px-3 py-1 rounded-full border border-blue-200">
                                                        📩 Izin
                                                    </span>
                                                    @break
                                                @case('Alpa')
                                                    <span class="inline-flex items-center gap-1.5 bg-rose-100 text-rose-700 text-xs font-bold px-3 py-1 rounded-full border border-rose-200">
                                                        ❌ Alpa
                                                    </span>
                                                    @break
                                                @default
                                                    <span class="inline-flex items-center gap-1.5 bg-slate-100 text-slate-700 text-xs font-bold px-3 py-1 rounded-full border border-slate-200">
                                                        {{ $item->status }}
                                                    </span>
                                            @endswitch
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="3" class="px-6 py-12 text-center text-slate-400">
                                            <div class="flex flex-col items-center gap-2">
                                                <div class="w-12 h-12 bg-slate-50 rounded-full flex items-center justify-center text-xl shadow-inner">📭</div>
                                                <p class="text-sm font-medium">Belum ada data absensi hari ini.</p>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50 text-center">
                            <a href="{{ route('presensi.rekap') }}" class="inline-flex items-center text-xs font-bold text-indigo-600 hover:text-indigo-700 transition-colors group">
                                Lihat Selengkapnya 
                                <svg class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                            </a>
                        </div>
                    </div>

                    <!-- Right Column (Widgets) -->
                    <div class="space-y-6">
                        
                        @if(Auth::user()->role !== 'siswa')
                        <!-- Quick Actions for Teachers/Admin -->
                        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                            <h4 class="font-bold text-slate-800 mb-4 flex items-center gap-2">
                                <span class="bg-indigo-100 text-indigo-600 p-1 rounded"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg></span>
                                Menu Cepat
                            </h4>
                            <div class="grid gap-3">
                                <a href="{{ route('presensi.scan') }}" class="flex items-center gap-3 p-4 bg-gradient-to-r from-indigo-600 to-indigo-700 text-white rounded-xl shadow-lg shadow-indigo-200 hover:shadow-indigo-300 transition-all transform hover:-translate-y-1">
                                    <div class="bg-white/20 p-2 rounded-lg"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4h2v-4zM6 8v4M6 20v-4M2 20h4M2 4h4M2 12h2m8 0h2M2 8v4M2 16h2M6 16h2M6 12h4m0-8h4m4 0h4M14 8h-2M10 8h2M10 4h2m4 0h2M18 8h2m0 4h2M18 16h2m-2 4h2M2 12v4m0 4v-4m10-4v4m2-4v4m4-4v4M6 4v4m12 0v4"></path></svg></div>
                                    <div class="text-left">
                                        <p class="font-bold text-sm">Scan QR Code</p>
                                        <p class="text-[10px] opacity-80">Absensi Siswa</p>
                                    </div>
                                </a>
                                
                                <a href="{{ route('presensi.create') }}" class="flex items-center gap-3 p-4 bg-white border border-slate-200 text-slate-600 rounded-xl hover:border-indigo-500 hover:text-indigo-600 transition-all hover:bg-indigo-50">
                                    <div class="bg-slate-100 p-2 rounded-lg text-slate-500 group-hover:text-indigo-600"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg></div>
                                    <div class="text-left">
                                        <p class="font-bold text-sm">Input Manual</p>
                                        <p class="text-[10px] text-slate-400">Jika scan gagal</p>
                                    </div>
                                </a>
                            </div>
                        </div>

                        <!-- Motivation Widget -->
                        <div class="bg-gradient-to-br from-blue-500 to-cyan-600 rounded-2xl shadow-lg p-6 text-white relative overflow-hidden group">
                             <div class="absolute top-0 right-0 -mr-8 -mt-8 w-32 h-32 rounded-full bg-white/10 blur-xl group-hover:scale-150 transition-transform duration-700"></div>
                             <div class="relative z-10">
                                 <h4 class="font-bold text-lg mb-2 flex items-center gap-2">💡 Tips Guru</h4>
                                 <p class="text-blue-50 text-sm leading-relaxed mb-4">
                                     "Dokumentasi yang baik adalah kunci evaluasi yang efektif. Jangan lupa isi jurnal hari ini."
                                 </p>
                                 <a href="{{ route('jurnal.create') }}" class="text-xs font-bold bg-white/20 hover:bg-white/30 px-4 py-2 rounded-lg transition-colors inline-block backdrop-blur-sm border border-white/10">
                                     Isi Jurnal Sekarang
                                 </a>
                             </div>
                        </div>

                        @else
                        
                        <!-- Student Widget -->
                        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 text-center">
                            <div class="w-20 h-20 bg-emerald-50 rounded-full flex items-center justify-center text-3xl mx-auto mb-4 animate-bounce shadow-sm text-emerald-500">
                                😊
                            </div>
                            <h4 class="font-bold text-slate-800 text-lg">Hadir Tepat Waktu!</h4>
                            <p class="text-sm text-slate-500 mt-1 mb-4">Kamu tercatat hadir pukul 07:05 WIB.</p>
                            <div class="w-full bg-slate-100 rounded-full h-2 mb-2 overflow-hidden">
                                <div class="bg-emerald-500 h-2 rounded-full" style="width: 85%"></div>
                            </div>
                            <p class="text-xs text-slate-400">Kehadiran bulan ini: 85%</p>
                        </div>
                        @endif

                    </div>
                </div>

            </main>
        </div>
    </div>
</body>
</html>