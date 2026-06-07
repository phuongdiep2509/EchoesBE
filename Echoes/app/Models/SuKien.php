<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuKien extends Model
{
    protected $table = 'su_kien';
    protected $primaryKey = 'MaSuKien';
    public $timestamps = false;

    protected $fillable = [
        'MaBTC', 'MaDiaDiem', 'MaLoaiSuKien', 'TenSuKien', 'AnhBia', 'MoTa', 'DiemNoiBat',
        'DieuKienVaDieuKhoan', 'ThoiGianBatDau', 'ThoiGianKetThuc', 'TrangThai'
    ];

    protected $casts = [
        'ThoiGianBatDau' => 'datetime',
        'ThoiGianKetThuc' => 'datetime',
    ];

    public function ve()
    {
        return $this->hasMany(Ve::class, 'MaSuKien', 'MaSuKien');
    }
}
