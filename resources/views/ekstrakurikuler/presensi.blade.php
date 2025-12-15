@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="flex items-center gap-4 mb-8">
            <a href="{{ route('admin.ekskul.index') }}" class="w-10 h-10 flex items-center justify-center rounded-full bg-white border border-slate-200 text-slate-500 hover:bg-slate-50 hover:text-emerald-600 transition-all shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </a>
            <div>
                <h2 class="text-2xl font-bold text-slate-800">Input Kehadiran</h2>
                <div class="flex items-center gap-2 text-sm text-slate-500">
                    <span>Ekskul: <strong class="text-emerald-600">{{ $ekskul->nama }}</strong></span>
                </div>
            </div>
        </div>

        <form action="{{ route('admin.ekskul.presensi.store', $ekskul->id) }}" method="POST">
            @csrf
            
            <!-- Card Atas: Picker Tanggal -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 mb-6 flex flex-col md:flex-row items-end md:items-center gap-6">
                <div class="flex-1 w-full">
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Pilih Tanggal Kegiatan</label>
                    <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" class="w-full font-bold text-slate-800 border-slate-200 rounded-xl focus:ring-emerald-500 focus:border-emerald-500 h-12">
                </div>
                <div class="hidden md:block w-px h-12 bg-slate-100"></div>
                <div class="flex-1 w-full text-right md:text-left">
                    <span class="block text-xs font-bold text-slate-400 uppercase mb-1">Total Siswa Terdaftar</span>
                    <p class="text-3xl font-extrabold text-slate-800 leading-none">{{ $siswas->count() }} <span class="text-sm font-medium text-slate-400">Anak</span></p>
                </div>
            </div>

            <!-- Card Bawah: List Siswa -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden mb-8">
                <div class="bg-slate-50/50 px-6 py-4 border-b border-slate-100 flex justify-between items-center">
                    <h3 class="font-bold text-slate-700 text-sm uppercase tracking-wide">Daftar Absensi</h3>
                    <span class="text-xs text-slate-400 italic">Default: Hadir</span>
                </div>
                
                <div class="max-h-[500px] overflow-y-auto custom-scrollbar">
                    <table class="w-full text-left">
                        <thead class="bg-white sticky top-0 z-10 text-xs font-bold text-slate-500 uppercase tracking-wider shadow-sm">
                            <tr>
                                <th class="p-4 border-b border-slate-100 bg-slate-50">Nama Siswa</th>
                                <th class="p-4 border-b border-slate-100 text-center w-20 bg-emerald-50 text-emerald-700">Hadir</th>
                                <th class="p-4 border-b border-slate-100 text-center w-20 bg-blue-50 text-blue-700">Sakit</th>
                                <th class="p-4 border-b border-slate-100 text-center w-20 bg-amber-50 text-amber-700">Izin</th>
                                <th class="p-4 border-b border-slate-100 text-center w-20 bg-rose-50 text-rose-700">Alpa</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white">
                            @foreach($siswas as $siswa)
                            <tr class="hover:bg-slate-50 transition-colors group">
                                <td class="p-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-full bg-gradient-to-br from-slate-100 to-slate-200 flex items-center justify-center text-slate-600 text-sm font-bold border border-slate-200">
                                            {{ substr($siswa->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="font-bold text-slate-700 text-sm group-hover:text-emerald-700 transition-colors">{{ $siswa->name }}</p>
                                            <p class="text-[10px] text-slate-400">{{ $siswa->nisn ?? 'NISN -' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-4 text-center bg-emerald-50/20 group-hover:bg-emerald-50/50 transition-colors">
                                    <label class="cursor-pointer flex justify-center h-full w-full items-center">
                                        <input type="radio" name="status[{{ $siswa->id }}]" value="Hadir" checked 
                                            class="w-5 h-5 text-emerald-600 border-2 border-slate-300 focus:ring-emerald-500 transition-all cursor-pointer">
                                    </label>
                                </td>
                                <td class="p-4 text-center group-hover:bg-blue-50/30 transition-colors">
                                    <label class="cursor-pointer flex justify-center h-full w-full items-center">
                                        <input type="radio" name="status[{{ $siswa->id }}]" value="Sakit" 
                                            class="w-5 h-5 text-blue-600 border-2 border-slate-300 focus:ring-blue-500 transition-all cursor-pointer">
                                    </label>
                                </td>
                                <td class="p-4 text-center group-hover:bg-amber-50/30 transition-colors">
                                    <label class="cursor-pointer flex justify-center h-full w-full items-center">
                                        <input type="radio" name="status[{{ $siswa->id }}]" value="Izin" 
                                            class="w-5 h-5 text-amber-600 border-2 border-slate-300 focus:ring-amber-500 transition-all cursor-pointer">
                                    </label>
                                </td>
                                <td class="p-4 text-center group-hover:bg-rose-50/30 transition-colors">
                                    <label class="cursor-pointer flex justify-center h-full w-full items-center">
                                        <input type="radio" name="status[{{ $siswa->id }}]" value="Alpa" 
                                            class="w-5 h-5 text-rose-600 border-2 border-slate-300 focus:ring-rose-500 transition-all cursor-pointer">
                                    </label>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Sticky Footer Action -->
            <div class="sticky bottom-6 flex justify-end">
                <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-4 px-10 rounded-2xl shadow-xl shadow-emerald-200 hover:shadow-2xl hover:shadow-emerald-300 transition-all transform hover:-translate-y-1 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                    Simpan Data Presensi
                </button>
            </div>
        </form>
    </div>
@endsection