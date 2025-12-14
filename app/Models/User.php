<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'nip_nis', 'kelas_id',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return ['email_verified_at' => 'datetime', 'password' => 'hashed'];
    }

    // Relasi Siswa ke Kelas (Siswa A ada di Kelas 7A)
    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    // Relasi Guru ke Kelas (Guru B adalah Wali Kelas 7A)
    // hasOne artinya satu guru hanya boleh jadi wali satu kelas
    public function kelasWali()
    {
        return $this->hasOne(Kelas::class, 'wali_kelas_id');
    }
}