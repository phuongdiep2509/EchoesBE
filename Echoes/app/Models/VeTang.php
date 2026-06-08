<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VeTang extends Model
{
    protected $table = 've_tang';
    protected $primaryKey = 'MaVeTang';
    public $timestamps = false;

    protected $fillable = [
        'MaVe',
        'MaTaiKhoanNguoiTang',
        'TenNguoiNhan',
        'EmailNguoiNhan',
        'SdtNguoiNhan',
        'LoaiThiep',
        'LoiChuc',
        'TrangThai',
        'TokenNhanVe',
        'ThoiGianTang',
        'ThoiGianNhan',
    ];

    protected $casts = [
        'ThoiGianTang' => 'datetime',
        'ThoiGianNhan' => 'datetime',
    ];

    public function ve()
    {
        return $this->belongsTo(Ve::class, 'MaVe', 'MaVe');
    }

    public function nguoiTang()
    {
        return $this->belongsTo(TaiKhoan::class, 'MaTaiKhoanNguoiTang', 'MaTaiKhoan');
    }
}