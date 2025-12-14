<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BukuPenghubung;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class BukuPenghubungController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $role = strtolower(trim($user->role));

        // === LOGIKA GURU / ADMIN ===
        if ($role === 'guru' || $role === 'admin') {
            
            // Ambil semua siswa untuk sidebar
            $listSiswa = User::where('role', 'siswa')->orderBy('name', 'asc')->get();

            // Skenario A: Guru membuka chat spesifik
            if ($request->has('siswa_id')) {
                $targetSiswaId = $request->siswa_id;
                $siswaAktif = User::find($targetSiswaId);
                
                // Ambil chat urut dari yang terlama ke terbaru
                $chat = BukuPenghubung::where('siswa_id', $targetSiswaId)
                                      ->orderBy('created_at', 'asc')
                                      ->get();

                return view('penghubung.guru_chat', compact('listSiswa', 'siswaAktif', 'chat'));
            }

            // Skenario B: Guru baru masuk menu (belum pilih siswa)
            return view('penghubung.guru_index', compact('listSiswa'));
        }

        // === LOGIKA SISWA / ORANG TUA ===
        else {
            // Siswa hanya melihat chat miliknya sendiri
            $chat = BukuPenghubung::where('siswa_id', $user->id)
                                  ->orderBy('created_at', 'asc')
                                  ->get();
            
            return view('penghubung.siswa_index', compact('chat'));
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'pesan' => 'required',
            'siswa_id' => 'required'
        ]);

        $user = Auth::user();
        $role = strtolower(trim($user->role));

        // Simpan Pesan
        BukuPenghubung::create([
            'siswa_id' => $request->siswa_id, 
            'pengirim_id' => $user->id,
            'pesan' => $request->pesan,
            'is_read' => false,
        ]);

        // Redirect kembali ke halaman yang benar agar chat langsung muncul
        if ($role === 'guru' || $role === 'admin') {
            return redirect('/buku-penghubung?siswa_id=' . $request->siswa_id);
        } else {
            return redirect('/buku-penghubung');
        }
    }
}