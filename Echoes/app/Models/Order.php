<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public const STATUS_PENDING = 'ChoThanhToan';
    public const STATUS_PAID = 'DaThanhToan';
    public const STATUS_CANCELLED = 'DaHuy';

    protected $table = 'don_hang';
    protected $primaryKey = 'MaDonHang';
    public $timestamps = false;

    protected $fillable = [
        'MaKhachHang',
        'NgayDat',
        'TongTien',
        'TrangThai',
    ];
}
