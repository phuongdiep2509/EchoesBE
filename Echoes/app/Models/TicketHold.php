<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketHold extends Model
{
    protected $table = 'giu_cho_ve';
    protected $primaryKey = 'MaGiuCho';
    public $timestamps = false;

    protected $fillable = [
        'MaKhachHang',
        'ThoiGianBatDau',
        'ThoiGianHetHan',
        'TrangThai',
    ];
}
