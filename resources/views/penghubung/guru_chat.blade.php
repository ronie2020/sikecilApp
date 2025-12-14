<!-- LOGIKA MARK AS READ -->
@php
    // Saat Guru membuka halaman ini, tandai semua pesan dari siswa ini sebagai 'sudah dibaca'
    \App\Models\BukuPenghubung::where('siswa_id', $siswaAktif->id)
        ->where('pengirim_id', '!=', Auth::id())
        ->where('is_read', 0)
        ->update(['is_read' => 1]);
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat: {{ $siswaAktif->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .bubble-me { border-bottom-right-radius: 2px; }
        .bubble-you { border-top-left-radius: 2px; }
        /* Custom Scrollbar */
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #cbd5e1; border-radius: 20px; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-slate-100 h-screen overflow-hidden" x-data="{ sidebarOpen: false, message: '' }">

    <div class="flex h-screen overflow-hidden">
        
        <!-- 1. MAIN MENU SIDEBAR -->
        @include('components.sidebar')

        <!-- Mobile Overlay -->
        <div x-show="sidebarOpen" 
             @click="sidebarOpen = false"
             class="fixed inset-0 bg-slate-900/50 z-40 md:hidden" x-cloak>
        </div>

        <!-- 2. WRAPPER KONTEN -->
        <div class="flex-1 flex h-screen overflow-hidden relative w-full">
            
            <!-- INNER SIDEBAR: LIST SISWA -->
            <aside class="w-80 bg-white border-r border-slate-200 flex-col hidden lg:flex z-20 shrink-0 h-full">
                <div class="h-16 flex items-center px-6 border-b border-slate-100 bg-white sticky top-0 shrink-0">
                    <a href="{{ url('/buku-penghubung') }}" class="flex items-center gap-2 text-slate-500 hover:text-emerald-600 font-bold text-sm transition-colors group">
                        <div class="p-1 rounded-md group-hover:bg-emerald-50 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                        </div>
                        Kembali
                    </a>
                </div>
                <div class="flex-1 overflow-y-auto p-3 space-y-1 custom-scrollbar">
                    @foreach($listSiswa as $s)
                    
                    @php
                        // Hitung pesan belum dibaca untuk sidebar
                        $unread = \App\Models\BukuPenghubung::where('siswa_id', $s->id)
                                    ->where('pengirim_id', '!=', Auth::id())
                                    ->where('is_read', 0)
                                    ->count();
                    @endphp

                    <a href="{{ url('/buku-penghubung?siswa_id=' . $s->id) }}" 
                       class="flex items-center gap-3 p-3 rounded-xl transition-all relative {{ $s->id == $siswaAktif->id ? 'bg-emerald-50 shadow-sm ring-1 ring-emerald-100' : 'hover:bg-slate-50' }}">
                        
                        <div class="h-10 w-10 rounded-full flex items-center justify-center text-sm font-bold shrink-0 transition-colors relative {{ $s->id == $siswaAktif->id ? 'bg-emerald-600 text-white' : 'bg-slate-100 text-slate-500' }}">
                            {{ substr($s->name, 0, 1) }}
                            
                            <!-- Dot Merah jika ada pesan -->
                            @if($unread > 0)
                                <span class="absolute -top-1 -right-1 w-3 h-3 bg-rose-500 border-2 border-white rounded-full"></span>
                            @endif
                        </div>
                        
                        <div class="flex-1 min-w-0">
                            <div class="flex justify-between items-center">
                                <p class="text-sm font-bold truncate {{ $s->id == $siswaAktif->id ? 'text-emerald-900' : 'text-slate-700' }}">{{ $s->name }}</p>
                                @if($unread > 0)
                                    <span class="text-[10px] font-bold bg-rose-100 text-rose-600 px-1.5 rounded-md">{{ $unread }}</span>
                                @endif
                            </div>
                            <p class="text-xs text-slate-400 truncate">Kelas {{ $s->kelas->nama_kelas ?? '-' }}</p>
                        </div>
                    </a>
                    @endforeach
                </div>
            </aside>

            <!-- 3. AREA CHAT UTAMA -->
            <main class="flex-1 flex flex-col bg-[#F8FAFC] relative w-full min-w-0 h-full">
                <!-- Header Chat -->
                <header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-4 sm:px-6 shadow-sm z-10 shrink-0">
                    <div class="flex items-center gap-3">
                        <button @click="sidebarOpen = !sidebarOpen" class="md:hidden p-2 -ml-2 text-slate-500 hover:text-emerald-600 rounded-lg hover:bg-slate-100 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                        </button>
                        
                        <a href="{{ url('/buku-penghubung') }}" class="lg:hidden p-2 text-slate-500 hover:text-emerald-600"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg></a>
                        
                        <div class="flex items-center gap-3">
                            <div class="h-10 w-10 rounded-full bg-emerald-600 flex items-center justify-center text-white font-bold shadow-md shadow-emerald-200 shrink-0">{{ substr($siswaAktif->name, 0, 1) }}</div>
                            <div>
                                <h1 class="font-bold text-sm sm:text-base text-slate-900 leading-tight">{{ $siswaAktif->name }}</h1>
                                <p class="text-xs text-slate-500">Kelas {{ $siswaAktif->kelas->nama_kelas ?? '-' }}</p>
                            </div>
                        </div>
                    </div>
                    <button onclick="location.reload()" class="text-slate-400 hover:text-emerald-600 transition-colors p-2 rounded-full hover:bg-emerald-50"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg></button>
                </header>

                <!-- Box Pesan -->
                <div class="flex-1 overflow-y-auto p-4 space-y-4 bg-[#F8FAFC]" id="chat-box">
                    @forelse($chat as $msg)
                        @if($msg->pengirim_id == Auth::id())
                            <!-- Guru (Kanan) -->
                            <div class="flex justify-end">
                                <div class="max-w-[85%] sm:max-w-[70%]">
                                    <div class="bg-emerald-600 text-white py-2 px-4 rounded-2xl bubble-me text-sm shadow-sm">
                                        {{ $msg->pesan }}
                                        <div class="flex items-center justify-end gap-1 mt-1">
                                            <!-- FIX TIMEZONE WIB -->
                                            <span class="text-[10px] text-emerald-100">{{ \Carbon\Carbon::parse($msg->created_at)->setTimezone('Asia/Jakarta')->format('H:i') }}</span>
                                            
                                            @if($msg->is_read)
                                                <svg class="w-3 h-3 text-emerald-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                <svg class="w-3 h-3 text-emerald-200 -ml-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            @else
                                                <svg class="w-3 h-3 text-emerald-200/70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <!-- Siswa (Kiri) -->
                            <div class="flex justify-start">
                                <div class="max-w-[85%] sm:max-w-[70%]">
                                    <div class="bg-white text-slate-800 py-2 px-4 rounded-2xl bubble-you border border-slate-200 text-sm shadow-sm">
                                        {{ $msg->pesan }}
                                        <!-- FIX TIMEZONE WIB -->
                                        <div class="text-[10px] text-slate-400 mt-1 ml-1">{{ \Carbon\Carbon::parse($msg->created_at)->setTimezone('Asia/Jakarta')->format('H:i') }}</div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @empty
                        <div class="flex flex-col items-center justify-center h-full text-slate-400 space-y-2 opacity-60">
                            <div class="text-4xl bg-slate-100 p-4 rounded-full grayscale">💬</div>
                            <p class="text-sm">Belum ada percakapan dengan siswa ini.</p>
                        </div>
                    @endforelse
                    <div id="scroll-anchor"></div>
                </div>

                <!-- Input Area -->
                <div class="bg-white border-t border-slate-200 p-4 shrink-0 z-10">
                    <form action="{{ route('penghubung.store') }}" method="POST" class="flex gap-2 max-w-4xl mx-auto w-full">
                        @csrf
                        <input type="hidden" name="siswa_id" value="{{ $siswaAktif->id }}">
                        <input x-model="message" type="text" name="pesan" class="flex-1 bg-slate-50 border border-slate-200 rounded-full px-4 py-3 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-transparent outline-none transition-all placeholder-slate-400" placeholder="Ketik pesan..." required autocomplete="off">
                        <button type="submit" class="bg-emerald-600 text-white p-3 rounded-full hover:bg-emerald-700 transition-colors shadow-lg shadow-emerald-200 active:scale-95 shrink-0 flex items-center justify-center w-12 h-12">
                            <svg class="w-5 h-5 translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                        </button>
                    </form>
                </div>
            </main>
        </div>
    </div>

    <script>
        window.addEventListener('load', () => {
            const anchor = document.getElementById('scroll-anchor');
            if(anchor) anchor.scrollIntoView(); 
        });
    </script>
</body>
</html>