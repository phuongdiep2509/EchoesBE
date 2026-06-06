@extends('layouts.app')

@section('title', 'Hồ sơ cá nhân')

@section('styles')
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
<style>
.profile-container { max-width: 700px; margin: 40px auto; padding: 0 20px 60px; }

.profile-card { background: #fff; border-radius: 16px; box-shadow: 0 4px 24px rgba(0,0,0,.1); overflow: hidden; }

.profile-header {
    background: linear-gradient(135deg, #74070d 0%, #a01015 60%, #f3e3b2 100%);
    padding: 40px 30px;
    text-align: center;
    color: #fff;
}
.avatar-placeholder {
    width: 90px; height: 90px;
    border-radius: 50%;
    border: 3px solid rgba(255,255,255,.6);
    background: rgba(255,255,255,.2);
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 14px;
    font-size: 40px; color: rgba(255,255,255,.85);
}
.profile-name { font-size: 22px; font-weight: 700; margin-bottom: 6px; }
.role-badge {
    display: inline-block; padding: 4px 14px;
    border-radius: 20px; font-size: 12px;
    font-weight: 600; text-transform: uppercase; letter-spacing: .5px;
}
.badge-Admin    { background: #ffd700; color: #333; }
.badge-NhanVien { background: #4CAF50; color: #fff; }
.badge-KhachHang{ background: rgba(255,255,255,.25); color: #fff; border: 1px solid rgba(255,255,255,.5); }

.profile-body { padding: 28px 30px; }

.info-row {
    display: flex; gap: 14px;
    align-items: flex-start;
    padding: 12px 0;
    border-bottom: 1px solid #f0f0f0;
}
.info-row:last-of-type { border-bottom: none; }
.info-icon { width: 36px; height: 36px; border-radius: 8px; background: #fef4f4; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.info-icon i { color: #74070d; font-size: 17px; }
.info-text {}
.info-label { font-size: 12px; color: #999; margin-bottom: 2px; }
.info-value { font-size: 15px; color: #222; font-weight: 500; }

.profile-actions { display: flex; gap: 12px; flex-wrap: wrap; margin-top: 24px; }
.btn-edit {
    background: #74070d; color: #fff;
    border: none; padding: 11px 28px;
    border-radius: 8px; font-size: 14px; font-weight: 600;
    cursor: pointer; text-decoration: none;
    display: inline-flex; align-items: center; gap: 6px;
    transition: background .2s;
}
.btn-edit:hover { background: #5a0509; color: #fff; }
.btn-back {
    padding: 11px 22px; border: 2px solid #ddd;
    border-radius: 8px; color: #555; font-size: 14px;
    font-weight: 600; text-decoration: none;
    display: inline-flex; align-items: center; gap: 6px;
    transition: border-color .2s;
}
.btn-back:hover { border-color: #74070d; color: #74070d; }

.alert-success {
    background: #d4edda; border: 1px solid #c3e6cb;
    color: #155724; padding: 12px 16px;
    border-radius: 8px; margin-bottom: 20px;
    display: flex; align-items: center; gap: 8px;
}
</style>
@endsection

@section('content')
<div class="profile-container">

    @if(session('success'))
        <div class="alert-success">
            <i class='bx bxs-check-circle'></i> {{ session('success') }}
        </div>
    @endif

    <div class="profile-card">

        {{-- Header --}}
        <div class="profile-header">
            <div class="avatar-placeholder">
                <i class='bx bxs-user'></i>
            </div>
            <div class="profile-name">{{ $user->HoTen }}</div>
            <span class="role-badge badge-{{ $user->VaiTro }}">
                @if($user->VaiTro === 'Admin') Quản trị viên
                @elseif($user->VaiTro === 'NhanVien') Nhân viên
                @else Khách hàng @endif
            </span>
        </div>

        {{-- Body --}}
        <div class="profile-body">

            <div class="info-row">
                <div class="info-icon"><i class='bx bxs-user-detail'></i></div>
                <div class="info-text">
                    <div class="info-label">Tên đăng nhập</div>
                    <div class="info-value">{{ $user->TenDangNhap }}</div>
                </div>
            </div>

            <div class="info-row">
                <div class="info-icon"><i class='bx bxs-id-card'></i></div>
                <div class="info-text">
                    <div class="info-label">Họ và tên</div>
                    <div class="info-value">{{ $user->HoTen }}</div>
                </div>
            </div>

            <div class="info-row">
                <div class="info-icon"><i class='bx bxs-envelope'></i></div>
                <div class="info-text">
                    <div class="info-label">Email</div>
                    <div class="info-value">{{ $user->Email }}</div>
                </div>
            </div>

            <div class="info-row">
                <div class="info-icon"><i class='bx bxs-phone'></i></div>
                <div class="info-text">
                    <div class="info-label">Số điện thoại</div>
                    <div class="info-value">{{ $user->SoDienThoai ?: '—' }}</div>
                </div>
            </div>

            @if($user->isKhachHang() && $user->khachHang)
            <div class="info-row">
                <div class="info-icon"><i class='bx bxs-cake'></i></div>
                <div class="info-text">
                    <div class="info-label">Ngày sinh</div>
                    <div class="info-value">
                        {{ $user->khachHang->NgaySinh ? $user->khachHang->NgaySinh->format('d/m/Y') : '—' }}
                    </div>
                </div>
            </div>

            <div class="info-row">
                <div class="info-icon"><i class='bx bx-male-female'></i></div>
                <div class="info-text">
                    <div class="info-label">Giới tính</div>
                    <div class="info-value">
                        @if($user->khachHang->GioiTinh === 'Nam') Nam
                        @elseif($user->khachHang->GioiTinh === 'Nu') Nữ
                        @elseif($user->khachHang->GioiTinh === 'Khac') Khác
                        @else — @endif
                    </div>
                </div>
            </div>
            @endif

            <div class="profile-actions">
                <a href="{{ route('profile.edit') }}" class="btn-edit">
                    <i class='bx bxs-edit'></i> Chỉnh sửa hồ sơ
                </a>
                <a href="{{ route('home') }}" class="btn-back">
                    <i class='bx bx-arrow-back'></i> Về trang chủ
                </a>
            </div>
        </div>

    </div>
</div>
@endsection
