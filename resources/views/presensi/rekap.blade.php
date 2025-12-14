<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap Kehadiran</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        /* Custom Scrollbar */
        .custom-scrollbar::-webkit-scrollbar { width: 6px; height: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #f1f5f9; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #d1fae5; border-radius: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #34d399; }
    </style>
</head>
<body class="bg-slate-50 flex text-slate-800" x-data="{ sidebarOpen: false }">

    @include('components.sidebar')

    <div class="flex-1 flex flex-col h-screen overflow-hidden">
        
        <!-- Header -->
        <header class="bg-white p-4 sm:px-6 shadow-sm border-b border-slate-200 flex justify-between items-center z-10">
            <div class="flex items-center gap-3">
                <!-- Mobile Sidebar Toggle -->
                <button @click="sidebarOpen = !sidebarOpen" class="md:hidden text-slate-500 hover:bg-slate-100 p-2 rounded-lg transition-colors">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <div>
                    <h1 class="text-xl font-extrabold text-emerald-800 tracking-tight">📊 Rekapitulasi Kehadiran</h1>
                    <p class="text-xs text-slate-500 hidden sm:block">Laporan bulanan absensi siswa.</p>
                </div>
            </div>
            
            <div class="flex items-center gap-3">
                <div class="text-right hidden sm:block">
                    <div class="text-sm font-bold text-slate-800">{{ Auth::user()->name }}</div>
                    <span class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-100">Guru</span>
                </div>
                <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-emerald-500 to-teal-500 flex items-center justify-center text-white font-bold shadow-md">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
            </div>
        </header>

        <main class="flex-1 overflow-auto p-4 sm:p-6 custom-scrollbar bg-slate-50">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                
                <!-- Filter Bulan -->
                <div class="flex flex-col sm:flex-row justify-between items-end sm:items-center mb-6 gap-4 border-b border-slate-100 pb-6">
                    <div class="flex items-center gap-3">
                        <div class="p-3 bg-emerald-50 text-emerald-600 rounded-xl">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-800">Periode Laporan</h3>
                            <p class="text-xs text-slate-500">Pilih bulan untuk melihat data.</p>
                        </div>
                    </div>
                    <form method="GET" class="w-full sm:w-auto">
                        <div class="relative">
                            <input type="month" name="bulan" value="{{ $bulan }}" onchange="this.form.submit()" 
                                class="w-full sm:w-48 bg-slate-50 border border-slate-200 text-slate-800 text-sm font-bold rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 block p-2.5 outline-none cursor-pointer hover:bg-emerald-50/50 transition-colors">
                        </div>
                    </form>
                </div>

                <!-- Tabel -->
                <div class="overflow-x-auto rounded-xl border border-slate-200">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-emerald-600 text-white uppercase font-bold text-xs">
                            <tr>
                                <th class="p-4 rounded-tl-xl">Nama Siswa</th>
                                <th class="p-4 text-center w-24 bg-emerald-700/20">Hadir</th>
                                <th class="p-4 text-center w-24">Sakit</th>
                                <th class="p-4 text-center w-24 bg-emerald-700/20">Izin</th>
                                <th class="p-4 text-center w-24">Alpa</th>
                                <th class="p-4 text-center w-28 rounded-tr-xl bg-emerald-800">Persentase</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($siswa as $s)
                            @php
                                $h = $presensi->where('user_id', $s->id)->where('status', 'Hadir')->count();
                                $sa = $presensi->where('user_id', $s->id)->where('status', 'Sakit')->count();
                                $i = $presensi->where('user_id', $s->id)->where('status', 'Izin')->count();
                                $a = $presensi->where('user_id', $s->id)->where('status', 'Alpa')->count();
                                $total = $h + $sa + $i + $a;
                                $persen = $total > 0 ? round(($h / $total) * 100) . '%' : '0%';
                                
                                // Warna Progress Bar
                                $persenVal = $total > 0 ? round(($h / $total) * 100) : 0;
                                $barColor = $persenVal >= 80 ? 'bg-emerald-500' : ($persenVal >= 50 ? 'bg-amber-400' : 'bg-rose-500');
                            @endphp
                            <tr class="hover:bg-emerald-50/50 transition-colors group">
                                <td class="p-4 font-bold text-slate-700 flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-xs text-slate-500 group-hover:bg-white group-hover:text-emerald-600 transition-colors">
                                        {{ substr($s->name, 0, 1) }}
                                    </div>
                                    {{ $s->name }}
                                </td>
                                <td class="p-4 text-center">
                                    @if($h > 0) <span class="bg-emerald-100 text-emerald-700 font-bold px-2.5 py-1 rounded-lg text-xs">{{ $h }}</span>
                                    @else <span class="text-slate-300">-</span> @endif
                                </td>
                                <td class="p-4 text-center">
                                    @if($sa > 0) <span class="bg-amber-100 text-amber-700 font-bold px-2.5 py-1 rounded-lg text-xs">{{ $sa }}</span>
                                    @else <span class="text-slate-300">-</span> @endif
                                </td>
                                <td class="p-4 text-center">
                                    @if($i > 0) <span class="bg-teal-100 text-teal-700 font-bold px-2.5 py-1 rounded-lg text-xs">{{ $i }}</span>
                                    @else <span class="text-slate-300">-</span> @endif
                                </td>
                                <td class="p-4 text-center">
                                    @if($a > 0) <span class="bg-rose-100 text-rose-700 font-bold px-2.5 py-1 rounded-lg text-xs">{{ $a }}</span>
                                    @else <span class="text-slate-300">-</span> @endif
                                </td>
                                <td class="p-4 text-center align-middle">
                                    <div class="flex items-center gap-2">
                                        <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden">
                                            <div class="{{ $barColor }} h-1.5 rounded-full" style="width: {{ $persen }}"></div>
                                        </div>
                                        <span class="text-xs font-bold text-slate-600 min-w-[30px]">{{ $persen }}</span>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="p-8 text-center text-slate-400 font-medium">Tidak ada data siswa untuk ditampilkan.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</body>
</html>