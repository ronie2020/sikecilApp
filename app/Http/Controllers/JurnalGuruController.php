<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JurnalGuru;
use App\Models\Kelas;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class JurnalGuruController extends Controller
{
    // 1. Halaman Utama (Feed/Timeline Jurnal)
    public function index(Request $request)
    {
        // Query Dasar
        $query = JurnalGuru::with(['user', 'kelas'])->latest();

        // Filter: Hanya tampilkan jurnal saya jika diminta
        if ($request->filter == 'me') {
            $query->where('guru_id', Auth::id());
        }

        // PERBAIKAN UTAMA: Gunakan paginate(), bukan get()
        // Ini memperbaiki error "Collection::total does not exist"
        $jurnals = $query->paginate(10); 

        return view('jurnal.index', compact('jurnals'));
    }

    // 2. Halaman Form Tambah Jurnal
    public function create()
    {
        $kelas = Kelas::all(); 
        return view('jurnal.create', compact('kelas'));
    }

    // 3. Simpan Jurnal Baru
    public function store(Request $request)
    {
        $request->validate([
            'kelas_id' => 'required',
            'mata_pelajaran' => 'required',
            'materi_pokok' => 'required',
            'foto' => 'image|mimes:jpeg,png,jpg|max:2048',
            'tanggal' => 'nullable|date',
        ]);

        $pathFoto = null;
        if ($request->hasFile('foto')) {
            $pathFoto = $request->file('foto')->store('uploads/jurnal', 'public');
        }

        JurnalGuru::create([
            'guru_id' => Auth::id(),
            'kelas_id' => $request->kelas_id,
            'mata_pelajaran' => $request->mata_pelajaran,
            'materi_pokok' => $request->materi_pokok,
            'deskripsi_kegiatan' => $request->deskripsi_kegiatan,
            'foto_bukti' => $pathFoto,
            'created_at' => $request->tanggal ? $request->tanggal . ' ' . now()->format('H:i:s') : now(),
        ]);

        return redirect()->route('jurnal.index')->with('success', 'Jurnal berhasil diposting!');
    }

    // 4. Halaman Edit Jurnal (Memperbaiki Route not defined)
    public function edit($id)
    {
        $jurnal = JurnalGuru::findOrFail($id);
        
        // Pastikan hanya pemilik yang bisa edit (Kecuali Admin)
        if (Auth::id() !== $jurnal->guru_id && Auth::user()->role !== 'admin') {
            return redirect()->route('jurnal.index')->with('error', 'Anda tidak memiliki akses.');
        }

        $kelas = Kelas::all();
        return view('jurnal.edit', compact('jurnal', 'kelas'));
    }

    // 5. Update Jurnal
    public function update(Request $request, $id)
    {
        $jurnal = JurnalGuru::findOrFail($id);

        if (Auth::id() !== $jurnal->guru_id && Auth::user()->role !== 'admin') {
            return abort(403);
        }

        $request->validate([
            'kelas_id' => 'required',
            'mata_pelajaran' => 'required',
            'materi_pokok' => 'required',
            'foto' => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Cek Foto Baru
        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($jurnal->foto_bukti) {
                Storage::disk('public')->delete($jurnal->foto_bukti);
            }
            $jurnal->foto_bukti = $request->file('foto')->store('uploads/jurnal', 'public');
        }

        $jurnal->update([
            'kelas_id' => $request->kelas_id,
            'mata_pelajaran' => $request->mata_pelajaran,
            'materi_pokok' => $request->materi_pokok,
            'deskripsi_kegiatan' => $request->deskripsi_kegiatan,
            // Foto diupdate otomatis di atas jika ada
        ]);

        return redirect()->route('jurnal.index')->with('success', 'Jurnal berhasil diperbarui!');
    }

    // 6. Hapus Jurnal
    public function destroy($id)
    {
        $jurnal = JurnalGuru::findOrFail($id);

        if (Auth::id() !== $jurnal->guru_id && Auth::user()->role !== 'admin') {
            return abort(403);
        }

        if ($jurnal->foto_bukti) {
            Storage::disk('public')->delete($jurnal->foto_bukti);
        }

        $jurnal->delete();

        return redirect()->route('jurnal.index')->with('success', 'Jurnal berhasil dihapus.');
    }
}