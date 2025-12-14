<nav 
    x-cloak
    class="fixed inset-y-0 left-0 w-72 h-screen flex-shrink-0 flex flex-col transition-all duration-300 ease-in-out z-50 md:static bg-slate-900 text-white shadow-2xl overflow-hidden border-r border-slate-800"
    :class="{'translate-x-0': sidebarOpen, '-translate-x-full md:translate-x-0': !sidebarOpen}">
    
    <!-- BRANDING AREA (Updated to Green) -->
    <div class="relative z-10 h-24 flex items-center px-6 border-b border-white/5 bg-slate-900">
        <div class="absolute inset-0 bg-gradient-to-b from-emerald-500/5 to-transparent pointer-events-none"></div>
        <a href="{{ route('dashboard') }}" class="flex items-center gap-4 group w-full relative z-10">
            <div class="relative">
                <div class="absolute inset-0 bg-emerald-500 blur-md opacity-20 group-hover:opacity-40 transition-opacity rounded-full"></div>
                <div class="bg-gradient-to-br from-emerald-500 to-teal-600 p-2.5 rounded-xl shadow-lg relative z-10 group-hover:scale-105 transition-transform duration-300 border border-white/10">
                    <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
            </div>
            <div class="flex flex-col">
                <span class="font-bold text-lg tracking-tight text-slate-100 font-['Plus_Jakarta_Sans']">SiKecil</span>
                <span class="text-[10px] text-emerald-400 font-medium tracking-wider uppercase">Sistem Informasi Kemajuan Edukasi & Capaian Individu Siswa</span>
            </div>
        </a>
    </div>

    <!-- NAVIGATION MENU -->
    <div class="flex-1 overflow-y-auto py-6 px-4 space-y-1 custom-scrollbar">
        @php
            $user = Auth::user();
            $role = strtolower(trim($user->role)); 

            // Definisi Menu Lengkap Berdasarkan Role
            $menuStructure = [
                // === MENU ADMIN / KEPSEK ===
                'admin' => [
                    'Utama' => [
                        ['name' => 'Dashboard Pusat', 'route' => 'dashboard', 'icon' => 'M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z'],
                    ],
                    'Administrasi' => [
                        ['name' => 'Data Master', 'route' => 'admin.dataMaster', 'active' => 'admin.dataMaster', 'icon' => 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10'],
                    ],
                    'Laporan & Rekap' => [
                        ['name' => 'Rekap Kehadiran', 'route' => 'presensi.rekap', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01'],
                        ['name' => 'Rekap 7 Kebiasaan', 'route' => 'kebiasaan.rekap', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                        ['name' => 'Jurnal Pembelajaran', 'route' => 'jurnal.index', 'active' => 'jurnal.*', 'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253'],
                        ['name' => 'Pantau Buku Hubung', 'route' => 'penghubung.index', 'active' => 'penghubung.*', 'icon' => 'M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z'],
                    ]
                ],

                // === MENU GURU ===
                'guru' => [
                    'Utama' => [
                        ['name' => 'Dashboard Guru', 'route' => 'dashboard', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
                    ],
                    'Absensi & Karakter' => [
                        ['name' => 'Scan Kehadiran', 'route' => 'presensi.scan', 'icon' => 'M12 4v1m6 11h2m-6 0h-2v4h2v-4zM6 8v4M6 20v-4M2 20h4M2 4h4M2 12h2m8 0h2M2 8v4M2 16h2M6 16h2M6 12h4m0-8h4m4 0h4M14 8h-2M10 8h2M10 4h2m4 0h2M18 8h2m0 4h2M18 16h2m-2 4h2M2 12v4m0 4v-4m10-4v4m2-4v4m4-4v4M6 4v4m12 0v4'],
                        ['name' => 'Absensi Manual', 'route' => 'presensi.create', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01'],
                        ['name' => 'Rekap Kebiasaan', 'route' => 'kebiasaan.rekap', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                    ],
                    'Akademik' => [
                        ['name' => 'Jurnal Mengajar', 'route' => 'jurnal.index', 'active' => 'jurnal.*', 'icon' => 'M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z'],
                        ['name' => 'Buku Penghubung', 'route' => 'penghubung.index', 'active' => 'penghubung.*', 'icon' => 'M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z'],
                    ]
                ],

                // === MENU SISWA / ORANG TUA ===
                'default' => [ 
                    'Menu Siswa' => [
                        ['name' => 'Rekap Kehadiran', 'route' => 'dashboard', 'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
                        ['name' => 'Input 7 Kebiasaan', 'route' => 'kebiasaan.index', 'icon' => 'M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z'],
                        ['name' => 'Buku Penghubung', 'route' => 'penghubung.index', 'active' => 'penghubung.*', 'icon' => 'M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z'],
                    ]
                ]
            ];

            // Tentukan menu berdasarkan role, jika tidak ada pakai default (siswa)
            $currentMenus = $menuStructure[$role] ?? $menuStructure['default'];
        @endphp

        <!-- Looping Menu -->
        @foreach($currentMenus as $section => $items)
            <div class="mb-6">
                <p class="px-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2 font-['Plus_Jakarta_Sans']">{{ $section }}</p>
                <div class="space-y-1">
                    @foreach($items as $item)
                        @php
                            $isActive = request()->routeIs($item['active'] ?? $item['route']);
                        @endphp
                        <a href="{{ route($item['route']) }}" 
                           class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 group relative
                                  {{ $isActive 
                                     ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-900/50' 
                                     : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                            
                            <svg class="w-5 h-5 mr-3 {{ $isActive ? 'text-emerald-200' : 'text-slate-500 group-hover:text-emerald-400' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"></path>
                            </svg>
                            <span class="text-sm font-medium">{{ $item['name'] }}</span>
                            
                            @if($isActive)
                                <div class="absolute right-3 w-1.5 h-1.5 rounded-full bg-emerald-300 shadow-[0_0_8px_rgba(110,231,183,0.8)]"></div>
                            @endif
                        </a>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>

    <!-- USER PROFILE FOOTER (Updated to Green/Teal) -->
    <div class="p-4 border-t border-white/5 bg-slate-900/50 backdrop-blur-sm">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-9 h-9 rounded-full bg-gradient-to-tr from-emerald-500 to-teal-500 flex items-center justify-center text-sm font-bold text-white shadow-inner ring-1 ring-white/10">
                {{ substr($user->name, 0, 1) }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-bold text-white truncate font-['Plus_Jakarta_Sans']">{{ $user->name }}</p>
                <p class="text-xs text-slate-500 truncate capitalize">{{ $role }}</p>
            </div>
        </div>
        
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="w-full flex items-center justify-center px-4 py-2 rounded-lg text-xs font-bold tracking-wide text-rose-400 bg-rose-500/10 hover:bg-rose-500/20 hover:text-rose-300 transition-all border border-rose-500/10 hover:border-rose-500/30">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                KELUAR APLIKASI
            </button>
        </form>
    </div>
</nav>