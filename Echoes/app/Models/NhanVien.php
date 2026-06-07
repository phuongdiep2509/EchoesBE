<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NhanVien extends Model
{
    protected $table      = 'NHAN_VIEN';
    protected $primaryKey = 'MaNhanVien';
    public $timestamps    = false;

    protected $fillable = [
        'MaTaiKhoan',
        'ChucVu',
        'NgaySinh',
        'GioiTinh',
        'DiaChi',
        'NgayVaoLam',
    ];

    protected function casts(): array
    {
        return [
            'NgaySinh'   => 'date',
            'NgayVaoLam' => 'date',
        ];
    }

    public function taiKhoan()
    {
        return $this->belongsTo(TaiKhoan::class, 'MaTaiKhoan', 'MaTaiKhoan');
    }
}
