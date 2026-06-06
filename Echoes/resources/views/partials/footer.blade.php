<style>
/* ===== FOOTER STYLES ===== */
.footer {
    font-family: "Cal Sans", sans-serif;
}

/* --- Footer Top --- */
.footer .footer-top {
    margin-top: 50px;
    display: flex !important;
    justify-content: space-evenly;
    background-color: #525233;
    padding: 30px 10px;
    flex-wrap: wrap;
    gap: 0;
}

.footer .footer-top .about-us {
    margin-right: 20px;
    width: 300px;
}

.footer .footer-top .about-us h1 {
    margin-top: 20px;
    font-size: 20px;
    color: white !important;
    margin-bottom: 10px;
    font-family: "Cal Sans", sans-serif;
    font-weight: normal;
}

.footer .footer-top .about-us .list-box {
    background-color: #e1cfac;
    height: 10px;
    border-radius: 15px;
    margin-bottom: 20px;
    width: 100%;
}

.footer .footer-top .about-us ul {
    list-style: none !important;
    list-style-type: none !important;
    padding: 0 !important;
    margin: 0 !important;
}

.footer .footer-top .about-us ul li {
    margin-bottom: 10px;
    list-style: none !important;
}

.footer .footer-top .about-us ul li a {
    transition: 0.3s;
    color: white !important;
    text-decoration: none !important;
    font-size: 16px;
    display: inline-block;
}

.footer .footer-top .about-us ul li a:hover {
    color: white !important;
    padding-left: 5px;
}

.footer .footer-top .about-us ul li p {
    color: white !important;
    margin-bottom: 0;
    font-size: 16px;
}

/* Social icons */
.footer .footer-top .about-us .social {
    display: flex !important;
    align-items: center;
    list-style: none !important;
    padding: 0 !important;
    margin-top: 15px !important;
    flex-wrap: wrap;
    gap: 0;
}

.footer .footer-top .about-us .social li {
    margin-right: 10px;
    margin-bottom: 0 !important;
    list-style: none !important;
}

.footer .footer-top .about-us .social li .inner-image {
    height: 40px;
    width: 40px;
    display: block;
}

.footer .footer-top .about-us .social li .inner-image img {
    height: 100%;
    width: 100%;
    display: block;
}

/* --- Footer Bottom --- */
.footer-bottom {
    font-size: 14px;
    line-height: 1.5;
    background-color: #272719;
    display: flex !important;
    align-items: center;
    padding: 10px 110px;
    gap: 0;
}

.footer-bottom .inner-logo {
    height: 100px;
    width: auto;
    flex-shrink: 0;
}

.footer-bottom .inner-logo img {
    height: 90px;
    width: 90px;
    margin-right: 20px;
    padding-top: 10px;
    display: block;
}

.footer-bottom .inner-content ul {
    list-style: none !important;
    padding: 0 !important;
    margin: 0 !important;
}

.footer-bottom .inner-content ul li {
    list-style: none !important;
    margin-bottom: 4px;
}

.footer-bottom .inner-content ul li p {
    color: white !important;
    margin: 0;
    font-size: 14px;
}

/* --- Responsive --- */
@media screen and (max-width: 1024px) {
    .footer .footer-top {
        flex-direction: column !important;
        align-items: center;
        gap: 30px;
        padding: 30px 20px;
    }
    .footer .footer-top .about-us {
        margin-right: 0;
        width: 100%;
        max-width: 400px;
        text-align: center;
    }
    .footer .footer-top .about-us ul li {
        text-align: center;
    }
    .footer .footer-top .about-us .social {
        justify-content: center;
    }
    .footer-bottom {
        flex-direction: column !important;
        align-items: center;
        text-align: center;
        padding: 20px;
        gap: 20px;
    }
    .footer-bottom .inner-logo img {
        margin-right: 0;
    }
}

@media screen and (max-width: 768px) {
    .footer .footer-top { padding: 25px 15px; gap: 25px; }
    .footer .footer-top .about-us { max-width: 350px; }
    .footer .footer-top .about-us h1 { font-size: 18px; margin-top: 15px; }
    .footer .footer-top .about-us ul li a,
    .footer .footer-top .about-us ul li p { font-size: 14px; }
    .footer-bottom { padding: 15px; }
    .footer-bottom .inner-logo img { height: 55px; width: 55px; }
    .footer-bottom .inner-content ul li p { font-size: 13px; }
    .footer .footer-top .about-us .social li .inner-image { height: 32px; width: 32px; }
}

@media screen and (max-width: 480px) {
    .footer .footer-top { padding: 20px 10px; gap: 20px; }
    .footer .footer-top .about-us { max-width: 300px; }
    .footer .footer-top .about-us h1 { font-size: 16px; margin-top: 10px; }
    .footer .footer-top .about-us ul li a,
    .footer .footer-top .about-us ul li p { font-size: 13px; }
    .footer-bottom { padding: 10px; }
    .footer-bottom .inner-logo img { height: 45px; width: 45px; }
    .footer-bottom .inner-content ul li p { font-size: 12px; line-height: 1.5; }
    .footer .footer-top .about-us .social li .inner-image { height: 28px; width: 28px; }
    .footer .footer-top .about-us .social li { margin-right: 8px; }
}
</style>

<div class="footer">

    <div class="footer-top">

        <div class="about-us">
            <h1>THÔNG TIN</h1>
            <div class="list-box"></div>
            <ul>
                <li><a href="{{ url('/about') }}">Về chúng tôi</a></li>
                <li><a href="{{ url('/news') }}">Tin tức &amp; Khuyến mãi</a></li>
            </ul>
        </div>

        <div class="about-us">
            <h1>KHÁM PHÁ</h1>
            <div class="list-box"></div>
            <ul>
                <li><a href="{{ url('/music') }}">Nhạc sống</a></li>
                <li><a href="{{ url('/concert') }}">Concert âm nhạc</a></li>
                <li><a href="{{ url('/merchandise') }}">Merchandise</a></li>
            </ul>
        </div>

        <div class="about-us">
            <h1>QUY ĐỊNH &amp; ĐIỀU KHOẢN</h1>
            <div class="list-box"></div>
            <ul>
                <li><a href="#">Điều khoản</a></li>
                <li><a href="#">Quy định thành viên</a></li>
                <li><a href="#">Quy định và chính sách chung</a></li>
                <li><a href="#">Chính sách bảo vệ thông tin</a></li>
            </ul>
        </div>

        <div class="about-us">
            <h1>CHĂM SÓC KHÁCH HÀNG</h1>
            <div class="list-box"></div>
            <ul>
                <li><p><b>Hotline:</b> 19001234</p></li>
                <li><p><b>Email:</b> echoes@gmail.com</p></li>
            </ul>
            <ul class="social">
                <li>
                    <a href="https://www.facebook.com/albumechoes" target="_blank" rel="noopener">
                        <div class="inner-image">
                            <img src="{{ asset('assets/images/index/facebook_icon.png') }}" alt="Facebook">
                        </div>
                    </a>
                </li>
                <li>
                    <a href="https://www.instagram.com/echoesalbum" target="_blank" rel="noopener">
                        <div class="inner-image">
                            <img src="{{ asset('assets/images/index/insta_icon.png') }}" alt="Instagram">
                        </div>
                    </a>
                </li>
                <li>
                    <a href="https://www.facebook.com/albumechoes" target="_blank" rel="noopener">
                        <div class="inner-image">
                            <img src="{{ asset('assets/images/index/tiktok_icon.png') }}" alt="TikTok">
                        </div>
                    </a>
                </li>
            </ul>
        </div>

    </div>

    <div class="footer-bottom">
        <div class="inner-logo">
            <img src="{{ asset('assets/images/index/logo-footer.png') }}" alt="Echoes Logo">
        </div>
        <div class="inner-content">
            <ul>
                <li><p><b>Công ty Sự kiện âm nhạc Echoes Việt Nam</b></p></li>
                <li><p><b>Giấy CNĐKDN:</b> Giấy phép kinh doanh số: 0104597158. Đăng ký lần đầu ngày 22 tháng 12 năm 2025</p></li>
                <li><p><b>Địa chỉ:</b> Số 12 Chùa Bộc, Đống Đa, Hà Nội</p></li>
                <li><p><b>Hotline:</b> 19001234</p></li>
            </ul>
        </div>
    </div>

</div>
