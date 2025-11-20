<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function konseling()
    {
        return $this->hasMany(SesiKonseling::class);
    }

    public function pengaduan()
    {
        return $this->hasMany(Pengaduan::class);
    }
}
