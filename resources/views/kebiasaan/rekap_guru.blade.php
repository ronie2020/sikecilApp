<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap Kebiasaan Siswa - SiKecil</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <style> 
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #F8FAFC; } 
        [x-cloak] { display: none !important; }
        
        /* Custom Scrollbar for Table */
        .custom-scroll::-webkit-scrollbar { height: 8px; width: 8px; }
        .custom-scroll::-webkit-scrollbar-track { background: #f1f5f9; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        .custom-scroll::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

        /* Tooltip */
        [data-tooltip]:hover::after {
            content: attr(data-tooltip);
            position: absolute;
            bottom: 100%;
            left: 50%;
            transform: translateX(-50%);
            margin-bottom: 8px;
            padding: 6px 10px;
            background-color: #1E293B;
            color: white;
            border-radius: 8px;
            font-size: 11px;
            white-space: nowrap;
            z-index: 50;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            animation: fadeIn 0.2s ease-out;
        }
        @keyframes fadeIn { from { opacity: 0; transform: translate(-50%, 5px); } to { opacity: 1; transform: translate(-50%, 0); } }

        /* Print Styles */
        @media print {
            .no-print { display: none !important; }
            .print-full { width: 100% !important; max-width: none !important; margin: 0 !important; padding: 0 !important; }
            body { background: white; }
            .print-border { border: 1px solid #000; }
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased" x-data="{ sidebarOpen: false, search: '' }">

    <div class="flex h-screen overflow-hidden">
        
        <!-- 1. SIDEBAR (Hidden on Print) -->
        <div class="no-print">
            @include('components.sidebar')
        </div>

        <!-- Mobile Overlay -->
        <div x-show="sidebarOpen" 
             @click="sidebarOpen = false"
             x-transition.opacity
             class="fixed inset-0 bg-slate-900/50 z-40 md:hidden no-print" x-cloak>
        </div>

        <!-- 2. MAIN CONTENT -->
        <div class="flex-1 flex flex-col h-screen overflow-hidden relative w-full print-full">
            
            <!-- Header (Hidden on Print) -->
            <header class="bg-white border-b border-slate-200 sticky top-0 z-30 shrink-0 no-print">
                <div class="px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16 items-center">
                        <div class="flex items-center gap-3">
                            <button @click="sidebarOpen = !sidebarOpen" class="md:hidden p-2 -ml-2 text-slate-500 hover:text-blue-600 rounded-lg hover:bg-slate-50 transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                            </button>
                            <div class="bg-indigo-600 text-white p-2 rounded-xl shadow-lg shadow-indigo-200 hidden sm:block">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            </div>
                            <div>
                                <h1 class="font-bold text-lg text-slate-800 leading-tight">Rekapitulasi Kebiasaan</h1>
                                <p class="text-[10px] text-slate-500 font-medium tracking-wide uppercase hidden sm:block">Monitoring Karakter Siswa</p>
                            </div>
                        </div>
                        
                        <!-- Print Button -->
                        <button onclick="window.print()" class="text-xs font-bold bg-white border border-slate-200 text-slate-600 hover:text-indigo-600 hover:border-indigo-200 px-4 py-2 rounded-lg transition-all shadow-sm flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2z"></path></svg>
                            <span class="hidden sm:inline">Cetak / PDF</span>
                        </button>
                    </div>
                </div>
            </header>

            <!-- Scrollable Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-slate-50 p-4 sm:p-6 lg:p-8 print-full">
                
                <!-- Filters & Stats Section -->
                <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-6 gap-4 no-print">
                    
                    <!-- Date & Search -->
                    <div class="flex flex-col sm:flex-row gap-4 w-full md:w-auto">
                        <div class="w-full sm:w-auto">
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Tanggal Data</label>
                            <form action="{{ route('kebiasaan.rekap') }}" method="GET" class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                                <input type="date" name="tanggal" value="{{ $tanggal }}" onchange="this.form.submit()" 
                                       class="pl-9 pr-4 py-2 bg-white border border-slate-200 rounded-xl text-sm font-bold text-slate-700 focus:ring-2 focus:ring-indigo-500 outline-none shadow-sm w-full cursor-pointer hover:border-indigo-300 transition-colors">
                            </form>
                        </div>
                        
                        <div class="w-full sm:w-64">
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Cari Siswa</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                </span>
                                <input x-model="search" type="text" placeholder="Ketik nama siswa..." class="w-full bg-white border border-slate-200 text-sm font-medium rounded-xl py-2 pl-9 pr-4 focus:ring-2 focus:ring-indigo-500 outline-none shadow-sm">
                            </div>
                        </div>
                    </div>

                    <!-- Stats Widgets -->
                    <div class="grid grid-cols-2 gap-3 w-full md:w-auto">
                        <div class="bg-emerald-50 px-4 py-2 rounded-xl border border-emerald-100 flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600 font-bold text-xs shrink-0">
                                {{ $sudahIsi }}
                            </div>
                            <div>
                                <span class="text-[10px] text-emerald-600/70 font-bold uppercase tracking-wider block">Sudah Isi</span>
                                <span class="text-sm font-bold text-emerald-800">Siswa</span>
                            </div>
                        </div>
                        <div class="bg-rose-50 px-4 py-2 rounded-xl border border-rose-100 flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-rose-100 flex items-center justify-center text-rose-600 font-bold text-xs shrink-0">
                                {{ $belumIsi }}
                            </div>
                            <div>
                                <span class="text-[10px] text-rose-600/70 font-bold uppercase tracking-wider block">Belum Isi</span>
                                <span class="text-sm font-bold text-rose-800">Siswa</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- TABLE CARD -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden print-border">
                    <!-- Table Header Info (Visible on Print) -->
                    <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                        <div class="flex items-center gap-2">
                            <div class="w-1 h-6 bg-indigo-500 rounded-full"></div>
                            <h3 class="font-bold text-slate-700">Laporan Harian Kelas</h3>
                        </div>
                        <span class="text-xs font-medium text-slate-500 bg-white border border-slate-200 px-3 py-1 rounded-full">
                            {{ \Carbon\Carbon::parse($tanggal)->locale('id')->isoFormat('dddd, D MMMM Y') }}
                        </span>
                    </div>

                    <!-- Responsive Table Wrapper -->
                    <div class="overflow-x-auto custom-scroll relative max-h-[calc(100vh-250px)]">
                        <table class="w-full text-sm text-left border-collapse">
                            <thead class="text-xs text-slate-500 uppercase bg-slate-50 border-b border-slate-200 sticky top-0 z-20 shadow-sm">
                                <tr>
                                    <th class="px-4 py-3 font-bold w-12 text-center bg-slate-50">No</th>
                                    
                                    <!-- Sticky Name Column -->
                                    <th class="px-4 py-3 font-bold min-w-[200px] sticky left-0 bg-slate-50 z-20 border-r border-slate-200 shadow-[4px_0_8px_-4px_rgba(0,0,0,0.05)]">
                                        Nama Siswa
                                    </th>
                                    
                                    <!-- Habits Icons -->
                                    @php 
                                        $habits = [
                                            ['label' => 'Bangun Pagi', 'color' => 'text-orange-500', 'icon' => 'M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z'],
                                            ['label' => 'Beribadah', 'color' => 'text-emerald-500', 'icon' => 'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z'],
                                            ['label' => 'Olahraga', 'color' => 'text-blue-500', 'icon' => 'M13 10V3L4 14h7v7l9-11h-7z'],
                                            ['label' => 'Makan Sehat', 'color' => 'text-lime-500', 'icon' => 'M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z'],
                                            ['label' => 'Belajar', 'color' => 'text-yellow-500', 'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253'],
                                            ['label' => 'Bermasyarakat', 'color' => 'text-violet-500', 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z'],
                                            ['label' => 'Tidur Cepat', 'color' => 'text-indigo-500', 'icon' => 'M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z'],
                                        ]; 
                                    @endphp
                                    
                                    @foreach($habits as $h)
                                    <th class="px-2 py-3 text-center min-w-[50px] group relative cursor-help bg-slate-50" data-tooltip="{{ $h['label'] }}">
                                        <div class="flex justify-center">
                                            <svg class="w-5 h-5 {{ $h['color'] }} opacity-70 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $h['icon'] }}"></path>
                                            </svg>
                                        </div>
                                    </th>
                                    @endforeach
                                    
                                    <th class="px-4 py-3 text-center font-bold bg-slate-50">Skor</th>
                                    <th class="px-4 py-3 font-bold min-w-[200px] bg-slate-50">Catatan</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 bg-white">
                                @forelse($siswa as $index => $s)
                                @php 
                                    $k = $s->kebiasaan; 
                                    $skor = $k ? ($k->k1 + $k->k2 + $k->k3 + $k->k4 + $k->k5 + $k->k6 + $k->k7) : 0;
                                @endphp
                                <tr class="hover:bg-slate-50/80 transition-colors group" 
                                    x-show="search === '' || '{{ strtolower($s->name) }}'.includes(search.toLowerCase())"
                                    x-transition>
                                    
                                    <td class="px-4 py-3 text-slate-500 font-medium text-center">{{ $index + 1 }}</td>
                                    
                                    <!-- Sticky Name Cell -->
                                    <td class="px-4 py-3 sticky left-0 bg-white group-hover:bg-slate-50 transition-colors border-r border-slate-100 z-10">
                                        <div class="font-bold text-slate-800 text-sm">{{ $s->name }}</div>
                                        <div class="text-[10px] text-slate-400 font-mono">{{ $s->nip_nis }}</div>
                                    </td>

                                    @for($i = 1; $i <= 7; $i++)
                                        <td class="px-2 py-3 text-center">
                                            @if($k && $k->{'k'.$i})
                                                <div class="inline-flex items-center justify-center w-6 h-6 rounded-md bg-emerald-50 text-emerald-500 border border-emerald-100">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                                </div>
                                            @else
                                                <div class="inline-block w-2 h-2 rounded-full bg-slate-100"></div>
                                            @endif
                                        </td>
                                    @endfor

                                    <td class="px-4 py-3 text-center">
                                        @if($k)
                                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-xs font-bold border {{ $skor == 7 ? 'bg-yellow-50 text-yellow-700 border-yellow-200' : ($skor >= 5 ? 'bg-blue-50 text-blue-700 border-blue-200' : 'bg-slate-50 text-slate-600 border-slate-200') }}">
                                                {{ $skor }}
                                            </span>
                                        @else
                                            <span class="text-[10px] font-bold text-slate-300 uppercase">n/a</span>
                                        @endif
                                    </td>

                                    <td class="px-4 py-3">
                                        @if($k && $k->catatan)
                                            <div class="text-xs text-slate-600 bg-slate-50 px-3 py-2 rounded-lg border border-slate-100 italic relative group-hover:bg-white transition-colors cursor-help group/note">
                                                <span class="line-clamp-1">"{{ $k->catatan }}"</span>
                                                <!-- Tooltip Note Full -->
                                                <div class="absolute right-0 bottom-full mb-2 w-64 p-3 bg-slate-800 text-white text-xs rounded-lg shadow-xl hidden group-hover/note:block z-50 text-left font-normal not-italic">
                                                    {{ $k->catatan }}
                                                    <div class="absolute -bottom-1 right-4 w-2 h-2 bg-slate-800 rotate-45"></div>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-slate-300 text-xs">-</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="11" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-4 border border-dashed border-slate-300">
                                                <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                            </div>
                                            <h3 class="text-slate-500 font-medium">Tidak ada data siswa</h3>
                                            <p class="text-slate-400 text-xs mt-1">Coba sesuaikan filter pencarian atau tanggal.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="px-6 py-4 border-t border-slate-100 bg-slate-50 text-xs text-slate-500 flex justify-between items-center">
                        <div class="flex gap-4">
                            <span class="flex items-center gap-1.5"><span class="w-4 h-4 rounded bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-500"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg></span> Dilakukan</span>
                            <span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-slate-200"></span> Tidak Dilakukan</span>
                        </div>
                        <div class="font-medium">Total: {{ count($siswa) }} Siswa</div>
                    </div>

                </div>

            </main>
        </div>
    </div>
</body>
</html>