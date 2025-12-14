<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kebiasaan extends Model
{
    protected $table = 'kebiasaan';

    protected $fillable = [
        'user_id', 'tanggal', 
        'k1', 'k2', 'k3', 'k4', 'k5', 'k6', 'k7',
        'catatan'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}