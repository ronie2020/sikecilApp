<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tulis Jurnal - SiKecil</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #F8FAFC; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-slate-50 min-h-screen text-slate-800" x-data="{ sidebarOpen: false }">

    <div class="flex h-screen overflow-hidden">
        
        <!-- SIDEBAR -->
        @include('components.sidebar')

        <!-- Mobile Overlay -->
        <div x-show="sidebarOpen" 
             @click="sidebarOpen = false"
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-slate-900/50 z-40 md:hidden" x-cloak>
        </div>

        <!-- MAIN CONTENT -->
        <div class="flex-1 flex flex-col h-screen overflow-hidden relative">
            
            <!-- Navbar / Header (Consistent with other pages) -->
            <nav class="bg-white border-b border-slate-200 sticky top-0 z-30 shrink-0">
                <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16 items-center">
                        <div class="flex items-center gap-3">
                            <!-- Hamburger (Mobile) -->
                            <button @click="sidebarOpen = !sidebarOpen" class="md:hidden p-2 -ml-2 text-slate-500 hover:text-emerald-600 rounded-lg hover:bg-slate-100 transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                            </button>

                            <div class="flex items-center gap-3">
                                <div class="bg-emerald-600 text-white p-2 rounded-xl shadow-lg shadow-emerald-200 hidden sm:block">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </div>
                                <div>
                                    <h1 class="font-bold text-lg text-slate-900 leading-tight">Jurnal Baru</h1>
                                    <p class="text-xs text-slate-500 font-medium hidden sm:block">Dokumentasi kegiatan belajar mengajar</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Status Draft Indicator -->
                        <div id="saveStatus" class="text-xs font-medium text-emerald-600 bg-emerald-50 px-3 py-1 rounded-full border border-emerald-100 opacity-0 transition-opacity flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Draft tersimpan
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Scrollable Form Area -->
            <div class="flex-1 overflow-y-auto bg-slate-50 p-4 sm:p-8">
                <div class="max-w-3xl mx-auto pb-12">
                    
                    <!-- Back Button Row -->
                    <div class="mb-6">
                        <a href="{{ route('jurnal.index') }}" class="inline-flex items-center text-slate-500 hover:text-emerald-700 transition-colors group font-medium text-sm">
                            <div class="w-8 h-8 rounded-full bg-white border border-slate-200 flex items-center justify-center mr-2 group-hover:border-emerald-300 group-hover:text-emerald-600 shadow-sm transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                            </div>
                            Kembali ke Timeline
                        </a>
                    </div>

                    <!-- Main Form Card (Green Progress Bar) -->
                    <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/50 overflow-hidden border border-slate-100">
                        <div class="h-1.5 w-full bg-slate-100"><div class="h-full bg-gradient-to-r from-emerald-500 to-teal-500 w-1/3 rounded-r-full"></div></div>

                        <div class="p-6 sm:p-8">
                            <div class="mb-8 border-b border-slate-100 pb-6">
                                <h2 class="text-2xl font-bold text-slate-900">Form Jurnal</h2>
                                <p class="text-slate-500 text-sm mt-1">Lengkapi data di bawah ini dengan detail kegiatan yang sesuai.</p>
                            </div>

                            <form action="{{ route('jurnal.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6" id="jurnalForm">
                                @csrf
                                
                                <!-- Tanggal Kegiatan -->
                                <div class="bg-emerald-50/50 p-4 rounded-xl border border-emerald-100">
                                    <label class="text-xs font-bold text-emerald-900 uppercase tracking-wider mb-1 block">Tanggal Kegiatan</label>
                                    <input type="date" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" class="w-full bg-white border border-emerald-200 text-slate-800 text-sm rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent p-2.5 outline-none font-medium transition-all" required>
                                    <p class="text-[10px] text-emerald-400 mt-1">Default adalah hari ini. Ubah jika mengisi jurnal rapel.</p>
                                </div>

                                <!-- Row: Kelas & Mapel -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="space-y-2">
                                        <label class="text-xs font-bold text-slate-500 uppercase tracking-wider ml-1">Kelas</label>
                                        <div class="relative">
                                            <select name="kelas_id" id="kelasInput" class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent block p-4 appearance-none transition-all cursor-pointer outline-none" required>
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
                                        <input type="text" name="mata_pelajaran" id="mapelInput" value="{{ old('mata_pelajaran') }}" class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent block p-4 outline-none placeholder-slate-400" placeholder="Contoh: Matematika" required>
                                    </div>
                                </div>

                                <!-- Materi Pokok -->
                                <div class="space-y-2">
                                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wider ml-1">Topik / Materi</label>
                                    <input type="text" name="materi_pokok" id="materiInput" value="{{ old('materi_pokok') }}" class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent block p-4 outline-none placeholder-slate-400" placeholder="Apa yang dipelajari hari ini?" required>
                                </div>

                                <!-- Deskripsi -->
                                <div class="space-y-2">
                                    <div class="flex justify-between items-center ml-1">
                                        <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Deskripsi Kegiatan</label>
                                        <span class="text-xs text-slate-400" id="charCount">0/500</span>
                                    </div>
                                    <textarea name="deskripsi_kegiatan" id="deskripsiTextarea" rows="6" maxlength="500" class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent block p-4 outline-none resize-none leading-relaxed placeholder-slate-400 transition-all" placeholder="Ceritakan alur pembelajaran..." required>{{ old('deskripsi_kegiatan') }}</textarea>
                                </div>

                                <!-- File Upload -->
                                <div class="space-y-2">
                                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wider ml-1">Foto Dokumentasi</label>
                                    <div id="uploadContainer" class="relative group">
                                        <label id="dropZone" class="flex flex-col items-center justify-center w-full h-40 border-2 border-slate-200 border-dashed rounded-2xl cursor-pointer bg-slate-50 hover:bg-emerald-50/50 hover:border-emerald-300 transition-all duration-300 relative overflow-hidden">
                                            <div id="emptyState" class="flex flex-col items-center justify-center pt-5 pb-6 text-center px-4">
                                                <svg class="w-8 h-8 text-slate-400 mb-2 group-hover:text-emerald-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                <p class="text-xs text-slate-500 group-hover:text-emerald-600 transition-colors">Klik untuk upload foto kegiatan</p>
                                            </div>
                                            <img id="imagePreview" class="hidden absolute inset-0 w-full h-full object-cover opacity-90" />
                                            <button type="button" id="removeBtn" class="hidden absolute top-2 right-2 bg-white/80 p-1.5 rounded-full hover:bg-white text-rose-500 shadow-sm transition-all hover:scale-110"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                                            <input type="file" name="foto" id="fotoInput" class="hidden" accept="image/*" />
                                        </label>
                                    </div>
                                </div>

                                <div class="pt-4">
                                    <button type="submit" id="submitBtn" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-4 rounded-xl shadow-lg shadow-emerald-200 transition-all transform active:scale-[0.99] flex items-center justify-center gap-2">
                                        <span id="btnText">Simpan Jurnal</span>
                                        <svg id="btnLoader" class="hidden animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Javascript sama seperti sebelumnya -->
    <script>
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

        // Optional: Character Count Script
        const textarea = document.getElementById('deskripsiTextarea');
        const charCount = document.getElementById('charCount');
        
        if(textarea && charCount) {
            textarea.addEventListener('input', function() {
                const currentLength = this.value.length;
                charCount.textContent = `${currentLength}/500`;
                if(currentLength >= 490) {
                    charCount.classList.add('text-rose-500', 'font-bold');
                    charCount.classList.remove('text-slate-400');
                } else {
                    charCount.classList.remove('text-rose-500', 'font-bold');
                    charCount.classList.add('text-slate-400');
                }
            });
        }
    </script>
</body>
</html>