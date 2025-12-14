<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>7 Kebiasaan Anak Hebat - SiKecil</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #F8FAFC; }
        
        /* Custom Checkbox Card Style */
        .check-card { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        .check-card:hover { transform: translateY(-2px); }
        
        .check-input:checked + .check-content {
            background-color: #F0F9FF; /* Sky 50 */
            border-color: #3B82F6; /* Blue 500 */
            box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.1), 0 2px 4px -1px rgba(59, 130, 246, 0.06);
        }
        
        .check-input:checked + .check-content .check-icon-bg {
            background-color: #DBEAFE; /* Blue 100 */
            color: #2563EB; /* Blue 600 */
        }

        .check-input:checked + .check-content .check-indicator {
            background-color: #2563EB;
            border-color: #2563EB;
        }
        
        .check-input:checked + .check-content .check-indicator svg {
            opacity: 1;
            transform: scale(1);
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800">

    <!-- Navbar Sederhana -->
    <nav class="bg-white border-b border-slate-200 sticky top-0 z-50">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center gap-2">
                    <div class="bg-blue-600 text-white p-1.5 rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <span class="font-bold text-lg text-slate-800">Anak Hebat</span>
                </div>
                <a href="{{ url('/dashboard') }}" class="text-sm font-medium text-slate-500 hover:text-blue-600 flex items-center gap-1 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Kembali
                </a>
            </div>
        </div>
    </nav>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- Header Section -->
        <div class="mb-10 text-center sm:text-left sm:flex sm:items-end sm:justify-between">
            <div>
                <h1 class="text-2xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">7 Kebiasaan Baik</h1>
                <p class="text-slate-500 mt-2 text-sm sm:text-base">Membangun karakter anak Indonesia yang hebat mulai dari rumah.</p>
            </div>
            
            <!-- PERBAIKAN 1: Form Ganti Tanggal (Date Picker) -->
            <div class="mt-4 sm:mt-0 text-right">
                <form action="{{ route('kebiasaan.index') }}" method="GET">
                    <label class="block text-xs font-bold text-slate-400 uppercase mb-1 text-right">Tanggal Pengisian</label>
                    <div class="inline-flex items-center px-4 py-2 bg-white text-blue-700 rounded-full text-sm font-semibold border border-blue-200 shadow-sm focus-within:ring-2 focus-within:ring-blue-500 hover:border-blue-300 transition-colors">
                        <span class="mr-2">📅</span>
                        <!-- Input Date dengan Auto Submit saat diganti -->
                        <input type="date" name="tanggal" 
                               value="{{ $tanggal ?? date('Y-m-d') }}" 
                               onchange="this.form.submit()"
                               class="bg-transparent border-none outline-none text-blue-700 font-bold p-0 focus:ring-0 cursor-pointer text-sm">
                    </div>
                </form>
            </div>
        </div>

        @if(session('success'))
        <div x-data="{ show: true }" x-show="show" class="mb-8 p-4 rounded-2xl bg-emerald-50 border border-emerald-100 flex items-start gap-4 shadow-sm animate-fade-in-down">
            <div class="flex-shrink-0 w-10 h-10 bg-emerald-100 rounded-full flex items-center justify-center text-emerald-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            </div>
            <div class="flex-1">
                <h3 class="font-bold text-emerald-800">Luar Biasa! Data Tersimpan 🎉</h3>
                <p class="text-sm text-emerald-600 mt-1">Data untuk tanggal <strong>{{ \Carbon\Carbon::parse($tanggal ?? now())->isoFormat('D MMMM Y') }}</strong> berhasil diperbarui.</p>
            </div>
            <button @click="show = false" class="text-emerald-400 hover:text-emerald-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
        </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- LEFT COLUMN: FORM -->
            <div class="lg:col-span-2">
                <form action="{{ route('kebiasaan.store') }}" method="POST">
                    @csrf
                    
                    <!-- PERBAIKAN 2: Hidden Input Tanggal -->
                    <!-- Ini wajib ada agar Controller tahu data ini untuk tanggal berapa -->
                    <input type="hidden" name="tanggal" value="{{ $tanggal ?? date('Y-m-d') }}">
                    
                    <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
                        <div class="p-6 sm:p-8 space-y-6">
                            
                            <h2 class="text-lg font-bold text-slate-800 flex items-center gap-2 mb-6">
                                <span class="w-2 h-6 bg-blue-500 rounded-full"></span>
                                Checklist: {{ \Carbon\Carbon::parse($tanggal ?? now())->locale('id')->isoFormat('dddd, D MMMM Y') }}
                            </h2>

                            <!-- Grid Item Kebiasaan -->
                            <div class="grid gap-4">
                                @php
                                    $items = [
                                        ['id' => 'k1', 'icon' => '🌅', 'title' => 'Bangun Pagi', 'desc' => 'Siap menyambut hari dengan semangat', 'color' => 'bg-orange-50 text-orange-600'],
                                        ['id' => 'k2', 'icon' => '🤲', 'title' => 'Beribadah', 'desc' => 'Menjalankan kewajiban tepat waktu', 'color' => 'bg-emerald-50 text-emerald-600'],
                                        ['id' => 'k3', 'icon' => '⚽', 'title' => 'Berolahraga', 'desc' => 'Gerak badan minimal 15 menit', 'color' => 'bg-blue-50 text-blue-600'],
                                        ['id' => 'k4', 'icon' => '🥗', 'title' => 'Makan Sehat', 'desc' => 'Makan sayur & buah, kurangi gula', 'color' => 'bg-green-50 text-green-600'],
                                        ['id' => 'k5', 'icon' => '📚', 'title' => 'Gemar Belajar', 'desc' => 'Membaca atau mengerjakan tugas', 'color' => 'bg-yellow-50 text-yellow-600'],
                                        ['id' => 'k6', 'icon' => '🤝', 'title' => 'Bermasyarakat', 'desc' => 'Membantu orang tua atau teman', 'color' => 'bg-purple-50 text-purple-600'],
                                        ['id' => 'k7', 'icon' => '🌙', 'title' => 'Tidur Cepat', 'desc' => 'Istirahat cukup (maks jam 21.00)', 'color' => 'bg-indigo-50 text-indigo-600'],
                                    ];
                                @endphp

                                @foreach($items as $item)
                                <label class="relative cursor-pointer group check-card">
                                    <input type="checkbox" name="{{ $item['id'] }}" id="{{ $item['id'] }}" 
                                           class="peer sr-only check-input" 
                                           {{ ($dataHariIni->{$item['id']} ?? false) ? 'checked' : '' }}>
                                    
                                    <div class="check-content p-4 rounded-2xl border-2 border-slate-100 bg-white flex items-center gap-4 group-hover:border-blue-200 transition-all">
                                        <!-- Icon -->
                                        <div class="check-icon-bg w-12 h-12 rounded-xl {{ $item['color'] }} flex items-center justify-center text-2xl transition-colors">
                                            {{ $item['icon'] }}
                                        </div>
                                        
                                        <!-- Text -->
                                        <div class="flex-1">
                                            <h3 class="font-bold text-slate-800 group-hover:text-blue-700 transition-colors">{{ $loop->iteration }}. {{ $item['title'] }}</h3>
                                            <p class="text-xs text-slate-500">{{ $item['desc'] }}</p>
                                        </div>

                                        <!-- Custom Checkbox UI -->
                                        <div class="check-indicator w-6 h-6 rounded-full border-2 border-slate-300 flex items-center justify-center transition-all bg-white">
                                            <svg class="w-3.5 h-3.5 text-white opacity-0 transform scale-50 transition-all duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                        </div>
                                    </div>
                                </label>
                                @endforeach
                            </div>

                            <!-- Catatan Section -->
                            <div class="pt-6 border-t border-slate-100">
                                <label class="block text-sm font-bold text-slate-700 mb-2">Catatan Ayah/Bunda <span class="text-slate-400 font-normal">(Opsional)</span></label>
                                <textarea name="catatan" class="w-full border-slate-200 bg-slate-50 rounded-xl p-4 text-sm focus:ring-2 focus:ring-blue-500 focus:bg-white focus:border-transparent transition-all" rows="3" placeholder="Ceritakan momen hebat ananda hari ini...">{{ $dataHariIni->catatan ?? '' }}</textarea>
                            </div>

                        </div>
                        
                        <!-- Footer Actions -->
                        <div class="p-6 bg-slate-50 border-t border-slate-200 sm:flex sm:items-center sm:justify-between gap-4">
                            <p class="text-xs text-slate-500 mb-4 sm:mb-0 text-center sm:text-left">
                                *Pastikan data diisi sesuai kondisi sebenarnya
                            </p>
                            <button type="submit" class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-xl shadow-lg shadow-blue-200 transform transition hover:-translate-y-0.5 active:scale-95 flex items-center justify-center gap-2">
                                <span>Simpan Data</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- RIGHT COLUMN: SIDEBAR -->
            <div class="lg:col-span-1 space-y-6">
                
                <!-- Quote Widget -->
                <div class="bg-gradient-to-br from-indigo-600 to-blue-600 rounded-3xl p-6 text-white shadow-xl shadow-blue-200 relative overflow-hidden group">
                    <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-white/10 rounded-full blur-xl group-hover:scale-150 transition-transform duration-700"></div>
                    <div class="relative z-10">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-10 h-10 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                            </div>
                            <h3 class="font-bold text-lg">Pesan Guru</h3>
                        </div>
                        <p class="text-blue-50 text-sm italic leading-relaxed">
                            "Membangun kebiasaan baik itu seperti menanam pohon. Sirami setiap hari dengan disiplin, kelak akan tumbuh menjadi karakter yang kuat."
                        </p>
                    </div>
                </div>

                <!-- Statistik Widget -->
                <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200">
                    <h3 class="font-bold text-slate-800 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 012 2h2a2 2 0 012-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                        Statistik Bulan Ini
                    </h3>
                    
                    <div class="flex items-end justify-between mb-2">
                        <span class="text-sm text-slate-500">Kedisiplinan</span>
                        <span class="text-2xl font-bold text-blue-600">{{ $persentase }}%</span>
                    </div>
                    
                    <div class="w-full bg-slate-100 rounded-full h-2.5 mb-4 overflow-hidden">
                        <div class="bg-blue-600 h-2.5 rounded-full transition-all duration-1000" style="width: {{ $persentase }}%"></div>
                    </div>
                    
                    <div class="flex items-center justify-between text-xs text-slate-500 bg-slate-50 p-3 rounded-xl">
                        <span>Sudah mengisi: <strong class="text-slate-800">{{ $jumlahDiisi }} hari</strong></span>
                        @if(count($hariBolong) > 0)
                            <span class="text-red-500">{{ count($hariBolong) }} hari kosong</span>
                        @else
                            <span class="text-emerald-500 font-bold">Sempurna!</span>
                        @endif
                    </div>
                </div>

                <!-- History Widget -->
                <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200">
                    <h3 class="font-bold text-slate-800 mb-4 text-sm uppercase tracking-wider text-slate-500">Riwayat Terakhir</h3>
                    <div class="space-y-4">
                        @forelse($riwayat as $r)
                        @php $score = $r->k1 + $r->k2 + $r->k3 + $r->k4 + $r->k5 + $r->k6 + $r->k7; @endphp
                        <div class="flex items-center justify-between group">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full {{ $score == 7 ? 'bg-yellow-50 text-yellow-600' : 'bg-slate-100 text-slate-500' }} flex items-center justify-center font-bold text-sm">
                                    {{ $score }}
                                </div>
                                <div>
                                    <p class="font-bold text-slate-700 text-sm">{{ \Carbon\Carbon::parse($r->tanggal)->locale('id')->isoFormat('dddd') }}</p>
                                    <p class="text-xs text-slate-400">{{ \Carbon\Carbon::parse($r->tanggal)->format('d M Y') }}</p>
                                </div>
                            </div>
                            @if($score == 7)
                                <span class="text-yellow-500 animate-pulse">⭐</span>
                            @endif
                        </div>
                        @empty
                        <div class="text-center py-4 text-slate-400 text-sm italic">Belum ada riwayat</div>
                        @endforelse
                    </div>
                </div>

            </div>
        </div>
    </div>
</body>
</html>