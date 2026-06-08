<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HangVe extends Model
{
    protected $table = 'hang_ve';
    protected $primaryKey = 'MaHangVe';
    public $timestamps = false;

    protected $fillable = [
        'MaKhuVuc', 'TenHangVe', 'GiaVe', 'SoLuongMoBan', 'SoLuongDaBan',
        'ThoiGianMoBan', 'ThoiGianKetThucBan', 'QuyenLoi'
    ];

    protected $casts = [
        'GiaVe' => 'decimal:2',
        'SoLuongMoBan' => 'integer',
        'SoLuongDaBan' => 'integer',
        'ThoiGianMoBan' => 'datetime',
        'ThoiGianKetThucBan' => 'datetime',
    ];

    public function ve()
    {
        return $this->hasMany(Ve::class, 'MaHangVe', 'MaHangVe');
    }
    public function khuVuc()
    {
    return $this->belongsTo(KhuVucSuKien::class, 'MaKhuVuc', 'MaKhuVuc');
    }
}
