<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Kelas;
use App\Models\Subject;
use Illuminate\Support\Facades\Hash;

class DataMasterController extends Controller
{
    public function index()
    {
        // Load data guru beserta info kelas yang dia wali-kan (jika ada)
        $guru = User::where('role', 'guru')->with('kelasWali')->orderBy('name')->get();
        
        $siswa = User::where('role', 'siswa')->with('kelas')->orderBy('name')->get();
        
        // Load data kelas beserta nama wali kelasnya
        $kelas = Kelas::with('waliKelas')->orderBy('nama_kelas')->get();
        
        $mapel = Subject::orderBy('nama_mapel')->get();

        return view('admin.data_master', compact('guru', 'siswa', 'kelas', 'mapel'));
    }

    // ... (Fungsi storeUser, updateUser User tetap sama, tidak perlu diubah) ...
    public function storeUser(Request $request) {
        // (Isi sama seperti sebelumnya)
        $request->validate(['name' => 'required', 'email' => 'required|email|unique:users', 'password' => 'required|min:6', 'role' => 'required', 'nip_nis' => 'required']);
        User::create(['name' => $request->name, 'email' => $request->email, 'password' => Hash::make($request->password), 'role' => $request->role, 'nip_nis' => $request->nip_nis, 'kelas_id' => $request->kelas_id ?? null]);
        return redirect()->back()->with('success', 'Data berhasil ditambahkan!');
    }

    public function updateUser(Request $request, $id) {
        // (Isi sama seperti sebelumnya)
        $user = User::find($id);
        $request->validate(['name' => 'required', 'email' => 'required|email|unique:users,email,'.$id, 'nip_nis' => 'required']);
        $data = ['name' => $request->name, 'email' => $request->email, 'nip_nis' => $request->nip_nis, 'kelas_id' => $request->kelas_id ?? null];
        if ($request->filled('password')) { $data['password'] = Hash::make($request->password); }
        $user->update($data);
        return redirect()->back()->with('success', 'Data berhasil diperbarui!');
    }

    // Simpan Kelas Baru
    public function storeKelas(Request $request)
    {
        $request->validate(['nama_kelas' => 'required']);
        Kelas::create([
            'nama_kelas' => $request->nama_kelas,
            'wali_kelas_id' => $request->wali_kelas_id // Bisa null jika belum ada wali
        ]);
        return redirect()->back()->with('success', 'Kelas berhasil ditambahkan!');
    }

    // === BARU: UPDATE KELAS (UNTUK SET WALI KELAS) ===
    public function updateKelas(Request $request, $id)
    {
        $kelas = Kelas::find($id);
        $request->validate(['nama_kelas' => 'required']);
        
        $kelas->update([
            'nama_kelas' => $request->nama_kelas,
            'wali_kelas_id' => $request->wali_kelas_id // Simpan ID Guru
        ]);

        return redirect()->back()->with('success', 'Data Kelas & Wali Kelas diperbarui!');
    }

    public function storeMapel(Request $request) {
        $request->validate(['nama_mapel' => 'required']);
        Subject::create(['nama_mapel' => $request->nama_mapel, 'kode_mapel' => $request->kode_mapel]);
        return redirect()->back()->with('success', 'Mata Pelajaran berhasil ditambahkan!');
    }
    
    public function destroy($type, $id) {
        if ($type == 'user') User::destroy($id);
        if ($type == 'kelas') Kelas::destroy($id);
        if ($type == 'mapel') Subject::destroy($id);
        return redirect()->back()->with('success', 'Data berhasil dihapus.');
    }
}