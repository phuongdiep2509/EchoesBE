<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Music extends Model
{
    // Map sang đúng bảng trong DB
    protected $table = 'su_kien';

    // Primary key theo DB
    protected $primaryKey = 'MaSuKien';

    // DB dùng tên cột tiếng Việt, không theo chuẩn snake_case
    public $timestamps = false;

    protected $fillable = [
        'MaBTC',
        'MaDiaDiem',
        'MaLoaiSuKien',
        'TenSuKien',
        'AnhBia',
        'MoTa',
        'DiemNoiBat',
        'DieuKienVaDieuKhoan',
        'ThoiGianBatDau',
        'ThoiGianKetThuc',
        'TrangThai',
    ];

    // Relationship: một sự kiện có nhiều hạng vé (qua khu_vuc_su_kien)
    public function khuVuc()
    {
        return $this->hasMany(KhuVuc::class, 'MaSuKien', 'MaSuKien');
    }

    // Relationship: nghệ sĩ biểu diễn
    public function ngheSi()
    {
        return $this->belongsToMany(
            NgheSi::class,
            'tham_gia_bieu_dien',
            'MaSuKien',
            'MaNgheSi'
        )->withPivot('ThuTuBieuDien', 'ThoiGianBieuDien');
    }

    // Relationship: địa điểm
    public function diaDiem()
    {
        return $this->belongsTo(DiaDiem::class, 'MaDiaDiem', 'MaDiaDiem');
    }
}
