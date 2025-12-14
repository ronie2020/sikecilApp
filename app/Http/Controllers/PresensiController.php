<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Presensi;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PresensiController extends Controller
{
    // 1. Tampilkan Form Absensi Manual
    public function create()
    {
        $hariIni = Carbon::now()->locale('id')->isoFormat('dddd, D MMMM Y');
        $siswa = User::where('role', 'siswa')->get();
        return view('presensi.create', compact('siswa', 'hariIni'));
    }

    // 2. Simpan Data Absensi Manual
    public function store(Request $request)
    {
        $request->validate(['presensi' => 'required|array']);
        $tanggal = Carbon::now()->toDateString();

        foreach ($request->presensi as $userId => $status) {
            Presensi::updateOrCreate(
                ['user_id' => $userId, 'tanggal' => $tanggal],
                ['status' => $status, 'jam_masuk' => Carbon::now()->toTimeString()]
            );
        }

        return redirect('/dashboard')->with('success', 'Absensi berhasil disimpan!');
    }

    // 3. Tampilkan Halaman Scan
    public function scan()
    {
        return view('presensi.scan');
    }

    // 4. Proses Hasil Scan (Menerima AJAX JSON)
    public function storeQr(Request $request)
    {
        $userId = $request->input('user_id');
        $siswa = User::find($userId);

        if (!$siswa) {
            return response()->json(['status' => 'error', 'message' => 'Siswa tidak ditemukan.']);
        }

        $tanggal = Carbon::now()->toDateString();
        $cek = Presensi::where('user_id', $userId)->where('tanggal', $tanggal)->first();

        if ($cek) {
            return response()->json(['status' => 'warning', 'nama_siswa' => $siswa->name, 'message' => 'Sudah absen hari ini.']);
        }

        Presensi::create([
            'user_id' => $userId,
            'tanggal' => $tanggal,
            'status' => 'Hadir',
            'jam_masuk' => Carbon::now()->toTimeString(),
        ]);

        return response()->json(['status' => 'success', 'nama_siswa' => $siswa->name, 'message' => 'Absensi berhasil dicatat.']);
    }

    // === 5. REKAP BULANAN (INI YANG SEBELUMNYA HILANG/ERROR) ===
    public function rekapBulanan(Request $request)
    {
        // Ambil bulan dari input atau default bulan ini (Format YYYY-MM)
        $bulan = $request->input('bulan', date('Y-m'));
        
        // Ambil semua siswa
        $siswa = User::where('role', 'siswa')->orderBy('name')->get();
        
        // Ambil data presensi di bulan tersebut
        $presensi = Presensi::where('tanggal', 'like', "$bulan%")->get();

        return view('presensi.rekap', compact('siswa', 'bulan', 'presensi'));
    }
}