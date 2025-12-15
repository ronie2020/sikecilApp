<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'SiKecil') }}</title>
    
    <!-- CDN Tailwind & Alpine.js (Wajib agar tampilan rapi) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        [x-cloak] { display: none !important; }
        /* Custom Scrollbar */
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #f1f5f9; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #cbd5e1; border-radius: 20px; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased">

    <!-- Wrapper Utama dengan Alpine Data untuk Mobile Sidebar -->
    <div x-data="{ sidebarOpen: false }" class="flex h-screen overflow-hidden">
        
        <!-- Panggil Sidebar Anda Disini -->
        <!-- Pastikan file sidebar.blade.php ada di resources/views/components/ -->
        @include('components.sidebar')

        <!-- Area Konten Utama -->
        <div class="relative flex flex-col flex-1 overflow-y-auto overflow-x-hidden">
            
            <!-- Header Mobile (Hanya muncul di HP untuk buka sidebar) -->
            <header class="flex items-center justify-between px-6 py-4 bg-white border-b border-slate-100 md:hidden">
                <div class="font-bold text-lg text-slate-800">SiKecil</div>
                <button @click="sidebarOpen = !sidebarOpen" class="text-slate-500 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
            </header>

            <!-- Slot Konten Halaman -->
            <main class="w-full flex-grow p-6">
                @yield('content')
            </main>
            
        </div>
        
        <!-- Overlay untuk Mobile -->
        <div x-show="sidebarOpen" @click="sidebarOpen = false" x-transition.opacity 
             class="fixed inset-0 z-40 bg-slate-900/50 backdrop-blur-sm md:hidden"></div>
    </div>

</body>
</html>