<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Data Master - SiKecil</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        [x-cloak] { display: none !important; }
        
        .custom-scrollbar::-webkit-scrollbar { width: 6px; height: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #f1f5f9; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

        /* --- LOGIKA CSS KHUSUS CETAK --- */
        #print-area { display: none; }

        @media print {
            body { margin: 0; padding: 0; background: white; }
            nav, .main-content, .fixed, header { display: none !important; }
            #print-area { 
                display: flex !important; 
                position: fixed; 
                top: 0; 
                left: 0; 
                width: 100vw;
                height: 100vh;
                justify-content: center;
                align-items: center;
                background-color: white;
                z-index: 99999;
                visibility: visible !important;
            }
            .force-print-bg {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            @page { margin: 0; size: auto; }
        }
    </style>
</head>
<body class="bg-slate-50 flex text-slate-800" x-data="{ 
    activeTab: 'siswa',
    
    // Modal Edit
    editModalOpen: false, editData: {},
    openEdit(user) { this.editData = user; this.editModalOpen = true; },
    
    editKelasOpen: false, kelasData: {},
    openEditKelas(kelas) { this.kelasData = kelas; this.editKelasOpen = true; },
    
    // Import
    importModalOpen: false, importRole: 'guru',
    openImport(role) { this.importRole = role; this.importModalOpen = true; },

    // QR Code & Print
    qrModalOpen: false,
    qrUser: { id: '', name: '', nis: '', kelas: '' },
    openQr(user) { 
        this.qrUser = user; 
        this.qrModalOpen = true; 
    },
    printQr() {
        setTimeout(() => window.print(), 100);
    },
    
    confirmDelete(url, message) {
        Swal.fire({
            title: 'Apakah Anda yakin?', text: message, icon: 'warning',
            showCancelButton: true, confirmButtonColor: '#059669', cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Hapus!', cancelButtonText: 'Batal'
        }).then((result) => { if (result.isConfirmed) window.location.href = url; })
    }
}">

    <!-- SIDEBAR -->
    @include('components.sidebar')

    <!-- KONTEN UTAMA -->
    <div class="flex-1 flex flex-col h-screen overflow-hidden main-content">
        
        <header class="bg-white px-6 py-4 shadow-sm border-b border-slate-200 flex justify-between items-center z-20">
            <div>
                <h1 class="text-xl font-extrabold text-slate-800 tracking-tight">📂 Data Master Sekolah</h1>
                <p class="text-xs text-slate-500">Kelola data guru, siswa, kelas, dan mata pelajaran.</p>
            </div>
            <div class="flex items-center gap-3 border-l border-slate-200 pl-4">
                <div class="text-right hidden sm:block">
                    <div class="text-sm font-bold text-slate-800">{{ Auth::user()->name }}</div>
                    <div class="text-xs text-slate-500">Administrator</div>
                </div>
                <div class="w-10 h-10 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
            </div>
        </header>

        <main class="flex-1 overflow-auto p-6 relative custom-scrollbar bg-slate-50/50">
            
            @if(session('success'))
            <script>document.addEventListener('DOMContentLoaded', () => Swal.fire({icon: 'success', title: 'Berhasil!', text: "{{ session('success') }}", timer: 3000, showConfirmButton: false, confirmButtonColor: '#059669'}));</script>
            @endif
            @if(session('error'))
            <script>document.addEventListener('DOMContentLoaded', () => Swal.fire({icon: 'error', title: 'Gagal!', text: "{{ session('error') }}"}));</script>
            @endif

            <!-- Tabs -->
            <div class="flex space-x-1 mb-6 bg-white p-1.5 rounded-xl shadow-sm border border-slate-200 w-fit overflow-x-auto no-scrollbar">
                <button @click="activeTab = 'guru'" :class="activeTab === 'guru' ? 'bg-emerald-600 text-white shadow-md' : 'text-slate-500 hover:text-slate-700 hover:bg-slate-50'" class="py-2 px-5 font-bold rounded-lg transition-all text-sm whitespace-nowrap">👨‍🏫 Guru</button>
                <button @click="activeTab = 'siswa'" :class="activeTab === 'siswa' ? 'bg-emerald-600 text-white shadow-md' : 'text-slate-500 hover:text-slate-700 hover:bg-slate-50'" class="py-2 px-5 font-bold rounded-lg transition-all text-sm whitespace-nowrap">🎓 Siswa</button>
                <button @click="activeTab = 'kelas'" :class="activeTab === 'kelas' ? 'bg-emerald-600 text-white shadow-md' : 'text-slate-500 hover:text-slate-700 hover:bg-slate-50'" class="py-2 px-5 font-bold rounded-lg transition-all text-sm whitespace-nowrap">🏫 Kelas</button>
                <button @click="activeTab = 'mapel'" :class="activeTab === 'mapel' ? 'bg-emerald-600 text-white shadow-md' : 'text-slate-500 hover:text-slate-700 hover:bg-slate-50'" class="py-2 px-5 font-bold rounded-lg transition-all text-sm whitespace-nowrap">📚 Mapel</button>
            </div>

            <!-- TAB GURU -->
            <div x-show="activeTab === 'guru'" x-transition.opacity.duration.300ms class="space-y-6">
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
                    <div class="flex justify-between items-center mb-5 border-b border-slate-100 pb-3">
                        <h3 class="font-bold text-slate-800 text-lg flex items-center gap-2">
                            <span class="p-1.5 bg-emerald-100 text-emerald-600 rounded-lg"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg></span>
                            Tambah Guru Baru
                        </h3>
                        <button @click="openImport('guru')" class="text-sm flex items-center gap-2 text-emerald-600 bg-emerald-50 px-3 py-1.5 rounded-lg border border-emerald-200 hover:bg-emerald-100 font-bold transition-all">Import CSV</button>
                    </div>
                    <form action="{{ route('admin.storeUser') }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                        @csrf <input type="hidden" name="role" value="guru">
                        <div class="space-y-1"><label class="text-xs font-bold text-slate-500 uppercase">Nama Lengkap</label><input type="text" name="name" class="w-full border border-slate-200 p-2.5 rounded-xl focus:ring-2 focus:ring-emerald-500 outline-none" required></div>
                        <div class="space-y-1"><label class="text-xs font-bold text-slate-500 uppercase">NIP</label><input type="text" name="nip_nis" class="w-full border border-slate-200 p-2.5 rounded-xl focus:ring-2 focus:ring-emerald-500 outline-none" required></div>
                        <div class="space-y-1"><label class="text-xs font-bold text-slate-500 uppercase">Email</label><input type="email" name="email" class="w-full border border-slate-200 p-2.5 rounded-xl focus:ring-2 focus:ring-emerald-500 outline-none" required></div>
                        <div class="space-y-1"><label class="text-xs font-bold text-slate-500 uppercase">Password</label><input type="password" name="password" class="w-full border border-slate-200 p-2.5 rounded-xl focus:ring-2 focus:ring-emerald-500 outline-none" required></div>
                        <div class="hidden lg:block"></div>
                        <div class="flex items-end"><button type="submit" class="w-full bg-emerald-600 text-white font-bold py-2.5 rounded-xl hover:bg-emerald-700 shadow-lg shadow-emerald-200 transition-all active:scale-95">Simpan Data Guru</button></div>
                    </form>
                </div>
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-slate-50 text-slate-500 font-bold uppercase text-xs tracking-wider border-b border-slate-200">
                            <tr><th class="px-6 py-4">Nama Guru</th><th class="px-6 py-4">NIP</th><th class="px-6 py-4">Tugas</th><th class="px-6 py-4 text-center">Aksi</th></tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($guru as $g)
                            <tr class="hover:bg-slate-50/80">
                                <td class="px-6 py-4 font-bold text-slate-700 flex items-center gap-3"><div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-xs">{{ substr($g->name, 0, 1) }}</div>{{ $g->name }}</td>
                                <td class="px-6 py-4 font-mono">{{ $g->nip_nis }}</td>
                                <td class="px-6 py-4">
                                    @if($g->kelasWali) <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-teal-100 text-teal-800 border border-teal-200">Wali {{ $g->kelasWali->nama_kelas }}</span>
                                    @else <span class="text-slate-400 text-xs italic">Guru Mapel</span> @endif
                                </td>
                                <td class="px-6 py-4 text-center flex justify-center gap-2">
                                    <button @click="openEdit({{ $g }})" class="text-emerald-600 font-bold text-xs">Edit</button>
                                    <button @click="confirmDelete('{{ route('admin.delete', ['type'=>'user', 'id'=>$g->id]) }}', 'Hapus?')" class="text-rose-500 font-bold text-xs">Hapus</button>
                                </td>
                            </tr>
                            @empty <tr><td colspan="4" class="px-6 py-10 text-center text-slate-400">Tidak ada data.</td></tr> @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- TAB SISWA -->
            <div x-show="activeTab === 'siswa'" x-transition.opacity.duration.300ms class="space-y-6">
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
                    <div class="flex justify-between items-center mb-5 border-b border-slate-100 pb-3">
                        <h3 class="font-bold text-slate-800 flex items-center gap-2">
                            <span class="bg-emerald-100 text-emerald-600 p-1.5 rounded-lg"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg></span>
                            Tambah Siswa Baru
                        </h3>
                        <button @click="openImport('siswa')" class="text-sm flex items-center gap-2 text-emerald-600 bg-emerald-50 px-3 py-1.5 rounded-lg border border-emerald-200 hover:bg-emerald-100 font-bold transition-all">Import CSV</button>
                    </div>
                    <form action="{{ route('admin.storeUser') }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @csrf <input type="hidden" name="role" value="siswa">
                        <input type="text" name="name" placeholder="Nama Lengkap" class="border border-slate-200 p-2.5 rounded-xl w-full focus:ring-emerald-500 outline-none" required>
                        <input type="text" name="nip_nis" placeholder="NISN" class="border border-slate-200 p-2.5 rounded-xl w-full focus:ring-emerald-500 outline-none" required>
                        <input type="email" name="email" placeholder="Email" class="border border-slate-200 p-2.5 rounded-xl w-full focus:ring-emerald-500 outline-none" required>
                        <input type="password" name="password" placeholder="Password" class="border border-slate-200 p-2.5 rounded-xl w-full focus:ring-emerald-500 outline-none" required>
                        <select name="kelas_id" class="border border-slate-200 p-2.5 rounded-xl w-full focus:ring-emerald-500 outline-none bg-white" required>
                            <option value="">Pilih Kelas</option>
                            @foreach($kelas as $k) <option value="{{ $k->id }}">{{ $k->nama_kelas }}</option> @endforeach
                        </select>
                        <button type="submit" class="bg-emerald-600 text-white font-bold py-2.5 rounded-xl hover:bg-emerald-700 transition-colors shadow-lg shadow-emerald-100">Simpan Siswa</button>
                    </form>
                </div>
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-slate-50 text-slate-500 font-bold uppercase text-xs">
                            <tr><th class="p-4">Nama</th><th class="p-4">NISN</th><th class="p-4">Kelas</th><th class="p-4 text-center">QR Code</th><th class="p-4 text-center">Aksi</th></tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <!-- PERBAIKAN SORTING: Urutkan Nama -> lalu Urutkan Kelas -->
                            @foreach($siswa->sortBy('name')->sortBy('kelas.nama_kelas') as $s)
                            <tr class="hover:bg-slate-50">
                                <td class="p-4 font-bold text-slate-700">{{ $s->name }}</td>
                                <td class="p-4 font-mono text-slate-600">{{ $s->nip_nis }}</td>
                                <td class="p-4"><span class="bg-emerald-50 text-emerald-600 text-xs font-bold px-2 py-1 rounded border border-emerald-100">{{ $s->kelas->nama_kelas ?? '-' }}</span></td>
                                <td class="p-4 text-center">
                                    <button @click="openQr({ id: '{{ $s->id }}', name: '{{ addslashes($s->name) }}', nis: '{{ $s->nip_nis }}', kelas: '{{ $s->kelas->nama_kelas ?? 'Umum' }}' })" class="text-xs bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold px-3 py-1.5 rounded-lg border border-slate-200 flex items-center justify-center gap-1 mx-auto transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4h2v-4zM6 8v4M6 20v-4M2 20h4M2 4h4M2 12h2m8 0h2M2 8v4M2 16h2M6 16h2M6 12h4m0-8h4m4 0h4M14 8h-2M10 8h2M10 4h2m4 0h2M18 8h2m0 4h2M18 16h2m-2 4h2M2 12v4m0 4v-4m10-4v4m2-4v4m4-4v4M6 4v4m12 0v4"></path></svg> QR
                                    </button>
                                </td>
                                <td class="p-4 text-center flex justify-center gap-2">
                                    <button @click="openEdit({{ $s }})" class="text-emerald-600 hover:underline text-xs font-bold">Edit</button>
                                    <button @click="confirmDelete('{{ route('admin.delete', ['type'=>'user', 'id'=>$s->id]) }}', 'Hapus?')" class="text-rose-500 hover:underline text-xs font-bold">Hapus</button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- TAB KELAS -->
            <div x-show="activeTab === 'kelas'" style="display: none;">
                <div class="flex flex-col md:flex-row gap-6">
                    <div class="w-full md:w-1/3 bg-white p-6 rounded-2xl shadow-sm border border-slate-200 h-fit">
                        <h3 class="font-bold text-slate-800 mb-4">➕ Tambah Kelas</h3>
                        <form action="{{ route('admin.storeKelas') }}" method="POST" class="space-y-4">
                            @csrf <input type="text" name="nama_kelas" placeholder="Nama Kelas (7A)" class="w-full border p-3 rounded-xl focus:ring-emerald-500 outline-none" required>
                            <select name="wali_kelas_id" class="w-full border p-3 rounded-xl focus:ring-emerald-500 outline-none bg-white">
                                <option value="">-- Wali Kelas (Opsional) --</option>
                                @foreach($guru as $g) <option value="{{ $g->id }}">{{ $g->name }}</option> @endforeach
                            </select>
                            <button type="submit" class="w-full bg-emerald-600 text-white font-bold py-3 rounded-xl hover:bg-emerald-700">Simpan Kelas</button>
                        </form>
                    </div>
                    <div class="w-full md:w-2/3 bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                        <table class="w-full text-sm text-left">
                            <thead class="bg-slate-50 font-bold uppercase text-xs"><tr><th class="p-4">Kelas</th><th class="p-4">Wali Kelas</th><th class="p-4 text-center">Aksi</th></tr></thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($kelas as $k)
                                <tr class="hover:bg-slate-50">
                                    <td class="p-4 font-bold text-lg text-slate-700">{{ $k->nama_kelas }}</td>
                                    <td class="p-4">
                                        @if($k->waliKelas) <div class="flex items-center gap-2 text-emerald-700 font-medium"><div class="w-6 h-6 rounded-full bg-emerald-100 flex items-center justify-center text-xs">{{ substr($k->waliKelas->name, 0, 1) }}</div>{{ $k->waliKelas->name }}</div>
                                        @else <span class="text-slate-400 italic text-xs">Belum ada wali</span> @endif
                                    </td>
                                    <td class="p-4 text-center flex justify-center gap-2">
                                        <button @click="openEditKelas({{ $k }})" class="text-emerald-600 font-medium text-xs">Edit</button>
                                        <button @click="confirmDelete('{{ route('admin.delete', ['type'=>'kelas', 'id'=>$k->id]) }}', 'Hapus?')" class="text-rose-500 font-medium text-xs">Hapus</button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- TAB MAPEL -->
            <div x-show="activeTab === 'mapel'" style="display: none;">
                 <div class="flex flex-col md:flex-row gap-6">
                    <div class="w-full md:w-1/3 bg-white p-6 rounded-2xl shadow-sm border border-slate-200 h-fit">
                        <h3 class="font-bold text-slate-800 mb-4">➕ Tambah Mapel</h3>
                        <form action="{{ route('admin.storeMapel') }}" method="POST" class="space-y-4">
                            @csrf <input type="text" name="nama_mapel" placeholder="Nama Mata Pelajaran" class="w-full border p-3 rounded-xl focus:ring-emerald-500 outline-none" required>
                            <input type="text" name="kode_mapel" placeholder="Kode (MTK)" class="w-full border p-3 rounded-xl focus:ring-emerald-500 outline-none">
                            <button type="submit" class="w-full bg-emerald-600 text-white font-bold py-3 rounded-xl hover:bg-emerald-700">Simpan Mapel</button>
                        </form>
                    </div>
                    <div class="w-full md:w-2/3 bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                        <table class="w-full text-sm text-left">
                            <thead class="bg-slate-50 font-bold uppercase text-xs"><tr><th class="p-4">Mata Pelajaran</th><th class="p-4">Kode</th><th class="p-4 text-center">Aksi</th></tr></thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($mapel as $m)
                                <tr class="hover:bg-slate-50">
                                    <td class="p-4 font-bold text-slate-700">{{ $m->nama_mapel }}</td>
                                    <td class="p-4 font-mono text-slate-500 text-xs">{{ $m->kode_mapel }}</td>
                                    <td class="p-4 text-center"><button @click="confirmDelete('{{ route('admin.delete', ['type'=>'mapel', 'id'=>$m->id]) }}', 'Hapus?')" class="text-rose-500 font-bold text-xs">Hapus</button></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- MODAL IMPORT -->
            <div x-show="importModalOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                <div class="flex items-center justify-center min-h-screen px-4">
                    <div class="fixed inset-0 bg-slate-900 bg-opacity-75 transition-opacity" @click="importModalOpen = false"></div>
                    <div class="bg-white rounded-2xl overflow-hidden shadow-xl transform transition-all sm:max-w-lg w-full relative z-10 p-6">
                        <h3 class="text-lg font-bold text-slate-900 mb-4">Import Data CSV</h3>
                        <form action="{{ route('admin.importUser') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                            @csrf <input type="hidden" name="role" x-model="importRole">
                            <input type="file" name="file" class="w-full border p-2 rounded-xl" required>
                            <button type="submit" class="w-full bg-emerald-600 text-white font-bold py-2 rounded-xl">Upload</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- MODAL EDIT -->
            <div x-show="editModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/75 p-4" @click.self="editModalOpen=false">
                <div class="bg-white rounded-2xl w-full max-w-lg p-6 shadow-2xl">
                    <h3 class="font-bold text-lg mb-4">Edit Data</h3>
                    <form :action="'/data-master/user/update/' + editData.id" method="POST" class="space-y-4">
                        @csrf <input type="text" name="name" x-model="editData.name" class="w-full border p-2 rounded-lg" required>
                        <input type="email" name="email" x-model="editData.email" class="w-full border p-2 rounded-lg" required>
                        <input type="text" name="nip_nis" x-model="editData.nip_nis" class="w-full border p-2 rounded-lg" required>
                        <div x-show="editData.role == 'siswa'"><select name="kelas_id" x-model="editData.kelas_id" class="w-full border p-2 rounded-lg"><option value="">Pilih Kelas</option>@foreach($kelas as $k)<option value="{{ $k->id }}">{{ $k->nama_kelas }}</option>@endforeach</select></div>
                        <input type="password" name="password" placeholder="Password Baru (Opsional)" class="w-full border p-2 rounded-lg">
                        <div class="flex justify-end gap-2"><button type="button" @click="editModalOpen=false" class="px-4 py-2 border rounded-lg">Batal</button><button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-lg">Simpan</button></div>
                    </form>
                </div>
            </div>

            <!-- MODAL QR CODE & PRINT (Hidden but available via button) -->
            <div x-show="qrModalOpen" x-cloak class="fixed inset-0 z-[60] flex items-center justify-center bg-slate-900/80 p-4" @click.self="qrModalOpen=false">
                <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden relative">
                    <div class="p-8 flex flex-col items-center justify-center relative bg-white">
                        <div class="absolute top-0 left-0 w-full h-24 bg-gradient-to-r from-emerald-500 to-teal-600 z-0"></div>
                        <div class="relative z-10 bg-white p-2 rounded-2xl shadow-lg mb-4 mt-8">
                            <img :src="'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' + qrUser.id" alt="QR Code" class="w-40 h-40 rounded-xl">
                        </div>
                        <div class="relative z-10 text-center">
                            <h2 class="text-xl font-extrabold text-slate-800" x-text="qrUser.name"></h2>
                            <p class="text-sm font-medium text-emerald-600 mb-1" x-text="qrUser.nis"></p>
                            <span class="bg-slate-100 text-slate-500 text-xs font-bold px-3 py-1 rounded-full border border-slate-200" x-text="'Kelas ' + qrUser.kelas"></span>
                        </div>
                    </div>
                    <div class="bg-slate-50 px-6 py-4 flex flex-row-reverse gap-2 border-t border-slate-200">
                        <button @click="printQr()" class="w-full bg-emerald-600 text-white py-2 rounded-xl font-bold shadow-md hover:bg-emerald-700 transition flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2z"></path></svg> Cetak Kartu
                        </button>
                        <button @click="qrModalOpen=false" class="w-full bg-white border border-slate-300 text-slate-700 py-2 rounded-xl font-bold hover:bg-slate-50 transition">Tutup</button>
                    </div>
                </div>
            </div>

        </main>
    </div>

    <!-- AREA CETAK TERSEMBUNYI (Hanya Muncul Saat Print) -->
    <div id="print-area">
        <div class="flex flex-col items-center justify-center border-2 border-slate-200 rounded-3xl p-8 w-[350px] h-[550px] relative overflow-hidden bg-white shadow-none force-print-bg">
            <div class="absolute top-0 left-0 w-full h-32 bg-gradient-to-br from-emerald-500 to-teal-600 z-0 force-print-bg"></div>
            <div class="absolute bottom-0 right-0 w-40 h-40 bg-slate-50 rounded-full translate-x-10 translate-y-10 z-0 force-print-bg"></div>
            
            <div class="relative z-10 mb-6 text-white text-center">
                <h2 class="text-xl font-bold tracking-widest">KARTU PELAJAR</h2>
                <p class="text-[10px] opacity-90 uppercase tracking-widest">SiKecil School System</p>
            </div>

            <div class="relative z-10 bg-white p-2 rounded-2xl shadow-none border-4 border-white mb-6 force-print-bg">
                <img :src="'https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=' + qrUser.id" class="w-48 h-48 rounded-lg">
            </div>

            <div class="relative z-10 text-center w-full">
                <h1 class="text-2xl font-black text-slate-800 uppercase leading-tight mb-1" x-text="qrUser.name"></h1>
                <p class="text-lg font-mono text-emerald-600 font-bold mb-3" x-text="qrUser.nis"></p>
                <div class="inline-block border-2 border-slate-200 px-6 py-1.5 rounded-full text-sm font-bold text-slate-500 uppercase tracking-wide" x-text="'Kelas ' + qrUser.kelas"></div>
            </div>

            <div class="absolute bottom-6 w-full text-center">
                <p class="text-[10px] text-slate-400 uppercase tracking-widest">Scan untuk Absensi</p>
            </div>
        </div>
    </div>

</body>
</html>