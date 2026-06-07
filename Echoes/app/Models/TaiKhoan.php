<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class TaiKhoan extends Authenticatable
{
    use Notifiable;

    protected $table      = 'TAI_KHOAN';
    protected $primaryKey = 'MaTaiKhoan';
    public $timestamps    = false;

    protected $fillable = [
        'TenDangNhap',
        'MatKhau',
        'HoTen',
        'Email',
        'SoDienThoai',
        'GoogleId',
        'ResetToken',
        'ResetTokenExpiry',
        'VaiTro',
        'TrangThai',
        'remember_token',
    ];

    protected $hidden = ['MatKhau', 'remember_token'];

    // Map password field cho Laravel Auth
    public function getAuthPassword(): string
    {
        return $this->MatKhau;
    }

    // Map tên field password để Auth::attempt() dùng đúng cột
    public function getAuthPasswordName(): string
    {
        return 'MatKhau';
    }

    // Laravel dùng remember_token column — khai báo rõ để tránh lỗi
    public function getRememberTokenName(): string
    {
        return 'remember_token';
    }

    protected function casts(): array
    {
        return [
            'ResetTokenExpiry' => 'datetime',
        ];
    }

    // ─── Role helpers ──────────────────────────────────
    public function isAdmin(): bool    { return $this->VaiTro === 'Admin'; }
    public function isNhanVien(): bool { return $this->VaiTro === 'NhanVien'; }
    public function isKhachHang(): bool{ return $this->VaiTro === 'KhachHang'; }
    public function isHoatDong(): bool { return $this->TrangThai === 'HoatDong'; }

    // ─── Relationships ─────────────────────────────────
    public function khachHang()
    {
        return $this->hasOne(KhachHang::class, 'MaTaiKhoan', 'MaTaiKhoan');
    }

    public function nhanVien()
    {
        return $this->hasOne(NhanVien::class, 'MaTaiKhoan', 'MaTaiKhoan');
    }
}
