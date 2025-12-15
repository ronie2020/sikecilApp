@extends('layouts.app')

@section('content')
    <!-- Bungkus seluruh konten dengan x-data untuk state modal -->
    <div x-data="{ showModal: false }" class="max-w-7xl mx-auto">
        
        <!-- Header Halaman -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <div>
                <h2 class="text-2xl font-bold text-slate-800">Manajemen Ekstrakurikuler</h2>
                <p class="text-slate-500 text-sm">Kelola informasi kegiatan dan input kehadiran siswa.</p>
            </div>
            <!-- Tombol Trigger Modal dengan Alpine JS -->
            <button @click="showModal = true" 
                    class="bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2.5 rounded-full text-sm font-bold shadow-lg shadow-emerald-200 transition-all flex items-center gap-2 transform hover:-translate-y-0.5">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Tambah Ekskul Baru
            </button>
        </div>

        <!-- Notifikasi Sukses -->
        @if(session('success'))
            <div class="bg-emerald-100 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl mb-6 flex items-center gap-2 animate-pulse">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                {{ session('success') }}
            </div>
        @endif

        <!-- Grid Ekskul -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($ekskuls as $ekskul)
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden hover:shadow-md transition-all group flex flex-col h-full">
                <!-- Cover Image -->
                <div class="h-40 bg-slate-100 relative overflow-hidden shrink-0">
                    @if($ekskul->foto)
                        <img src="{{ asset('storage/'.$ekskul->foto) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    @else
                        <div class="w-full h-full flex flex-col items-center justify-center bg-slate-50 text-slate-400">
                            <svg class="w-10 h-10 mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <span class="text-xs font-bold uppercase">No Image</span>
                        </div>
                    @endif
                    <div class="absolute top-3 right-3 bg-white/90 backdrop-blur px-2 py-1 rounded-lg text-[10px] font-bold uppercase shadow-sm tracking-wide
                        {{ $ekskul->kategori == 'olahraga' ? 'text-emerald-600' : ($ekskul->kategori == 'seni' ? 'text-rose-500' : 'text-blue-600') }}">
                        {{ $ekskul->kategori }}
                    </div>
                </div>
                
                <!-- Content -->
                <div class="p-5 flex flex-col flex-grow">
                    <div class="flex justify-between items-start mb-3">
                        <h3 class="font-bold text-lg text-slate-800 line-clamp-1">{{ $ekskul->nama }}</h3>
                        
                        <!-- Tombol Hapus -->
                        <form action="{{ route('admin.ekskul.destroy', $ekskul->id) }}" method="POST" onsubmit="return confirm('Yakin hapus ekskul ini?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-slate-300 hover:text-rose-500 transition-colors p-1" title="Hapus Data">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </form>
                    </div>
                    
                    <div class="space-y-2 mb-6 flex-grow">
                        <div class="flex items-center gap-2 text-xs text-slate-500">
                            <svg class="w-4 h-4 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            <span><span class="font-semibold text-slate-700">{{ $ekskul->pembina }}</span></span>
                        </div>
                        <div class="flex items-center gap-2 text-xs text-slate-500">
                            <svg class="w-4 h-4 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span><span class="font-semibold text-slate-700">{{ $ekskul->jadwal }}</span></span>
                        </div>
                    </div>

                    <!-- Tombol Aksi -->
                    <a href="{{ route('admin.ekskul.presensi', $ekskul->id) }}" class="w-full bg-slate-50 text-slate-600 py-2.5 rounded-xl text-xs font-bold text-center hover:bg-emerald-600 hover:text-white transition-all border border-slate-200 hover:border-emerald-600 flex items-center justify-center gap-2 group-hover/btn">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                        Input Absensi
                    </a>
                </div>
            </div>
            @empty
            <div class="col-span-full flex flex-col items-center justify-center py-16 bg-white rounded-3xl border-2 border-dashed border-slate-200">
                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                </div>
                <h3 class="text-slate-900 font-bold text-lg mb-1">Belum ada Ekskul</h3>
                <p class="text-slate-500 text-sm mb-6">Silakan tambahkan kegiatan ekstrakurikuler baru.</p>
                <button @click="showModal = true" class="text-emerald-600 font-bold text-sm hover:underline">
                    + Tambah Sekarang
                </button>
            </div>
            @endforelse
        </div>

        <!-- MODAL TAMBAH EKSKUL (Alpine Version - Lebih Stabil) -->
        <div x-show="showModal" 
             style="display: none;"
             class="fixed inset-0 z-50 overflow-y-auto" 
             aria-labelledby="modal-title" role="dialog" aria-modal="true">
            
            <!-- Backdrop Gelap (Klik ini untuk close) -->
            <div x-show="showModal"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" 
                 @click="showModal = false"></div>
        
            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <!-- Modal Panel -->
                <div x-show="showModal"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-slate-100">
                    
                    <!-- Header Modal -->
                    <div class="bg-slate-50 px-6 py-4 border-b border-slate-100 flex justify-between items-center">
                        <h3 class="font-bold text-lg text-slate-800" id="modal-title">Tambah Kegiatan Baru</h3>
                        <!-- Tombol Close (X) -->
                        <button @click="showModal = false" type="button" class="text-slate-400 hover:text-slate-600 transition-colors">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Form Content -->
                    <form action="{{ route('admin.ekskul.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
                        @csrf
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Nama Ekskul</label>
                            <input type="text" name="nama" class="w-full rounded-lg border-slate-200 focus:border-emerald-500 focus:ring focus:ring-emerald-200 transition-all placeholder:text-slate-300" placeholder="Contoh: Futsal Club" required>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Pembina</label>
                                <input type="text" name="pembina" class="w-full rounded-lg border-slate-200 focus:border-emerald-500 focus:ring focus:ring-emerald-200 transition-all placeholder:text-slate-300" placeholder="Nama Guru" required>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Kategori</label>
                                <select name="kategori" class="w-full rounded-lg border-slate-200 focus:border-emerald-500 focus:ring focus:ring-emerald-200 transition-all text-slate-700">
                                    <option value="olahraga">Olahraga</option>
                                    <option value="seni">Seni & Kreatif</option>
                                    <option value="akademik">Akademik</option>
                                    <option value="teknologi">Teknologi</option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Jadwal Latihan</label>
                            <input type="text" name="jadwal" class="w-full rounded-lg border-slate-200 focus:border-emerald-500 focus:ring focus:ring-emerald-200 transition-all placeholder:text-slate-300" placeholder="Contoh: Senin, 15.00 WIB">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Deskripsi Singkat</label>
                            <textarea name="deskripsi" rows="3" class="w-full rounded-lg border-slate-200 focus:border-emerald-500 focus:ring focus:ring-emerald-200 transition-all placeholder:text-slate-300" placeholder="Jelaskan kegiatan ini..."></textarea>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Foto Cover (Opsional)</label>
                            <input type="file" name="foto" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 transition-all cursor-pointer">
                        </div>
                        
                        <div class="pt-4 flex gap-3">
                            <button type="button" @click="showModal = false" class="flex-1 py-3 rounded-xl border border-slate-200 text-slate-600 font-bold hover:bg-slate-50 transition-colors">Batal</button>
                            <button type="submit" class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 rounded-xl shadow-lg shadow-emerald-200 transition-all">Simpan Data</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
@endsection