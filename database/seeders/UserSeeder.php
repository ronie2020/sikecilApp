<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Buat Akun Kepala Sekolah (Admin)
        DB::table('users')->insert([
            'name' => 'Bapak Kepala Sekolah',
            'email' => 'kepsek@sekolah.id',
            'password' => Hash::make('password123'), // Passwordnya: password123
            'role' => 'admin',
            'nip_nis' => '198001012000',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 2. Buat Akun Guru (Pak Budi)
        DB::table('users')->insert([
            'name' => 'Budi Santoso, S.Pd',
            'email' => 'budi@sekolah.id',
            'password' => Hash::make('guru123'), // Passwordnya: guru123
            'role' => 'guru',
            'nip_nis' => '199002022010',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 3. Buat Data Kelas 7A
        $kelasId = DB::table('kelas')->insertGetId([
            'nama_kelas' => '7A',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 4. Buat Akun Siswa (Doni)
        DB::table('users')->insert([
            'name' => 'Doni Pratama',
            'email' => 'doni@sekolah.id',
            'password' => Hash::make('siswa123'), // Passwordnya: siswa123
            'role' => 'siswa',
            'nip_nis' => '54321',
            'kelas_id' => $kelasId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}