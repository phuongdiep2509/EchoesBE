@extends('layouts.app')

@section('title', 'Echoes - Trang chủ')

@section('content')

{{-- ===== CAROUSEL ===== --}}
<div class="carousel">
    <div class="carousel-list">

        <div class="carousel-image">
            <a href="{{ url('/concert/YConcert') }}">
                <img src="{{ asset('assets/images/index/main_banner_1.png') }}" alt="Y Concert Banner">
            </a>
        </div>

        <div class="carousel-image">
            <a href="{{ url('/concert/nhung-thanh-pho-mo-mang') }}">
                <img src="{{ asset('assets/images/index/main_banner_2.png') }}" alt="NTPMM Banner">
            </a>
        </div>

        <div class="carousel-image">
            <a href="{{ url('/concert/anh-trai-say-hi-2025') }}">
                <img src="{{ asset('assets/images/index/main_banner_3.png') }}" alt="ATSH Banner">
            </a>
        </div>

    </div>

    <div class="carousel-button-prev">&#8249;</div>
    <div class="carousel-button-next">&#8250;</div>
    <div class="carousel-counter">1 / 3</div>
</div>

{{-- ===== SECTION 1: SỰ KIỆN HOT ===== --}}
<div class="section-1">
    <div class="section-content">
        <h2 class="inner-title">SỰ KIỆN HOT THÁNG 12</h2>

        <div class="event-list">
            <div class="event-wrapper">
                <img src="{{ asset('assets/images/concert/hot1.png') }}" alt="Y Concert">
                <div class="event-content">
                    <button class="event-btn" onclick="location.href='{{ url('/concert/YConcert') }}'">MUA NGAY</button>
                </div>
            </div>
            <div class="event-wrapper">
                <img src="{{ asset('assets/images/concert/hot2.png') }}" alt="Anh Trai Say Hi">
                <div class="event-content">
                    <button class="event-btn" onclick="location.href='{{ url('/concert/anh-trai-say-hi-2025') }}'">MUA NGAY</button>
                </div>
            </div>
            <div class="event-wrapper">
                <img src="{{ asset('assets/images/music/lc16.1.jpg') }}" alt="Bốn Cánh Chim Trời">
                <div class="event-content">
                    <button class="event-btn" onclick="location.href='{{ url('/music/concert-bon-canh-chim-troi') }}'">MUA NGAY</button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ===== NHẠC SỐNG ===== --}}
<div class="container">

    <div class="section-header">
        <h2 class="section-title">NHẠC SỐNG</h2>
        <a href="{{ url('/music') }}" class="see-more">XEM THÊM ›</a>
    </div>

    <div class="music-grid">

        <x-music-card
            title="BỐN CÁNH CHIM TRỜI"
            location="Nhà hát Lớn Hà Nội"
            price="Từ 500.000đ - 800.000đ"
            date="27/12/2025 - 20:00"
            image="assets/images/music/lc16.1.jpg"
            link="{{ url('/music/concert-bon-canh-chim-troi') }}"
        />

        <x-music-card
            title="A TALE OF TWO CHRISTMAS"
            location="Rock Club, TP.HCM"
            price="Từ 500.000đ - 800.000đ"
            date="29/12/2025 - 21:30"
            image="assets/images/music/lc7.1.jpg"
            link="{{ url('/music/a-tale-of-two-christmas') }}"
        />

        <x-music-card
            title="WHEN I REMEMBER THIS LIFE"
            location="Nhà hát Tuổi trẻ, TP.HCM"
            price="Từ 500.000đ - 800.000đ"
            date="25/12/2025 - 20:30"
            image="assets/images/music/lc8.1.jpg"
            link="{{ url('/music/when-i-remember-this-life') }}"
        />

        <x-music-card
            title="THE ROSE"
            location="Sân vận động Đà Lạt"
            price="Từ 500.000đ - 800.000đ"
            date="17/01/2026 - 20:00"
            image="assets/images/music/lc4.2.webp"
            link="{{ url('/music/concert-the-rose') }}"
        />

    </div>

    {{-- ===== CONCERT ÂM NHẠC ===== --}}
    <div class="section-header">
        <h2 class="section-title">CONCERT ÂM NHẠC</h2>
        <a href="{{ url('/concert') }}" class="see-more">XEM THÊM ›</a>
    </div>

    <div class="music-grid">

        <x-concert-card
            title="Y CONCERT 2025"
            location="Vinhomes Ocean Park 3"
            price="Từ 1.000.000đ - 5.000.000đ"
            date="20/12/2026 - 14:00"
            image="assets/images/concert/hot1.png"
            link="{{ url('/concert/YConcert') }}"   
        />

        <x-concert-card
            title="Những Thành Phố Mơ Màng Year End 2025"
            location="Công Viên Yên Sở, Hà Nội"
            price="Từ 750.000đ - 4.000.000đ"
            date="21/12/2025 - 15:30"
            image="assets/images/concert/hot2.png"
            link="{{ url('/concert/nhung-thanh-pho-mo-mang') }}"
        />

        <x-concert-card
            title="ANH TRAI SAY HI 2025 CONCERT"
            location="Khu đô thị Vạn Phúc"
            price="Từ 1.000.000đ - 10.000.000đ"
            date="27/12/2025 - 12:00"
            image="assets/images/concert/hot3.png"
            link="{{ url('/concert/anh-trai-say-hi-2025') }}"
        />

        <x-concert-card
            title="Ai Cũng Giấu Trong Lòng Tảng Băng"
            location="Nhà Hát Quân Đội Phía Nam"
            price="Từ 600.000đ - 2.800.000đ"
            date="10/01/2025 - 19:00"
            image="assets/images/concert/hot4.png"
            link="{{ url('/concert/mr-siro-concert') }}"
        />

    </div>

</div>

{{-- ===== SECTION 2: STATS ===== --}}
<div class="section-2">
    <div class="stats-section container">
        <h2 class="stats-title">SỐ LIỆU TRONG NĂM QUA</h2>

        <div class="stats-container">

            {{-- Big box --}}
            <div class="stats-box big-box">
                <img src="{{ asset('assets/images/index/cart.png') }}" alt="cart" class="big-cart-img">
                <p class="big-number-text">23.5K+</p>
                <p class="label">NGƯỜI LỰA CHỌN</p>
            </div>

            {{-- Small boxes --}}
            <div class="stats-column">
                <div class="stats-box small-box">
                    <div class="number-wrapper">
                        <img src="{{ asset('assets/images/index/followers.png') }}" alt="users" class="number">
                        <p class="number">2.5K+</p>
                    </div>
                    <p class="label">NGƯỜI DÙNG MỚI</p>
                </div>
                <div class="stats-box small-box">
                    <div class="number-wrapper">
                        <img src="{{ asset('assets/images/index/partners.png') }}" alt="partners" class="number">
                        <p class="number">100+</p>
                    </div>
                    <p class="label">ĐỐI TÁC ĐỒNG HÀNH</p>
                </div>
            </div>

            {{-- Follow & Contact --}}
            <div class="follow-box">
                <h3 class="follow-title">FOLLOW US</h3>
                <ul class="social-list">
                    <li>
                        <a href="https://www.facebook.com/albumechoes" target="_blank" rel="noopener">
                            <img src="{{ asset('assets/images/index/facebook_icon.png') }}" class="social-icon" alt="Facebook">
                            ECHOES
                        </a>
                    </li>
                    <li>
                        <a href="https://www.instagram.com/echoesalbum" target="_blank" rel="noopener">
                            <img src="{{ asset('assets/images/index/insta_icon.png') }}" class="social-icon" alt="Instagram">
                            echoes
                        </a>
                    </li>
                    <li>
                        <a href="https://www.facebook.com/albumechoes" target="_blank" rel="noopener">
                            <img src="{{ asset('assets/images/index/tiktok_icon.png') }}" class="social-icon" alt="TikTok">
                            echoes.m
                        </a>
                    </li>
                </ul>

                <h3 class="contact-title">CONTACT</h3>
                <div class="contact-email">echoes@gmail.com</div>
            </div>

        </div>
    </div>
</div>

{{-- ===== TIN TỨC | KHUYẾN MÃI ===== --}}
<div class="container">

    <div class="section-header">
        <h2 class="section-title">TIN TỨC | KHUYẾN MÃI</h2>
        <a href="{{ url('/news') }}" class="see-more">XEM THÊM ›</a>
    </div>

    <div class="news-list">

        <x-news-card
            title='CHUỖI CONCERT "ANH TRAI VƯỢT NGÀN CHÔNG GAI" CHÍNH THỨC KHÉP LẠI SAU 8 ĐÊM DIỄN'
            description="Tối 7/9, chuỗi concert "Anh trai vượt ngàn chông gai" đã chính thức khép lại bằng đêm diễn thứ 8 tổ chức tại The Global City, (Thủ Đức, Thành phố Hồ Chí Minh). 33 "anh tài" đã cháy hết mình cùng hàng chục nghìn khán giả."
            image="assets/images/news/atvncg.png"
            link="{{ url('/news/atvncg') }}"
        />

        <x-news-card
            title='HỒNG NHUNG, CHILLIES HÒA GIỌNG CÙNG KHÁN GIẢ Ở "TỰ HÀO BẢN SẮC VIỆT"'
            description="Đêm nhạc "Tự Hào Bản Sắc Việt" diễn ra trong bầu không khí tự hào, sôi động. Hồng Nhung, Chillies cùng hàng chục nghìn khán giả ca vang trên quảng trường Đông Kinh Nghĩa Thục."
            image="assets/images/news/THBSV.png"
            link="{{ url('/news/tu-hao-ban-sac-viet') }}"
        />

        <x-news-card
            title='WATERBOMB ĐÃ KẾT THÚC NHƯNG DƯ ÂM RỰC RỠ VẪN "NÍU CHÂN" KHIẾN AI CŨNG MUỐN QUAY LẠI MÙA TỚI'
            description="Một trong những lễ hội mùa hè được mong chờ nhất châu Á đã chính thức đổ bộ Việt Nam, biến Khu đô thị Vạn Phúc City thành tâm điểm giải trí suốt hai ngày liên tiếp."
            image="assets/images/news/wtb3.png"
            link="{{ url('/news/waterbomb-2025') }}"
        />

    </div>

</div>

{{-- ===== AUTH MODAL ===== --}}
<div class="modal-overlay" id="modal-overlay" style="display:none;">
    <div class="modal-container">
        <span class="close-modal" onclick="document.getElementById('modal-overlay').style.display='none'">&times;</span>
        <iframe id="authIframe" src="{{ url('/auth/login') }}" frameborder="0"></iframe>
    </div>
</div>

@endsection
