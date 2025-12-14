<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Chat Guru</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #EFE7DD; }
        .bubble-me { border-bottom-right-radius: 4px; }
        .bubble-you { border-top-left-radius: 4px; }
    </style>
</head>
<body class="h-[100dvh] flex flex-col overflow-hidden" style="background-image: url('https://www.transparenttextures.com/patterns/subtle-white-feathers.png');">

    <!-- Header -->
    <header class="bg-white px-4 py-3 flex items-center justify-between shadow-sm border-b border-slate-100 z-20 shrink-0">
        <div class="flex items-center gap-3">
            <a href="{{ url('/dashboard') }}" class="text-slate-500 hover:bg-slate-50 p-2 rounded-full"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg></a>
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center text-indigo-600 font-bold border-2 border-white shadow-sm">WK</div>
                <div>
                    <h1 class="font-bold text-slate-800 text-sm leading-tight">Wali Kelas</h1>
                    <p class="text-[11px] text-green-600 font-medium">Online</p>
                </div>
            </div>
        </div>
        <button onclick="location.reload()" class="p-2 text-slate-400 hover:text-indigo-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg></button>
    </header>

    <!-- Chat Area -->
    <div class="flex-1 overflow-y-auto p-4 space-y-3" id="chat-container">
        @forelse($chat as $msg)
            @if($msg->pengirim_id == Auth::id())
                <!-- Saya (Kanan) -->
                <div class="flex justify-end">
                    <div class="max-w-[85%]">
                        <div class="bg-indigo-600 text-white px-4 py-2 rounded-2xl bubble-me shadow-sm text-sm">{{ $msg->pesan }}</div>
                        <div class="text-[10px] text-slate-500 text-right mt-1">{{ $msg->created_at->format('H:i') }}</div>
                    </div>
                </div>
            @else
                <!-- Guru (Kiri) -->
                <div class="flex justify-start">
                    <div class="max-w-[85%]">
                        <div class="bg-white text-slate-800 px-4 py-2 rounded-2xl bubble-you shadow-sm border border-slate-100 text-sm">
                            <p class="text-[10px] font-bold text-orange-500 mb-0.5">Guru</p>
                            {{ $msg->pesan }}
                        </div>
                        <div class="text-[10px] text-slate-500 mt-1 ml-1">{{ $msg->created_at->format('H:i') }}</div>
                    </div>
                </div>
            @endif
        @empty
            <div class="flex flex-col items-center justify-center h-64 text-slate-400/60">
                <svg class="w-12 h-12 mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                <p class="text-xs">Mulai chat dengan guru...</p>
            </div>
        @endforelse
        <div id="scroll-anchor"></div>
    </div>

    <!-- Input -->
    <div class="bg-white border-t border-slate-100 p-3 pb-safe shrink-0 z-20">
        <form action="{{ route('penghubung.store') }}" method="POST" class="flex items-end gap-2">
            @csrf
            <input type="hidden" name="siswa_id" value="{{ Auth::id() }}">
            <div class="flex-1 bg-slate-100 rounded-2xl px-4 py-2">
                <input type="text" name="pesan" class="w-full bg-transparent border-none focus:ring-0 text-sm text-slate-800 placeholder-slate-400 p-0" placeholder="Ketik pesan..." required autocomplete="off">
            </div>
            <button type="submit" class="p-3 bg-indigo-600 text-white rounded-full shadow-lg active:scale-95 transition-transform">
                <svg class="w-5 h-5 translate-x-0.5 -translate-y-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
            </button>
        </form>
    </div>

    <script>
        window.addEventListener('load', () => document.getElementById('scroll-anchor').scrollIntoView());
    </script>
</body>
</html>