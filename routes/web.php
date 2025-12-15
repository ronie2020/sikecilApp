<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\JurnalGuruController;
use App\Http\Controllers\BukuPenghubungController;
use App\Http\Controllers\KebiasaanController;
use App\Http\Controllers\DataMasterController;
use App\Http\Controllers\EkskulController;

// PENTING: Tambahkan Import Model Ekskul di sini agar tidak error "Class not found"
use App\Models\Ekskul; 

// --- HALAMAN UTAMA (LANDING PAGE) YANG DIPERBAIKI ---
Route::get('/', function () { 
    // Ambil data ekskul dari database
    $ekskuls = Ekskul::all(); 
    
    // Kirim variable $ekskuls ke view welcome
    return view('welcome', compact('ekskuls')); 
})->name('home');

// === HALAMAN INFO EKSKUL (YANG BARU KITA BUAT) ===
Route::get('/ekskul', [EkskulController::class, 'landingPage'])->name('ekskul');
// ================================================

// Halaman Login
Route::get('/login', function () { return view('login'); })->name('login');

// Proses Login
Route::post('/login-proses', function (Request $request) {
    $credentials = $request->validate([ 
        'email' => ['required', 'email'], 
        'password' => ['required'] 
    ]);

    if (Auth::attempt($credentials)) { 
        $request->session()->regenerate(); 
        return redirect()->intended('dashboard'); 
    }

    return back()->with('error', 'Email atau Password salah!');
})->name('login.process');


// AREA USER LOGIN (Sudah Login)
Route::middleware('auth')->group(function () {
    
    // DASHBOARD
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // === MANAJEMEN EKSKUL (ADMIN & GURU) ===
    Route::prefix('admin')->name('admin.')->group(function() {
        Route::get('/ekskul', [EkskulController::class, 'index'])->name('ekskul.index');
        Route::post('/ekskul', [EkskulController::class, 'store'])->name('ekskul.store');
        Route::delete('/ekskul/{id}', [EkskulController::class, 'destroy'])->name('ekskul.destroy');
        
        Route::get('/ekskul/{id}/presensi', [EkskulController::class, 'presensiView'])->name('ekskul.presensi');
        Route::post('/ekskul/{id}/presensi', [EkskulController::class, 'presensiStore'])->name('ekskul.presensi.store');
    });

    // Data Master Routes
    Route::prefix('data-master')->name('admin.')->group(function() {
        Route::get('/', [DataMasterController::class, 'index'])->name('dataMaster');
        Route::post('/user', [DataMasterController::class, 'storeUser'])->name('storeUser');   
        Route::post('/user/update/{id}', [DataMasterController::class, 'updateUser'])->name('updateUser');  
        Route::post('/kelas/update/{id}', [DataMasterController::class, 'updateKelas'])->name('updateKelas'); 
        Route::post('/kelas', [DataMasterController::class, 'storeKelas'])->name('storeKelas');
        Route::post('/mapel', [DataMasterController::class, 'storeMapel'])->name('storeMapel');
        Route::post('/user/import', [DataMasterController::class, 'importUser'])->name('importUser');
        Route::get('/delete/{type}/{id}', [DataMasterController::class, 'destroy'])->name('delete');
    });

    // Presensi Routes
    Route::prefix('presensi')->name('presensi.')->group(function() {
        Route::get('/rekap', [PresensiController::class, 'rekapBulanan'])->name('rekap');
        Route::get('/create', [PresensiController::class, 'create'])->name('create');
        Route::post('/', [PresensiController::class, 'store'])->name('store');
        Route::get('/scan', [PresensiController::class, 'scan'])->name('scan');
        Route::post('/scan-proses', [PresensiController::class, 'storeQr'])->name('store-qr');
    });
    
    // Jurnal Routes
    Route::resource('jurnal', JurnalGuruController::class)->names('jurnal');

    // Buku Penghubung
    Route::get('/buku-penghubung', [BukuPenghubungController::class, 'index'])->name('penghubung.index');
    Route::post('/buku-penghubung', [BukuPenghubungController::class, 'store'])->name('penghubung.store');

    // 7 Kebiasaan
    Route::get('/kebiasaan', [KebiasaanController::class, 'index'])->name('kebiasaan.index');
    Route::post('/kebiasaan', [KebiasaanController::class, 'store'])->name('kebiasaan.store');
    Route::get('/kebiasaan/rekap', [KebiasaanController::class, 'rekap'])->name('kebiasaan.rekap');

    // Logout
    Route::post('/logout', function (Request $request) {
        Auth::logout(); 
        $request->session()->invalidate(); 
        $request->session()->regenerateToken(); 
        return redirect('/'); 
    })->name('logout');
});