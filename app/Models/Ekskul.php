<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ekskul extends Model
{
    use HasFactory;

    protected $table = 'ekskuls';
    
    // Field yang boleh diisi (Mass Assignment)
    protected $fillable = [
        'nama',
        'pembina',
        'jadwal',
        'kategori',
        'deskripsi',
        'foto',
    ];

    /**
     * Relasi: Satu Ekskul memiliki banyak data PresensiEkskul.
     * Fungsi ini yang dipanggil oleh withCount('presensis') di Controller.
     */
    public function presensis()
    {
        // Pastikan nama model PresensiEkskul sesuai dengan file yang Anda buat
        return $this->hasMany(PresensiEkskul::class, 'ekskul_id');
    }
}