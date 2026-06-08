<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoaiSuKien extends Model
{
    protected $table = 'loai_su_kien';
    protected $primaryKey = 'MaLoaiSuKien';
    public $timestamps = false;

    protected $fillable = [
        'TenLoai',
    ];
}
