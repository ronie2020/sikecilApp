<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scan QR Absensi - SiKecil</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Pustaka Scanner QR -->
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .scan-line {
            width: 100%;
            height: 3px;
            background: #10b981; /* Emerald-500 */
            position: absolute;
            top: 0;
            left: 0;
            animation: scanning 2s infinite cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 0 15px #10b981;
            border-radius: 50%;
        }
        @keyframes scanning {
            0% { top: 0; opacity: 0; }
            10% { opacity: 1; }
            90% { opacity: 1; }
            100% { top: 100%; opacity: 0; }
        }
    </style>
</head>
<body class="bg-slate-900 min-h-screen flex flex-col items-center justify-center text-white relative overflow-hidden">

    <!-- Background Accents -->
    <div class="absolute top-0 left-0 w-full h-full overflow-hidden z-0 pointer-events-none">
        <div class="absolute -top-32 -left-32 w-96 h-96 bg-emerald-600/20 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-32 -right-32 w-96 h-96 bg-teal-600/20 rounded-full blur-3xl"></div>
    </div>

    <!-- Header -->
    <div class="fixed top-0 w-full bg-slate-900/80 backdrop-blur-md p-4 shadow-lg z-50 flex justify-between items-center border-b border-white/10">
        <div class="flex items-center gap-3">
            <div class="bg-emerald-500/20 p-2 rounded-lg text-emerald-400 border border-emerald-500/20">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4h2v-4zM6 8v4M6 20v-4M2 20h4M2 4h4M2 12h2m8 0h2M2 8v4M2 16h2M6 16h2M6 12h4m0-8h4m4 0h4M14 8h-2M10 8h2M10 4h2m4 0h2M18 8h2m0 4h2M18 16h2m-2 4h2M2 12v4m0 4v-4m10-4v4m2-4v4m4-4v4M6 4v4m12 0v4"></path></svg>
            </div>
            <div>
                <h1 class="font-bold text-lg leading-tight">Scan Kehadiran</h1>
                <p class="text-xs text-slate-400">SiKecil Absensi</p>
            </div>
        </div>
        <a href="{{ url('/dashboard') }}" class="text-xs font-bold bg-white/10 hover:bg-white/20 px-4 py-2 rounded-lg transition border border-white/10 flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            <span class="hidden sm:inline">Kembali</span>
        </a>
    </div>

    <!-- Area Kamera -->
    <div class="w-full max-w-md p-6 mt-16 relative z-10">
        <div class="relative rounded-3xl overflow-hidden border-4 border-slate-700 shadow-2xl bg-black aspect-square flex items-center justify-center group">
            <div id="reader" class="w-full h-full object-cover"></div>
            
            <!-- Loading State -->
            <div id="loading-msg" class="absolute inset-0 flex flex-col items-center justify-center text-slate-400 z-0 bg-slate-800">
                <svg class="animate-spin h-10 w-10 text-emerald-500 mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <p class="text-sm font-medium animate-pulse">Menghubungkan Kamera...</p>
            </div>

            <!-- Overlay Garis Scan (Emerald) -->
            <div class="absolute inset-0 pointer-events-none z-10 hidden transition-all duration-300" id="scan-overlay">
                <div class="w-64 h-64 border-2 border-emerald-500/50 rounded-2xl absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 shadow-[0_0_50px_-10px_rgba(16,185,129,0.3)]">
                    <div class="absolute top-0 left-0 w-4 h-4 border-t-4 border-l-4 border-emerald-400 rounded-tl-lg -mt-1 -ml-1"></div>
                    <div class="absolute top-0 right-0 w-4 h-4 border-t-4 border-r-4 border-emerald-400 rounded-tr-lg -mt-1 -mr-1"></div>
                    <div class="absolute bottom-0 left-0 w-4 h-4 border-b-4 border-l-4 border-emerald-400 rounded-bl-lg -mb-1 -ml-1"></div>
                    <div class="absolute bottom-0 right-0 w-4 h-4 border-b-4 border-r-4 border-emerald-400 rounded-br-lg -mb-1 -mr-1"></div>
                    <div class="scan-line"></div>
                </div>
                <div class="absolute bottom-8 left-0 right-0 text-center">
                    <span class="bg-black/50 text-emerald-400 text-xs font-bold px-3 py-1.5 rounded-full border border-emerald-500/30 backdrop-blur-sm">
                        Scanner Aktif
                    </span>
                </div>
            </div>
        </div>
        <p class="text-center text-slate-400 text-sm mt-6 bg-slate-800/50 py-3 px-4 rounded-xl border border-white/5 backdrop-blur-sm">
            Arahkan kamera ke <strong class="text-white">QR Code</strong> pada kartu siswa.
        </p>
    </div>

    <!-- Hasil Scan (Modal Notification) -->
    <div id="result-container" class="fixed bottom-10 w-full max-w-md px-6 hidden z-50">
        <div id="result-box" class="bg-white text-slate-900 p-5 rounded-2xl shadow-2xl flex items-center transform transition-all duration-500 translate-y-10 opacity-0 border border-slate-100">
            <div id="result-icon" class="text-4xl mr-5 p-2 bg-slate-50 rounded-xl">✅</div>
            <div class="flex-1">
                <h3 id="result-title" class="font-bold text-lg text-slate-800 leading-tight">Berhasil!</h3>
                <p id="result-message" class="text-sm text-slate-500 mt-0.5">Absensi tercatat.</p>
                <p id="result-time" class="text-xs font-bold text-emerald-600 mt-1"></p>
            </div>
        </div>
    </div>

    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        function playBeep() {
            const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
            const oscillator = audioCtx.createOscillator();
            const gainNode = audioCtx.createGain();

            oscillator.connect(gainNode);
            gainNode.connect(audioCtx.destination);

            oscillator.type = 'sine';
            oscillator.frequency.setValueAtTime(1200, audioCtx.currentTime); // Nada lebih tinggi (High pitch beep)
            gainNode.gain.setValueAtTime(0.1, audioCtx.currentTime);
            
            oscillator.start();
            setTimeout(() => { 
                oscillator.frequency.setValueAtTime(1800, audioCtx.currentTime); // Double beep effect
                setTimeout(() => { oscillator.stop(); }, 100);
            }, 100); 
        }

        let isProcessing = false;

        function onScanSuccess(decodedText, decodedResult) {
            if (isProcessing) return; 
            isProcessing = true;
            
            playBeep();

            // Visual feedback flash
            const overlay = document.getElementById('scan-overlay');
            overlay.classList.add('bg-emerald-500/20');
            setTimeout(() => overlay.classList.remove('bg-emerald-500/20'), 300);

            // Kirim ke server
            fetch("{{ route('presensi.store-qr') }}", {
                method: "POST",
                headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": csrfToken },
                body: JSON.stringify({ user_id: decodedText })
            })
            .then(response => response.json())
            .then(data => {
                showResult(data.status, data.message, data.nama_siswa);
            })
            .catch(error => {
                showResult('error', 'Gagal terhubung ke server.', '-');
            })
            .finally(() => {
                setTimeout(() => { isProcessing = false; }, 2500); // Delay sebelum scan berikutnya
            });
        }

        function showResult(status, message, nama) {
            const container = document.getElementById('result-container');
            const box = document.getElementById('result-box');
            const icon = document.getElementById('result-icon');
            const title = document.getElementById('result-title');
            const msg = document.getElementById('result-message');
            const timeDisplay = document.getElementById('result-time');

            container.classList.remove('hidden');
            
            // Reset Classes
            box.classList.remove("bg-white", "bg-emerald-50", "bg-amber-50", "bg-rose-50", "border-emerald-100", "border-amber-100", "border-rose-100");
            icon.classList.remove("bg-emerald-100", "bg-amber-100", "bg-rose-100");
            timeDisplay.innerText = "";

            // Animasi Masuk
            requestAnimationFrame(() => {
                box.classList.remove('translate-y-10', 'opacity-0');
            });
            
            if (status === 'success') {
                box.classList.add("bg-white", "border-emerald-100");
                icon.innerHTML = "✅";
                icon.classList.add("bg-emerald-100", "text-emerald-600");
                title.innerText = nama;
                title.className = "font-bold text-lg text-emerald-800 leading-tight";
                msg.innerText = "Berhasil Absensi Masuk";
                timeDisplay.innerText = new Date().toLocaleTimeString('id-ID', {hour: '2-digit', minute:'2-digit'}) + " WIB";
            } else if (status === 'warning') {
                box.classList.add("bg-white", "border-amber-100");
                icon.innerHTML = "⚠️";
                icon.classList.add("bg-amber-100", "text-amber-600");
                title.innerText = nama || "Peringatan";
                title.className = "font-bold text-lg text-amber-800 leading-tight";
                msg.innerText = message;
            } else {
                box.classList.add("bg-white", "border-rose-100");
                icon.innerHTML = "❌";
                icon.classList.add("bg-rose-100", "text-rose-600");
                title.innerText = "Gagal";
                title.className = "font-bold text-lg text-rose-800 leading-tight";
                msg.innerText = "QR Code tidak dikenali.";
            }

            // Auto hide setelah 3 detik
            setTimeout(() => {
                box.classList.add('translate-y-10', 'opacity-0');
                setTimeout(() => container.classList.add('hidden'), 500);
            }, 3000);
        }

        // Setup Kamera
        const html5QrCode = new Html5Qrcode("reader");
        const config = { fps: 10, qrbox: { width: 250, height: 250 } };
        
        html5QrCode.start({ facingMode: "environment" }, config, onScanSuccess)
        .then(() => {
            document.getElementById('loading-msg').classList.add('hidden');
            document.getElementById('scan-overlay').classList.remove('hidden');
        })
        .catch(err => {
            console.error("Error start kamera: ", err);
            document.getElementById('loading-msg').innerHTML = `<p class="text-rose-400 font-bold">Gagal akses kamera</p><p class="text-xs mt-2">Izinkan akses kamera di browser.</p>`;
        });
    </script>
</body>
</html>