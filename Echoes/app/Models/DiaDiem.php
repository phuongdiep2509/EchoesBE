<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiaDiem extends Model
{
    protected $table = 'dia_diem_to_chuc';
    protected $primaryKey = 'MaDiaDiem';
    public $timestamps = false;

    protected $fillable = [
        'TenDiaDiem',
        'DiaChiChiTiet',
        'ThanhPho',
    ];
}
