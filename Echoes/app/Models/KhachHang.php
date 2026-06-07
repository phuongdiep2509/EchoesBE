<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KhachHang extends Model
{
    protected $table = 'khach_hang';
    protected $primaryKey = 'MaKhachHang';
    public $timestamps = false;

    protected $fillable = ['MaTaiKhoan', 'NgaySinh', 'GioiTinh', 'DiaChi'];

    public function taiKhoan()
    {
        return $this->belongsTo(TaiKhoan::class, 'MaTaiKhoan', 'MaTaiKhoan');
    }

    public function donHangs()
    {
        return $this->hasMany(DonHang::class, 'MaKhachHang', 'MaKhachHang');
    }
}
