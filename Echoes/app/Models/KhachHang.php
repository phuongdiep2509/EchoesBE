<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KhachHang extends Model
{
    // Specify the table and primary key once
    protected $table = 'KHACH_HANG';
    protected $primaryKey = 'MaKhachHang';
    public $timestamps = false;

    protected $fillable = [
        'MaTaiKhoan',
        'NgaySinh',
        'GioiTinh',
        'DiaChi',
    ];

    // Use the $casts property for attribute casting
    protected $casts = [
        'NgaySinh' => 'date',
    ];

    public function taiKhoan()
    {
        return $this->belongsTo(TaiKhoan::class, 'MaTaiKhoan', 'MaTaiKhoan');
    }

    public function donHangs()
    {
        return $this->hasMany(DonHang::class, 'MaKhachHang', 'MaKhachHang');
    }
}
