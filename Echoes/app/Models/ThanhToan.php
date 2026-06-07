<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ThanhToan extends Model
{
    protected $table = 'thanh_toan';
    protected $primaryKey = 'MaThanhToan';
    public $timestamps = false;

    protected $fillable = [
        'MaDonHang', 'PhuongThucThanhToan', 'SoTien', 'ThoiGianThanhToan', 'MaGiaoDich', 'TrangThai'
    ];

    protected $casts = [
        'SoTien' => 'decimal:2',
        'ThoiGianThanhToan' => 'datetime',
    ];

    public function donHang()
    {
        return $this->belongsTo(DonHang::class, 'MaDonHang', 'MaDonHang');
    }

    public function scopeThanhCong($query)
    {
        return $query->where('TrangThai', 'ThanhCong');
    }

    public function scopeChoThanhToan($query)
    {
        return $query->where('TrangThai', 'ChoThanhToan');
    }
}
