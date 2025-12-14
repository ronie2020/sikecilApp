<!-- LOGIKA MARK AS READ & HITUNG NOTIFIKASI -->
@php
    // Hitung dulu pesan belum dibaca dari Guru
    $unreadCount = \App\Models\BukuPenghubung::where('siswa_id', Auth::id())
                    ->where('pengirim_id', '!=', Auth::id()) 
                    ->where('is_read', 0)
                    ->count();

    // Lalu tandai semua sebagai sudah dibaca saat halaman dirender
    if($unreadCount > 0) {
        \App\Models\BukuPenghubung::where('siswa_id', Auth::id())
            ->where('pengirim_id', '!=', Auth::id())
            ->where('is_read', 0)
            ->update(['is_read' => 1]);
    }
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Chat Guru - SiKecil</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #F0FDF4; }
        .bubble-me { border-bottom-right-radius: 4px; }
        .bubble-you { border-top-left-radius: 4px; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-emerald-50/30 h-screen flex overflow-hidden text-slate-800" x-data="{ sidebarOpen: false }">

    <!-- 1. MAIN SIDEBAR -->
    @include('components.sidebar')

    <!-- Mobile Overlay -->
    <div x-show="sidebarOpen" 
         @click="sidebarOpen = false"
         class="fixed inset-0 bg-slate-900/50 z-40 md:hidden" x-cloak>
    </div>

    <!-- 2. CONTENT WRAPPER -->
    <div class="flex-1 flex flex-col h-screen overflow-hidden relative w-full" style="background-image: url('https://www.transparenttextures.com/patterns/subtle-white-feathers.png');">
        
        <!-- Header -->
        <header class="bg-white px-4 py-3 flex items-center justify-between shadow-sm border-b border-emerald-100 z-20 shrink-0">
            <div class="flex items-center gap-3">
                <button @click="sidebarOpen = !sidebarOpen" class="md:hidden p-2 -ml-2 text-slate-500 hover:text-emerald-600 rounded-lg hover:bg-emerald-50 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>

                <a href="{{ url('/dashboard') }}" class="text-slate-500 hover:bg-emerald-50 hover:text-emerald-600 p-2 rounded-full transition-colors hidden md:block">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </a>

                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-emerald-100 rounded-full flex items-center justify-center text-emerald-600 font-bold border-2 border-white shadow-sm ring-1 ring-emerald-50 text-xs relative">
                        WK
                        <span class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-emerald-500 border-2 border-white rounded-full"></span>
                    </div>
                    <div>
                        <h1 class="font-bold text-slate-800 text-sm leading-tight">Wali Kelas</h1>
                        <p class="text-[11px] text-emerald-600 font-medium">Online</p>
                    </div>
                </div>
            </div>
            
            <button onclick="location.reload()" class="p-2 text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-full transition-colors"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg></button>
        </header>

        <!-- NOTIFIKASI PESAN BARU -->
        @if($unreadCount > 0)
        <div x-data="{ show: true }" x-show="show" class="absolute top-20 left-1/2 transform -translate-x-1/2 z-30 bg-emerald-600 text-white px-4 py-2 rounded-full shadow-lg text-xs font-bold flex items-center gap-2 animate-bounce cursor-pointer" @click="show = false">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
            {{ $unreadCount }} Pesan Baru dari Guru!
        </div>
        @endif

        <!-- Chat Area -->
        <div class="flex-1 overflow-y-auto p-4 space-y-3" id="chat-container">
            @forelse($chat as $msg)
                @if($msg->pengirim_id == Auth::id())
                    <!-- Saya (Kanan) -->
                    <div class="flex justify-end">
                        <div class="max-w-[85%]">
                            <div class="bg-emerald-600 text-white px-4 py-2 rounded-2xl bubble-me shadow-sm text-sm">
                                {{ $msg->pesan }}
                                <div class="flex items-center justify-end gap-1 mt-1">
                                    <!-- FIX TIMEZONE WIB -->
                                    <span class="text-[10px] text-emerald-100/90">{{ \Carbon\Carbon::parse($msg->created_at)->setTimezone('Asia/Jakarta')->format('H:i') }}</span>
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
                    <!-- Guru (Kiri) -->
                    <div class="flex justify-start">
                        <div class="max-w-[85%]">
                            <div class="bg-white text-slate-800 px-4 py-2 rounded-2xl bubble-you shadow-sm border border-slate-100 text-sm">
                                <p class="text-[10px] font-bold text-teal-600 mb-0.5 uppercase tracking-wide flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    Guru Wali Kelas
                                </p>
                                {{ $msg->pesan }}
                                <!-- FIX TIMEZONE WIB -->
                                <div class="text-[10px] text-slate-500 mt-1 font-medium">{{ \Carbon\Carbon::parse($msg->created_at)->setTimezone('Asia/Jakarta')->format('H:i') }}</div>
                            </div>
                        </div>
                    </div>
                @endif
            @empty
                <div class="flex flex-col items-center justify-center h-64 text-slate-400/60">
                    <div class="bg-white p-4 rounded-full shadow-sm mb-3">
                        <svg class="w-8 h-8 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                    </div>
                    <p class="text-xs font-medium text-slate-500">Mulai chat dengan guru...</p>
                </div>
            @endforelse
            <div id="scroll-anchor"></div>
        </div>

        <!-- Input -->
        <div class="bg-white border-t border-slate-100 p-3 pb-safe shrink-0 z-20">
            <form action="{{ route('penghubung.store') }}" method="POST" class="flex items-end gap-2 max-w-4xl mx-auto w-full">
                @csrf
                <input type="hidden" name="siswa_id" value="{{ Auth::id() }}">
                <div class="flex-1 bg-slate-50 rounded-2xl px-4 py-2 border border-transparent focus-within:border-emerald-300 focus-within:ring-2 focus-within:ring-emerald-100 transition-all shadow-inner">
                    <input type="text" name="pesan" class="w-full bg-transparent border-none focus:ring-0 text-sm text-slate-800 placeholder-slate-400 p-0" placeholder="Ketik pesan..." required autocomplete="off">
                </div>
                <button type="submit" class="p-3 bg-emerald-600 text-white rounded-full shadow-lg shadow-emerald-200 active:scale-95 transition-all hover:bg-emerald-700 w-11 h-11 flex items-center justify-center">
                    <svg class="w-5 h-5 translate-x-0.5 -translate-y-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                </button>
            </form>
        </div>
    </div>

    <script>
        window.addEventListener('load', () => document.getElementById('scroll-anchor').scrollIntoView());
    </script>
</body>
</html>