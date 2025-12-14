<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Input Absensi - SiKecil</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #F8FAFC; }
        [x-cloak] { display: none !important; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>

<body class="bg-slate-50 text-slate-800 antialiased" 
      x-data="{ 
          sidebarOpen: false, 
          search: '',
          markAllPresent() {
              document.querySelectorAll('input[value=\'Hadir\']').forEach(el => {
                  el.checked = true;
                  el.dispatchEvent(new Event('change')); // Trigger update stats
              });
              this.calculateStats();
          },
          stats: { h: 0, s: 0, i: 0, a: 0 },
          calculateStats() {
              this.stats.h = document.querySelectorAll('input[value=\'Hadir\']:checked').length;
              this.stats.s = document.querySelectorAll('input[value=\'Sakit\']:checked').length;
              this.stats.i = document.querySelectorAll('input[value=\'Izin\']:checked').length;
              this.stats.a = document.querySelectorAll('input[value=\'Alpa\']:checked').length;
          },
          init() {
              this.calculateStats();
          }
      }">

    <div class="flex h-screen overflow-hidden">
        
        <!-- 1. SIDEBAR -->
        @include('components.sidebar')

        <!-- Mobile Overlay -->
        <div x-show="sidebarOpen" 
             @click="sidebarOpen = false"
             x-transition.opacity
             class="fixed inset-0 bg-slate-900/50 z-40 md:hidden" x-cloak>
        </div>

        <!-- 2. MAIN CONTENT WRAPPER -->
        <div class="flex-1 flex flex-col h-screen overflow-hidden relative w-full">
            
            <!-- Navbar / Header -->
            <nav class="bg-white border-b border-slate-200 sticky top-0 z-30 shrink-0">
                <div class="px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16 items-center">
                        <div class="flex items-center gap-3">
                            <button @click="sidebarOpen = !sidebarOpen" class="md:hidden p-2 -ml-2 text-slate-500 hover:text-emerald-600 rounded-lg hover:bg-slate-50 transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                            </button>
                            <div class="bg-emerald-600 text-white p-2 rounded-xl shadow-lg shadow-emerald-200 hidden sm:block">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                            </div>
                            <div>
                                <h1 class="font-bold text-lg text-slate-800 leading-tight">Absensi Harian</h1>
                                <p class="text-[10px] text-slate-500 font-medium tracking-wide uppercase hidden sm:block">Pencatatan Kehadiran Siswa</p>
                            </div>
                        </div>

                        <!-- Summary Badge (Desktop) -->
                        <div class="hidden md:flex items-center gap-3 bg-slate-50 px-4 py-1.5 rounded-full border border-slate-200">
                            <div class="flex items-center gap-1 text-xs font-bold text-emerald-600"><span class="w-2 h-2 rounded-full bg-emerald-500"></span> <span x-text="stats.h">0</span> Hadir</div>
                            <div class="w-px h-4 bg-slate-300"></div>
                            <div class="flex items-center gap-1 text-xs font-bold text-amber-500"><span class="w-2 h-2 rounded-full bg-amber-500"></span> <span x-text="stats.s">0</span> Sakit</div>
                            <div class="w-px h-4 bg-slate-300"></div>
                            <div class="flex items-center gap-1 text-xs font-bold text-teal-500"><span class="w-2 h-2 rounded-full bg-teal-500"></span> <span x-text="stats.i">0</span> Izin</div>
                            <div class="w-px h-4 bg-slate-300"></div>
                            <div class="flex items-center gap-1 text-xs font-bold text-rose-500"><span class="w-2 h-2 rounded-full bg-rose-500"></span> <span x-text="stats.a">0</span> Alpa</div>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Scrollable Content -->
            <main class="flex-1 overflow-y-auto bg-slate-50 p-4 sm:p-6 lg:p-8 pb-32">
                <div class="max-w-5xl mx-auto">

                    <!-- Info Card & Controls -->
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5 mb-6 flex flex-col md:flex-row justify-between items-center gap-4 sticky top-0 z-20 md:static">
                        <div class="flex items-center gap-4 w-full md:w-auto">
                            <div class="p-3 bg-emerald-50 text-emerald-600 rounded-xl hidden sm:block">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Tanggal Absensi</p>
                                <h2 class="text-lg font-extrabold text-slate-800">{{ $hariIni }}</h2>
                            </div>
                        </div>
                        
                        <!-- Actions -->
                        <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
                            <!-- Search -->
                            <div class="relative flex-1 sm:w-64">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                </span>
                                <input x-model="search" type="text" placeholder="Cari siswa..." class="w-full bg-slate-50 border border-slate-200 text-sm rounded-xl py-2.5 pl-9 pr-4 focus:ring-2 focus:ring-emerald-500 outline-none transition-all">
                            </div>

                            <!-- Mark All Button -->
                            <button @click="markAllPresent()" type="button" class="bg-emerald-100 text-emerald-700 hover:bg-emerald-200 px-4 py-2.5 rounded-xl text-sm font-bold transition-colors flex items-center justify-center gap-2 whitespace-nowrap active:scale-95">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Semua Hadir
                            </button>
                        </div>
                    </div>

                    <!-- Form Absensi -->
                    <form action="{{ route('presensi.store') }}" method="POST">
                        @csrf
                        
                        <div class="space-y-4">
                            @forelse($siswa as $s)
                            <div class="bg-white rounded-2xl border border-slate-200 p-4 flex flex-col lg:flex-row items-center justify-between transition-all hover:shadow-md hover:border-emerald-200 group"
                                 x-show="search === '' || '{{ strtolower($s->name) }}'.includes(search.toLowerCase())"
                                 x-transition>
                                
                                <!-- Info Siswa -->
                                <div class="flex items-center w-full lg:w-1/3 mb-4 lg:mb-0 gap-4">
                                    <div class="h-12 w-12 rounded-full bg-slate-100 text-slate-500 flex items-center justify-center text-lg font-bold group-hover:bg-emerald-50 group-hover:text-emerald-600 transition-colors shrink-0">
                                        {{ substr($s->name, 0, 1) }}
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-bold text-slate-800 text-sm sm:text-base truncate">{{ $s->name }}</p>
                                        <p class="text-xs text-slate-400 font-mono bg-slate-50 px-2 py-0.5 rounded w-fit mt-1">{{ $s->nip_nis }}</p>
                                    </div>
                                </div>

                                <!-- Pilihan Status -->
                                <div class="w-full lg:w-2/3 flex justify-between gap-2 sm:gap-3 overflow-x-auto no-scrollbar pb-1 lg:pb-0">
                                    <!-- Hadir -->
                                    <label class="cursor-pointer flex-1 min-w-[70px]">
                                        <input type="radio" name="presensi[{{ $s->id }}]" value="Hadir" class="peer sr-only" checked @change="calculateStats()">
                                        <div class="flex flex-col items-center justify-center py-2.5 rounded-xl border-2 border-slate-100 text-slate-400 bg-white peer-checked:bg-emerald-50 peer-checked:text-emerald-600 peer-checked:border-emerald-500 transition-all hover:bg-slate-50">
                                            <span class="font-extrabold text-sm sm:text-lg mb-0.5">H</span>
                                            <span class="text-[10px] font-bold uppercase tracking-wide">Hadir</span>
                                        </div>
                                    </label>

                                    <!-- Sakit -->
                                    <label class="cursor-pointer flex-1 min-w-[70px]">
                                        <input type="radio" name="presensi[{{ $s->id }}]" value="Sakit" class="peer sr-only" @change="calculateStats()">
                                        <div class="flex flex-col items-center justify-center py-2.5 rounded-xl border-2 border-slate-100 text-slate-400 bg-white peer-checked:bg-amber-50 peer-checked:text-amber-600 peer-checked:border-amber-500 transition-all hover:bg-slate-50">
                                            <span class="font-extrabold text-sm sm:text-lg mb-0.5">S</span>
                                            <span class="text-[10px] font-bold uppercase tracking-wide">Sakit</span>
                                        </div>
                                    </label>

                                    <!-- Izin -->
                                    <label class="cursor-pointer flex-1 min-w-[70px]">
                                        <input type="radio" name="presensi[{{ $s->id }}]" value="Izin" class="peer sr-only" @change="calculateStats()">
                                        <div class="flex flex-col items-center justify-center py-2.5 rounded-xl border-2 border-slate-100 text-slate-400 bg-white peer-checked:bg-teal-50 peer-checked:text-teal-600 peer-checked:border-teal-500 transition-all hover:bg-slate-50">
                                            <span class="font-extrabold text-sm sm:text-lg mb-0.5">I</span>
                                            <span class="text-[10px] font-bold uppercase tracking-wide">Izin</span>
                                        </div>
                                    </label>

                                    <!-- Alpa -->
                                    <label class="cursor-pointer flex-1 min-w-[70px]">
                                        <input type="radio" name="presensi[{{ $s->id }}]" value="Alpa" class="peer sr-only" @change="calculateStats()">
                                        <div class="flex flex-col items-center justify-center py-2.5 rounded-xl border-2 border-slate-100 text-slate-400 bg-white peer-checked:bg-rose-50 peer-checked:text-rose-600 peer-checked:border-rose-500 transition-all hover:bg-slate-50">
                                            <span class="font-extrabold text-sm sm:text-lg mb-0.5">A</span>
                                            <span class="text-[10px] font-bold uppercase tracking-wide">Alpa</span>
                                        </div>
                                    </label>
                                </div>
                            </div>
                            @empty
                            <div class="text-center py-12 bg-white rounded-2xl border border-dashed border-slate-300">
                                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-3 text-2xl">🎓</div>
                                <p class="text-slate-500 font-medium">Belum ada data siswa di kelas ini.</p>
                            </div>
                            @endforelse
                        </div>

                        <!-- Sticky Footer Stats (Mobile Only) & Submit -->
                        <div class="fixed bottom-0 left-0 right-0 lg:absolute lg:bottom-0 lg:left-0 lg:w-full bg-white/90 backdrop-blur-lg border-t border-slate-200 p-4 shadow-[0_-4px_10px_rgba(0,0,0,0.05)] z-30">
                            <div class="max-w-5xl mx-auto flex flex-col md:flex-row items-center gap-4">
                                
                                <!-- Mobile Stats Summary -->
                                <div class="flex md:hidden w-full justify-between px-2 text-[10px] font-bold text-slate-500 uppercase tracking-wide mb-1">
                                    <span class="text-emerald-600">H: <span x-text="stats.h"></span></span>
                                    <span class="text-amber-500">S: <span x-text="stats.s"></span></span>
                                    <span class="text-teal-500">I: <span x-text="stats.i"></span></span>
                                    <span class="text-rose-500">A: <span x-text="stats.a"></span></span>
                                </div>

                                <button type="submit" class="w-full bg-slate-900 hover:bg-slate-800 text-white font-bold py-3.5 rounded-xl shadow-lg shadow-slate-300 transition-all transform hover:-translate-y-0.5 active:scale-95 flex items-center justify-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                                    SIMPAN DATA ABSENSI
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </main>
        </div>
    </div>
</body>
</html>