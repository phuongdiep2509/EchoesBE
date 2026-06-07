<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $table = 've';
    protected $primaryKey = 'MaVe';
    public $timestamps = false;

    protected $fillable = [
        'MaDonHang',
        'MaHangVe',
        'MaGhe',
        'MaSuKien',
        'MaQR',
        'MaVeDienTu',
        'TrangThai',
        'ThoiGianCheckIn',
    ];
}
