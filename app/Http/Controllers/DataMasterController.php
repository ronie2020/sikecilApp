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

    // === FITUR IMPORT EXCEL (CSV) YANG SUDAH DIPERBAIKI ===
    public function importUser(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt',
            'role' => 'required|in:guru,siswa'
        ]);

        $file = $request->file('file');
        $path = $file->getPathname();

        // 1. Deteksi Delimiter (Pemisah) secara Otomatis (; atau ,)
        $delimiter = ','; // Default
        $handleCheck = fopen($path, 'r');
        if ($handleCheck) {
            $firstLine = fgets($handleCheck);
            if ($firstLine && strpos($firstLine, ';') !== false) {
                $delimiter = ';'; // Ubah jadi titik koma jika ditemukan
            }
            fclose($handleCheck);
        }

        // 2. Proses Import
        $handle = fopen($path, 'r');
        
        // Skip header baris pertama
        fgetcsv($handle, 0, $delimiter);

        $role = $request->role;
        $count = 0;

        while (($row = fgetcsv($handle, 0, $delimiter)) !== FALSE) {
            // Pastikan baris memiliki minimal data yang cukup
            // Gunakan array_filter untuk membuang baris kosong excel
            if (count($row) < 3 || empty(array_filter($row))) continue;

            $name = trim($row[0]);
            $nip_nis = trim($row[1]);
            $email = trim($row[2]);
            // Password di kolom 3, kalau kosong default '123456'
            $passwordRaw = isset($row[3]) && !empty(trim($row[3])) ? trim($row[3]) : '123456'; 
            $password = Hash::make($passwordRaw);

            // Logika Khusus Siswa (Mencari ID Kelas berdasarkan Nama Kelas)
            $kelasId = null;
            if ($role == 'siswa' && isset($row[4])) {
                $namaKelas = trim($row[4]);
                // Cari kelas, ignore case sensitive
                $kelas = Kelas::where('nama_kelas', $namaKelas)->first();
                if ($kelas) {
                    $kelasId = $kelas->id;
                }
            }

            // Cek apakah email sudah ada agar tidak error duplicate entry
            if (User::where('email', $email)->exists()) {
                continue; 
            }

            User::create([
                'name' => $name,
                'nip_nis' => $nip_nis,
                'email' => $email,
                'password' => $password,
                'role' => $role,
                'kelas_id' => $kelasId
            ]);

            $count++;
        }

        fclose($handle);

        if ($count == 0) {
            return redirect()->back()->with('error', 'Tidak ada data yang diimport. Pastikan format CSV benar (Pemisah titik koma atau koma).');
        }

        return redirect()->back()->with('success', "Berhasil mengimport $count data $role!");
    }

    public function storeUser(Request $request) {
        $request->validate(['name' => 'required', 'email' => 'required|email|unique:users', 'password' => 'required|min:6', 'role' => 'required', 'nip_nis' => 'required']);
        User::create(['name' => $request->name, 'email' => $request->email, 'password' => Hash::make($request->password), 'role' => $request->role, 'nip_nis' => $request->nip_nis, 'kelas_id' => $request->kelas_id ?? null]);
        return redirect()->back()->with('success', 'Data berhasil ditambahkan!');
    }

    public function updateUser(Request $request, $id) {
        $user = User::find($id);
        $request->validate(['name' => 'required', 'email' => 'required|email|unique:users,email,'.$id, 'nip_nis' => 'required']);
        $data = ['name' => $request->name, 'email' => $request->email, 'nip_nis' => $request->nip_nis, 'kelas_id' => $request->kelas_id ?? null];
        if ($request->filled('password')) { $data['password'] = Hash::make($request->password); }
        $user->update($data);
        return redirect()->back()->with('success', 'Data berhasil diperbarui!');
    }

    public function storeKelas(Request $request)
    {
        $request->validate(['nama_kelas' => 'required']);
        Kelas::create([
            'nama_kelas' => $request->nama_kelas,
            'wali_kelas_id' => $request->wali_kelas_id 
        ]);
        return redirect()->back()->with('success', 'Kelas berhasil ditambahkan!');
    }

    public function updateKelas(Request $request, $id)
    {
        $kelas = Kelas::find($id);
        $request->validate(['nama_kelas' => 'required']);
        
        $kelas->update([
            'nama_kelas' => $request->nama_kelas,
            'wali_kelas_id' => $request->wali_kelas_id 
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