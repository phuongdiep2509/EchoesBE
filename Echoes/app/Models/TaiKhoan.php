<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaiKhoan extends Model
{
    protected $table = 'tai_khoan';
    protected $primaryKey = 'MaTaiKhoan';
    public $timestamps = false;

    protected $fillable = [
        'TenDangNhap', 'MatKhau', 'HoTen', 'Email', 'SoDienThoai', 'VaiTro', 'TrangThai'
    ];
    protected $hidden = ['MatKhau'];

    public function khachHang()
    {
        return $this->hasOne(KhachHang::class, 'MaTaiKhoan', 'MaTaiKhoan');
    }
}
