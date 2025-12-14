<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Presensi;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PresensiController extends Controller
{
    public function create()
    {
        $hariIni = Carbon::now('Asia/Jakarta')->locale('id')->isoFormat('dddd, D MMMM Y');
        $siswa = User::where('role', 'siswa')->orderBy('name')->get();
        return view('presensi.create', compact('siswa', 'hariIni'));
    }

    public function store(Request $request)
    {
        $request->validate(['presensi' => 'required|array']);
        
        // PENTING: Gunakan Tanggal WIB
        $waktuIndo = Carbon::now('Asia/Jakarta');
        $tanggal = $waktuIndo->toDateString(); 
        $jam = $waktuIndo->toTimeString();

        foreach ($request->presensi as $userId => $status) {
            Presensi::updateOrCreate(
                ['user_id' => $userId, 'tanggal' => $tanggal],
                ['status' => $status, 'jam_masuk' => $jam]
            );
        }

        return redirect('/dashboard')->with('success', 'Absensi berhasil disimpan!');
    }

    public function scan()
    {
        return view('presensi.scan');
    }

    public function storeQr(Request $request)
    {
        $userId = $request->input('user_id');
        $siswa = User::find($userId);

        if (!$siswa) {
            return response()->json(['status' => 'error', 'message' => 'Siswa tidak ditemukan.']);
        }

        // PENTING: Gunakan Tanggal WIB untuk konsistensi
        $waktuIndo = Carbon::now('Asia/Jakarta');
        $tanggal = $waktuIndo->toDateString();
        $jam = $waktuIndo->toTimeString();

        $cek = Presensi::where('user_id', $userId)->where('tanggal', $tanggal)->first();

        if ($cek) {
            return response()->json([
                'status' => 'warning', 
                'nama_siswa' => $siswa->name, 
                'message' => 'Sudah absen masuk pukul ' . $cek->jam_masuk
            ]);
        }

        Presensi::create([
            'user_id' => $userId,
            'tanggal' => $tanggal,
            'status' => 'Hadir',
            'jam_masuk' => $jam,
        ]);

        return response()->json([
            'status' => 'success', 
            'nama_siswa' => $siswa->name, 
            'message' => 'Absensi berhasil dicatat pukul ' . $jam
        ]);
    }

    public function rekapBulanan(Request $request)
    {
        $bulan = $request->input('bulan', Carbon::now('Asia/Jakarta')->format('Y-m'));
        $siswa = User::where('role', 'siswa')->orderBy('name')->get();
        $presensi = Presensi::where('tanggal', 'like', "$bulan%")->get();

        return view('presensi.rekap', compact('siswa', 'bulan', 'presensi'));
    }
}