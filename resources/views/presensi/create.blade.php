<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Absensi - SiKecil</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-gray-100">

    <!-- Topbar Sederhana -->
    <header class="bg-blue-800 text-white p-4 shadow-md sticky top-0 z-50">
        <div class="max-w-4xl mx-auto flex justify-between items-center">
            <h1 class="text-lg font-bold">📝 Absensi Harian</h1>
            <a href="{{ url('/dashboard') }}" class="text-sm bg-blue-700 hover:bg-blue-600 px-3 py-1 rounded transition">
                &larr; Kembali
            </a>
        </div>
    </header>

    <div class="max-w-4xl mx-auto p-4 pb-24">
        
        <!-- Info Tanggal -->
        <div class="bg-white rounded-lg shadow-sm p-4 mb-4 border-l-4 border-blue-500 flex justify-between items-center">
            <div>
                <p class="text-gray-500 text-sm">Tanggal Absensi</p>
                <h2 class="text-xl font-bold text-gray-800">{{ $hariIni }}</h2>
            </div>
            <div class="text-right hidden sm:block">
                <p class="text-sm text-gray-500">Guru Piket</p>
                <p class="font-semibold">{{ Auth::user()->name }}</p>
            </div>
        </div>

        <!-- Form Absensi -->
        <form action="{{ route('presensi.store') }}" method="POST">
            @csrf
            
            <div class="space-y-3">
                @forelse($siswa as $s)
                <div class="bg-white rounded-xl shadow-sm p-4 flex flex-col sm:flex-row items-center justify-between transition hover:shadow-md">
                    
                    <!-- Info Siswa -->
                    <div class="flex items-center w-full sm:w-1/3 mb-3 sm:mb-0">
                        <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-600 font-bold mr-3">
                            {{ substr($s->name, 0, 1) }}
                        </div>
                        <div>
                            <p class="font-bold text-gray-800">{{ $s->name }}</p>
                            <p class="text-xs text-gray-500">{{ $s->nip_nis }}</p>
                        </div>
                    </div>

                    <!-- Pilihan Status (Radio Button Keren) -->
                    <div class="w-full sm:w-2/3 flex justify-between gap-2">
                        <!-- Hadir -->
                        <label class="cursor-pointer w-full">
                            <input type="radio" name="presensi[{{ $s->id }}]" value="Hadir" class="peer sr-only" checked>
                            <div class="text-center py-2 rounded-lg border border-gray-200 text-gray-500 peer-checked:bg-green-500 peer-checked:text-white peer-checked:border-green-600 transition">
                                <span class="block text-lg font-bold">H</span>
                                <span class="text-[10px] uppercase">Hadir</span>
                            </div>
                        </label>

                        <!-- Sakit -->
                        <label class="cursor-pointer w-full">
                            <input type="radio" name="presensi[{{ $s->id }}]" value="Sakit" class="peer sr-only">
                            <div class="text-center py-2 rounded-lg border border-gray-200 text-gray-500 peer-checked:bg-yellow-400 peer-checked:text-white peer-checked:border-yellow-500 transition">
                                <span class="block text-lg font-bold">S</span>
                                <span class="text-[10px] uppercase">Sakit</span>
                            </div>
                        </label>

                        <!-- Izin -->
                        <label class="cursor-pointer w-full">
                            <input type="radio" name="presensi[{{ $s->id }}]" value="Izin" class="peer sr-only">
                            <div class="text-center py-2 rounded-lg border border-gray-200 text-gray-500 peer-checked:bg-blue-400 peer-checked:text-white peer-checked:border-blue-500 transition">
                                <span class="block text-lg font-bold">I</span>
                                <span class="text-[10px] uppercase">Izin</span>
                            </div>
                        </label>

                        <!-- Alpa -->
                        <label class="cursor-pointer w-full">
                            <input type="radio" name="presensi[{{ $s->id }}]" value="Alpa" class="peer sr-only">
                            <div class="text-center py-2 rounded-lg border border-gray-200 text-gray-500 peer-checked:bg-red-500 peer-checked:text-white peer-checked:border-red-600 transition">
                                <span class="block text-lg font-bold">A</span>
                                <span class="text-[10px] uppercase">Alpa</span>
                            </div>
                        </label>
                    </div>

                </div>
                @empty
                <div class="text-center py-10">
                    <p class="text-gray-400">Belum ada data siswa.</p>
                </div>
                @endforelse
            </div>

            <!-- Tombol Simpan Mengambang di Bawah -->
            <div class="fixed bottom-0 left-0 w-full bg-white border-t p-4 shadow-lg z-40">
                <div class="max-w-4xl mx-auto">
                    <button type="submit" class="w-full bg-blue-700 hover:bg-blue-800 text-white font-bold py-3 rounded-lg shadow-lg transition transform active:scale-95">
                        💾 SIMPAN DATA ABSENSI
                    </button>
                </div>
            </div>

        </form>
    </div>
</body>
</html>