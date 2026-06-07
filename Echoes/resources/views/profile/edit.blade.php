@extends('layouts.app')

@section('title', 'Chỉnh sửa hồ sơ')

@section('styles')
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
<style>
.profile-edit-container { max-width: 700px; margin: 40px auto; padding: 0 20px 60px; }

.profile-edit-card {
    background: #fff; border-radius: 16px;
    box-shadow: 0 4px 24px rgba(0,0,0,.1); overflow: hidden;
}
.card-header {
    background: linear-gradient(135deg, #74070d 0%, #a01015 60%, #f3e3b2 100%);
    padding: 22px 30px; color: #fff;
    display: flex; align-items: center; gap: 10px;
}
.card-header h2 { margin: 0; font-size: 20px; font-weight: 700; }

.card-body { padding: 28px 30px; }

/* Tabs */
.tab-btns {
    display: flex; border-bottom: 2px solid #eee; margin-bottom: 24px;
}
.tab-btn {
    padding: 10px 24px; border: none; background: none;
    font-size: 14px; cursor: pointer; color: #888;
    font-weight: 600; border-bottom: 2px solid transparent; margin-bottom: -2px;
    transition: color .2s;
}
.tab-btn.active { color: #74070d; border-bottom-color: #74070d; }
.tab-content { display: none; }
.tab-content.active { display: block; }

/* Form */
.form-group { margin-bottom: 18px; }
.form-group label { display: block; font-size: 13px; font-weight: 600; color: #555; margin-bottom: 6px; }
.form-group input,
.form-group select {
    width: 100%; padding: 10px 14px;
    border: 1.5px solid #ddd; border-radius: 8px;
    font-size: 15px; outline: none;
    transition: border-color .2s; font-family: inherit;
}
.form-group input:focus, .form-group select:focus { border-color: #74070d; }
.form-group input[readonly] { background: #f7f7f7; color: #999; cursor: not-allowed; }

/* Password toggle */
.pwd-wrap { position: relative; }
.pwd-wrap input { padding-right: 44px; }
.pwd-toggle {
    position: absolute; right: 14px; top: 50%;
    transform: translateY(-50%);
    background: none; border: none; cursor: pointer;
    color: #888; font-size: 18px; padding: 0;
}
.pwd-toggle:hover { color: #74070d; }

.field-error { color: #e74c3c; font-size: 13px; margin-top: 4px; }

/* Buttons */
.btn-save {
    background: #74070d; color: #fff; border: none;
    padding: 11px 30px; border-radius: 8px;
    font-size: 14px; font-weight: 600; cursor: pointer;
    display: inline-flex; align-items: center; gap: 6px;
    transition: background .2s;
}
.btn-save:hover { background: #5a0509; }
.btn-cancel {
    padding: 11px 22px; border: 2px solid #ddd;
    border-radius: 8px; color: #555;
    font-size: 14px; font-weight: 600;
    background: #fff; cursor: pointer;
    text-decoration: none;
    display: inline-flex; align-items: center; gap: 6px;
    transition: border-color .2s;
}
.btn-cancel:hover { border-color: #74070d; color: #74070d; }

.alert-success {
    background: #d4edda; border: 1px solid #c3e6cb;
    color: #155724; padding: 12px 16px;
    border-radius: 8px; margin-bottom: 20px;
    display: flex; align-items: center; gap: 8px;
}
.alert-error {
    background: #f8d7da; border: 1px solid #f5c6cb;
    color: #721c24; padding: 12px 16px;
    border-radius: 8px; margin-bottom: 20px; font-size: 14px;
}

/* Password rules */
.pwd-rules {
    list-style: none; padding: 8px 12px;
    border: 1px solid #ddd; border-radius: 8px;
    margin-top: 8px; font-size: 12px; background: #fafafa;
}
.pwd-rules li { display: flex; align-items: center; gap: 6px; margin-bottom: 2px; color: #888; }
.pwd-rules.valid { border-color: #38c172; }
</style>
@endsection

@section('content')
<div class="profile-edit-container">

    @if(session('success'))
        <div class="alert-success">
            <i class='bx bxs-check-circle'></i> {{ session('success') }}
        </div>
    @endif

    <div class="profile-edit-card">
        <div class="card-header">
            <i class='bx bxs-edit' style="font-size:22px;"></i>
            <h2>Chỉnh sửa hồ sơ</h2>
        </div>
        <div class="card-body">

            <div class="tab-btns">
                <button class="tab-btn active" onclick="switchTab('info', this)">
                    <i class='bx bxs-user'></i> Thông tin cá nhân
                </button>
                <button class="tab-btn" onclick="switchTab('password', this)">
                    <i class='bx bxs-lock-alt'></i> Đổi mật khẩu
                </button>
            </div>

            {{-- ── Tab: Thông tin ── --}}
            <div id="tab-info" class="tab-content active">
                <form action="{{ route('profile.update') }}" method="POST">
                    @csrf @method('PUT')

                    <div class="form-group">
                        <label>Tên đăng nhập</label>
                        <input type="text" value="{{ $user->TenDangNhap }}" readonly>
                    </div>

                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" value="{{ $user->Email }}" readonly>
                    </div>

                    <div class="form-group">
                        <label for="HoTen">Họ và tên <span style="color:red">*</span></label>
                        <input type="text" id="HoTen" name="HoTen"
                               value="{{ old('HoTen', $user->HoTen) }}" required>
                        @error('HoTen') <div class="field-error">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label for="SoDienThoai">Số điện thoại</label>
                        <input type="text" id="SoDienThoai" name="SoDienThoai"
                               value="{{ old('SoDienThoai', $user->SoDienThoai) }}"
                               placeholder="Ví dụ: 0901234567">
                        @error('SoDienThoai') <div class="field-error">{{ $message }}</div> @enderror
                    </div>

                    @if($user->isKhachHang())
                    <div class="form-group">
                        <label for="NgaySinh">Ngày sinh</label>
                        <input type="date" id="NgaySinh" name="NgaySinh"
                               value="{{ old('NgaySinh', $user->khachHang?->NgaySinh?->format('Y-m-d')) }}">
                        @error('NgaySinh') <div class="field-error">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label for="GioiTinh">Giới tính</label>
                        <select id="GioiTinh" name="GioiTinh">
                            <option value="">-- Chọn --</option>
                            <option value="Nam"  {{ old('GioiTinh', $user->khachHang?->GioiTinh) === 'Nam'  ? 'selected' : '' }}>Nam</option>
                            <option value="Nu"   {{ old('GioiTinh', $user->khachHang?->GioiTinh) === 'Nu'   ? 'selected' : '' }}>Nữ</option>
                            <option value="Khac" {{ old('GioiTinh', $user->khachHang?->GioiTinh) === 'Khac' ? 'selected' : '' }}>Khác</option>
                        </select>
                    </div>
                    @endif

                    <div style="display:flex; gap:12px; flex-wrap:wrap; margin-top:8px;">
                        <button type="submit" class="btn-save">
                            <i class='bx bxs-save'></i> Lưu thay đổi
                        </button>
                        <a href="{{ route('profile.show') }}" class="btn-cancel">
                            <i class='bx bx-x'></i> Hủy
                        </a>
                    </div>
                </form>
            </div>

            {{-- ── Tab: Đổi mật khẩu ── --}}
            <div id="tab-password" class="tab-content">
                <form action="{{ route('profile.password') }}" method="POST">
                    @csrf @method('PUT')

                    @error('current_password')
                        <div class="alert-error">{{ $message }}</div>
                    @enderror

                    <div class="form-group">
                        <label>Mật khẩu hiện tại <span style="color:red">*</span></label>
                        <div class="pwd-wrap">
                            <input type="password" name="current_password" id="currentPwd" required>
                            <button type="button" class="pwd-toggle" onclick="togglePwd('currentPwd', this)">
                                <i class='bx bx-show'></i>
                            </button>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Mật khẩu mới <span style="color:red">*</span></label>
                        <div class="pwd-wrap">
                            <input type="password" name="MatKhau" id="newPwd" required autocomplete="new-password">
                            <button type="button" class="pwd-toggle" onclick="togglePwd('newPwd', this)">
                                <i class='bx bx-show'></i>
                            </button>
                        </div>
                        @error('MatKhau') <div class="field-error">{{ $message }}</div> @enderror
                        <ul class="pwd-rules" id="pwdRules">
                            <li data-rule="length"><span>✗</span> Từ 8–32 ký tự</li>
                            <li data-rule="lower_digit"><span>✗</span> Chữ thường và số</li>
                            <li data-rule="special"><span>✗</span> Ký tự đặc biệt (!@$%...)</li>
                            <li data-rule="upper"><span>✗</span> Ít nhất 1 chữ in hoa</li>
                        </ul>
                    </div>

                    <div class="form-group">
                        <label>Xác nhận mật khẩu mới <span style="color:red">*</span></label>
                        <div class="pwd-wrap">
                            <input type="password" name="MatKhau_confirmation" id="confirmPwd"
                                   required autocomplete="new-password">
                            <button type="button" class="pwd-toggle" onclick="togglePwd('confirmPwd', this)">
                                <i class='bx bx-show'></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="btn-save">
                        <i class='bx bxs-lock-alt'></i> Đổi mật khẩu
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>

<script>
function switchTab(name, btn) {
    document.querySelectorAll('.tab-content').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    document.getElementById('tab-' + name).classList.add('active');
    btn.classList.add('active');
}

function togglePwd(id, btn) {
    const input = document.getElementById(id);
    const icon  = btn.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('bx-show', 'bx-hide');
    } else {
        input.type = 'password';
        icon.classList.replace('bx-hide', 'bx-show');
    }
}

// Validate password strength
const rules = {
    length:      p => p.length >= 8 && p.length <= 32,
    lower_digit: p => /[a-z]/.test(p) && /\d/.test(p),
    special:     p => /[!@#$%^&*_]/.test(p),
    upper:       p => /[A-Z]/.test(p),
};
document.getElementById('newPwd')?.addEventListener('input', function () {
    const pwd = this.value;
    let allOk = true;
    document.querySelectorAll('#pwdRules li').forEach(li => {
        const ok = rules[li.dataset.rule]?.(pwd);
        li.querySelector('span').textContent = ok ? '✓' : '✗';
        li.style.color = ok ? '#38c172' : '#888';
        if (!ok) allOk = false;
    });
    document.getElementById('pwdRules').classList.toggle('valid', allOk);
});

// Tự chuyển tab nếu có lỗi mật khẩu
@if($errors->has('current_password') || $errors->has('MatKhau'))
document.addEventListener('DOMContentLoaded', () => {
    switchTab('password', document.querySelectorAll('.tab-btn')[1]);
});
@endif
</script>
@endsection
