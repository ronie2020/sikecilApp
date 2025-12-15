<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SiKecil - Sistem Informasi Sekolah Dasar</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .gradient-text {
            background: linear-gradient(to right, #059669, #0d9488);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .blob {
            position: absolute;
            filter: blur(40px);
            z-index: -1;
            opacity: 0.4;
            animation: float 10s infinite ease-in-out;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-20px); }
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 overflow-x-hidden">

    <!-- NAVBAR -->
    <nav class="fixed w-full z-50 bg-white/80 backdrop-blur-md border-b border-slate-100 transition-all duration-300" id="navbar">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <div class="flex items-center gap-2">
                    <div class="bg-gradient-to-br from-emerald-500 to-teal-600 p-2 rounded-xl shadow-lg shadow-emerald-200">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path></svg>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-xl font-bold tracking-tight text-slate-900 leading-none">SiKecil</span>
                        <span class="text-[10px] font-semibold text-emerald-600 tracking-wider uppercase">Sistem Informasi Sekolah</span>
                    </div>
                </div>

                <div class="hidden md:flex space-x-8 items-center">
                    <a href="#fitur" class="text-sm font-medium text-slate-600 hover:text-emerald-600 transition-colors">Fitur Unggulan</a>
                    <a href="#ekskul" class="text-sm font-medium text-slate-600 hover:text-emerald-600 transition-colors">Ekstrakurikuler</a>
                    <a href="#kontak" class="text-sm font-medium text-slate-600 hover:text-emerald-600 transition-colors">Hubungi Kami</a>
                </div>

                <div class="flex items-center gap-4">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ route('dashboard') }}" class="bg-emerald-600 text-white px-5 py-2.5 rounded-full text-sm font-bold shadow-lg shadow-emerald-200 hover:bg-emerald-700 transition-all transform hover:-translate-y-0.5">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="bg-emerald-600 text-white px-6 py-2.5 rounded-full text-sm font-bold shadow-lg shadow-emerald-200 hover:bg-emerald-700 transition-all transform hover:-translate-y-0.5 flex items-center gap-2">Masuk Sistem</a>
                        @endauth
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <!-- HERO SECTION -->
    <section class="relative pt-32 pb-20 lg:pt-48 lg:pb-32 overflow-hidden">
        <div class="blob bg-emerald-200 w-96 h-96 rounded-full top-0 left-0 -translate-x-1/2 -translate-y-1/2"></div>
        <div class="blob bg-teal-200 w-80 h-80 rounded-full bottom-0 right-0 translate-x-1/3 translate-y-1/3 animation-delay-2000"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
            <div data-aos="fade-up" data-aos-duration="1000">
                <span class="inline-flex items-center gap-2 py-1 px-3 rounded-full bg-emerald-50 border border-emerald-100 text-emerald-600 text-xs font-bold tracking-wide uppercase mb-6">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    Sekolah Dasar Berbasis Karakter
                </span>
                <h1 class="text-4xl md:text-6xl lg:text-7xl font-extrabold text-slate-900 tracking-tight leading-tight mb-6">
                    Mencetak Generasi Cerdas <br>
                    <span class="gradient-text">& Berbudi Pekerti Luhur</span>
                </h1>
                <p class="text-lg md:text-xl text-slate-500 max-w-2xl mx-auto mb-10 leading-relaxed">
                    Platform monitoring akademik dan karakter siswa yang terintegrasi, memudahkan orang tua memantau perkembangan anak secara real-time.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="#fitur" class="px-8 py-4 bg-emerald-600 text-white rounded-full font-bold shadow-lg shadow-emerald-200 hover:bg-emerald-700 transition-all hover:-translate-y-1">Pelajari Lebih Lanjut</a>
                    <a href="#kontak" class="px-8 py-4 bg-white text-slate-700 border border-slate-200 rounded-full font-bold hover:bg-slate-50 transition-all hover:-translate-y-1">Hubungi Kami</a>
                </div>
            </div>
        </div>
    </section>

    <!-- FITUR UNGGULAN (Baru ditambahkan kembali) -->
    <section id="fitur" class="py-24 bg-white relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16" data-aos="fade-up">
                <h2 class="text-emerald-600 font-bold tracking-wide uppercase text-sm mb-3">Kenapa Memilih Kami?</h2>
                <h3 class="text-3xl md:text-4xl font-extrabold text-slate-900 mb-4">Fitur Unggulan SiKecil</h3>
                <p class="text-slate-500 text-lg">Mendukung sinergi antara guru dan orang tua dalam memantau tumbuh kembang anak.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Fitur 1 -->
                <div class="bg-slate-50 rounded-3xl p-8 border border-slate-100 hover:shadow-xl transition-all duration-300 group" data-aos="fade-up" data-aos-delay="100">
                    <div class="w-14 h-14 bg-emerald-100 rounded-2xl flex items-center justify-center text-emerald-600 mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                    </div>
                    <h4 class="text-xl font-bold text-slate-900 mb-3">Presensi Digital</h4>
                    <p class="text-slate-500 leading-relaxed">Monitoring kehadiran siswa secara realtime dengan laporan yang akurat dan mudah diakses orang tua.</p>
                </div>

                <!-- Fitur 2 -->
                <div class="bg-slate-50 rounded-3xl p-8 border border-slate-100 hover:shadow-xl transition-all duration-300 group" data-aos="fade-up" data-aos-delay="200">
                    <div class="w-14 h-14 bg-blue-100 rounded-2xl flex items-center justify-center text-blue-600 mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h4 class="text-xl font-bold text-slate-900 mb-3">Monitoring Karakter</h4>
                    <p class="text-slate-500 leading-relaxed">Pantau perkembangan 7 kebiasaan efektif anak dan catatan perilaku harian di sekolah.</p>
                </div>

                <!-- Fitur 3 -->
                <div class="bg-slate-50 rounded-3xl p-8 border border-slate-100 hover:shadow-xl transition-all duration-300 group" data-aos="fade-up" data-aos-delay="300">
                    <div class="w-14 h-14 bg-amber-100 rounded-2xl flex items-center justify-center text-amber-600 mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    </div>
                    <h4 class="text-xl font-bold text-slate-900 mb-3">Jurnal Penghubung</h4>
                    <p class="text-slate-500 leading-relaxed">Komunikasi dua arah antara guru dan wali murid menjadi lebih mudah melalui buku penghubung digital.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION EKSKUL DINAMIS -->
    <section id="ekskul" class="py-24 bg-slate-50 relative overflow-hidden">
        <div class="blob bg-purple-100 w-96 h-96 rounded-full bottom-0 left-0 -translate-x-1/2 translate-y-1/2 opacity-50"></div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center max-w-3xl mx-auto mb-16" data-aos="fade-up">
                <h2 class="text-emerald-600 font-bold tracking-wide uppercase text-sm mb-3">Minat & Bakat</h2>
                <h3 class="text-3xl md:text-4xl font-extrabold text-slate-900 mb-4">Ekstrakurikuler Pilihan</h3>
                <p class="text-slate-500 text-lg">Kembangkan potensi non-akademik siswa melalui berbagai kegiatan seru dan mendidik.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($ekskuls as $item)
                <div class="bg-white rounded-3xl overflow-hidden shadow-sm border border-slate-100 hover:shadow-xl transition-all duration-300 group" data-aos="fade-up" data-aos-delay="{{ $loop->iteration * 100 }}">
                    <div class="h-48 overflow-hidden relative bg-slate-200">
                        @if($item->foto)
                            <img src="{{ asset('storage/'.$item->foto) }}" alt="{{ $item->nama }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-slate-400">
                                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                        @endif
                        
                        <div class="absolute top-4 right-4 bg-white/90 backdrop-blur px-3 py-1 rounded-lg text-xs font-bold uppercase shadow-sm
                            {{ $item->kategori == 'olahraga' ? 'text-emerald-600' : ($item->kategori == 'seni' ? 'text-rose-500' : 'text-blue-600') }}">
                            {{ $item->kategori }}
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-slate-900 mb-2">{{ $item->nama }}</h3>
                        <p class="text-slate-500 text-sm mb-4 line-clamp-2">{{ Str::limit($item->deskripsi, 80) }}</p>
                        
                        <div class="flex items-center gap-3 text-xs text-slate-500 mb-4 bg-slate-50 p-3 rounded-xl border border-slate-100">
                            <div class="flex items-center gap-1">
                                <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <span>{{ $item->jadwal }}</span>
                            </div>
                            <div class="w-px h-4 bg-slate-300"></div>
                            <div class="flex items-center gap-1">
                                <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                <span>{{ $item->pembina }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-full text-center py-12">
                    <p class="text-slate-500">Belum ada data kegiatan ekstrakurikuler yang ditambahkan.</p>
                </div>
                @endforelse
            </div>
            
            <div class="mt-10 text-center">
                 <a href="{{ route('ekskul') }}" class="inline-flex items-center gap-2 text-emerald-600 font-bold hover:text-emerald-700">
                    Lihat Semua Ekskul
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                </a>
            </div>
        </div>
    </section>

    <!-- KONTAK & INFO SEKOLAH (Baru ditambahkan kembali) -->
    <section id="kontak" class="py-24 bg-white border-t border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <!-- Info Text -->
                <div data-aos="fade-right">
                    <span class="inline-block py-1 px-3 rounded-full bg-emerald-50 text-emerald-600 text-xs font-bold tracking-wide uppercase mb-4">
                        Hubungi Kami
                    </span>
                    <h2 class="text-3xl md:text-4xl font-extrabold text-slate-900 mb-6">Mari Bergabung Bersama <br> Keluarga Besar SiKecil</h2>
                    <p class="text-slate-500 text-lg mb-8 leading-relaxed">
                        Kami siap melayani dan memberikan informasi terbaik untuk pendidikan buah hati Anda. Jangan ragu untuk menghubungi kami.
                    </p>
                    
                    <ul class="space-y-6">
                        <li class="flex items-start gap-4">
                            <div class="w-10 h-10 bg-emerald-100 rounded-full flex items-center justify-center text-emerald-600 shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            </div>
                            <div>
                                <h5 class="font-bold text-slate-900">Alamat Sekolah</h5>
                                <p class="text-slate-500 text-sm">Jl. Cibodas Raya Dusun Cibodas, Cibanten, Kec. Cijulang, Kab. Pangandaran, Jawa Barat 46394</p>
                            </div>
                        </li>
                        <li class="flex items-start gap-4">
                            <div class="w-10 h-10 bg-emerald-100 rounded-full flex items-center justify-center text-emerald-600 shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            </div>
                            <div>
                                <h5 class="font-bold text-slate-900">Email Resmi</h5>
                                <p class="text-slate-500 text-sm">info@sikecil-school.sch.id</p>
                            </div>
                        </li>
                        <li class="flex items-start gap-4">
                            <div class="w-10 h-10 bg-emerald-100 rounded-full flex items-center justify-center text-emerald-600 shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                            </div>
                            <div>
                                <h5 class="font-bold text-slate-900">Telepon</h5>
                                <p class="text-slate-500 text-sm">(021) 555-0123</p>
                            </div>
                        </li>
                    </ul>
                </div>

                <!-- Maps / Image Placeholder -->
                <div class="relative h-80 rounded-3xl overflow-hidden shadow-2xl" data-aos="fade-left">
                    <!-- Ganti iframe src dengan Google Maps sekolah Anda -->
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3953.842346188862!2d108.4169026751693!3d-7.700062076261482!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e65ebfc5ad43427%3A0xbe197a41222c7af!2sSD%20Negeri%203%20Cibanten!5e0!3m2!1sid!2sid!4v1765807869173!5m2!1sid!2sid" 
                        width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="bg-slate-900 text-slate-300 py-12 border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="text-center md:text-left">
                <h3 class="text-xl font-bold text-white mb-1">SiKecil</h3>
                <p class="text-xs text-slate-500">Sistem Informasi Sekolah Dasar Terpadu</p>
            </div>
            <p class="text-sm text-slate-500">&copy; {{ date('Y') }} SiKecil. Membangun Generasi Hebat.</p>
        </div>
    </footer>

    <script>
        AOS.init({ once: true, offset: 50, duration: 800 });
    </script>
</body>
</html>