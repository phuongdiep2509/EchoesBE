<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KhachHang extends Model
{
    protected $table      = 'KHACH_HANG';
    protected $primaryKey = 'MaKhachHang';
    public $timestamps    = false;

    protected $fillable = [
        'MaTaiKhoan',
        'NgaySinh',
        'GioiTinh',
        'DiaChi',
    ];

    protected function casts(): array
    {
        return ['NgaySinh' => 'date'];
    }

    public function taiKhoan()
    {
        return $this->belongsTo(TaiKhoan::class, 'MaTaiKhoan', 'MaTaiKhoan');
    }
}
