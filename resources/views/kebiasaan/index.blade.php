<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>7 Kebiasaan Anak Hebat - SiKecil</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #F8FAFC; }
        [x-cloak] { display: none !important; }

        /* Smooth Transition */
        .check-card { transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1); }
        .check-card:active { transform: scale(0.98); }
        
        /* State Checked Logic */
        .check-input:checked + .check-content {
            border-color: var(--theme-color);
            background-color: var(--bg-color);
            box-shadow: 0 0 0 1px var(--theme-color);
        }
        
        .check-input:checked + .check-content .icon-box {
            background-color: var(--theme-color);
            color: white;
            transform: scale(1.1) rotate(-3deg);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .check-input:checked + .check-content .check-badge {
            opacity: 1;
            transform: scale(1);
        }

        /* Progress Bar Animation */
        .progress-bar-fill { transition: width 0.5s cubic-bezier(0.4, 0, 0.2, 1); }
    </style>
</head>

<body class="bg-slate-50 text-slate-800 antialiased pb-24 md:pb-0" 
      x-data="{ 
          sidebarOpen: false,
          totalChecked: 0,
          totalItems: 7,
          progress: 0,
          updateProgress() {
              this.totalChecked = document.querySelectorAll('.check-input:checked').length;
              this.progress = (this.totalChecked / this.totalItems) * 100;
          },
          init() {
              this.updateProgress();
          }
      }">

    <div class="flex h-screen overflow-hidden">
        
        <!-- 1. SIDEBAR (Navigasi Kiri) -->
        @include('components.sidebar')

        <!-- Mobile Overlay -->
        <div x-show="sidebarOpen" 
             @click="sidebarOpen = false"
             x-transition.opacity
             class="fixed inset-0 bg-slate-900/50 z-40 md:hidden" x-cloak>
        </div>

        <!-- 2. MAIN CONTENT WRAPPER -->
        <div class="flex-1 flex flex-col h-screen overflow-hidden relative w-full">
            
            <!-- Navbar Mobile & Desktop -->
            <nav class="bg-white border-b border-slate-200 sticky top-0 z-30 shrink-0">
                <div class="px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16 items-center">
                        <div class="flex items-center gap-3">
                            <button @click="sidebarOpen = !sidebarOpen" class="md:hidden p-2 -ml-2 text-slate-500 hover:text-blue-600 rounded-lg hover:bg-slate-50 transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                            </button>
                            <div class="hidden sm:block bg-blue-600 text-white p-1.5 rounded-lg shadow-md shadow-blue-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <h1 class="font-bold text-lg text-slate-800 tracking-tight">Ceklis Kebiasaan</h1>
                        </div>
                        
                        <!-- User Info Compact -->
                        <div class="flex items-center gap-2">
                            <div class="text-right hidden sm:block">
                                <p class="text-xs font-bold text-slate-700">{{ Auth::user()->name }}</p>
                                <p class="text-[10px] text-slate-500">Siswa / Ortu</p>
                            </div>
                            <div class="w-8 h-8 rounded-full bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-600 font-bold text-xs">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Scrollable Content Area -->
            <main class="flex-1 overflow-y-auto bg-slate-50 p-4 sm:p-6 lg:p-8" id="mainContent">
                <div class="max-w-7xl mx-auto pb-20">
                    
                    <!-- Progress Header Card (Full Width) -->
                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 mb-8 relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-blue-50 rounded-full blur-3xl -mr-16 -mt-16"></div>
                        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                            <div class="flex-1">
                                <div class="flex items-center justify-between mb-2">
                                    <h2 class="text-lg font-bold text-slate-800">Progres Hari Ini</h2>
                                    <span class="text-sm font-bold text-blue-600 bg-blue-50 px-3 py-1 rounded-full" x-text="`${totalChecked} / ${totalItems} Selesai`">0/7 Selesai</span>
                                </div>
                                <div class="w-full bg-slate-100 rounded-full h-3 overflow-hidden">
                                    <div class="bg-gradient-to-r from-blue-500 to-indigo-500 h-full rounded-full progress-bar-fill relative" :style="`width: ${progress}%`">
                                        <div class="absolute inset-0 bg-white/20 animate-pulse"></div>
                                    </div>
                                </div>
                                <p class="text-xs text-slate-500 mt-2" x-show="progress < 100">Ayo lengkapi kebiasaan baikmu hari ini!</p>
                                <p class="text-xs text-emerald-600 mt-2 font-bold flex items-center gap-1" x-show="progress === 100" x-cloak>
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    Luar biasa! Semua target tercapai.
                                </p>
                            </div>

                            <!-- Date Picker -->
                            <div class="shrink-0 w-full md:w-auto">
                                <form action="{{ route('kebiasaan.index') }}" method="GET" class="relative">
                                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Tanggal</label>
                                    <div class="relative">
                                        <input type="date" name="tanggal" 
                                               value="{{ $tanggal ?? date('Y-m-d') }}" 
                                               onchange="this.form.submit()"
                                               class="w-full md:w-48 pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-700 font-bold rounded-xl focus:ring-2 focus:ring-blue-500 focus:bg-white outline-none cursor-pointer text-sm transition-all hover:bg-slate-100">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Flash Message -->
                    @if(session('success'))
                    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-100 flex items-center gap-3 shadow-sm animate-bounce-in">
                        <div class="w-8 h-8 bg-emerald-100 rounded-full flex items-center justify-center text-emerald-600 shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-emerald-900 text-sm">Berhasil Disimpan!</h3>
                        </div>
                    </div>
                    @endif

                    <!-- TWO COLUMN LAYOUT -->
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                        
                        <!-- LEFT COLUMN: FORM (8 Cols) -->
                        <div class="lg:col-span-8 w-full">
                            <form action="{{ route('kebiasaan.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="tanggal" value="{{ $tanggal ?? date('Y-m-d') }}">

                                <!-- Main Grid Checklist -->
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    @php
                                        $items = [
                                            ['id' => 'k1', 'icon' => 'M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z', 'title' => 'Bangun Pagi', 'desc' => 'Bangun sendiri, semangat!', 'color' => '#F59E0B', 'bg' => '#FFFBEB'],
                                            ['id' => 'k2', 'icon' => 'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z', 'title' => 'Beribadah', 'desc' => 'Sholat/doa tepat waktu', 'color' => '#10B981', 'bg' => '#ECFDF5'],
                                            ['id' => 'k3', 'icon' => 'M13 10V3L4 14h7v7l9-11h-7z', 'title' => 'Berolahraga', 'desc' => 'Gerak badan min 15 mnt', 'color' => '#3B82F6', 'bg' => '#EFF6FF'],
                                            ['id' => 'k4', 'icon' => 'M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z', 'title' => 'Makan Sehat', 'desc' => 'Sayur, buah & air putih', 'color' => '#84CC16', 'bg' => '#F7FEE7'],
                                            ['id' => 'k5', 'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253', 'title' => 'Gemar Belajar', 'desc' => 'Baca buku / kerjakan PR', 'color' => '#EAB308', 'bg' => '#FEFCE8'],
                                            ['id' => 'k6', 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z', 'title' => 'Bermasyarakat', 'desc' => 'Bantu orang tua/teman', 'color' => '#8B5CF6', 'bg' => '#F5F3FF'],
                                            ['id' => 'k7', 'icon' => 'M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z', 'title' => 'Tidur Cepat', 'desc' => 'Istirahat maks jam 21.00', 'color' => '#6366F1', 'bg' => '#EEF2FF'],
                                        ];
                                    @endphp

                                    @foreach($items as $item)
                                    <label class="relative cursor-pointer group check-card block h-28">
                                        <input type="checkbox" name="{{ $item['id'] }}" id="{{ $item['id'] }}" 
                                               class="peer sr-only check-input" 
                                               @change="updateProgress()"
                                               {{ ($dataHariIni->{$item['id']} ?? false) ? 'checked' : '' }}>
                                        
                                        <div class="check-content w-full h-full p-4 rounded-2xl border border-slate-200 bg-white flex flex-row items-center gap-4 hover:shadow-md transition-all relative overflow-hidden"
                                             style="--theme-color: {{ $item['color'] }}; --bg-color: {{ $item['bg'] }};">
                                            
                                            <div class="icon-box w-12 h-12 rounded-xl flex items-center justify-center text-2xl transition-all duration-300 shrink-0"
                                                 style="background-color: {{ $item['bg'] }}; color: {{ $item['color'] }};">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"></path>
                                                </svg>
                                            </div>
                                            
                                            <div class="flex-1 min-w-0 z-10">
                                                <h3 class="font-bold text-slate-800 text-sm leading-tight mb-0.5">{{ $item['title'] }}</h3>
                                                <p class="text-[11px] text-slate-500 line-clamp-2 leading-snug">{{ $item['desc'] }}</p>
                                            </div>

                                            <div class="check-badge absolute top-3 right-3 text-emerald-500 opacity-0 transition-all duration-300">
                                                <div class="w-5 h-5 bg-emerald-500 text-white rounded-full flex items-center justify-center shadow-sm">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                                </div>
                                            </div>
                                        </div>
                                    </label>
                                    @endforeach
                                    
                                    <!-- DESKTOP SAVE BUTTON (Kotak Terakhir di Grid) -->
                                    <div class="hidden sm:block h-28">
                                        <button type="submit" class="w-full h-full rounded-2xl bg-slate-900 hover:bg-slate-800 text-white flex flex-col items-center justify-center gap-2 shadow-xl hover:shadow-2xl transition-all transform hover:scale-[1.02] active:scale-95 group border border-slate-700">
                                            <div class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center group-hover:bg-white/20 transition-colors">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                                            </div>
                                            <span class="font-bold text-sm">Simpan Data</span>
                                        </button>
                                    </div>
                                </div>

                                <!-- Notes Section -->
                                <div class="mt-6 bg-white rounded-2xl p-5 border border-slate-200 shadow-sm">
                                    <label class="flex items-center gap-2 text-sm font-bold text-slate-700 mb-2">
                                        <span class="bg-blue-100 text-blue-600 p-1 rounded-md"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg></span>
                                        Catatan Tambahan <span class="text-slate-400 font-normal text-xs ml-auto">(Opsional)</span>
                                    </label>
                                    <textarea name="catatan" rows="2" class="w-full bg-slate-50 border-slate-200 rounded-xl p-3 text-sm focus:ring-2 focus:ring-blue-500 focus:bg-white focus:border-transparent transition-all placeholder-slate-400 resize-none" placeholder="Tulis catatan untuk wali kelas...">{{ $dataHariIni->catatan ?? '' }}</textarea>
                                </div>
                                
                                <!-- MOBILE STICKY BUTTON -->
                                <div class="fixed bottom-0 left-0 right-0 p-4 bg-white/80 backdrop-blur-md border-t border-slate-200 sm:hidden z-20 flex items-center gap-3">
                                    <div class="flex-1">
                                        <p class="text-[10px] text-slate-500 font-bold uppercase tracking-wider">Progres</p>
                                        <div class="flex items-baseline gap-1">
                                            <span class="text-lg font-bold text-blue-600" x-text="totalChecked">0</span>
                                            <span class="text-xs text-slate-400">/ 7 Selesai</span>
                                        </div>
                                    </div>
                                    <button type="submit" class="bg-slate-900 text-white px-6 py-3 rounded-xl font-bold text-sm shadow-lg shadow-slate-300 active:scale-95 transition-transform flex items-center gap-2">
                                        <span>Simpan</span>
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- RIGHT COLUMN: SIDEBAR (Widgets) -->
                        <div class="lg:col-span-4 space-y-6">
                            
                            <!-- 1. KATA MUTIARA WIDGET (Restored) -->
                            <div class="relative bg-gradient-to-br from-indigo-600 to-violet-600 rounded-3xl p-8 text-white shadow-xl overflow-hidden group">
                                <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full blur-2xl -mr-10 -mt-10 group-hover:scale-110 transition-transform duration-700"></div>
                                <div class="relative z-10">
                                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 text-[10px] font-bold uppercase tracking-wider mb-4 border border-white/10">
                                        ✨ Kata Mutiara
                                    </div>
                                    <p class="text-lg font-serif italic leading-relaxed text-indigo-50 mb-4">
                                        "Membangun kebiasaan baik itu seperti menanam pohon. Sirami setiap hari dengan disiplin, kelak akan tumbuh menjadi karakter yang kuat."
                                    </p>
                                    <div class="flex items-center gap-3 border-t border-white/10 pt-4 mt-2">
                                        <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center text-xs font-bold">G</div>
                                        <span class="text-xs font-medium text-indigo-100">Pesan Wali Kelas</span>
                                    </div>
                                </div>
                            </div>

                            <!-- 2. STATISTIK BULANAN WIDGET -->
                            <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200">
                                <h3 class="font-bold text-slate-800 mb-6 flex items-center justify-between">
                                    <span>Statistik Bulan Ini</span>
                                    <span class="text-xs font-normal text-slate-500 bg-slate-100 px-2 py-1 rounded-lg">{{ date('F Y') }}</span>
                                </h3>
                                
                                <!-- Donut Chart Visual -->
                                <div class="flex justify-center mb-6">
                                    <div class="relative w-32 h-32">
                                        <svg class="w-full h-full transform -rotate-90">
                                            <circle cx="64" cy="64" r="56" stroke="currentColor" stroke-width="12" fill="none" class="text-slate-100" />
                                            <circle cx="64" cy="64" r="56" stroke="currentColor" stroke-width="12" fill="none" class="text-blue-600 transition-all duration-1000 ease-out" stroke-dasharray="351.86" stroke-dashoffset="{{ 351.86 - (351.86 * $persentase / 100) }}" stroke-linecap="round" />
                                        </svg>
                                        <div class="absolute inset-0 flex flex-col items-center justify-center text-slate-800">
                                            <span class="text-3xl font-bold">{{ $persentase }}%</span>
                                            <span class="text-[10px] text-slate-400 uppercase font-bold">Disiplin</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="grid grid-cols-2 gap-3 text-center">
                                    <div class="bg-emerald-50 p-3 rounded-2xl border border-emerald-100">
                                        <span class="block text-2xl font-bold text-emerald-600">{{ $jumlahDiisi }}</span>
                                        <span class="text-[10px] text-emerald-600/70 font-bold uppercase">Mengisi</span>
                                    </div>
                                    <div class="bg-rose-50 p-3 rounded-2xl border border-rose-100">
                                        <span class="block text-2xl font-bold text-rose-500">{{ count($hariBolong) }}</span>
                                        <span class="text-[10px] text-rose-500/70 font-bold uppercase">Kosong</span>
                                    </div>
                                </div>
                            </div>

                        </div> <!-- End Right Column -->

                    </div> <!-- End Grid -->
                </div>
            </main>
        </div>
    </div>
</body>
</html>