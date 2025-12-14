<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tulis Jurnal - SiKecil</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="bg-slate-50 min-h-screen py-8 px-4 sm:px-6">

    <div class="max-w-3xl mx-auto">
        
        <!-- Header Nav -->
        <div class="flex items-center justify-between mb-8">
            <a href="{{ route('jurnal.index') }}" class="flex items-center text-slate-500 hover:text-slate-800 transition-colors group">
                <div class="w-8 h-8 rounded-full bg-white border border-slate-200 flex items-center justify-center mr-2 group-hover:border-indigo-300 group-hover:text-indigo-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </div>
                <span class="font-medium text-sm">Kembali</span>
            </a>
            <h1 class="text-xl font-bold text-slate-800">Jurnal Baru</h1>
            <!-- Auto-save Indicator -->
            <div id="saveStatus" class="text-xs font-medium text-slate-400 opacity-0 transition-opacity flex items-center gap-1">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                Draft tersimpan
            </div>
        </div>

        <!-- Main Form Card -->
        <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/50 overflow-hidden border border-slate-100">
            <div class="h-1.5 w-full bg-slate-100"><div class="h-full bg-gradient-to-r from-indigo-500 to-purple-500 w-1/3 rounded-r-full"></div></div>

            <div class="p-8">
                <div class="mb-8 text-center sm:text-left">
                    <h2 class="text-2xl font-bold text-slate-900">Dokumentasi Pembelajaran</h2>
                    <p class="text-slate-500 text-sm mt-1">Isi detail kegiatan belajar mengajar hari ini.</p>
                </div>

                <form action="{{ route('jurnal.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6" id="jurnalForm">
                    @csrf
                    
                    <!-- FITUR BARU: Tanggal Kegiatan -->
                    <div class="bg-indigo-50/50 p-4 rounded-xl border border-indigo-100">
                        <label class="text-xs font-bold text-indigo-900 uppercase tracking-wider mb-1 block">Tanggal Kegiatan</label>
                        <input type="date" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" class="w-full bg-white border border-indigo-200 text-slate-800 text-sm rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent p-2.5 outline-none font-medium" required>
                        <p class="text-[10px] text-indigo-400 mt-1">Default adalah hari ini. Ubah jika mengisi jurnal rapel.</p>
                    </div>

                    <!-- Row: Kelas & Mapel -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-wider ml-1">Kelas</label>
                            <div class="relative">
                                <select name="kelas_id" id="kelasInput" class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent block p-4 appearance-none transition-all cursor-pointer outline-none" required>
                                    <option value="" disabled selected>Pilih Kelas...</option>
                                    @foreach($kelas as $k)
                                        <option value="{{ $k->id }}" {{ old('kelas_id') == $k->id ? 'selected' : '' }}>Kelas {{ $k->nama_kelas }}</option>
                                    @endforeach
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-500"><svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg></div>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-wider ml-1">Mata Pelajaran</label>
                            <input type="text" name="mata_pelajaran" id="mapelInput" value="{{ old('mata_pelajaran') }}" class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent block p-4 outline-none placeholder-slate-400" placeholder="Contoh: Matematika" required>
                        </div>
                    </div>

                    <!-- Materi Pokok -->
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-wider ml-1">Topik / Materi</label>
                        <input type="text" name="materi_pokok" id="materiInput" value="{{ old('materi_pokok') }}" class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent block p-4 outline-none placeholder-slate-400" placeholder="Apa yang dipelajari hari ini?" required>
                    </div>

                    <!-- Deskripsi -->
                    <div class="space-y-2">
                        <div class="flex justify-between items-center ml-1">
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Deskripsi Kegiatan</label>
                            <span class="text-xs text-slate-400" id="charCount">0/500</span>
                        </div>
                        <textarea name="deskripsi_kegiatan" id="deskripsiTextarea" rows="6" maxlength="500" class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent block p-4 outline-none resize-none leading-relaxed placeholder-slate-400" placeholder="Ceritakan alur pembelajaran..." required>{{ old('deskripsi_kegiatan') }}</textarea>
                    </div>

                    <!-- File Upload (Sama seperti sebelumnya, solid implementation) -->
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-wider ml-1">Foto Dokumentasi</label>
                        <div id="uploadContainer" class="relative group">
                            <label id="dropZone" class="flex flex-col items-center justify-center w-full h-40 border-2 border-slate-200 border-dashed rounded-2xl cursor-pointer bg-slate-50 hover:bg-indigo-50/50 hover:border-indigo-300 transition-all duration-300 relative overflow-hidden">
                                <div id="emptyState" class="flex flex-col items-center justify-center pt-5 pb-6 text-center px-4">
                                    <svg class="w-8 h-8 text-slate-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    <p class="text-xs text-slate-500">Klik untuk upload foto kegiatan</p>
                                </div>
                                <img id="imagePreview" class="hidden absolute inset-0 w-full h-full object-cover opacity-90" />
                                <button type="button" id="removeBtn" class="hidden absolute top-2 right-2 bg-white/80 p-1.5 rounded-full hover:bg-white text-rose-500 shadow-sm"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                                <input type="file" name="foto" id="fotoInput" class="hidden" accept="image/*" />
                            </label>
                        </div>
                    </div>

                    <div class="pt-4">
                        <button type="submit" id="submitBtn" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-4 rounded-xl shadow-lg shadow-indigo-200 transition-all transform active:scale-95 flex items-center justify-center gap-2">
                            <span id="btnText">Simpan Jurnal</span>
                            <!-- Loading Spinner -->
                            <svg id="btnLoader" class="hidden animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // --- SCRIPT AUTO SAVE DRAFT (LOCALSTORAGE) ---
        const inputs = ['mapelInput', 'materiInput', 'deskripsiTextarea'];
        const saveStatus = document.getElementById('saveStatus');

        // Load Draft saat halaman dibuka
        window.addEventListener('DOMContentLoaded', () => {
            inputs.forEach(id => {
                const val = localStorage.getItem('draft_' + id);
                if(val) document.getElementById(id).value = val;
            });
            // Trigger character count
            document.getElementById('deskripsiTextarea').dispatchEvent(new Event('input'));
        });

        // Simpan Draft saat mengetik
        inputs.forEach(id => {
            document.getElementById(id).addEventListener('input', (e) => {
                localStorage.setItem('draft_' + id, e.target.value);
                
                // Show "Saved" indicator
                saveStatus.classList.remove('opacity-0');
                setTimeout(() => saveStatus.classList.add('opacity-0'), 2000);
            });
        });

        // Hapus Draft saat submit berhasil
        document.getElementById('jurnalForm').addEventListener('submit', () => {
            inputs.forEach(id => localStorage.removeItem('draft_' + id));
            // Show loading
            document.getElementById('submitBtn').disabled = true;
            document.getElementById('submitBtn').classList.add('opacity-80');
            document.getElementById('btnText').classList.add('hidden');
            document.getElementById('btnLoader').classList.remove('hidden');
        });

        // --- EXISTING IMAGE PREVIEW LOGIC (Simplified) ---
        const dropZone = document.getElementById('dropZone');
        const fileInput = document.getElementById('fotoInput');
        const preview = document.getElementById('imagePreview');
        const emptyState = document.getElementById('emptyState');
        const removeBtn = document.getElementById('removeBtn');

        fileInput.addEventListener('change', (e) => {
            const file = e.target.files[0];
            if(file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    emptyState.classList.add('hidden');
                    removeBtn.classList.remove('hidden');
                }
                reader.readAsDataURL(file);
            }
        });

        removeBtn.addEventListener('click', (e) => {
            e.preventDefault();
            fileInput.value = '';
            preview.src = '';
            preview.classList.add('hidden');
            emptyState.classList.remove('hidden');
            removeBtn.classList.add('hidden');
        });

        // Char Counter
        const deskripsiInput = document.getElementById('deskripsiTextarea');
        const charCount = document.getElementById('charCount');
        deskripsiInput.addEventListener('input', (e) => {
            charCount.textContent = `${e.target.value.length}/500`;
        });
    </script>
</body>
</html>