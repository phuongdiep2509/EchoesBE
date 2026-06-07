{{-- ═══════════════════════════════════════════════════════
     AUTH POPUP — Đăng nhập / Đăng ký
     Hiển thị khi: bấm TÀI KHOẢN, hoặc session open_auth
═══════════════════════════════════════════════════════ --}}

<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

<style>
/* ══════════════════════════════════════════
   AUTH POPUP — All styles scoped to .auth-overlay
   Không dùng file CSS ngoài để tránh ảnh hưởng layout
   ══════════════════════════════════════════ */

/* Overlay */
.auth-overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.6);
    z-index: 99999;
    justify-content: center;
    align-items: center;
    backdrop-filter: blur(3px);
}
.auth-overlay.show { display: flex; }

/* Wrapper */
.auth-popup-wrapper {
    position: relative;
    display: inline-block;
}

/* Nút đóng */
.auth-popup-close {
    position: absolute;
    top: -14px; right: -14px;
    width: 32px; height: 32px;
    border-radius: 50%;
    background: #fff;
    border: none;
    font-size: 18px;
    cursor: pointer;
    color: #333;
    box-shadow: 0 2px 8px rgba(0,0,0,0.25);
    z-index: 100000;
    display: flex;
    align-items: center;
    justify-content: center;
}
.auth-popup-close:hover { background: #f0f0f0; }

/* ── Container chính ── */
.auth-overlay .container {
    position: relative;
    width: 850px;
    height: 550px;
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 0 30px rgba(0,0,0,.25);
    overflow: hidden;
    transition: .5s ease;
    font-family: "Fraunces", sans-serif;
}

/* ── Form box ── */
.auth-overlay .form-box {
    position: absolute;
    top: 0; right: 0;
    width: 50%; height: 100%;
    padding: 30px 40px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    transition: .6s ease-in-out;
    box-sizing: border-box;
}
.auth-overlay .form-box form { width: 100%; }

.auth-overlay .form-box h1 {
    font-size: 32px;
    margin-bottom: 10px;
    font-family: "Fraunces", serif;
}
.auth-overlay .form-box p {
    margin: 10px 0;
    font-size: 13.5px;
    text-align: center;
}

/* Login */
.auth-overlay .form-box.login {
    background: #f3e3b2;
    color: #000;
}
/* Register */
.auth-overlay .form-box.register {
    background: #74070d;
    color: #fff;
    visibility: hidden;
    right: 0;
}
.auth-overlay .container.active .form-box { right: 50%; }
.auth-overlay .container.active .form-box.register { visibility: visible; }

/* ── Input box ── */
.auth-overlay .input-box {
    position: relative;
    width: 100%;
    margin: 10px 0;
}
.auth-overlay .input-box input {
    width: 100%;
    padding: 9px 50px 9px 16px;
    background: #eee;
    border-radius: 8px;
    border: none;
    outline: none;
    font-size: 15px;
    color: #333;
    box-sizing: border-box;
    font-family: inherit;
}
.auth-overlay .input-box i {
    position: absolute;
    right: 16px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 18px;
    color: #666;
    pointer-events: none;
}
/* Icon khóa lùi vào khi có icon mắt bên cạnh */
.auth-overlay .input-box:has(.toggle-password) i:not(.toggle-password) {
    right: 42px;
}
.auth-overlay .input-box .toggle-password {
    right: 14px;
    cursor: pointer;
    pointer-events: auto;
    color: #555;
    font-size: 18px;
}
.auth-overlay .form-box.register .input-box i { color: #ddd; }

/* ── Buttons ── */
.auth-overlay .btn {
    width: 100%;
    height: 44px;
    border-radius: 8px;
    font-size: 15px;
    font-weight: 600;
    cursor: pointer;
    border: none;
    margin-top: 8px;
    font-family: "Fraunces", serif;
    transition: opacity .2s;
}
.auth-overlay .btn:hover { opacity: .85; }
.auth-overlay .form-box.login .btn {
    background: #f3e3b2;
    color: #000;
    border: 2px solid #000;
}
.auth-overlay .form-box.register .btn {
    background: #74070d;
    color: #fff;
    border: 2px solid #fff;
}

/* ── Social icons ── */
.auth-overlay .social-icons {
    display: flex;
    justify-content: center;
    margin-top: 4px;
}
.auth-overlay .social-icons a {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 9px 20px;
    margin: 0 5px;
    font-size: 14px;
    font-weight: 600;
    border-radius: 8px;
    border: 2px solid #000;
    color: #000;
    background: transparent;
    text-decoration: none;
    transition: background .2s, color .2s;
    font-family: "Fraunces", serif;
}
.auth-overlay .social-icons a:hover {
    background: #000;
    color: #fff;
}
.auth-overlay .form-box.register .social-icons a {
    color: #fff;
    border-color: #fff;
}
.auth-overlay .form-box.register .social-icons a:hover {
    background: #fff;
    color: #74070d;
}

/* ── Password validation box ── */
.auth-overlay .password-validation-box {
    width: 100%;
    border-radius: 8px;
    background: transparent;
    padding: 6px 12px;
    border: 1px solid rgba(255,255,255,.5);
    color: #fff;
    margin: 4px 0 8px;
    font-size: 12px;
    list-style: none;
}
.auth-overlay .password-validation-box.valid { border-color: #38c172; }
.auth-overlay .password-validation-box .error-title {
    font-weight: bold;
    margin-bottom: 2px;
    font-size: 12px;
    color: #fff;
}
.auth-overlay .password-validation-box li {
    display: flex;
    align-items: center;
    margin-bottom: 1px;
    color: #fff;
    list-style: none;
}
.auth-overlay .check-icon {
    width: 16px;
    margin-right: 6px;
    font-weight: bold;
    text-align: center;
}

/* ── Alert error ── */
.auth-overlay .auth-alert-error {
    background: rgba(231,76,60,.15);
    border: 1px solid #e74c3c;
    color: #c0392b;
    padding: 8px 12px;
    border-radius: 8px;
    font-size: 12px;
    margin-bottom: 8px;
    text-align: center;
}
.auth-overlay .form-box.register .auth-alert-error {
    color: #ffcccc;
    border-color: #ffaaaa;
    background: rgba(255,100,100,.15);
}

/* ── Toggle box animation ── */
.auth-overlay .toggle-box {
    position: absolute;
    top: 0; left: 0;
    width: 100%; height: 100%;
    pointer-events: none;
}
.auth-overlay .toggle-box::before {
    content: "";
    position: absolute;
    left: -250%;
    width: 300%;
    height: 100%;
    background: linear-gradient(90deg, #f3e3b2, #74070d);
    border-radius: 150px;
    z-index: 2;
    transition: 1.8s ease-in-out;
    pointer-events: none;
}
.auth-overlay .container.active .toggle-box::before { left: 50%; }

.auth-overlay .toggle-panel {
    position: absolute;
    width: 50%; height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    transition: .6s ease-in-out;
    z-index: 2;
    pointer-events: auto;
    padding: 20px;
    text-align: center;
    box-sizing: border-box;
}
.auth-overlay .toggle-panel.toggle-left {
    background: #74070d;
    color: #fff;
    left: 0;
    transition-delay: 1.2s;
}
.auth-overlay .container.active .toggle-panel.toggle-left {
    left: -50%;
    transition-delay: .6s;
}
.auth-overlay .toggle-panel.toggle-right {
    background: #f3e3b2;
    color: #000;
    right: -50%;
    transition-delay: .6s;
}
.auth-overlay .container.active .toggle-panel.toggle-right {
    right: 0;
    transition-delay: 1.2s;
}
.auth-overlay .toggle-panel h1 {
    font-size: 28px;
    margin-bottom: 8px;
    font-family: "Fraunces", serif;
}
.auth-overlay .toggle-panel p { font-size: 14px; margin-bottom: 16px; }
.auth-overlay .toggle-panel .btn {
    width: 160px; height: 44px;
    background: transparent;
    border: 2px solid #fff;
    color: #fff;
}
.auth-overlay .toggle-panel.toggle-right .btn {
    border-color: #000;
    color: #000;
}

/* ── Forgot modal ── */
.forgot-modal {
    position: fixed;
    inset: 0;
    display: none;
    justify-content: center;
    align-items: center;
    background: rgba(0,0,0,.5);
    z-index: 100050;
}
.forgot-form {
    background: #74070d;
    color: #fff;
    width: 380px;
    max-width: 90vw;
    padding: 32px;
    border-radius: 12px;
    position: relative;
    box-shadow: 0 8px 32px rgba(0,0,0,.3);
    font-family: "Fraunces", serif;
}
.forgot-form h1 { font-size: 26px; text-align: center; margin-bottom: 8px; }
.forgot-form p  { text-align: center; font-size: 13px; margin-bottom: 20px; opacity: .9; }
.forgot-form .input-box { position: relative; margin-bottom: 16px; }
.forgot-form .input-box input {
    width: 100%;
    padding: 10px 44px 10px 16px;
    background: rgba(255,255,255,.12);
    border: 1px solid rgba(255,255,255,.35);
    border-radius: 8px;
    color: #fff;
    font-size: 14px;
    outline: none;
    box-sizing: border-box;
}
.forgot-form .input-box input::placeholder { color: rgba(255,255,255,.6); }
.forgot-form .input-box i {
    position: absolute;
    right: 14px; top: 50%;
    transform: translateY(-50%);
    color: rgba(255,255,255,.7);
    font-size: 18px;
}
.forgot-form .btn {
    width: 100%; height: 44px;
    background: transparent;
    border: 2px solid #fff;
    color: #fff;
    border-radius: 8px;
    font-size: 15px;
    font-weight: 600;
    cursor: pointer;
    font-family: "Fraunces", serif;
}
.forgot-form .close-btn {
    position: absolute;
    top: 12px; right: 14px;
    background: transparent;
    border: none;
    font-size: 28px;
    color: rgba(255,255,255,.8);
    cursor: pointer;
    line-height: 1;
}
.forgot-form .close-btn:hover { color: #fff; }

/* ── Responsive mobile ── */
@media (max-width: 900px) {
    .auth-overlay .container {
        width: 95vw;
        max-width: 850px;
        height: 550px;
    }
}
@media (max-width: 650px) {
    .auth-overlay .container {
        width: 100vw;
        height: 100dvh;
        border-radius: 0;
        overflow-y: auto;
    }
    .auth-overlay .form-box {
        width: 100%;
        height: auto;
        min-height: 100dvh;
        position: relative;
        top: auto; right: auto;
        padding: 50px 24px 80px;
        justify-content: flex-start;
    }
    .auth-overlay .form-box.register { visibility: visible; display: none; }
    .auth-overlay .container.active .form-box.login { display: none; }
    .auth-overlay .container.active .form-box.register { display: flex; }
    .auth-overlay .toggle-box { display: none; }
    .auth-popup-close { top: 12px; right: 12px; position: fixed; z-index: 100010; }
}
</style>

{{-- Overlay --}}
<div class="auth-overlay" id="authOverlay">
    <div class="auth-popup-wrapper">

        <button class="auth-popup-close" id="authPopupClose" aria-label="Đóng">×</button>

        {{-- Container login/register --}}
        <div class="container" id="authContainer">

            {{-- ─── ĐĂNG NHẬP ─── --}}
            <div class="form-box login">
                <form action="{{ route('login.post') }}" method="POST" novalidate>
                    @csrf
                    <h1>Đăng nhập</h1>

                    @if(session('error'))
                        <div class="auth-alert-error">{{ session('error') }}</div>
                    @endif
                    @if($errors->has('login'))
                        <div class="auth-alert-error">{{ $errors->first('login') }}</div>
                    @endif

                    <div class="input-box">
                        <input type="text" name="login" placeholder="Tên đăng nhập hoặc Email"
                               value="{{ old('login') }}" required autocomplete="username">
                        <i class='bx bxs-user'></i>
                    </div>

                    <div class="input-box">
                        <input type="password" name="password" placeholder="Mật khẩu"
                               required autocomplete="current-password">
                        <i class="bx bx-show toggle-password" title="Hiện/ẩn mật khẩu"></i>
                    </div>

                    <div style="display:flex;justify-content:space-between;align-items:center;margin:-5px 0 15px;">
                        <label style="font-size:13px;display:flex;align-items:center;gap:5px;cursor:pointer;">
                            <input type="checkbox" name="remember"> Ghi nhớ
                        </label>
                        <a href="#" id="forgotLink" style="font-size:13px;color:#74070d;">Quên mật khẩu?</a>
                    </div>

                    <button type="submit" class="btn">Đăng nhập</button>

                    <p>Hoặc đăng nhập bằng</p>
                    <div class="social-icons">
                        <a href="{{ route('auth.google') }}" aria-label="Đăng nhập bằng Google">
                            <i class='bx bxl-google'></i> Google
                        </a>
                    </div>
                </form>
            </div>

            {{-- ─── ĐĂNG KÝ ─── --}}
            <div class="form-box register">
                <form action="{{ route('register') }}" method="POST" novalidate>
                    @csrf
                    <h1>Đăng ký</h1>

                    @if($errors->hasAny(['TenDangNhap','HoTen','Email','MatKhau']))
                        <div class="auth-alert-error">
                            @foreach($errors->all() as $err)<div>{{ $err }}</div>@endforeach
                        </div>
                    @endif

                    <div class="input-box">
                        <input type="text" name="TenDangNhap" placeholder="Tên đăng nhập (không dấu)"
                               value="{{ old('TenDangNhap') }}" required>
                        <i class='bx bxs-user'></i>
                    </div>
                    <div class="input-box">
                        <input type="text" name="HoTen" placeholder="Họ và tên"
                               value="{{ old('HoTen') }}" required>
                        <i class='bx bxs-id-card'></i>
                    </div>
                    <div class="input-box">
                        <input type="email" name="Email" placeholder="Email"
                               value="{{ old('Email') }}" required>
                        <i class='bx bxs-envelope'></i>
                    </div>
                    <div class="input-box">
                        <input type="password" name="MatKhau" id="regPassword"
                               placeholder="Mật khẩu" required autocomplete="new-password">
                        <i class="bx bx-show toggle-password" title="Hiện/ẩn mật khẩu"></i>
                    </div>
                    <div class="input-box">
                        <input type="password" name="MatKhau_confirmation"
                               placeholder="Xác nhận mật khẩu" required autocomplete="new-password">
                        <i class="bx bx-show toggle-password" title="Hiện/ẩn mật khẩu"></i>
                    </div>

                    <div class="password-validation-box error-message" id="pwdValidation">
                        <p class="error-title" id="pwdTitle">❌ Mật khẩu chưa hợp lệ</p>
                        <ul>
                            <li data-rule="length"><span class="check-icon">✗</span> Từ 8 - 32 ký tự</li>
                            <li data-rule="lower_digit"><span class="check-icon">✗</span> Chữ thường và số</li>
                            <li data-rule="special"><span class="check-icon">✗</span> Ký tự đặc biệt (!@$%...)</li>
                            <li data-rule="upper"><span class="check-icon">✗</span> Ít nhất 1 chữ in hoa</li>
                        </ul>
                    </div>

                    <button type="submit" class="btn">Đăng ký</button>

                    <p>Hoặc đăng ký bằng</p>
                    <div class="social-icons">
                        <a href="{{ route('auth.google') }}" aria-label="Đăng ký bằng Google">
                            <i class='bx bxl-google'></i> Google
                        </a>
                    </div>
                </form>
            </div>

            {{-- Toggle panels --}}
            <div class="toggle-box">
                <div class="toggle-panel toggle-left">
                    <h1>Chào mừng bạn!</h1>
                    <p>Bạn chưa có tài khoản?</p>
                    <button class="btn register-btn" type="button">Đăng ký</button>
                </div>
                <div class="toggle-panel toggle-right">
                    <h1>Chào mừng trở lại!</h1>
                    <p>Bạn đã có tài khoản?</p>
                    <button class="btn login-btn" type="button">Đăng nhập</button>
                </div>
            </div>
        </div>{{-- /.container --}}

    </div>{{-- /.auth-popup-wrapper --}}
</div>{{-- /.auth-overlay --}}

{{-- ─── QUÊN MẬT KHẨU ─── --}}
<div class="forgot-modal" id="forgotModal">
    <div class="form-box forgot-form">
        <button class="close-btn" id="closeForgotBtn">&times;</button>
        <form id="forgotForm">
            <h1>Quên mật khẩu</h1>
            <p>Nhập email để nhận liên kết đặt lại mật khẩu.</p>
            <div class="input-box">
                <input type="email" name="forgot_email" placeholder="Email" required>
                <i class='bx bxs-envelope'></i>
            </div>
            <button type="submit" class="btn send-email-btn">GỬI</button>
        </form>
        <div id="forgotSuccessMessage" style="display:none;text-align:center;padding:20px 0;">
            <i class='bx bxs-check-circle' style="font-size:50px;color:#38c172;margin-bottom:10px;display:block;"></i>
            <h2>Hoàn tất!</h2>
            <p>Liên kết đặt lại mật khẩu đã được gửi.</p>
            <button id="forgotCloseSuccess" class="btn" style="width:150px;margin-top:10px;">Đóng</button>
        </div>
    </div>
</div>

<script>
(function () {
    const overlay    = document.getElementById('authOverlay');
    const container  = document.getElementById('authContainer');
    const closeBtn   = document.getElementById('authPopupClose');

    // ── Mở / Đóng popup ──────────────────────────────
    window.openAuthPopup = function (tab) {
        overlay.classList.add('show');
        document.body.style.overflow = 'hidden';
        if (tab === 'register') {
            container.classList.add('active');
        } else {
            container.classList.remove('active');
        }
    };

    window.closeAuthPopup = function () {
        overlay.classList.remove('show');
        document.body.style.overflow = '';
    };

    closeBtn?.addEventListener('click', closeAuthPopup);

    // Đóng khi click ngoài
    overlay?.addEventListener('click', function (e) {
        if (e.target === overlay) closeAuthPopup();
    });

    // Đóng bằng Esc
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closeAuthPopup();
    });

    // ── Toggle login/register ─────────────────────────
    document.querySelector('.register-btn')?.addEventListener('click', () => {
        container.classList.add('active');
    });
    document.querySelector('.login-btn')?.addEventListener('click', () => {
        container.classList.remove('active');
    });

    // ── Toggle hiện/ẩn mật khẩu ──────────────────────
    document.querySelectorAll('.toggle-password').forEach(icon => {
        icon.addEventListener('click', () => {
            const input = icon.parentElement.querySelector('input[type="password"], input[type="text"]');
            if (!input) return;
            input.type = input.type === 'password' ? 'text' : 'password';
            icon.classList.toggle('bx-show');
            icon.classList.toggle('bx-hide');
        });
    });

    // ── Validate mật khẩu đăng ký ────────────────────
    const pwdInput = document.getElementById('regPassword');
    const pwdBox   = document.getElementById('pwdValidation');
    const pwdTitle = document.getElementById('pwdTitle');
    const pwdRules = pwdBox?.querySelectorAll('li') ?? [];

    const rules = {
        length:      p => p.length >= 8 && p.length <= 32,
        lower_digit: p => /[a-z]/.test(p) && /\d/.test(p),
        special:     p => /[!@#$%^&*_]/.test(p),
        upper:       p => /[A-Z]/.test(p),
    };

    function validatePassword(pwd) {
        let ok = true;
        pwdRules.forEach(li => {
            const icon   = li.querySelector('.check-icon');
            const passed = rules[li.dataset.rule]?.(pwd);
            icon.textContent   = passed ? '✓' : '✗';
            li.style.color     = passed ? '#38c172' : '#fff';
            icon.style.color   = passed ? '#38c172' : '#fff';
            if (!passed) ok = false;
        });
        if (ok) {
            pwdBox.classList.add('valid'); pwdBox.classList.remove('error-message');
            pwdTitle.textContent = '✓ Mật khẩu hợp lệ'; pwdTitle.style.color = '#38c172';
        } else {
            pwdBox.classList.remove('valid'); pwdBox.classList.add('error-message');
            pwdTitle.textContent = '❌ Mật khẩu chưa hợp lệ'; pwdTitle.style.color = '#fff';
        }
    }

    pwdInput?.addEventListener('input', () => validatePassword(pwdInput.value));
    validatePassword(pwdInput?.value ?? '');

    // ── Quên mật khẩu ────────────────────────────────
    const forgotModal   = document.getElementById('forgotModal');
    const forgotForm    = document.getElementById('forgotForm');
    const forgotSuccess = document.getElementById('forgotSuccessMessage');

    document.getElementById('forgotLink')?.addEventListener('click', e => {
        e.preventDefault();
        forgotModal.style.display = 'flex';
    });
    document.getElementById('closeForgotBtn')?.addEventListener('click', () => {
        forgotModal.style.display = 'none';
    });
    document.getElementById('forgotCloseSuccess')?.addEventListener('click', () => {
        forgotModal.style.display = 'none';
    });
    forgotForm?.addEventListener('submit', e => {
        e.preventDefault();
        const email = new FormData(forgotForm).get('forgot_email');
        if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            alert('Vui lòng nhập email hợp lệ.'); return;
        }

        const btn = forgotForm.querySelector('.send-email-btn');
        btn.disabled = true;
        btn.textContent = 'Đang gửi...';

        fetch('{{ route("password.forgot") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                    || '{{ csrf_token() }}'
            },
            body: JSON.stringify({ forgot_email: email })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                forgotForm.style.display    = 'none';
                forgotSuccess.style.display = 'block';
                forgotForm.reset();
            } else {
                btn.disabled = false;
                btn.textContent = 'GỬI';
                alert(data.message || 'Có lỗi xảy ra. Vui lòng thử lại.');
            }
        })
        .catch(() => {
            btn.disabled = false;
            btn.textContent = 'GỬI';
            alert('Không thể kết nối. Vui lòng thử lại.');
        });
    });

    // ── Tự động mở nếu có lỗi validation hoặc session ──
    document.addEventListener('DOMContentLoaded', function () {
        @if($errors->has('login') || $errors->has('TenDangNhap') || $errors->has('HoTen') || $errors->has('Email') || $errors->has('MatKhau') || session('error'))
            openAuthPopup('{{ $errors->hasAny(["TenDangNhap","HoTen","Email","MatKhau"]) ? "register" : "login" }}');
        @endif

        @if(session('open_auth'))
            openAuthPopup('{{ session("open_auth") }}');
        @endif
    });
})();
</script>
