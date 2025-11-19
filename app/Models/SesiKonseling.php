<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SesiKonseling extends Model
{
    protected $guarded = ['id'];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'guru_id');
    }

    public function detailSesi()
    {
        return $this->hasOne(DetailSesi::class);
    }
}
