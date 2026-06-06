@extends('layouts.app')

@section('title', 'Giới thiệu | Echoes')

@section('styles')
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:wght@300;500;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/about.css') }}">
@endsection

@section('content')

{{-- HERO --}}
<section class="hero hero-poster">
    <div class="hero-content">
        <h1>CHÚNG MÌNH LÀ AI?</h1>
        <p>
            Echoes là nền tảng kết nối âm nhạc sống và cảm xúc chân thật dành cho cộng đồng yêu nghệ thuật.
            Với sứ mệnh mang những buổi diễn chất lượng đến gần hơn với khán giả,
            chúng tôi tin rằng mỗi giai điệu đều phải để lại dư âm sâu sắc.
        </p>
    </div>
</section>

{{-- VISION --}}
<section class="vision-section">
    <div class="vision-grid">

        <div class="vision-item">
            <img src="{{ asset('assets/images/about/eye.png') }}" alt="Tầm nhìn">
            <h3>TẦM NHÌN</h3>
            <p>Echoes hướng đến việc trở thành nền tảng kết nối âm nhạc sống.</p>
        </div>

        <div class="vision-item">
            <img src="{{ asset('assets/images/about/flower.png') }}" alt="Sứ mệnh">
            <h3>SỨ MỆNH</h3>
            <p>Mang những buổi biểu diễn chất lượng đến gần hơn với khán giả.</p>
        </div>

        <div class="vision-item">
            <img src="{{ asset('assets/images/about/butterfly.png') }}" alt="Giá trị cốt lõi">
            <h3>GIÁ TRỊ CỐT LÕI</h3>
            <p>Nghệ thuật – Cảm xúc – Kết nối – Trải nghiệm chân thật.</p>
        </div>

    </div>
</section>

@endsection
