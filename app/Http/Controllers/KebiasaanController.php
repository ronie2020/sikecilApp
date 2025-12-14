<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kebiasaan;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class KebiasaanController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();
        
        // PERBAIKAN UTAMA: Set Timezone ke Asia/Jakarta
        // Jika tidak ada input tanggal, gunakan Hari Ini (WIB)
        $todayWIB = Carbon::now('Asia/Jakarta')->toDateString();
        $tanggal = $request->input('tanggal', $todayWIB);
        
        $tanggalObj = Carbon::parse($tanggal);

        // 1. Data Kebiasaan
        $dataHariIni = Kebiasaan::where('user_id', $userId)
                                ->where('tanggal', $tanggal)
                                ->first();
        
        // 2. Riwayat
        $riwayat = Kebiasaan::where('user_id', $userId)
                            ->orderBy('tanggal', 'desc')
                            ->take(7)
                            ->get();

        // 3. Logika Rekap (WIB)
        // Gunakan copy() agar $tanggalObj tidak berubah
        $awalBulan = $tanggalObj->copy()->startOfMonth();
        
        // Hitung target hari
        $targetHari = $awalBulan->diffInDays($tanggalObj) + 1; 
        if ($targetHari < 1) $targetHari = 1;

        $tanggalDiisi = Kebiasaan::where('user_id', $userId)
                                 ->whereBetween('tanggal', [$awalBulan->toDateString(), $tanggalObj->toDateString()])
                                 ->pluck('tanggal')
                                 ->toArray();
        
        $jumlahDiisi = count($tanggalDiisi);
        $persentase = $targetHari > 0 ? round(($jumlahDiisi / $targetHari) * 100) : 0;

        // Cari Hari Bolong
        $hariBolong = [];
        $loopDate = $awalBulan->copy();
        
        while($loopDate->lte($tanggalObj)) {
            $dateString = $loopDate->toDateString();
            if (!in_array($dateString, $tanggalDiisi) && $dateString != $tanggal) {
                 $hariBolong[] = $loopDate->locale('id')->isoFormat('dddd, D MMM');
            }
            $loopDate->addDay();
        }

        return view('kebiasaan.index', compact('dataHariIni', 'riwayat', 'persentase', 'hariBolong', 'jumlahDiisi', 'tanggal'));
    }

    public function store(Request $request)
    {
        $userId = Auth::id();
        
        // Ambil tanggal dari form, ATAU default ke Hari Ini (WIB) jika entah kenapa kosong
        $todayWIB = Carbon::now('Asia/Jakarta')->toDateString();
        $tanggal = $request->input('tanggal', $todayWIB);

        Kebiasaan::updateOrCreate(
            ['user_id' => $userId, 'tanggal' => $tanggal],
            [
                'k1' => $request->has('k1'), 
                'k2' => $request->has('k2'), 
                'k3' => $request->has('k3'),
                'k4' => $request->has('k4'), 
                'k5' => $request->has('k5'), 
                'k6' => $request->has('k6'),
                'k7' => $request->has('k7'), 
                'catatan' => $request->catatan
            ]
        );

        return redirect()->route('kebiasaan.index', ['tanggal' => $tanggal])
                         ->with('success', 'Hebat! Data kebiasaan berhasil disimpan.');
    }

    public function rekap(Request $request)
    {
        // Pakai WIB juga untuk rekap guru
        $todayWIB = Carbon::now('Asia/Jakarta')->toDateString();
        $tanggal = $request->input('tanggal', $todayWIB);
        
        $tanggalFormat = Carbon::parse($tanggal)->locale('id')->isoFormat('dddd, D MMMM Y');
        
        $siswa = User::where('role', 'siswa')->orderBy('name', 'asc')->get();
        foreach ($siswa as $s) {
            $s->kebiasaan = Kebiasaan::where('user_id', $s->id)->where('tanggal', $tanggal)->first();
        }
        
        $sudahIsi = Kebiasaan::where('tanggal', $tanggal)->count();
        $totalSiswa = $siswa->count();
        $belumIsi = $totalSiswa - $sudahIsi;

        return view('kebiasaan.rekap_guru', compact('siswa', 'tanggal', 'tanggalFormat', 'sudahIsi', 'belumIsi'));
    }
}