<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BukuPenghubung extends Model
{
    use HasFactory;

    // PERBAIKAN FATAL: Nama tabel harus jamak (plural) sesuai migrasi Laravel
    protected $table = 'buku_penghubungs'; 

    protected $fillable = [
        'siswa_id',
        'pengirim_id',
        'pesan',
        'is_read',
    ];

    protected $dates = ['created_at', 'updated_at'];

    // Relasi ke Siswa (Pemilik buku penghubung)
    public function siswa()
    {
        return $this->belongsTo(User::class, 'siswa_id');
    }

    // Relasi ke Pengirim (Siapa yang ngetik pesan)
    public function pengirim()
    {
        return $this->belongsTo(User::class, 'pengirim_id');
    }
}