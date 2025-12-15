<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ekskul;
use App\Models\User;
use App\Models\PresensiEkskul; 
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class EkskulController extends Controller
{
    // --- HALAMAN PUBLIK: INFO EKSKUL ---
    public function landingPage()
    {
        // Ambil semua data ekskul
        $ekskuls = Ekskul::all();
        
        // Perbaikan: Arahkan ke view khusus Info Ekskul, bukan Welcome
        return view('ekstrakurikuler.info', compact('ekskuls'));
    }

    // --- DASHBOARD: MANAJEMEN EKSKUL ---
    public function index()
    {
        // Mengambil data ekskul untuk admin mengelola data
        $ekskuls = Ekskul::withCount('presensis')->get(); 
        return view('ekstrakurikuler.index', compact('ekskuls'));
    }

    // --- PROSES SIMPAN DATA EKSKUL ---
    public function store(Request $request)
    {
        $request->validate([
            'nama'      => 'required|string|max:255',
            'pembina'   => 'required|string|max:255',
            'kategori'  => 'required|string',
            'jadwal'    => 'required|string',
            'deskripsi' => 'required|string',
            'foto'      => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $data = $request->all();

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('public/ekskul_images');
            $data['foto'] = str_replace('public/', '', $path);
        }

        Ekskul::create($data);

        return redirect()->back()->with('success', 'Data Ekskul berhasil ditambahkan!');
    }

    public function destroy($id)
    {
        $ekskul = Ekskul::findOrFail($id);
        if($ekskul->foto) Storage::delete('public/'.$ekskul->foto);
        $ekskul->delete();
        return redirect()->back()->with('success', 'Ekskul dihapus.');
    }

    // --- DASHBOARD: HALAMAN INPUT ABSEN ---
    public function presensiView($id)
    {
        $ekskul = Ekskul::findOrFail($id);
        $siswas = User::where('role', 'siswa')->orderBy('name')->get();
        return view('ekstrakurikuler.presensi', compact('ekskul', 'siswas'));
    }

    // --- PROSES SIMPAN ABSENSI ---
    public function presensiStore(Request $request, $id)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'status'  => 'required|array',
        ]);

        $tanggal = $request->tanggal;
        
        DB::transaction(function() use ($request, $id, $tanggal) {
            foreach($request->status as $userId => $status) {
                PresensiEkskul::updateOrInsert(
                    [
                        'ekskul_id' => $id,
                        'user_id'   => $userId,
                        'tanggal'   => $tanggal
                    ],
                    [
                        'status'     => $status,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]
                );
            }
        });

        return redirect()->route('admin.ekskul.index')->with('success', 'Presensi berhasil disimpan!');
    }
}