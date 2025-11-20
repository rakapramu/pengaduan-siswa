<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailSesi extends Model
{
    protected $guarded = ['id'];

    public function sesi()
    {
        return $this->belongsTo(SesiKonseling::class, 'sesi_id');
    }
}
