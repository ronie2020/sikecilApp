<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    protected $table = 'kelas';
    
    // Tambahkan 'wali_kelas_id' agar bisa disimpan
    protected $fillable = ['nama_kelas', 'wali_kelas_id'];

    // Relasi ke Guru (Wali Kelas)
    public function waliKelas()
    {
        return $this->belongsTo(User::class, 'wali_kelas_id');
    }
}