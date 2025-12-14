<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap Kehadiran</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-gray-100 flex">

    @include('components.sidebar')

    <div class="flex-1 flex flex-col h-screen overflow-hidden">
        <header class="bg-white p-4 shadow-sm border-b flex justify-between items-center">
            <h1 class="text-xl font-bold text-blue-800">📊 Rekapitulasi Kehadiran</h1>
            <div class="text-sm font-bold text-gray-700">{{ Auth::user()->name }}</div>
        </header>

        <main class="flex-1 overflow-auto p-6">
            <div class="bg-white rounded-xl shadow p-6">
                <!-- Filter Bulan -->
                <form method="GET" class="mb-6 flex gap-4 items-end">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Pilih Bulan</label>
                        <input type="month" name="bulan" value="{{ $bulan }}" onchange="this.form.submit()" class="border rounded-lg p-2 text-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </form>

                <!-- Tabel -->
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left border rounded-lg overflow-hidden">
                        <thead class="bg-blue-600 text-white uppercase font-bold">
                            <tr>
                                <th class="p-3">Nama Siswa</th>
                                <th class="p-3 text-center">Hadir</th>
                                <th class="p-3 text-center">Sakit</th>
                                <th class="p-3 text-center">Izin</th>
                                <th class="p-3 text-center">Alpa</th>
                                <th class="p-3 text-center">Persentase</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($siswa as $s)
                            @php
                                $h = $presensi->where('user_id', $s->id)->where('status', 'Hadir')->count();
                                $sa = $presensi->where('user_id', $s->id)->where('status', 'Sakit')->count();
                                $i = $presensi->where('user_id', $s->id)->where('status', 'Izin')->count();
                                $a = $presensi->where('user_id', $s->id)->where('status', 'Alpa')->count();
                                $total = $h + $sa + $i + $a;
                                $persen = $total > 0 ? round(($h / $total) * 100) . '%' : '0%';
                            @endphp
                            <tr class="hover:bg-blue-50">
                                <td class="p-3 font-medium text-gray-800">{{ $s->name }}</td>
                                <td class="p-3 text-center text-green-600 font-bold bg-green-50">{{ $h }}</td>
                                <td class="p-3 text-center text-yellow-600 font-bold bg-yellow-50">{{ $sa }}</td>
                                <td class="p-3 text-center text-blue-600 font-bold bg-blue-50">{{ $i }}</td>
                                <td class="p-3 text-center text-red-600 font-bold bg-red-50">{{ $a }}</td>
                                <td class="p-3 text-center font-bold">{{ $persen }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</body>
</html>