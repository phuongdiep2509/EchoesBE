<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Concert extends Model
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

    // Accessor: tính trạng thái hiện tại
    public function getTrangThaiHienTaiAttribute()
    {
        if ($this->TrangThai === 'DaHuy') {
            return 'DaHuy';
        }

        $now = \Carbon\Carbon::now();
        $start = \Carbon\Carbon::parse($this->ThoiGianBatDau);
        $end = \Carbon\Carbon::parse($this->ThoiGianKetThuc);

        if ($now->greaterThan($end)) {
            return 'DaKetThuc';
        } elseif ($now->between($start, $end)) {
            return 'DangDienRa';
        } elseif ($now->lessThan($start)) {
            // Có thể kiểm tra thêm logic DangMoBan nếu cần, tạm thời trả về TrangThai gốc hoặc SapDienRa
            return $this->TrangThai !== 'DangDienRa' && $this->TrangThai !== 'DaKetThuc' ? $this->TrangThai : 'SapDienRa';
        }

        return $this->TrangThai;
    }
}
