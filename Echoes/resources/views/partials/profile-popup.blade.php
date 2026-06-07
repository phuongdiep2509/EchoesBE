@auth
{{-- ═══════════════════════════════════════════════════
     PROFILE POPUP — Hồ sơ cá nhân
     Mở bằng: openProfilePopup()
═══════════════════════════════════════════════════ --}}
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

<style>
/* ── Overlay ── */
.profile-overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,.55);
    z-index: 99998;
    justify-content: center;
    align-items: center;
    backdrop-filter: blur(3px);
}
.profile-overlay.show { display: flex; }

/* ── Modal box ── */
.profile-modal {
    position: relative;
    background: #fff;
    border-radius: 16px;
    width: 520px;
    max-width: 95vw;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow: 0 8px 40px rgba(0,0,0,.25);
    font-family: "Fraunces", sans-serif;
    animation: pmSlideIn .25s ease;
}
@keyframes pmSlideIn {
    from { transform: translateY(-20px); opacity: 0; }
    to   { transform: translateY(0);     opacity: 1; }
}

/* ── Close btn ── */
.pm-close {
    position: absolute;
    top: 14px; right: 16px;
    background: rgba(255,255,255,.3);
    border: none; border-radius: 50%;
    width: 30px; height: 30px;
    font-size: 18px; cursor: pointer;
    color: #fff; display: flex;
    align-items: center; justify-content: center;
    z-index: 10; transition: background .2s;
}
.pm-close:hover { background: rgba(255,255,255,.5); }

/* ── Header ── */
.pm-header {
    background: linear-gradient(135deg, #74070d 0%, #a01015 60%, #f3e3b2 100%);
    padding: 30px 28px 20px;
    color: #fff;
    text-align: center;
    border-radius: 16px 16px 0 0;
}
.pm-avatar {
    width: 72px; height: 72px;
    border-radius: 50%;
    border: 3px solid rgba(255,255,255,.6);
    background: rgba(255,255,255,.2);
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 10px;
    font-size: 32px; color: rgba(255,255,255,.9);
}
.pm-name { font-size: 18px; font-weight: 700; margin-bottom: 4px; }
.pm-role {
    display: inline-block; padding: 3px 12px;
    border-radius: 20px; font-size: 11px;
    font-weight: 600; text-transform: uppercase; letter-spacing: .5px;
}
.pm-role-admin    { background: #ffd700; color: #333; }
.pm-role-nhanvien { background: #4CAF50; color: #fff; }
.pm-role-kh       { background: rgba(255,255,255,.25); color: #fff; border: 1px solid rgba(255,255,255,.4); }

/* ── Tabs ── */
.pm-tabs {
    display: flex;
    border-bottom: 2px solid #f0f0f0;
    padding: 0 28px;
    background: #fafafa;
}
.pm-tab {
    padding: 12px 18px;
    border: none; background: none;
    font-size: 13px; font-weight: 600;
    color: #999; cursor: pointer;
    border-bottom: 2px solid transparent;
    margin-bottom: -2px;
    font-family: "Fraunces", sans-serif;
    display: flex; align-items: center; gap: 6px;
    transition: color .2s;
}
.pm-tab.active { color: #74070d; border-bottom-color: #74070d; }
.pm-tab-content { display: none; padding: 24px 28px; }
.pm-tab-content.active { display: block; }

/* ── Info rows ── */
.pm-info-row {
    display: flex; gap: 12px; align-items: flex-start;
    padding: 10px 0; border-bottom: 1px solid #f5f5f5;
}
.pm-info-row:last-of-type { border-bottom: none; }
.pm-info-icon {
    width: 34px; height: 34px; flex-shrink: 0;
    border-radius: 8px; background: #fef4f4;
    display: flex; align-items: center; justify-content: center;
}
.pm-info-icon i { color: #74070d; font-size: 15px; }
.pm-info-label { font-size: 11px; color: #aaa; margin-bottom: 2px; }
.pm-info-value { font-size: 14px; color: #222; font-weight: 500; }

/* ── Form elements ── */
.pm-form-group { margin-bottom: 16px; }
.pm-form-group label {
    display: block; font-size: 12px;
    font-weight: 600; color: #555; margin-bottom: 5px;
}
.pm-form-group input,
.pm-form-group select {
    width: 100%; padding: 9px 12px;
    border: 1.5px solid #e0e0e0; border-radius: 8px;
    font-size: 14px; outline: none;
    transition: border-color .2s;
    font-family: inherit; box-sizing: border-box;
}
.pm-form-group input:focus,
.pm-form-group select:focus { border-color: #74070d; }
.pm-form-group input[readonly] { background: #f7f7f7; color: #aaa; cursor: not-allowed; }

.pm-pwd-wrap { position: relative; }
.pm-pwd-wrap input { padding-right: 42px; }
.pm-pwd-eye {
    position: absolute; right: 12px; top: 50%;
    transform: translateY(-50%);
    background: none; border: none;
    cursor: pointer; color: #aaa; font-size: 16px; padding: 0;
}
.pm-pwd-eye:hover { color: #74070d; }

/* ── Buttons ── */
.pm-btn-save {
    background: #74070d; color: #fff;
    border: none; padding: 10px 26px;
    border-radius: 8px; font-size: 13px; font-weight: 600;
    cursor: pointer; font-family: inherit;
    display: inline-flex; align-items: center; gap: 6px;
    transition: background .2s;
}
.pm-btn-save:hover { background: #5a0509; }
.pm-btn-edit {
    background: #74070d; color: #fff;
    border: none; padding: 9px 20px;
    border-radius: 8px; font-size: 13px; font-weight: 600;
    cursor: pointer; font-family: inherit;
    display: inline-flex; align-items: center; gap: 6px;
    margin-top: 16px; transition: background .2s;
}
.pm-btn-edit:hover { background: #5a0509; }

/* ── Alerts ── */
.pm-alert-ok {
    background: #d4edda; border: 1px solid #c3e6cb;
    color: #155724; padding: 10px 14px;
    border-radius: 8px; margin-bottom: 16px;
    font-size: 13px; display: flex; align-items: center; gap: 7px;
}
.pm-alert-err {
    background: #f8d7da; border: 1px solid #f5c6cb;
    color: #721c24; padding: 10px 14px;
    border-radius: 8px; margin-bottom: 16px; font-size: 13px;
}
.pm-field-err { color: #e74c3c; font-size: 12px; margin-top: 3px; }

/* ── Password rules ── */
.pm-pwd-rules {
    list-style: none; padding: 7px 10px;
    border: 1px solid #e0e0e0; border-radius: 8px;
    margin-top: 6px; font-size: 11px; background: #fafafa;
}
.pm-pwd-rules li { display: flex; align-items: center; gap: 5px; margin-bottom: 1px; color: #999; }
.pm-pwd-rules.valid { border-color: #38c172; }

/* ── Sub-tabs (edit) ── */
.pm-subtabs {
    display: flex; gap: 4px;
    margin-bottom: 20px;
}
.pm-subtab {
    padding: 7px 16px;
    border: 1.5px solid #e0e0e0; border-radius: 20px;
    background: #fff; font-size: 12px; font-weight: 600;
    color: #888; cursor: pointer; font-family: inherit;
    transition: all .2s;
}
.pm-subtab.active { background: #74070d; color: #fff; border-color: #74070d; }

/* ── Responsive ── */
@media (max-width: 560px) {
    .profile-modal { border-radius: 0; max-height: 100dvh; width: 100vw; }
}
</style>

<div class="profile-overlay" id="profileOverlay">
    <div class="profile-modal" id="profileModal">

        <button class="pm-close" onclick="closeProfilePopup()" aria-label="Đóng">×</button>

        {{-- Header --}}
        <div class="pm-header">
            <div class="pm-avatar"><i class='bx bxs-user'></i></div>
            <div class="pm-name">{{ Auth::user()->HoTen }}</div>
            <span class="pm-role {{ Auth::user()->isAdmin() ? 'pm-role-admin' : (Auth::user()->isNhanVien() ? 'pm-role-nhanvien' : 'pm-role-kh') }}">
                @if(Auth::user()->isAdmin()) Quản trị viên
                @elseif(Auth::user()->isNhanVien()) Nhân viên
                @else Khách hàng @endif
            </span>
        </div>

        {{-- Tabs --}}
        <div class="pm-tabs">
            <button class="pm-tab active" onclick="pmSwitchTab('view', this)">
                <i class='bx bxs-user-detail'></i> Hồ sơ
            </button>
            <button class="pm-tab" onclick="pmSwitchTab('edit', this)">
                <i class='bx bxs-edit'></i> Chỉnh sửa
            </button>
            <button class="pm-tab" onclick="pmSwitchTab('pwd', this)">
                <i class='bx bxs-lock-alt'></i> Đổi mật khẩu
            </button>
        </div>

        {{-- ── Tab: Xem thông tin ── --}}
        <div id="pm-tab-view" class="pm-tab-content active">

            @if(session('profile_success'))
                <div class="pm-alert-ok">
                    <i class='bx bxs-check-circle'></i> {{ session('profile_success') }}
                </div>
            @endif

            <div class="pm-info-row">
                <div class="pm-info-icon"><i class='bx bxs-user-detail'></i></div>
                <div>
                    <div class="pm-info-label">Tên đăng nhập</div>
                    <div class="pm-info-value">{{ Auth::user()->TenDangNhap }}</div>
                </div>
            </div>
            <div class="pm-info-row">
                <div class="pm-info-icon"><i class='bx bxs-id-card'></i></div>
                <div>
                    <div class="pm-info-label">Họ và tên</div>
                    <div class="pm-info-value">{{ Auth::user()->HoTen }}</div>
                </div>
            </div>
            <div class="pm-info-row">
                <div class="pm-info-icon"><i class='bx bxs-envelope'></i></div>
                <div>
                    <div class="pm-info-label">Email</div>
                    <div class="pm-info-value">{{ Auth::user()->Email }}</div>
                </div>
            </div>
            <div class="pm-info-row">
                <div class="pm-info-icon"><i class='bx bxs-phone'></i></div>
                <div>
                    <div class="pm-info-label">Số điện thoại</div>
                    <div class="pm-info-value">{{ Auth::user()->SoDienThoai ?: '—' }}</div>
                </div>
            </div>
            @if(Auth::user()->isKhachHang() && Auth::user()->khachHang)
            <div class="pm-info-row">
                <div class="pm-info-icon"><i class='bx bxs-cake'></i></div>
                <div>
                    <div class="pm-info-label">Ngày sinh</div>
                    <div class="pm-info-value">
                        {{ Auth::user()->khachHang->NgaySinh?->format('d/m/Y') ?? '—' }}
                    </div>
                </div>
            </div>
            <div class="pm-info-row">
                <div class="pm-info-icon"><i class='bx bx-male-female'></i></div>
                <div>
                    <div class="pm-info-label">Giới tính</div>
                    <div class="pm-info-value">
                        @if(Auth::user()->khachHang->GioiTinh === 'Nam') Nam
                        @elseif(Auth::user()->khachHang->GioiTinh === 'Nu') Nữ
                        @elseif(Auth::user()->khachHang->GioiTinh === 'Khac') Khác
                        @else — @endif
                    </div>
                </div>
            </div>
            @endif

            <button class="pm-btn-edit" onclick="pmSwitchTab('edit', document.querySelectorAll('.pm-tab')[1])">
                <i class='bx bxs-edit'></i> Chỉnh sửa hồ sơ
            </button>
        </div>

        {{-- ── Tab: Chỉnh sửa thông tin ── --}}
        <div id="pm-tab-edit" class="pm-tab-content">

            @if($errors->hasAny(['HoTen','SoDienThoai','NgaySinh','GioiTinh']))
                <div class="pm-alert-err">
                    @foreach($errors->only(['HoTen','SoDienThoai','NgaySinh','GioiTinh']) as $e)
                        <div>{{ $e }}</div>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('profile.update') }}" method="POST">
                @csrf @method('PUT')

                <div class="pm-form-group">
                    <label>Tên đăng nhập</label>
                    <input type="text" value="{{ Auth::user()->TenDangNhap }}" readonly>
                </div>
                <div class="pm-form-group">
                    <label>Email</label>
                    <input type="email" value="{{ Auth::user()->Email }}" readonly>
                </div>
                <div class="pm-form-group">
                    <label>Họ và tên <span style="color:red">*</span></label>
                    <input type="text" name="HoTen"
                           value="{{ old('HoTen', Auth::user()->HoTen) }}" required>
                    @error('HoTen')<div class="pm-field-err">{{ $message }}</div>@enderror
                </div>
                <div class="pm-form-group">
                    <label>Số điện thoại</label>
                    <input type="text" name="SoDienThoai"
                           value="{{ old('SoDienThoai', Auth::user()->SoDienThoai) }}"
                           placeholder="Ví dụ: 0901234567">
                    @error('SoDienThoai')<div class="pm-field-err">{{ $message }}</div>@enderror
                </div>

                @if(Auth::user()->isKhachHang())
                <div class="pm-form-group">
                    <label>Ngày sinh</label>
                    <input type="date" name="NgaySinh"
                           value="{{ old('NgaySinh', Auth::user()->khachHang?->NgaySinh?->format('Y-m-d')) }}">
                </div>
                <div class="pm-form-group">
                    <label>Giới tính</label>
                    <select name="GioiTinh">
                        <option value="">-- Chọn --</option>
                        <option value="Nam"  {{ old('GioiTinh', Auth::user()->khachHang?->GioiTinh) === 'Nam'  ? 'selected' : '' }}>Nam</option>
                        <option value="Nu"   {{ old('GioiTinh', Auth::user()->khachHang?->GioiTinh) === 'Nu'   ? 'selected' : '' }}>Nữ</option>
                        <option value="Khac" {{ old('GioiTinh', Auth::user()->khachHang?->GioiTinh) === 'Khac' ? 'selected' : '' }}>Khác</option>
                    </select>
                </div>
                @endif

                <button type="submit" class="pm-btn-save">
                    <i class='bx bxs-save'></i> Lưu thay đổi
                </button>
            </form>
        </div>

        {{-- ── Tab: Đổi mật khẩu ── --}}
        <div id="pm-tab-pwd" class="pm-tab-content">

            @if($errors->has('current_password'))
                <div class="pm-alert-err">{{ $errors->first('current_password') }}</div>
            @endif
            @if($errors->has('MatKhau'))
                <div class="pm-alert-err">{{ $errors->first('MatKhau') }}</div>
            @endif

            <form action="{{ route('profile.password') }}" method="POST">
                @csrf @method('PUT')

                <div class="pm-form-group">
                    <label>Mật khẩu hiện tại <span style="color:red">*</span></label>
                    <div class="pm-pwd-wrap">
                        <input type="password" name="current_password" id="pmCurPwd" required>
                        <button type="button" class="pm-pwd-eye" onclick="pmTogglePwd('pmCurPwd',this)">
                            <i class='bx bx-show'></i>
                        </button>
                    </div>
                </div>
                <div class="pm-form-group">
                    <label>Mật khẩu mới <span style="color:red">*</span></label>
                    <div class="pm-pwd-wrap">
                        <input type="password" name="MatKhau" id="pmNewPwd" required autocomplete="new-password">
                        <button type="button" class="pm-pwd-eye" onclick="pmTogglePwd('pmNewPwd',this)">
                            <i class='bx bx-show'></i>
                        </button>
                    </div>
                    <ul class="pm-pwd-rules" id="pmPwdRules">
                        <li data-rule="length"><span>✗</span> Từ 8–32 ký tự</li>
                        <li data-rule="lower_digit"><span>✗</span> Chữ thường và số</li>
                        <li data-rule="special"><span>✗</span> Ký tự đặc biệt (!@$%...)</li>
                        <li data-rule="upper"><span>✗</span> Ít nhất 1 chữ in hoa</li>
                    </ul>
                </div>
                <div class="pm-form-group">
                    <label>Xác nhận mật khẩu mới <span style="color:red">*</span></label>
                    <div class="pm-pwd-wrap">
                        <input type="password" name="MatKhau_confirmation" id="pmConfPwd"
                               required autocomplete="new-password">
                        <button type="button" class="pm-pwd-eye" onclick="pmTogglePwd('pmConfPwd',this)">
                            <i class='bx bx-show'></i>
                        </button>
                    </div>
                </div>

                <button type="submit" class="pm-btn-save">
                    <i class='bx bxs-lock-alt'></i> Đổi mật khẩu
                </button>
            </form>
        </div>

    </div>{{-- /.profile-modal --}}
</div>{{-- /.profile-overlay --}}

<script>
// ── Mở / Đóng ──────────────────────────────────────
window.openProfilePopup = function(tab) {
    document.getElementById('profileOverlay').classList.add('show');
    document.body.style.overflow = 'hidden';
    if (tab) pmSwitchTabByName(tab);
};
window.closeProfilePopup = function() {
    document.getElementById('profileOverlay').classList.remove('show');
    document.body.style.overflow = '';
};

// Đóng khi click ngoài
document.getElementById('profileOverlay').addEventListener('click', function(e) {
    if (e.target === this) closeProfilePopup();
});
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeProfilePopup();
});

// ── Tabs ───────────────────────────────────────────
function pmSwitchTab(name, btn) {
    document.querySelectorAll('.pm-tab-content').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('.pm-tab').forEach(b => b.classList.remove('active'));
    document.getElementById('pm-tab-' + name).classList.add('active');
    if (btn) btn.classList.add('active');
}
function pmSwitchTabByName(name) {
    const idx = { view:0, edit:1, pwd:2 }[name] ?? 0;
    pmSwitchTab(name, document.querySelectorAll('.pm-tab')[idx]);
}

// ── Toggle mật khẩu ────────────────────────────────
function pmTogglePwd(id, btn) {
    const inp  = document.getElementById(id);
    const icon = btn.querySelector('i');
    if (inp.type === 'password') {
        inp.type = 'text';
        icon.classList.replace('bx-show', 'bx-hide');
    } else {
        inp.type = 'password';
        icon.classList.replace('bx-hide', 'bx-show');
    }
}

// ── Validate mật khẩu ──────────────────────────────
const pmRules = {
    length:      p => p.length >= 8 && p.length <= 32,
    lower_digit: p => /[a-z]/.test(p) && /\d/.test(p),
    special:     p => /[!@#$%^&*_]/.test(p),
    upper:       p => /[A-Z]/.test(p),
};
document.getElementById('pmNewPwd')?.addEventListener('input', function() {
    const pwd = this.value; let ok = true;
    document.querySelectorAll('#pmPwdRules li').forEach(li => {
        const pass = pmRules[li.dataset.rule]?.(pwd);
        li.querySelector('span').textContent = pass ? '✓' : '✗';
        li.style.color = pass ? '#38c172' : '#999';
        if (!pass) ok = false;
    });
    document.getElementById('pmPwdRules').classList.toggle('valid', ok);
});

// ── Tự mở nếu có lỗi validation ───────────────────
document.addEventListener('DOMContentLoaded', function() {
    @if($errors->hasAny(['HoTen','SoDienThoai','NgaySinh','GioiTinh']))
        openProfilePopup('edit');
    @elseif($errors->hasAny(['current_password','MatKhau']))
        openProfilePopup('pwd');
    @elseif(session('profile_success'))
        openProfilePopup('view');
    @endif
});
</script>
@endauth
