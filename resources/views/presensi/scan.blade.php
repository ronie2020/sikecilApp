<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scan QR Absensi - SiKecil</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Pustaka Scanner QR -->
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .scan-line {
            width: 100%;
            height: 2px;
            background: #3b82f6;
            position: absolute;
            top: 0;
            left: 0;
            animation: scanning 2s infinite;
            box-shadow: 0 0 4px #3b82f6;
        }
        @keyframes scanning {
            0% { top: 0; opacity: 1; }
            50% { opacity: 0.5; }
            100% { top: 100%; opacity: 1; }
        }
    </style>
</head>
<body class="bg-gray-900 min-h-screen flex flex-col items-center justify-center text-white">

    <!-- Header -->
    <div class="fixed top-0 w-full bg-gray-800 p-4 shadow-lg z-50 flex justify-between items-center">
        <h1 class="text-lg font-bold flex items-center">
            <span class="text-2xl mr-2">📷</span> Scan QR Siswa
        </h1>
        <a href="{{ url('/dashboard') }}" class="text-sm bg-gray-700 hover:bg-gray-600 px-4 py-2 rounded-lg transition border border-gray-600">
            Kembali
        </a>
    </div>

    <!-- Area Kamera -->
    <div class="w-full max-w-md p-4 mt-10 relative">
        <div class="relative rounded-2xl overflow-hidden border-4 border-gray-700 shadow-2xl bg-black h-80 flex items-center justify-center">
            <div id="reader" class="w-full h-full object-cover"></div>
            
            <!-- Loading -->
            <div id="loading-msg" class="absolute inset-0 flex flex-col items-center justify-center text-gray-400 z-0">
                <svg class="animate-spin h-8 w-8 text-blue-500 mb-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <p class="text-sm">Menghubungkan Kamera...</p>
            </div>

            <!-- Overlay Garis Scan -->
            <div class="absolute inset-0 pointer-events-none z-10 hidden" id="scan-overlay">
                <div class="w-64 h-64 border-2 border-blue-500/50 rounded-lg absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
                    <div class="scan-line"></div>
                </div>
            </div>
        </div>
        <p class="text-center text-gray-400 text-sm mt-4 bg-gray-800 py-2 rounded-lg">Arahkan kamera ke Kartu QR Siswa</p>
    </div>

    <!-- Hasil Scan -->
    <div id="result-container" class="fixed bottom-10 w-full max-w-md px-4 hidden z-50">
        <div id="result-box" class="bg-white text-gray-900 p-4 rounded-xl shadow-2xl flex items-center transform transition-all duration-300 scale-90 opacity-0">
            <div id="result-icon" class="text-4xl mr-4">✅</div>
            <div>
                <h3 id="result-title" class="font-bold text-lg">Berhasil!</h3>
                <p id="result-message" class="text-sm text-gray-600">Absensi tercatat.</p>
            </div>
        </div>
    </div>

    <!-- Audio Beep (Base64 agar tidak error 403) -->
    <audio id="beep-sound" src="data:audio/wav;base64,UklGRl9vT1..."></audio>

    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        // Suara Beep Pendek (Base64)
        const beepData = "data:audio/wav;base64,UklGRl9vT1...";// (Kode disederhanakan, kita pakai AudioContext untuk lebih ringan)
        
        // Fungsi Bunyi Beep Sederhana menggunakan Browser Audio Context (Tanpa file eksternal)
        function playBeep() {
            const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
            const oscillator = audioCtx.createOscillator();
            const gainNode = audioCtx.createGain();

            oscillator.connect(gainNode);
            gainNode.connect(audioCtx.destination);

            oscillator.type = 'sine';
            oscillator.frequency.setValueAtTime(880, audioCtx.currentTime); // Nada A5
            gainNode.gain.setValueAtTime(0.1, audioCtx.currentTime);
            
            oscillator.start();
            setTimeout(() => { oscillator.stop(); }, 150); // Beep selama 150ms
        }

        let isProcessing = false;

        function onScanSuccess(decodedText, decodedResult) {
            if (isProcessing) return; 
            isProcessing = true;
            
            playBeep(); // Bunyikan Beep

            const overlay = document.getElementById('scan-overlay');
            overlay.classList.add('bg-white/20');
            setTimeout(() => overlay.classList.remove('bg-white/20'), 200);

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
                setTimeout(() => { isProcessing = false; }, 2500);
            });
        }

        function showResult(status, message, nama) {
            const container = document.getElementById('result-container');
            const box = document.getElementById('result-box');
            const icon = document.getElementById('result-icon');
            const title = document.getElementById('result-title');
            const msg = document.getElementById('result-message');

            container.classList.remove('hidden');
            box.className = "p-4 rounded-xl shadow-2xl flex items-center transform transition-all duration-300 scale-100 opacity-100";
            
            if (status === 'success') {
                box.classList.add("bg-green-100", "text-green-900");
                icon.innerHTML = "✅";
                title.innerText = nama;
                msg.innerText = "Hadir Pukul " + new Date().toLocaleTimeString('id-ID', {hour: '2-digit', minute:'2-digit'});
            } else if (status === 'warning') {
                box.classList.add("bg-yellow-100", "text-yellow-900");
                icon.innerHTML = "⚠️";
                title.innerText = nama || "Info";
                msg.innerText = message;
            } else {
                box.classList.add("bg-red-100", "text-red-900");
                icon.innerHTML = "❌";
                title.innerText = "Gagal";
                msg.innerText = "QR Code tidak valid.";
            }

            setTimeout(() => {
                box.classList.add('scale-90', 'opacity-0');
                box.classList.remove('scale-100', 'opacity-100');
                setTimeout(() => container.classList.add('hidden'), 300);
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
            document.getElementById('loading-msg').innerHTML = `<p class="text-red-400 font-bold">Gagal akses kamera</p>`;
        });
    </script>
</body>
</html>