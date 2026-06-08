<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketClass extends Model
{
    protected $table = 'hang_ve';
    protected $primaryKey = 'MaHangVe';
    public $timestamps = false;

    protected $fillable = [
        'MaKhuVuc',
        'TenHangVe',
        'GiaVe',
        'SoLuongMoBan',
        'SoLuongDaBan',
        'ThoiGianMoBan',
        'ThoiGianKetThucBan',
        'QuyenLoi',
    ];

    public function khuVuc()
    {
        return $this->belongsTo(KhuVuc::class, 'MaKhuVuc', 'MaKhuVuc');
    }
}
