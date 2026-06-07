<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ve extends Model
{
    protected $table = 've';
    protected $primaryKey = 'MaVe';
    public $timestamps = false;

    protected $fillable = [
        'MaDonHang', 'MaHangVe', 'MaGhe', 'MaSuKien', 'MaQR', 'MaVeDienTu', 'TrangThai', 'ThoiGianCheckIn'
    ];

    protected $casts = [
        'ThoiGianCheckIn' => 'datetime',
    ];

    public function donHang()
    {
        return $this->belongsTo(DonHang::class, 'MaDonHang', 'MaDonHang');
    }

    public function suKien()
    {
        return $this->belongsTo(SuKien::class, 'MaSuKien', 'MaSuKien');
    }

    public function hangVe()
    {
        return $this->belongsTo(HangVe::class, 'MaHangVe', 'MaHangVe');
    }

    public function veTang()
    {
        return $this->hasMany(VeTang::class, 'MaVe', 'MaVe');
    }
    public function gheNgoi()
    {
    return $this->belongsTo(GheNgoi::class, 'MaGhe', 'MaGhe');
    }

    public function veTangs()
    {
    return $this->hasMany(VeTang::class, 'MaVe', 'MaVe');
    }
}
