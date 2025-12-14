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
    </style>
</head>
<body class="bg-slate-100 h-screen flex overflow-hidden" x-data="{ message: '' }">

    <!-- SIDEBAR LIST SISWA -->
    <aside class="w-80 bg-white border-r border-slate-200 flex-col hidden md:flex z-20">
        <div class="h-16 flex items-center px-6 border-b border-slate-100 bg-white sticky top-0 shrink-0">
            <a href="{{ url('/buku-penghubung') }}" class="flex items-center gap-2 text-slate-500 hover:text-indigo-600 font-bold text-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                Kembali
            </a>
        </div>
        <div class="flex-1 overflow-y-auto p-3 space-y-1">
            @foreach($listSiswa as $s)
            <a href="{{ url('/buku-penghubung?siswa_id=' . $s->id) }}" 
               class="flex items-center gap-3 p-3 rounded-xl transition-all {{ $s->id == $siswaAktif->id ? 'bg-indigo-50 shadow-sm ring-1 ring-indigo-100' : 'hover:bg-slate-50' }}">
                <div class="h-10 w-10 rounded-full flex items-center justify-center text-sm font-bold {{ $s->id == $siswaAktif->id ? 'bg-indigo-600 text-white' : 'bg-slate-100 text-slate-500' }}">
                    {{ substr($s->name, 0, 1) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-bold truncate {{ $s->id == $siswaAktif->id ? 'text-indigo-900' : 'text-slate-700' }}">{{ $s->name }}</p>
                </div>
            </a>
            @endforeach
        </div>
    </aside>

    <!-- AREA CHAT -->
    <main class="flex-1 flex flex-col bg-[#F1F5F9] relative w-full">
        <!-- Header -->
        <header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-4 sm:px-6 shadow-sm z-10 shrink-0">
            <div class="flex items-center gap-3">
                <a href="{{ url('/buku-penghubung') }}" class="md:hidden p-2 -ml-2 text-slate-500"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg></a>
                <div class="h-10 w-10 rounded-full bg-indigo-600 flex items-center justify-center text-white font-bold">{{ substr($siswaAktif->name, 0, 1) }}</div>
                <div>
                    <h1 class="font-bold text-sm sm:text-base text-slate-900">{{ $siswaAktif->name }}</h1>
                    <p class="text-xs text-slate-500">Kelas {{ $siswaAktif->kelas->nama_kelas ?? '-' }}</p>
                </div>
            </div>
            <button onclick="location.reload()" class="text-slate-400 hover:text-indigo-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg></button>
        </header>

        <!-- Pesan -->
        <div class="flex-1 overflow-y-auto p-4 space-y-4" id="chat-box">
            @forelse($chat as $msg)
                @if($msg->pengirim_id == Auth::id())
                    <!-- Guru (Kanan) -->
                    <div class="flex justify-end">
                        <div class="max-w-[80%]">
                            <div class="bg-indigo-600 text-white py-2 px-4 rounded-2xl bubble-me text-sm">{{ $msg->pesan }}</div>
                            <div class="text-[10px] text-slate-400 text-right mt-1">{{ $msg->created_at->format('H:i') }}</div>
                        </div>
                    </div>
                @else
                    <!-- Siswa (Kiri) -->
                    <div class="flex justify-start">
                        <div class="max-w-[80%]">
                            <div class="bg-white text-slate-800 py-2 px-4 rounded-2xl bubble-you border border-slate-200 text-sm">{{ $msg->pesan }}</div>
                            <div class="text-[10px] text-slate-400 mt-1">{{ $msg->created_at->format('H:i') }}</div>
                        </div>
                    </div>
                @endif
            @empty
                <div class="flex flex-col items-center justify-center h-full text-slate-400 space-y-2">
                    <div class="text-4xl">💬</div>
                    <p class="text-sm">Belum ada percakapan.</p>
                </div>
            @endforelse
            <div id="scroll-anchor"></div>
        </div>

        <!-- Input -->
        <div class="bg-white border-t border-slate-200 p-4 shrink-0">
            <form action="{{ route('penghubung.store') }}" method="POST" class="flex gap-2">
                @csrf
                <input type="hidden" name="siswa_id" value="{{ $siswaAktif->id }}">
                <input x-model="message" type="text" name="pesan" class="flex-1 bg-slate-50 border border-slate-200 rounded-full px-4 py-3 text-sm focus:ring-2 focus:ring-indigo-500 outline-none" placeholder="Ketik pesan..." required autocomplete="off">
                <button type="submit" class="bg-indigo-600 text-white p-3 rounded-full hover:bg-indigo-700 transition-colors shadow-lg">
                    <svg class="w-5 h-5 translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                </button>
            </form>
        </div>
    </main>

    <script>
        window.onload = function() { document.getElementById('scroll-anchor').scrollIntoView(); }
    </script>
</body>
</html>