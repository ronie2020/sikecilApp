<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap Kebiasaan Siswa - SiKecil</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style> 
        body { font-family: 'Plus Jakarta Sans', sans-serif; } 
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        
        /* Modern Tooltip */
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
    </style>
</head>
<body class="bg-slate-50 text-slate-800" x-data="{ sidebarOpen: false }">

    <div class="flex h-screen overflow-hidden">
        
        <!-- Sidebar Include -->
        @include('components.sidebar')

        <!-- Mobile Overlay -->
        <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 z-40 bg-slate-900/50 backdrop-blur-sm md:hidden" x-transition.opacity></div>

        <div class="flex-1 flex flex-col overflow-hidden relative w-full">
            
            <!-- Header Modern -->
            <header class="flex justify-between items-center py-4 px-6 bg-white border-b border-slate-200 sticky top-0 z-30">
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = !sidebarOpen" class="md:hidden p-2 text-slate-500 hover:bg-slate-100 rounded-lg">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                    <div>
                        <h2 class="text-xl font-bold text-slate-800 leading-tight">Rekapitulasi Kebiasaan</h2>
                        <p class="text-xs text-slate-500">Monitoring perkembangan karakter siswa</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="text-right hidden sm:block">
                        <div class="text-sm font-bold text-slate-700">{{ Auth::user()->name }}</div>
                        <div class="text-xs text-slate-500">Wali Kelas</div>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-blue-500 to-indigo-500 flex items-center justify-center text-white font-bold shadow-md">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                </div>
            </header>

            <!-- Main Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-slate-50 p-4 sm:p-6 lg:p-8">
                
                <!-- Controls Section -->
                <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-8 gap-4">
                    
                    <!-- Date Picker Filter -->
                    <div class="w-full md:w-auto">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Pilih Tanggal</label>
                        <form action="{{ route('kebiasaan.rekap') }}" method="GET" class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                            <input type="date" name="tanggal" value="{{ $tanggal }}" onchange="this.form.submit()" 
                                   class="pl-10 pr-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm font-medium text-slate-700 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none shadow-sm w-full md:w-64 cursor-pointer hover:border-blue-300 transition-colors">
                        </form>
                    </div>

                    <!-- Summary Stats -->
                    <div class="flex gap-3 w-full md:w-auto">
                        <div class="flex-1 md:flex-none bg-white px-5 py-3 rounded-xl border border-slate-200 shadow-sm flex items-center gap-3">
                            <div class="w-2 h-2 rounded-full bg-emerald-500"></div>
                            <div>
                                <span class="text-[10px] text-slate-400 uppercase font-bold tracking-wider block">Sudah Mengisi</span>
                                <span class="text-xl font-bold text-slate-800">{{ $sudahIsi }} <span class="text-xs font-normal text-slate-500">Siswa</span></span>
                            </div>
                        </div>
                        <div class="flex-1 md:flex-none bg-white px-5 py-3 rounded-xl border border-slate-200 shadow-sm flex items-center gap-3">
                            <div class="w-2 h-2 rounded-full bg-rose-500"></div>
                            <div>
                                <span class="text-[10px] text-slate-400 uppercase font-bold tracking-wider block">Belum Mengisi</span>
                                <span class="text-xl font-bold text-slate-800">{{ $belumIsi }} <span class="text-xs font-normal text-slate-500">Siswa</span></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Table Card -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                    <!-- Table Header Actions -->
                    <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                        <h3 class="font-bold text-slate-700 flex items-center gap-2">
                            <span>📅 Data Tanggal:</span>
                            <span class="text-blue-600 bg-blue-50 px-2 py-0.5 rounded text-sm">{{ $tanggalFormat }}</span>
                        </h3>
                        <button class="text-xs font-bold bg-white border border-slate-200 text-slate-600 hover:text-blue-600 hover:border-blue-200 px-4 py-2 rounded-lg transition-all shadow-sm flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                            Cetak Laporan
                        </button>
                    </div>

                    <!-- Table Responsive Wrapper -->
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead class="text-xs text-slate-500 uppercase bg-slate-50 border-b border-slate-200">
                                <tr>
                                    <th class="px-6 py-4 font-bold w-16">No</th>
                                    <th class="px-6 py-4 font-bold min-w-[200px]">Nama Siswa</th>
                                    
                                    <!-- Icons Header with Tooltips -->
                                    @php $icons = ['🌅', '🤲', '⚽', '🥗', '📚', '🤝', '🌙']; @endphp
                                    @php $labels = ['Bangun Pagi', 'Beribadah', 'Olahraga', 'Makan Sehat', 'Belajar', 'Masyarakat', 'Tidur Cepat']; @endphp
                                    
                                    @foreach($icons as $i => $icon)
                                    <th class="px-2 py-4 text-center relative group cursor-help" data-tooltip="{{ $labels[$i] }}">
                                        <span class="text-lg filter grayscale opacity-70 group-hover:grayscale-0 group-hover:opacity-100 transition-all">{{ $icon }}</span>
                                    </th>
                                    @endforeach
                                    
                                    <th class="px-6 py-4 text-center font-bold">Skor</th>
                                    <th class="px-6 py-4 font-bold min-w-[200px]">Catatan Ortu</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($siswa as $index => $s)
                                @php 
                                    $k = $s->kebiasaan; 
                                    $skor = $k ? ($k->k1 + $k->k2 + $k->k3 + $k->k4 + $k->k5 + $k->k6 + $k->k7) : 0;
                                @endphp
                                <tr class="hover:bg-slate-50/80 transition-colors group">
                                    <td class="px-6 py-4 text-slate-500 font-medium">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-slate-800">{{ $s->name }}</div>
                                        <div class="text-xs text-slate-400 font-mono mt-0.5">{{ $s->nip_nis }}</div>
                                    </td>

                                    <!-- Status Checklist -->
                                    @for($i = 1; $i <= 7; $i++)
                                        <td class="px-2 py-4 text-center">
                                            @if($k && $k->{'k'.$i})
                                                <div class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-emerald-100 text-emerald-600">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                                </div>
                                            @else
                                                <div class="inline-block w-1.5 h-1.5 rounded-full bg-slate-200"></div>
                                            @endif
                                        </td>
                                    @endfor

                                    <!-- Total Skor -->
                                    <td class="px-6 py-4 text-center">
                                        @if($k)
                                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-xs font-bold {{ $skor == 7 ? 'bg-yellow-100 text-yellow-700 ring-1 ring-yellow-200' : 'bg-slate-100 text-slate-600' }}">
                                                {{ $skor }}
                                            </span>
                                        @else
                                            <span class="px-2 py-1 rounded text-[10px] font-bold bg-rose-50 text-rose-500 uppercase tracking-wide">Belum</span>
                                        @endif
                                    </td>

                                    <!-- Catatan -->
                                    <td class="px-6 py-4">
                                        @if($k && $k->catatan)
                                            <div class="text-xs text-slate-600 bg-slate-50 px-3 py-2 rounded-lg border border-slate-100 italic relative group-hover:bg-white transition-colors">
                                                "{{ Str::limit($k->catatan, 30) }}"
                                                @if(strlen($k->catatan) > 30)
                                                <div class="absolute left-0 bottom-full mb-2 w-48 p-2 bg-slate-800 text-white text-xs rounded shadow-lg hidden group-hover:block z-10">
                                                    {{ $k->catatan }}
                                                </div>
                                                @endif
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
                                            <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                                                <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                            </div>
                                            <h3 class="text-slate-500 font-medium">Belum ada data siswa ditemukan</h3>
                                            <p class="text-slate-400 text-xs mt-1">Pastikan data siswa sudah diinputkan ke dalam sistem.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Footer Info -->
                    <div class="px-6 py-4 border-t border-slate-100 bg-slate-50 text-xs text-slate-500 flex justify-between items-center">
                        <div class="flex gap-4">
                            <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-emerald-500"></span> Dilakukan</span>
                            <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-slate-300"></span> Tidak Dilakukan</span>
                        </div>
                        <div>Total Data: {{ count($siswa) }} Siswa</div>
                    </div>

                </div>

            </main>
        </div>
    </div>
</body>
</html>