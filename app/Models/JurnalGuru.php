<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JurnalGuru extends Model
{
    // Nama tabel di database (sesuai SQL yang kita buat di awal)
    protected $table = 'jurnal_guru';

    // Kolom yang boleh diisi
    protected $fillable = [
        'guru_id',
        'kelas_id',
        'mata_pelajaran',
        'materi_pokok',
        'deskripsi_kegiatan',
        'foto_bukti',
    ];

    // Relasi: Jurnal ini ditulis oleh satu Guru (User)
    public function user()
    {
        return $this->belongsTo(User::class, 'guru_id');
    }

    // Relasi: Jurnal ini untuk satu Kelas
    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }
}