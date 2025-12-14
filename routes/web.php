<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\JurnalGuruController;
use App\Http\Controllers\BukuPenghubungController;
use App\Http\Controllers\KebiasaanController;
use App\Http\Controllers\DataMasterController;
use App\Models\User;
use App\Models\Presensi;
use Carbon\Carbon;

// Redirect awal
Route::get('/', function () { return redirect('/login'); });

// Login
Route::get('/login', function () { return view('login'); })->name('login');
Route::post('/login-proses', function (Request $request) {
    $credentials = $request->validate([ 'email' => ['required', 'email'], 'password' => ['required'] ]);
    if (Auth::attempt($credentials)) { 
        $request->session()->regenerate(); 
        return redirect()->intended('dashboard'); 
    }
    return back()->with('error', 'Email atau Password salah!');
});

// AREA USER LOGIN
Route::middleware('auth')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', function () {
        $hariIni = Carbon::now()->toDateString();
        $totalSiswa = User::where('role', 'siswa')->count();
        $hadir = Presensi::where('tanggal', $hariIni)->where('status', 'Hadir')->count();
        $sakitIzin = Presensi::where('tanggal', $hariIni)->whereIn('status', ['Sakit', 'Izin'])->count();
        $alpa = Presensi::where('tanggal', $hariIni)->where('status', 'Alpa')->count();
        $aktivitasTerbaru = Presensi::with('user')->where('tanggal', $hariIni)->latest()->take(5)->get();
        return view('dashboard', compact('totalSiswa', 'hadir', 'sakitIzin', 'alpa', 'aktivitasTerbaru'));
    })->name('dashboard');

    // Data Master
    Route::get('/data-master', [DataMasterController::class, 'index'])->name('admin.dataMaster');
    Route::post('/data-master/user', [DataMasterController::class, 'storeUser'])->name('admin.storeUser');   
    Route::post('/data-master/user/update/{id}', [DataMasterController::class, 'updateUser'])->name('admin.updateUser');  
    Route::post('/data-master/kelas/update/{id}', [DataMasterController::class, 'updateKelas'])->name('admin.updateKelas'); 
    Route::post('/data-master/kelas', [DataMasterController::class, 'storeKelas'])->name('admin.storeKelas');
    Route::post('/data-master/mapel', [DataMasterController::class, 'storeMapel'])->name('admin.storeMapel');
    Route::get('/data-master/delete/{type}/{id}', [DataMasterController::class, 'destroy'])->name('admin.delete');

    // Presensi
    Route::get('/rekap-kehadiran', [PresensiController::class, 'rekapBulanan'])->name('presensi.rekap');
    Route::get('/presensi/create', [PresensiController::class, 'create'])->name('presensi.create');
    Route::post('/presensi', [PresensiController::class, 'store'])->name('presensi.store');
    Route::get('/presensi/scan', [PresensiController::class, 'scan'])->name('presensi.scan');
    Route::post('/presensi/scan-proses', [PresensiController::class, 'storeQr'])->name('presensi.store-qr');
    // Fitur Jurnal
    Route::get('/jurnal', [JurnalGuruController::class, 'index'])->name('jurnal.index');
    Route::get('/jurnal/tulis', [JurnalGuruController::class, 'create'])->name('jurnal.create');
    Route::post('/jurnal', [JurnalGuruController::class, 'store'])->name('jurnal.store');
    
    // TAMBAHKAN 3 ROUTE INI:
    Route::get('/jurnal/{id}/edit', [JurnalGuruController::class, 'edit'])->name('jurnal.edit');
    Route::put('/jurnal/{id}', [JurnalGuruController::class, 'update'])->name('jurnal.update');
    Route::delete('/jurnal/{id}', [JurnalGuruController::class, 'destroy'])->name('jurnal.destroy');

    // Buku Penghubung
    Route::get('/buku-penghubung', [BukuPenghubungController::class, 'index'])->name('penghubung.index');
    Route::post('/buku-penghubung', [BukuPenghubungController::class, 'store'])->name('penghubung.store');

    // 7 Kebiasaan
    Route::get('/kebiasaan', [KebiasaanController::class, 'index'])->name('kebiasaan.index');
    Route::post('/kebiasaan', [KebiasaanController::class, 'store'])->name('kebiasaan.store');
    Route::get('/kebiasaan/rekap', [KebiasaanController::class, 'rekap'])->name('kebiasaan.rekap');

    // Logout
    Route::post('/logout', function (Request $request) {
        Auth::logout(); $request->session()->invalidate(); $request->session()->regenerateToken(); return redirect('/login');
    })->name('logout');
});