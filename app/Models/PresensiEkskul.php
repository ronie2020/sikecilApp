<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PresensiEkskul extends Model
{
    use HasFactory;

    // Sesuaikan dengan nama tabel di migration Anda (biasanya plural)
    protected $table = 'presensi_ekskuls';

    protected $fillable = [
        'ekskul_id',
        'user_id',
        'tanggal',
        'status', // Hadir, Sakit, Izin, Alpa
    ];

    /**
     * Relasi kebalikannya (Opsional, tapi bagus untuk ada)
     */
    public function ekskul()
    {
        return $this->belongsTo(Ekskul::class, 'ekskul_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}