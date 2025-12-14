<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Data Master - SiKecil</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <!-- SweetAlert2 untuk Alert yang lebih cantik -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        [x-cloak] { display: none !important; }
        
        /* Custom Scrollbar halus */
        .custom-scrollbar::-webkit-scrollbar { width: 6px; height: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #f1f5f9; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
</head>
<body class="bg-slate-50 flex text-slate-800" x-data="{ 
    activeTab: 'guru', 
    
    // Modal Edit User
    editModalOpen: false, 
    editData: {},
    openEdit(user) {
        this.editData = user;
        this.editModalOpen = true;
    },

    // Modal Edit Kelas
    editKelasOpen: false,
    kelasData: {},
    openEditKelas(kelas) {
        this.kelasData = kelas;
        this.editKelasOpen = true;
    },

    // Modal Import (BARU)
    importModalOpen: false,
    importRole: 'guru', // 'guru' atau 'siswa'
    openImport(role) {
        this.importRole = role;
        this.importModalOpen = true;
    },

    // Fungsi Konfirmasi Hapus SweetAlert
    confirmDelete(url, message) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: message,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#2563eb', // Blue-600
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = url;
            }
        })
    }
}">

    <!-- INCLUDE SIDEBAR DISINI -->
    @include('components.sidebar')

    <div class="flex-1 flex flex-col h-screen overflow-hidden">
        
        <!-- Header -->
        <header class="bg-white px-6 py-4 shadow-sm border-b border-slate-200 flex justify-between items-center z-20">
            <div>
                <h1 class="text-xl font-extrabold text-slate-800 tracking-tight">📂 Data Master Sekolah</h1>
                <p class="text-xs text-slate-500">Kelola data guru, siswa, kelas, dan mata pelajaran.</p>
            </div>
            
            <div class="flex items-center gap-4">
                <div class="hidden md:flex relative">
                    <input type="text" placeholder="Cari data..." class="pl-10 pr-4 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent w-64 transition-all">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                
                <div class="flex items-center gap-3 border-l border-slate-200 pl-4">
                    <div class="text-right hidden sm:block">
                        <div class="text-sm font-bold text-slate-800">{{ Auth::user()->name }}</div>
                        <div class="text-xs text-slate-500">Administrator</div>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center font-bold">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                </div>
            </div>
        </header>

        <main class="flex-1 overflow-auto p-6 relative custom-scrollbar bg-slate-50/50">
            
            @if(session('success'))
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: "{{ session('success') }}",
                        timer: 3000,
                        showConfirmButton: false
                    });
                });
            </script>
            @endif

            @if(session('error'))
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: "{{ session('error') }}",
                    });
                });
            </script>
            @endif

            <!-- TABS MENU -->
            <div class="flex space-x-1 mb-6 bg-white p-1.5 rounded-xl shadow-sm border border-slate-200 w-fit">
                <button @click="activeTab = 'guru'" 
                    :class="activeTab === 'guru' ? 'bg-blue-600 text-white shadow-md' : 'text-slate-500 hover:text-slate-700 hover:bg-slate-50'" 
                    class="py-2 px-5 font-bold rounded-lg transition-all duration-200 flex items-center gap-2 text-sm">
                    👨‍🏫 Guru & Wali
                </button>
                <button @click="activeTab = 'siswa'" 
                    :class="activeTab === 'siswa' ? 'bg-blue-600 text-white shadow-md' : 'text-slate-500 hover:text-slate-700 hover:bg-slate-50'" 
                    class="py-2 px-5 font-bold rounded-lg transition-all duration-200 flex items-center gap-2 text-sm">
                    🎓 Siswa
                </button>
                <button @click="activeTab = 'kelas'" 
                    :class="activeTab === 'kelas' ? 'bg-blue-600 text-white shadow-md' : 'text-slate-500 hover:text-slate-700 hover:bg-slate-50'" 
                    class="py-2 px-5 font-bold rounded-lg transition-all duration-200 flex items-center gap-2 text-sm">
                    🏫 Kelas
                </button>
                <button @click="activeTab = 'mapel'" 
                    :class="activeTab === 'mapel' ? 'bg-blue-600 text-white shadow-md' : 'text-slate-500 hover:text-slate-700 hover:bg-slate-50'" 
                    class="py-2 px-5 font-bold rounded-lg transition-all duration-200 flex items-center gap-2 text-sm">
                    📚 Mapel
                </button>
            </div>

            <!-- KONTEN TAB: GURU -->
            <div x-show="activeTab === 'guru'" x-transition.opacity.duration.300ms class="space-y-6">
                <!-- Form Input Card -->
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
                    <div class="flex justify-between items-center mb-5 border-b border-slate-100 pb-3">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-blue-50 rounded-lg text-blue-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            </div>
                            <h3 class="font-bold text-slate-800 text-lg">Tambah Guru Baru</h3>
                        </div>
                        <!-- TOMBOL IMPORT GURU -->
                        <button @click="openImport('guru')" class="text-sm flex items-center gap-2 text-emerald-600 bg-emerald-50 px-3 py-1.5 rounded-lg border border-emerald-200 hover:bg-emerald-100 font-bold transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                            Import CSV
                        </button>
                    </div>
                    
                    <form action="{{ route('admin.storeUser') }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                        @csrf
                        <input type="hidden" name="role" value="guru">
                        <div class="space-y-1">
                            <label class="text-xs font-bold text-slate-500 uppercase">Nama Lengkap</label>
                            <input type="text" name="name" placeholder="Contoh: Budi Santoso, S.Pd" class="w-full border border-slate-200 p-2.5 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all" required>
                        </div>
                        <div class="space-y-1">
                            <label class="text-xs font-bold text-slate-500 uppercase">NIP / NUPTK</label>
                            <input type="text" name="nip_nis" placeholder="Nomor Induk" class="w-full border border-slate-200 p-2.5 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all" required>
                        </div>
                        <div class="space-y-1">
                            <label class="text-xs font-bold text-slate-500 uppercase">Email Login</label>
                            <input type="email" name="email" placeholder="email@sekolah.id" class="w-full border border-slate-200 p-2.5 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all" required>
                        </div>
                        <div class="space-y-1">
                            <label class="text-xs font-bold text-slate-500 uppercase">Password</label>
                            <input type="password" name="password" placeholder="******" class="w-full border border-slate-200 p-2.5 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all" required>
                        </div>
                        <div class="hidden lg:block"></div>
                        <div class="flex items-end">
                            <button type="submit" class="w-full bg-blue-600 text-white font-bold py-2.5 rounded-xl hover:bg-blue-700 shadow-lg shadow-blue-200 transition-all transform active:scale-95">Simpan Data Guru</button>
                        </div>
                    </form>
                </div>

                <!-- Table Card -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead class="bg-slate-50 text-slate-500 font-bold uppercase text-xs tracking-wider border-b border-slate-200">
                                <tr>
                                    <th class="px-6 py-4">Nama Guru</th>
                                    <th class="px-6 py-4">NIP</th>
                                    <th class="px-6 py-4">Tugas Tambahan</th>
                                    <th class="px-6 py-4 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($guru as $g)
                                <tr class="hover:bg-slate-50/80 transition-colors group">
                                    <td class="px-6 py-4 font-bold text-slate-700 flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-xs">
                                            {{ substr($g->name, 0, 1) }}
                                        </div>
                                        {{ $g->name }}
                                    </td>
                                    <td class="px-6 py-4 text-slate-500 font-mono">{{ $g->nip_nis }}</td>
                                    <td class="px-6 py-4">
                                        @if($g->kelasWali)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 border border-purple-200">
                                                Wali Kelas {{ $g->kelasWali->nama_kelas }}
                                            </span>
                                        @else
                                            <span class="text-slate-400 text-xs italic">Guru Mapel</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex justify-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <button @click="openEdit({{ $g }})" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Edit">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                            </button>
                                            <button @click="confirmDelete('{{ route('admin.delete', ['type'=>'user', 'id'=>$g->id]) }}', 'Menghapus guru ini akan menghapus semua riwayat mengajarnya.')" class="p-1.5 text-rose-500 hover:bg-rose-50 rounded-lg transition-colors" title="Hapus">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-10 text-center text-slate-400">
                                        Tidak ada data guru. Silakan tambah baru.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- KONTEN TAB: SISWA -->
            <div x-show="activeTab === 'siswa'" x-transition.opacity.duration.300ms class="space-y-6" style="display: none;">
                <!-- Form Input Siswa -->
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
                    <div class="flex justify-between items-center mb-5 border-b border-slate-100 pb-3">
                        <div class="flex items-center gap-3">
                            <div class="bg-blue-100 text-blue-600 p-1.5 rounded-lg"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg></div>
                            <h3 class="font-bold text-slate-800">Tambah Siswa Baru</h3>
                        </div>
                        <!-- TOMBOL IMPORT SISWA -->
                        <button @click="openImport('siswa')" class="text-sm flex items-center gap-2 text-emerald-600 bg-emerald-50 px-3 py-1.5 rounded-lg border border-emerald-200 hover:bg-emerald-100 font-bold transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                            Import CSV
                        </button>
                    </div>

                    <form action="{{ route('admin.storeUser') }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @csrf
                        <input type="hidden" name="role" value="siswa">
                        <input type="text" name="name" placeholder="Nama Lengkap Siswa" class="input-field border border-slate-200 p-2.5 rounded-xl w-full focus:ring-blue-500 focus:border-blue-500 outline-none" required>
                        <input type="text" name="nip_nis" placeholder="NISN" class="input-field border border-slate-200 p-2.5 rounded-xl w-full focus:ring-blue-500 focus:border-blue-500 outline-none" required>
                        <input type="email" name="email" placeholder="Email Login" class="input-field border border-slate-200 p-2.5 rounded-xl w-full focus:ring-blue-500 focus:border-blue-500 outline-none" required>
                        <input type="password" name="password" placeholder="Password" class="input-field border border-slate-200 p-2.5 rounded-xl w-full focus:ring-blue-500 focus:border-blue-500 outline-none" required>
                        <select name="kelas_id" class="input-field border border-slate-200 p-2.5 rounded-xl w-full focus:ring-blue-500 focus:border-blue-500 outline-none bg-white" required>
                            <option value="">Pilih Kelas</option>
                            @foreach($kelas as $k)
                                <option value="{{ $k->id }}">{{ $k->nama_kelas }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="bg-blue-600 text-white font-bold py-2.5 rounded-xl hover:bg-blue-700 transition-colors shadow-lg shadow-blue-100">Simpan Siswa</button>
                    </form>
                </div>

                <!-- Tabel Siswa -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-slate-50 text-slate-500 font-bold uppercase text-xs">
                            <tr><th class="p-4">Nama</th><th class="p-4">NISN</th><th class="p-4">Kelas</th><th class="p-4 text-center">Aksi</th></tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($siswa as $s)
                            <tr class="hover:bg-slate-50 group">
                                <td class="p-4">
                                    <div class="font-bold text-slate-700">{{ $s->name }}</div>
                                    <div class="text-xs text-slate-400">{{ $s->email }}</div>
                                </td>
                                <td class="p-4 font-mono text-slate-600">{{ $s->nip_nis }}</td>
                                <td class="p-4"><span class="bg-blue-50 text-blue-600 text-xs font-bold px-2 py-1 rounded border border-blue-100">{{ $s->kelas->nama_kelas ?? 'Tanpa Kelas' }}</span></td>
                                <td class="p-4 text-center">
                                    <div class="flex justify-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <button @click="openEdit({{ $s }})" class="text-blue-600 hover:underline text-xs font-bold">Edit</button>
                                        <button @click="confirmDelete('{{ route('admin.delete', ['type'=>'user', 'id'=>$s->id]) }}', 'Data kehadiran siswa ini juga akan terhapus.')" class="text-rose-500 hover:underline text-xs font-bold">Hapus</button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- KONTEN TAB: KELAS (TETAP SAMA) -->
            <div x-show="activeTab === 'kelas'" x-transition.opacity.duration.300ms class="space-y-6" style="display: none;">
                <div class="flex flex-col md:flex-row gap-6">
                    <div class="w-full md:w-1/3 bg-white p-6 rounded-2xl shadow-sm border border-slate-200 h-fit sticky top-6">
                        <h3 class="font-bold text-slate-800 mb-4">➕ Tambah Kelas</h3>
                        <form action="{{ route('admin.storeKelas') }}" method="POST" class="space-y-4">
                            @csrf
                            <input type="text" name="nama_kelas" placeholder="Nama Kelas (7A)" class="w-full border p-3 rounded-xl focus:ring-blue-500 outline-none border-slate-200" required>
                            <select name="wali_kelas_id" class="w-full border p-3 rounded-xl focus:ring-blue-500 outline-none border-slate-200 bg-white">
                                <option value="">-- Wali Kelas (Opsional) --</option>
                                @foreach($guru as $g)
                                    <option value="{{ $g->id }}">{{ $g->name }}</option>
                                @endforeach
                            </select>
                            <button type="submit" class="w-full bg-blue-600 text-white font-bold py-3 rounded-xl hover:bg-blue-700 transition-colors">Simpan Kelas</button>
                        </form>
                    </div>
                    <div class="w-full md:w-2/3 bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                        <table class="w-full text-sm text-left">
                            <thead class="bg-slate-50 text-slate-500 font-bold uppercase text-xs">
                                <tr><th class="p-4">Kelas</th><th class="p-4">Wali Kelas</th><th class="p-4 text-center">Aksi</th></tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($kelas as $k)
                                <tr class="hover:bg-slate-50 group">
                                    <td class="p-4 font-bold text-lg text-slate-700">{{ $k->nama_kelas }}</td>
                                    <td class="p-4">
                                        @if($k->waliKelas)
                                            <div class="flex items-center gap-2 text-blue-700 font-medium">
                                                <div class="w-6 h-6 rounded-full bg-blue-100 flex items-center justify-center text-xs">{{ substr($k->waliKelas->name, 0, 1) }}</div>
                                                {{ $k->waliKelas->name }}
                                            </div>
                                        @else
                                            <span class="text-slate-400 italic text-xs">Belum ada wali</span>
                                        @endif
                                    </td>
                                    <td class="p-4 text-center">
                                        <button @click="openEditKelas({{ $k }})" class="text-blue-600 hover:text-blue-800 font-medium text-xs">Edit</button>
                                        <span class="text-slate-300 mx-1">|</span>
                                        <button @click="confirmDelete('{{ route('admin.delete', ['type'=>'kelas', 'id'=>$k->id]) }}', 'Menghapus kelas tidak menghapus siswanya, tapi siswa akan kehilangan kelas.')" class="text-rose-500 hover:text-rose-700 font-medium text-xs">Hapus</button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- KONTEN TAB: MAPEL (TETAP SAMA) -->
            <div x-show="activeTab === 'mapel'" class="space-y-6" style="display: none;">
                 <div class="flex flex-col md:flex-row gap-6">
                    <div class="w-full md:w-1/3 bg-white p-6 rounded-2xl shadow-sm border border-slate-200 h-fit">
                        <h3 class="font-bold text-slate-800 mb-4">➕ Tambah Mapel</h3>
                        <form action="{{ route('admin.storeMapel') }}" method="POST" class="space-y-4">
                            @csrf
                            <input type="text" name="nama_mapel" placeholder="Nama Mata Pelajaran" class="w-full border p-3 rounded-xl focus:ring-blue-500 outline-none border-slate-200" required>
                            <input type="text" name="kode_mapel" placeholder="Kode (MTK)" class="w-full border p-3 rounded-xl focus:ring-blue-500 outline-none border-slate-200">
                            <button type="submit" class="w-full bg-blue-600 text-white font-bold py-3 rounded-xl hover:bg-blue-700 transition-colors">Simpan Mapel</button>
                        </form>
                    </div>
                    <div class="w-full md:w-2/3 bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                        <table class="w-full text-sm text-left">
                            <thead class="bg-slate-50 text-slate-500 font-bold uppercase text-xs">
                                <tr><th class="p-4">Mata Pelajaran</th><th class="p-4">Kode</th><th class="p-4 text-center">Aksi</th></tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($mapel as $m)
                                <tr class="hover:bg-slate-50">
                                    <td class="p-4 font-bold text-slate-700">{{ $m->nama_mapel }}</td>
                                    <td class="p-4 font-mono text-slate-500 text-xs">{{ $m->kode_mapel }}</td>
                                    <td class="p-4 text-center">
                                        <button @click="confirmDelete('{{ route('admin.delete', ['type'=>'mapel', 'id'=>$m->id]) }}', 'Jurnal mengajar terkait mapel ini mungkin terganggu.')" class="text-rose-500 hover:underline text-xs font-bold">Hapus</button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- MODAL IMPORT (BARU) -->
            <div x-show="importModalOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <div x-show="importModalOpen" x-transition.opacity class="fixed inset-0 bg-slate-900 bg-opacity-75 transition-opacity" aria-hidden="true" @click="importModalOpen = false"></div>
                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                    <div x-show="importModalOpen" x-transition.scale class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="flex justify-between items-center mb-4 border-b pb-3">
                                <h3 class="text-lg leading-6 font-bold text-slate-900">📥 Import Data <span x-text="importRole == 'guru' ? 'Guru' : 'Siswa'"></span></h3>
                                <button @click="importModalOpen = false" class="text-slate-400 hover:text-slate-600"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                            </div>

                            <form action="{{ route('admin.importUser') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                                @csrf
                                <input type="hidden" name="role" x-model="importRole">
                                
                                <div class="bg-blue-50 p-4 rounded-xl border border-blue-100 text-sm text-blue-800">
                                    <p class="font-bold mb-1">Panduan Format File (CSV):</p>
                                    <ul class="list-disc list-inside space-y-1 text-xs">
                                        <li>Gunakan format <strong>.csv (Comma delimited)</strong> dari Excel.</li>
                                        <li>Baris pertama adalah <strong>Header</strong> (tidak akan diimport).</li>
                                        <li>Urutan kolom wajib:</li>
                                        <li x-show="importRole == 'guru'" class="font-mono bg-white p-1 rounded border border-blue-200 mt-1">Nama, NIP, Email, Password</li>
                                        <li x-show="importRole == 'siswa'" class="font-mono bg-white p-1 rounded border border-blue-200 mt-1">Nama, NISN, Email, Password, Nama Kelas (Contoh: 7A)</li>
                                    </ul>
                                </div>

                                <div>
                                    <label class="block text-slate-600 text-xs font-bold uppercase mb-2">Pilih File CSV</label>
                                    <input type="file" name="file" accept=".csv" class="w-full border p-2 rounded-xl focus:ring-blue-500 outline-none file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" required>
                                </div>

                                <div class="flex justify-end pt-4">
                                    <button type="submit" class="w-full bg-emerald-600 text-white px-4 py-3 rounded-xl text-sm font-bold hover:bg-emerald-700 shadow-md flex justify-center items-center gap-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                        Mulai Upload
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- MODAL EDIT USER (TETAP SAMA) -->
            <div x-show="editModalOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <div x-show="editModalOpen" x-transition.opacity class="fixed inset-0 bg-slate-900 bg-opacity-75 transition-opacity" aria-hidden="true" @click="editModalOpen = false"></div>
                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                    <div x-show="editModalOpen" x-transition.scale class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-lg leading-6 font-bold text-slate-900 mb-5 border-b border-slate-100 pb-2">✏️ Edit Data Pengguna</h3>
                            <form :action="'/data-master/user/update/' + editData.id" method="POST" class="space-y-4">
                                @csrf
                                <div>
                                    <label class="block text-slate-600 text-xs font-bold uppercase mb-1">Nama Lengkap</label>
                                    <input type="text" name="name" x-model="editData.name" class="w-full border p-2.5 rounded-xl focus:ring-blue-500 outline-none" required>
                                </div>
                                <div>
                                    <label class="block text-slate-600 text-xs font-bold uppercase mb-1">Email</label>
                                    <input type="email" name="email" x-model="editData.email" class="w-full border p-2.5 rounded-xl focus:ring-blue-500 outline-none" required>
                                </div>
                                <div>
                                    <label class="block text-slate-600 text-xs font-bold uppercase mb-1">ID (NIP/NISN)</label>
                                    <input type="text" name="nip_nis" x-model="editData.nip_nis" class="w-full border p-2.5 rounded-xl focus:ring-blue-500 outline-none" required>
                                </div>
                                <div x-show="editData.role == 'siswa'">
                                    <label class="block text-slate-600 text-xs font-bold uppercase mb-1">Kelas</label>
                                    <select name="kelas_id" x-model="editData.kelas_id" class="w-full border p-2.5 rounded-xl focus:ring-blue-500 outline-none bg-white">
                                        <option value="">Pilih Kelas</option>
                                        @foreach($kelas as $k)
                                            <option value="{{ $k->id }}">{{ $k->nama_kelas }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-slate-600 text-xs font-bold uppercase mb-1">Password Baru <span class="text-slate-400 normal-case font-normal">(Biarkan kosong jika tetap)</span></label>
                                    <input type="password" name="password" class="w-full border p-2.5 rounded-xl focus:ring-blue-500 outline-none">
                                </div>
                                <div class="flex justify-end pt-4 gap-2">
                                    <button type="button" @click="editModalOpen = false" class="bg-white border border-slate-300 text-slate-700 px-4 py-2 rounded-xl text-sm font-bold hover:bg-slate-50">Batal</button>
                                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-xl text-sm font-bold hover:bg-blue-700 shadow-md">Simpan Perubahan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- MODAL EDIT KELAS (TETAP SAMA) -->
            <div x-show="editKelasOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <div x-show="editKelasOpen" x-transition.opacity class="fixed inset-0 bg-slate-900 bg-opacity-75 transition-opacity" aria-hidden="true" @click="editKelasOpen = false"></div>
                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                    <div x-show="editKelasOpen" x-transition.scale class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-lg leading-6 font-bold text-slate-900 mb-5 border-b border-slate-100 pb-2">✏️ Edit Data Kelas</h3>
                            <form :action="'/data-master/kelas/update/' + kelasData.id" method="POST" class="space-y-4">
                                @csrf
                                <div>
                                    <label class="block text-slate-600 text-xs font-bold uppercase mb-1">Nama Kelas</label>
                                    <input type="text" name="nama_kelas" x-model="kelasData.nama_kelas" class="w-full border p-2.5 rounded-xl focus:ring-blue-500 outline-none" required>
                                </div>
                                <div>
                                    <label class="block text-slate-600 text-xs font-bold uppercase mb-1">Wali Kelas</label>
                                    <select name="wali_kelas_id" x-model="kelasData.wali_kelas_id" class="w-full border p-2.5 rounded-xl focus:ring-blue-500 outline-none bg-white">
                                        <option value="">-- Pilih Wali Kelas --</option>
                                        @foreach($guru as $g)
                                            <option value="{{ $g->id }}">{{ $g->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="flex justify-end pt-4 gap-2">
                                    <button type="button" @click="editKelasOpen = false" class="bg-white border border-slate-300 text-slate-700 px-4 py-2 rounded-xl text-sm font-bold hover:bg-slate-50">Batal</button>
                                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-xl text-sm font-bold hover:bg-blue-700 shadow-md">Simpan Perubahan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </main>
    </div>
</body>
</html>