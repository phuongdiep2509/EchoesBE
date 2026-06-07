<style>
:root {
    --color-text: #000;
    --color-yellow: #f0efeb;
    --color-white: #fff;
    --color-red: #74070d;
    --color-green: #46462a;
    --color-beige: #e1cfac;
    --font: "Cal Sans", sans-serif;
}
* { margin: 0; padding: 0; box-sizing: border-box; font-family: var(--font); }
body { padding-top: 50px; font-family: var(--font); }

.header {
    position: fixed;
    top: 0; left: 0;
    width: 100%;
    z-index: 9999;
    transition: transform 0.35s ease, background 0.3s ease;
    transform: translateY(0);
}
.header.show {
    background: rgba(255,255,255,0.85);
    backdrop-filter: blur(8px);
}
.header.hide { transform: translateY(-100%); }
.header.show .inner-wrap-2 { padding: 6px 50px; }
.header.show .inner-logo img { height: 70px; }

.inner-wrap-1 {
    border-bottom: 1px solid #000;
    width: 100%;
    display: flex;
    justify-content: flex-end;
    padding: 4px 20px;
    font-weight: normal;
    font-size: 18px;
    color: #000;
}
.inner-wrap-1 ul {
    margin: 0 50px;
    display: flex;
    list-style: none;
    align-items: center;
    gap: 34px;
}
.inner-wrap-1 ul li a { color: #000; text-decoration: none; }
.inner-wrap-1 ul li a:hover { color: #74070d; text-decoration: none; }
.inner-wrap-1 ul li img {
    height: 16px; width: auto;
    vertical-align: middle; margin-right: 5px;
}
.header-cart-icon {
    width: 16px;
    height: 16px;
    margin-right: 5px;
    color: #000;
    vertical-align: middle;
}

.inner-wrap-2 {
    padding: 10px 50px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.inner-logo img { height: 100px; display: block; }

.inner-menu ul {
    list-style: none;
    display: flex;
    gap: 100px;
    font-weight: normal;
    font-size: 18px;
    color: #000;
}
.inner-menu ul li { position: relative; }
.inner-menu ul li a { text-decoration: none; color: #000; }
.inner-menu ul li a:hover { color: #74070d; cursor: pointer; }
.inner-menu img { margin-left: 6px; height: 15px; }

.inner-menu ul ul.submenu {
    display: none;
    position: absolute;
    background-color: #fff;
    padding: 15px 0;
    list-style: none;
    margin-top: 5px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    min-width: 150px;
    z-index: 999;
}
.inner-menu ul li:hover > ul.submenu { display: block; }
.inner-menu ul ul.submenu li { padding: 5px 20px; }
.inner-menu ul ul.submenu li a { color: #000; text-decoration: none; display: block; }
.inner-menu ul ul.submenu li a:hover { color: #74070d; }

.search-box { position: relative; }
.search-icon {
    background: none; border: none; border-radius: 50%;
    cursor: pointer; color: #000; padding: 10px;
}
.search-icon:hover { opacity: 0.8; }

.search-container {
    position: absolute;
    top: 100%; right: 0;
    background: white;
    border: 2px solid var(--color-green);
    border-radius: 10px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    width: 400px; max-height: 500px;
    overflow-y: auto;
    z-index: 10000;
    display: none;
}
.search-container.show { display: block; }
.search-input-container { padding: 15px; border-bottom: 1px solid #eee; }
.search-input {
    width: 100%; padding: 12px 15px;
    border: 1px solid var(--color-green);
    border-radius: 25px; font-size: 16px; outline: none;
}
.search-input:focus {
    border-color: var(--color-red);
    box-shadow: 0 0 5px rgba(116,7,13,0.3);
}
.search-results { max-height: 400px; overflow-y: auto; }
.search-category {
    padding: 10px 15px;
    background: var(--color-beige);
    font-weight: bold; color: var(--color-green);
    border-bottom: 1px solid #ddd;
}
.search-item {
    padding: 12px 15px;
    border-bottom: 1px solid #f0f0f0;
    cursor: pointer; transition: background 0.2s;
}
.search-item:hover { background: var(--color-beige); }
.search-item-title { font-weight: bold; color: var(--color-green); margin-bottom: 5px; }
.search-item-info { font-size: 14px; color: #666; }
.search-item-price { color: var(--color-red); font-weight: bold; }
.no-results { padding: 20px; text-align: center; color: #666; }
mark { background-color: #ffeb3b; color: #333; padding: 1px 2px; border-radius: 2px; }

#logoutBtn { color: #74070d; font-weight: 500; }
#logoutBtn:hover { text-decoration: underline; }
.button-dk { font-family: var(--font); border: none; font-weight: normal; font-size: 18px; color: #000; }

.mobile-menu-toggle {
    display: none;
    background: none; border: none;
    font-size: 24px; cursor: pointer; color: #000;
    padding: 8px; border-radius: 4px;
    transition: background-color 0.2s;
}
.mobile-menu-toggle:hover { background-color: rgba(0,0,0,0.1); }
.header-right { display: flex; align-items: center; gap: 15px; }

@media screen and (max-width: 1024px) {
    .inner-wrap-1 { display: none; }
    .inner-wrap-2 { padding: 10px 20px; position: relative; }
    .mobile-menu-toggle { display: block !important; }
    .inner-menu {
        display: none;
        position: absolute; top: 100%; left: 0;
        width: 100%;
        background: white; border: 1px solid #ccc;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        z-index: 9999; padding: 10px 0;
    }
    .inner-menu.show-menu { display: block !important; }
    .inner-menu.show-menu ul { display: block; margin: 0; padding: 0; list-style: none; }
    .inner-menu.show-menu ul li { display: block; width: 100%; border-bottom: 1px solid #eee; }
    .inner-menu.show-menu ul li a { display: block; padding: 12px 20px; color: #000; font-size: 16px; }
    .inner-menu ul { flex-direction: column; width: 100%; gap: 0; }
    .inner-menu ul li { width: 100%; border-bottom: 1px solid #ddd; }
    .inner-menu ul li a { padding: 15px 20px; font-size: 16px; }
    .inner-menu ul li img { display: none; }
    .inner-menu ul ul.submenu {
        position: static; display: block;
        background-color: #f5f5f5; box-shadow: none;
        padding: 0; margin: 0; width: 100%;
    }
    .inner-menu ul ul.submenu li a { padding: 12px 40px; font-size: 14px; color: #666; }
    .inner-logo img { height: 80px; }
    .search-container { width: 350px; right: -20px; }
}
@media screen and (max-width: 768px) {
    .inner-wrap-2 { padding: 8px 15px; }
    .inner-logo img { height: 60px; }
    .search-container { width: 320px; right: -30px; }
    .search-input { font-size: 14px; padding: 10px 12px; }
}
@media screen and (max-width: 480px) {
    .inner-wrap-2 { padding: 5px 10px; }
    .inner-logo img { height: 50px; }
    .search-container { width: 280px; right: -40px; }
}
</style>

<div class="header" id="mainHeader">
    <div id="popup-container" style="display:none;"></div>

    <div class="inner-wrap-1">
        <ul>
            <li>
                <img src="{{ asset('assets/images/index/ticket-icon.png') }}" alt="">
                <a href="{{ url('/my-ticket') }}">VÉ CỦA TÔI</a>
            </li>
            <li>
                <svg class="header-cart-icon" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                     viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M7.3 18.3a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm10 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4ZM3.2 2.5a1 1 0 0 0 0 2h1.4l2.3 11.1A2.4 2.4 0 0 0 9.2 17h8.4a2.4 2.4 0 0 0 2.3-1.8l1.8-7.4A1 1 0 0 0 20.7 6H7L6.5 3.3a1 1 0 0 0-1-.8H3.2Z"/>
                </svg>
                <a href="{{ url('/cart') }}">GIỎ HÀNG</a>
            </li>
            <li>
                <img src="{{ asset('assets/images/index/user-icon.png') }}" alt="">
                <a href="javascript:void(0)" onclick="openModal()">TÀI KHOẢN</a>
                <span id="userBox" style="display:none;">
                    <span id="account-name"></span>
                    |
                    <a href="javascript:void(0)" id="logoutBtn">ĐĂNG XUẤT</a>
                </span>
            </li>
        </ul>
    </div>

    <div class="inner-wrap-2">
        <a href="{{ url('/') }}" class="inner-logo">
            <img src="{{ asset('assets/images/index/logo (no back).png') }}" alt="Echoes Logo">
        </a>

        <nav class="inner-menu" id="mainNav">
            <ul>
                <li>
                    <a href="#" onclick="return false;">GIỚI THIỆU</a>
                    <img src="{{ asset('assets/images/index/dropdown-icon.png') }}" alt="">
                    <ul class="submenu">
                        <li><a href="{{ url('/about') }}">VỀ CHÚNG TÔI</a></li>
                        <li><a href="{{ url('/rules') }}">QUY ĐỊNH</a></li>
                    </ul>
                </li>
                <li><a href="{{ url('/music') }}">NHẠC SỐNG</a></li>
                <li><a href="{{ url('/concert') }}">CONCERT ÂM NHẠC</a></li>
                <li><a href="{{ url('/merchandise') }}">MERCHANDISE</a></li>
                <li><a href="{{ url('/news') }}">TIN TỨC | KHUYẾN MÃI</a></li>
            </ul>
        </nav>

        <div class="header-right">
            <button class="mobile-menu-toggle" onclick="toggleMobileMenu()" aria-label="Menu">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                    stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <line x1="3" y1="6" x2="21" y2="6"/>
                    <line x1="3" y1="12" x2="21" y2="12"/>
                    <line x1="3" y1="18" x2="21" y2="18"/>
                </svg>
            </button>

            <div class="search-box">
                <button class="search-icon" aria-label="Tìm kiếm" onclick="toggleSearch()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="11" cy="11" r="8"/>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                    </svg>
                </button>
                <div class="search-container" id="searchContainer">
                    <div class="search-input-container">
                        <input type="text" class="search-input" id="searchInput"
                            placeholder="Tìm kiếm sự kiện, concert, merchandise..."
                            onkeyup="performSearch(this.value)"
                            onkeydown="handleSearchKeydown(event)">
                    </div>
                    <div class="search-results" id="searchResults">
                        <div class="no-results">Nhập từ khóa để tìm kiếm...</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleSearch() {
    const container = document.getElementById('searchContainer');
    if (container) {
        container.classList.toggle('show');
        if (container.classList.contains('show')) {
            document.getElementById('searchInput')?.focus();
        }
    }
}

function toggleMobileMenu() {
    document.getElementById('mainNav')?.classList.toggle('show-menu');
}

// Hide/show header on scroll
(function() {
    let lastY = 0;
    const header = document.getElementById('mainHeader');
    window.addEventListener('scroll', function() {
        const y = window.scrollY;
        if (!header) return;
        if (y > 80) {
            header.classList.add('show');
            header.classList.toggle('hide', y > lastY && y > 200);
        } else {
            header.classList.remove('show', 'hide');
        }
        lastY = y;
    });

    // Close search on outside click
    document.addEventListener('click', function(e) {
        const box = document.querySelector('.search-box');
        if (box && !box.contains(e.target)) {
            document.getElementById('searchContainer')?.classList.remove('show');
        }
    });
})();

function openModal() {
    const overlay = document.getElementById('modal-overlay');
    if (overlay) overlay.style.display = 'flex';
}

function performSearch(q) { /* hook into real search if needed */ }
function handleSearchKeydown(e) {
    if (e.key === 'Escape') document.getElementById('searchContainer')?.classList.remove('show');
}
</script>
