<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Presensi;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Set locale & timezone agar akurat
        Carbon::setLocale('id');
        $today = Carbon::now('Asia/Jakarta')->toDateString(); 
        $currentMonth = Carbon::now('Asia/Jakarta')->format('Y-m');
        $startOfWeek = Carbon::now('Asia/Jakarta')->startOfWeek()->format('Y-m-d');
        $endOfWeek = Carbon::now('Asia/Jakarta')->endOfWeek()->format('Y-m-d');

        if ($user->role !== 'siswa') {
            // ============================
            // --- LOGIKA GURU / ADMIN ---
            // ============================

            // 1. Statistik Cards (Top)
            $totalSiswa = User::where('role', 'siswa')->count();
            $hadir = Presensi::where('tanggal', $today)->where('status', 'Hadir')->count();
            $sakitIzin = Presensi::where('tanggal', $today)->whereIn('status', ['Sakit', 'Izin'])->count();
            $alpa = Presensi::where('tanggal', $today)->where('status', 'Alpa')->count();

            // 2. Data Grafik Mingguan (Chart)
            $chartStartDate = Carbon::now('Asia/Jakarta')->startOfWeek(); 
            $chartLabels = [];
            $chartDataHadir = [];
            $chartDataTidakHadir = [];

            for ($i = 0; $i < 5; $i++) { // Senin s/d Jumat
                $d = $chartStartDate->copy()->addDays($i);
                $dateStr = $d->toDateString();
                
                $chartLabels[] = $d->isoFormat('dddd'); 
                $chartDataHadir[] = Presensi::where('tanggal', $dateStr)->where('status', 'Hadir')->count();
                $chartDataTidakHadir[] = Presensi::where('tanggal', $dateStr)->where('status', '!=', 'Hadir')->count();
            }

            // 3. Aktivitas Terbaru (List Samping/Bawah)
            $aktivitasTerbaru = Presensi::with(['user.kelas'])
                                        ->where('tanggal', $today)
                                        ->orderBy('jam_masuk', 'desc')
                                        ->limit(5)
                                        ->get();

            // 4. DATA REKAPITULASI TABEL (CORE FEATURE)
            // Ambil semua siswa & preload presensi bulan ini agar query ringan (Eager Loading)
            $students = User::where('role', 'siswa')
                            ->with('kelas')
                            ->orderBy('name', 'asc')
                            ->get();

            // Ambil semua data presensi bulan ini sekaligus
            $presensiBulanIni = Presensi::where('tanggal', 'like', "$currentMonth%")
                                        ->get()
                                        ->groupBy('user_id');

            // Map data siswa dengan statistik presensinya
            $rekapAbsensi = $students->map(function($student) use ($presensiBulanIni, $today, $startOfWeek, $endOfWeek) {
                // Ambil koleksi presensi milik siswa ini (jika ada)
                $records = $presensiBulanIni->get($student->id, collect());
                
                // Status Hari Ini
                $todayRecord = $records->firstWhere('tanggal', $today);

                // Hitung Mingguan (Filter dari koleksi bulan ini)
                $weeklyRecords = $records->filter(function ($value) use ($startOfWeek, $endOfWeek) {
                    return $value->tanggal >= $startOfWeek && $value->tanggal <= $endOfWeek;
                });

                return (object) [
                    'id' => $student->id,
                    'name' => $student->name,
                    'kelas' => $student->kelas->nama_kelas ?? '-',
                    
                    // Data Harian
                    'status_harian' => $todayRecord ? $todayRecord->status : 'Belum',
                    'jam_masuk' => $todayRecord && $todayRecord->jam_masuk ? Carbon::parse($todayRecord->jam_masuk)->format('H:i') : '-',

                    // Data Mingguan
                    'w_h' => $weeklyRecords->where('status', 'Hadir')->count(),
                    'w_s' => $weeklyRecords->where('status', 'Sakit')->count(),
                    'w_i' => $weeklyRecords->where('status', 'Izin')->count(),
                    'w_a' => $weeklyRecords->where('status', 'Alpa')->count(),
                    'w_total' => 5, // Asumsi 5 hari sekolah seminggu

                    // Data Bulanan
                    'm_h' => $records->where('status', 'Hadir')->count(),
                    'm_s' => $records->where('status', 'Sakit')->count(),
                    'm_i' => $records->where('status', 'Izin')->count(),
                    'm_a' => $records->where('status', 'Alpa')->count(),
                    'm_total' => 20, // Asumsi rata-rata 20 hari efektif
                ];
            });

            return view('dashboard', compact(
                'totalSiswa', 'hadir', 'sakitIzin', 'alpa',
                'chartLabels', 'chartDataHadir', 'chartDataTidakHadir',
                'aktivitasTerbaru', 'rekapAbsensi'
            ));

        } else {
            // ============================
            // --- LOGIKA SISWA ---
            // ============================
            
            $presensiHariIni = Presensi::where('user_id', $user->id)
                                        ->where('tanggal', $today)
                                        ->first();
            
            $jamMasukUser = $presensiHariIni ? Carbon::parse($presensiHariIni->jam_masuk)->format('H:i') : null;

            $totalHariSekolah = 20; // Bisa dibuat dinamis nanti
            
            $totalHadirSiswa = Presensi::where('user_id', $user->id)
                                        ->where('tanggal', 'like', $currentMonth . '%')
                                        ->where('status', 'Hadir')
                                        ->count();
            
            $persentaseKehadiran = ($totalHariSekolah > 0) ? round(($totalHadirSiswa / $totalHariSekolah) * 100) : 0;

            $aktivitasTerbaru = Presensi::where('user_id', $user->id)
                                        ->orderBy('tanggal', 'desc')
                                        ->limit(5)
                                        ->get();

            return view('dashboard', compact(
                'jamMasukUser', 'persentaseKehadiran', 'aktivitasTerbaru'
            ));
        }
    }
}