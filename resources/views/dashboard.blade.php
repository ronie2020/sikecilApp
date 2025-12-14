<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - SiKecil</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style> 
        body { font-family: 'Plus Jakarta Sans', sans-serif; } 
        [x-cloak] { display: none !important; }

        /* --- STYLING KHUSUS CETAK (PRINT) --- */
        @media print {
            @page { size: A4; margin: 2cm; }
            body { background: white; -webkit-print-color-adjust: exact; }
            
            /* Sembunyikan elemen navigasi/interaktif */
            .no-print, nav, header, aside, .btn-action, .chart-container, .greeting-section, .filter-tabs { 
                display: none !important; 
            }

            /* Tampilkan elemen khusus print */
            .print-only { display: block !important; }

            /* Reset container untuk print */
            .main-content { 
                margin: 0 !important; 
                padding: 0 !important; 
                width: 100% !important; 
                overflow: visible !important;
            }

            /* Styling Tabel Cetak */
            table { width: 100%; border-collapse: collapse; border: 1px solid #000; font-size: 12px; }
            th, td { border: 1px solid #000; padding: 6px 8px; text-align: left; }
            th { background-color: #f3f4f6 !important; font-weight: bold; text-align: center; }
            
            /* Hapus bayangan dan border rounded */
            .card-report { 
                box-shadow: none !important; 
                border: none !important; 
                padding: 0 !important; 
                margin: 0 !important; 
            }

            /* Kop Surat */
            .kop-surat {
                border-bottom: 2px solid #000;
                margin-bottom: 20px;
                padding-bottom: 10px;
                text-align: center;
            }
        }

        .print-only { display: none; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800" x-data="{ sidebarOpen: false }">

    <div class="flex h-screen overflow-hidden">
        
        <div class="no-print">
            @include('components.sidebar')
        </div>

        <!-- Mobile Overlay -->
        <div x-show="sidebarOpen" @click="sidebarOpen = false" x-transition.opacity class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-40 md:hidden no-print" x-cloak></div>

        <!-- PERBAIKAN: Ditambahkan min-w-0 agar flex child tidak overflow -->
        <div class="flex-1 flex flex-col overflow-hidden relative main-content min-w-0">
            
            <!-- Header (Hidden on Print) -->
            <header class="flex justify-between items-center py-4 px-6 bg-white/80 backdrop-blur-md border-b border-slate-200 z-30 sticky top-0 no-print">
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = !sidebarOpen" class="md:hidden text-slate-500 hover:bg-slate-100 p-2 rounded-lg transition-colors">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                    <div>
                        <h2 class="text-xl font-bold text-slate-800 leading-tight">Dashboard</h2>
                        <p class="text-xs text-slate-500 hidden sm:block">
                            {{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM Y') }}
                        </p>
                    </div>
                </div>
                
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 rounded-full bg-gradient-to-tr from-emerald-500 to-teal-500 flex items-center justify-center text-white font-bold shadow-md ring-2 ring-white/50 cursor-pointer">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-slate-50 p-4 sm:p-6 lg:p-8">
                
                <!-- KOP SURAT (Hanya Tampil Saat Print) -->
                <div class="print-only kop-surat">
                    <h1 class="text-2xl font-bold uppercase tracking-widest mb-1">SD NEGERI CONTOH 01</h1>
                    <p class="text-sm">Jl. Pendidikan Karakter No. 123, Kota Belajar, Indonesia</p>
                    <p class="text-xs italic">Email: info@sdcontoh.sch.id | Telp: (021) 1234567</p>
                </div>

                <!-- Greeting Section (Hidden on Print) -->
                @php
                    $hour = date('H');
                    if ($hour < 11) $greeting = 'Selamat Pagi';
                    elseif ($hour < 15) $greeting = 'Selamat Siang';
                    elseif ($hour < 18) $greeting = 'Selamat Sore';
                    else $greeting = 'Selamat Malam';
                @endphp
                
                <div class="flex flex-col md:flex-row justify-between items-end mb-8 gap-4 greeting-section no-print">
                    <div>
                        <h1 class="text-2xl font-bold text-slate-900">{{ $greeting }}, {{ explode(' ', Auth::user()->name)[0] }}! 👋</h1>
                        <p class="text-slate-500 mt-1">Berikut adalah ringkasan aktivitas akademik hari ini.</p>
                    </div>
                </div>

                @if(Auth::user()->role !== 'siswa')
                    
                    <!-- Quick Stats (Hidden on Print) -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8 no-print">
                        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 flex items-center justify-between hover:shadow-md transition-all">
                            <div>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Total Siswa</p>
                                <h4 class="text-3xl font-extrabold text-teal-600">{{ $totalSiswa ?? 0 }}</h4>
                            </div>
                            <div class="w-12 h-12 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center text-xl">🎓</div>
                        </div>
                        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 flex items-center justify-between hover:shadow-md transition-all">
                            <div>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Hadir Hari Ini</p>
                                <h4 class="text-3xl font-extrabold text-emerald-600">{{ $hadir ?? 0 }}</h4>
                            </div>
                            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl">✅</div>
                        </div>
                        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 flex items-center justify-between hover:shadow-md transition-all">
                            <div>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Izin / Sakit</p>
                                <h4 class="text-3xl font-extrabold text-amber-500">{{ $sakitIzin ?? 0 }}</h4>
                            </div>
                            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl">📩</div>
                        </div>
                        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 flex items-center justify-between hover:shadow-md transition-all">
                            <div>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Tanpa Ket.</p>
                                <h4 class="text-3xl font-extrabold text-rose-500">{{ $alpa ?? 0 }}</h4>
                            </div>
                            <div class="w-12 h-12 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center text-xl">❌</div>
                        </div>
                    </div>

                    <!-- Chart Section (Hidden on Print) -->
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 mb-8 chart-container no-print">
                        <h4 class="font-bold text-slate-800 mb-6 flex items-center gap-2">
                            <span class="p-1.5 bg-emerald-100 text-emerald-600 rounded-lg"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path></svg></span>
                            Tren Kehadiran Minggu Ini
                        </h4>
                        <!-- PERBAIKAN: relative container untuk chart agar responsif -->
                        <div class="h-64 w-full relative">
                            <canvas id="attendanceChart"></canvas>
                        </div>
                    </div>

                    <!-- REKAPITULASI & LAPORAN SECTION (CORE FEATURE) -->
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 card-report overflow-hidden" x-data="{ period: 'bulanan' }">
                        
                        <!-- Header Laporan (Controls hidden on print) -->
                        <div class="px-6 py-5 border-b border-slate-100 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                            <div>
                                <h4 class="font-bold text-slate-800 text-lg">Laporan Rekapitulasi Kehadiran</h4>
                                <p class="text-sm text-slate-500 no-print">Data berikut diambil secara real-time dari database.</p>
                                <p class="text-sm text-slate-600 print-only">
                                    Periode Laporan: <span x-text="period.charAt(0).toUpperCase() + period.slice(1)"></span> | 
                                    Tanggal Cetak: {{ \Carbon\Carbon::now()->format('d/m/Y') }}
                                </p>
                            </div>
                            
                            <div class="flex gap-2 no-print filter-tabs flex-wrap">
                                <!-- Tab Filter -->
                                <div class="bg-slate-100 p-1 rounded-lg flex text-xs font-bold">
                                    <button @click="period = 'harian'" :class="period === 'harian' ? 'bg-white text-slate-800 shadow-sm' : 'text-slate-500 hover:text-slate-700'" class="px-4 py-2 rounded-md transition-all">Harian</button>
                                    <button @click="period = 'mingguan'" :class="period === 'mingguan' ? 'bg-white text-slate-800 shadow-sm' : 'text-slate-500 hover:text-slate-700'" class="px-4 py-2 rounded-md transition-all">Mingguan</button>
                                    <button @click="period = 'bulanan'" :class="period === 'bulanan' ? 'bg-white text-slate-800 shadow-sm' : 'text-slate-500 hover:text-slate-700'" class="px-4 py-2 rounded-md transition-all">Bulanan</button>
                                </div>
                                
                                <!-- Tombol Cetak -->
                                <button onclick="window.print()" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg text-xs font-bold flex items-center gap-2 shadow-lg shadow-emerald-200 transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2z"></path></svg>
                                    <span class="hidden sm:inline">Cetak PDF</span>
                                </button>
                            </div>
                        </div>

                        <!-- Content Table (PERBAIKAN: Max Width Full untuk mencegah overflow) -->
                        <div class="overflow-x-auto w-full">
                            <table class="w-full text-sm text-left whitespace-nowrap md:whitespace-normal">
                                <thead class="text-xs text-slate-500 uppercase bg-slate-50 border-b border-slate-100">
                                    <tr>
                                        <th class="px-6 py-3 font-bold w-12 text-center">No</th>
                                        <th class="px-6 py-3 font-bold min-w-[150px]">Nama Siswa</th>
                                        <th class="px-6 py-3 font-bold w-32">Kelas</th>
                                        
                                        <!-- Harian Columns -->
                                        <template x-if="period === 'harian'">
                                            <th class="px-6 py-3 font-bold w-32 text-center">Status</th>
                                        </template>
                                        <template x-if="period === 'harian'">
                                            <th class="px-6 py-3 font-bold w-48 text-center">Jam Masuk</th>
                                        </template>

                                        <!-- Mingguan/Bulanan Columns -->
                                        <template x-if="period !== 'harian'">
                                            <th class="px-4 py-3 font-bold text-center w-16 bg-emerald-50 text-emerald-700">H</th>
                                        </template>
                                        <template x-if="period !== 'harian'">
                                            <th class="px-4 py-3 font-bold text-center w-16 bg-amber-50 text-amber-700">S</th>
                                        </template>
                                        <template x-if="period !== 'harian'">
                                            <th class="px-4 py-3 font-bold text-center w-16 bg-teal-50 text-teal-700">I</th>
                                        </template>
                                        <template x-if="period !== 'harian'">
                                            <th class="px-4 py-3 font-bold text-center w-16 bg-rose-50 text-rose-700">A</th>
                                        </template>
                                        <template x-if="period !== 'harian'">
                                            <th class="px-6 py-3 font-bold text-center w-24">% Hadir</th>
                                        </template>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @forelse($rekapAbsensi as $idx => $s)
                                    <tr class="hover:bg-slate-50/50">
                                        <td class="px-6 py-3 text-center text-slate-500">{{ $idx + 1 }}</td>
                                        <td class="px-6 py-3 font-bold text-slate-700">{{ $s->name }}</td>
                                        <td class="px-6 py-3 text-slate-600">{{ $s->kelas }}</td>
                                        
                                        <!-- Harian View -->
                                        <template x-if="period === 'harian'">
                                            <td class="px-6 py-3 text-center">
                                                <span class="px-2 py-1 rounded text-xs font-bold border"
                                                      :class="{
                                                        'bg-emerald-50 text-emerald-700 border-emerald-200': '{{ $s->status_harian }}' == 'Hadir',
                                                        'bg-amber-50 text-amber-700 border-amber-200': '{{ $s->status_harian }}' == 'Sakit',
                                                        'bg-teal-50 text-teal-700 border-teal-200': '{{ $s->status_harian }}' == 'Izin',
                                                        'bg-rose-50 text-rose-700 border-rose-200': '{{ $s->status_harian }}' == 'Alpa',
                                                        'bg-slate-100 text-slate-500 border-slate-200': '{{ $s->status_harian }}' == 'Belum'
                                                      }">
                                                    {{ $s->status_harian }}
                                                </span>
                                            </td>
                                        </template>
                                        <template x-if="period === 'harian'">
                                            <td class="px-6 py-3 text-center font-mono text-xs">{{ $s->jam_masuk }}</td>
                                        </template>

                                        <!-- Mingguan View (Data Mingguan) -->
                                        <template x-if="period === 'mingguan'">
                                            <td class="px-4 py-3 text-center font-bold text-emerald-600 bg-emerald-50/30">{{ $s->w_h }}</td>
                                        </template>
                                        <template x-if="period === 'mingguan'">
                                            <td class="px-4 py-3 text-center font-bold text-amber-600 bg-amber-50/30">{{ $s->w_s }}</td>
                                        </template>
                                        <template x-if="period === 'mingguan'">
                                            <td class="px-4 py-3 text-center font-bold text-teal-600 bg-teal-50/30">{{ $s->w_i }}</td>
                                        </template>
                                        <template x-if="period === 'mingguan'">
                                            <td class="px-4 py-3 text-center font-bold text-rose-600 bg-rose-50/30">{{ $s->w_a }}</td>
                                        </template>
                                        <template x-if="period === 'mingguan'">
                                            <td class="px-6 py-3 text-center font-bold text-slate-700">
                                                {{ ($s->w_total > 0) ? round(($s->w_h / $s->w_total) * 100) : 0 }}%
                                            </td>
                                        </template>

                                        <!-- Bulanan View (Data Bulanan) -->
                                        <template x-if="period === 'bulanan'">
                                            <td class="px-4 py-3 text-center font-bold text-emerald-600 bg-emerald-50/30">{{ $s->m_h }}</td>
                                        </template>
                                        <template x-if="period === 'bulanan'">
                                            <td class="px-4 py-3 text-center font-bold text-amber-600 bg-amber-50/30">{{ $s->m_s }}</td>
                                        </template>
                                        <template x-if="period === 'bulanan'">
                                            <td class="px-4 py-3 text-center font-bold text-teal-600 bg-teal-50/30">{{ $s->m_i }}</td>
                                        </template>
                                        <template x-if="period === 'bulanan'">
                                            <td class="px-4 py-3 text-center font-bold text-rose-600 bg-rose-50/30">{{ $s->m_a }}</td>
                                        </template>
                                        <template x-if="period === 'bulanan'">
                                            <td class="px-6 py-3 text-center font-bold text-slate-700">
                                                {{ ($s->m_total > 0) ? round(($s->m_h / $s->m_total) * 100) : 0 }}%
                                            </td>
                                        </template>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="8" class="px-6 py-8 text-center text-slate-400">Belum ada data siswa.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Tanda Tangan (Visible only on print) -->
                        <div class="print-only mt-12 px-6">
                            <div class="flex justify-end">
                                <div class="text-center">
                                    <p class="mb-1">Kota Belajar, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
                                    <p class="font-bold mb-16">Wali Kelas</p>
                                    <p class="font-bold underline">{{ Auth::user()->name }}</p>
                                    <p>NIP. -</p>
                                </div>
                            </div>
                        </div>
                    </div>

                @else
                    <!-- STUDENT VIEW (Tetap Sama) -->
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        <div class="lg:col-span-2 space-y-6">
                            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 flex flex-col items-center text-center">
                                <div class="w-24 h-24 bg-emerald-50 rounded-full flex items-center justify-center text-4xl mb-4 text-emerald-500 animate-bounce">
                                    {{ $jamMasukUser ? '😊' : '👋' }}
                                </div>
                                <h4 class="font-bold text-slate-800 text-xl">
                                    {{ $jamMasukUser ? 'Kamu Sudah Absen!' : 'Jangan Lupa Absen!' }}
                                </h4>
                                <p class="text-slate-500 mt-1 mb-6">
                                    {{ $jamMasukUser ? "Tercatat masuk pukul $jamMasukUser WIB" : 'Silakan scan QR Code di sekolah.' }}
                                </p>
                            </div>
                        </div>
                         <div class="space-y-6">
                            <div class="bg-gradient-to-br from-emerald-500 to-teal-600 rounded-2xl shadow-lg p-6 text-white">
                                <h4 class="font-bold text-lg mb-2">💡 Tips Belajar</h4>
                                <p class="text-emerald-50 text-sm leading-relaxed">
                                    "Konsistensi adalah kunci. Sedikit demi sedikit, lama-lama menjadi bukit."
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

            </main>
        </div>
    </div>

    <!-- SCRIPT CHART DYNAMIC -->
    @if(Auth::user()->role !== 'siswa')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('attendanceChart').getContext('2d');
            
            const labels = {!! json_encode($chartLabels ?? []) !!};
            const dataHadir = {!! json_encode($chartDataHadir ?? []) !!};
            const dataTidakHadir = {!! json_encode($chartDataTidakHadir ?? []) !!};

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Hadir',
                            data: dataHadir,
                            backgroundColor: '#10b981', 
                            borderRadius: 6,
                            barPercentage: 0.6
                        },
                        {
                            label: 'Tidak Hadir',
                            data: dataTidakHadir,
                            backgroundColor: '#f43f5e', 
                            borderRadius: 6,
                            barPercentage: 0.6
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: { 
                            beginAtZero: true, 
                            grid: { display: false },
                            ticks: { stepSize: 1 } 
                        },
                        x: { grid: { display: false } }
                    },
                    plugins: {
                        legend: { position: 'bottom', labels: { usePointStyle: true, padding: 20 } }
                    }
                }
            });
        });
    </script>
    @endif

</body>
</html>