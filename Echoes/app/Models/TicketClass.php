<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketClass extends Model
{
    protected $table = 'hang_ve';
    protected $primaryKey = 'MaHangVe';
    public $timestamps = false;

    protected $fillable = [
        'MaKhuVuc',
        'TenHangVe',
        'GiaVe',
        'SoLuongMoBan',
        'SoLuongDaBan',
        'ThoiGianMoBan',
        'ThoiGianKetThucBan',
        'QuyenLoi',
        'TrangThai',
    ];

    public function khuVuc()
    {
        return $this->belongsTo(KhuVuc::class, 'MaKhuVuc', 'MaKhuVuc');
    }

    public function getTrangThaiHienTaiAttribute()
    {
        if (in_array($this->TrangThai, ['DaHuy', 'TamDung'])) {
            return $this->TrangThai;
        }

        if ($this->SoLuongMoBan > 0 && $this->SoLuongDaBan >= $this->SoLuongMoBan) {
            return 'HetVe';
        }

        $now = \Carbon\Carbon::now();

        if ($this->ThoiGianMoBan && $now->lt(\Carbon\Carbon::parse($this->ThoiGianMoBan))) {
            return 'SapMoBan';
        }

        if ($this->ThoiGianKetThucBan && $now->gt(\Carbon\Carbon::parse($this->ThoiGianKetThucBan))) {
            return 'DaKetThuc';
        }

        return 'DangMoBan';
    }
}
