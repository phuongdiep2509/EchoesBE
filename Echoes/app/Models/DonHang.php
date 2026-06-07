<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DonHang extends Model
{
    protected $table = 'don_hang';
    protected $primaryKey = 'MaDonHang';
    public $timestamps = false;

    protected $fillable = ['MaKhachHang', 'NgayDat', 'TongTien', 'TrangThai'];
    protected $casts = [
        'NgayDat' => 'datetime',
        'TongTien' => 'decimal:2',
    ];

    public function khachHang()
    {
        return $this->belongsTo(KhachHang::class, 'MaKhachHang', 'MaKhachHang');
    }

    public function thanhToans()
    {
        return $this->hasMany(ThanhToan::class, 'MaDonHang', 'MaDonHang');
    }

    public function ve()
    {
        return $this->hasMany(Ve::class, 'MaDonHang', 'MaDonHang');
    }

    public function latestPayment()
    {
        return $this->hasOne(ThanhToan::class, 'MaDonHang', 'MaDonHang')->latest('MaThanhToan');
    }
}
