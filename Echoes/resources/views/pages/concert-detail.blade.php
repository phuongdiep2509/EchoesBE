@extends('layouts.app')

@section('content')

<main class="container my-5">

    <nav class="breadcrumb-custom mb-4">
        <a href="{{ url('/') }}">TRANG CHỦ</a> /
        <a href="{{ url('/concert') }}">CONCERT</a> /
        <span>{{ $concert->title }}</span>
    </nav>

    <div class="row">

        <!-- LEFT -->
        <div class="col-lg-8">

            <!-- POSTER -->
            <div class="mb-4">
                <img src="{{ asset($event->image) }}"
                     class="img-fluid rounded shadow"
                     style="width:100%;height:400px;object-fit:cover;">
            </div>

            <!-- INFO -->
            <div class="card shadow-sm mb-4">

                <div class="card-header text-white bg-danger">
                    <h3>✶ THÔNG TIN SỰ KIỆN</h3>
                </div>

                <div class="card-body">

                    <h1 class="fw-bold text-danger mb-4">
                        {{ $event->title }}
                    </h1>

                    <div class="row">

                        <div class="col-md-6 mb-3">
                            <small>THỜI GIAN</small>
                            <div>{{ $event->event_date }}</div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <small>ĐỊA ĐIỂM</small>
                            <div>{{ $event->location }}</div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <small>THỜI LƯỢNG</small>
                            <div>{{ $event->duration ?? '---' }}</div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <small>THỂ LOẠI</small>
                            <div>{{ $event->genre ?? 'Concert' }}</div>
                        </div>

                    </div>

                </div>
            </div>

            <!-- DESCRIPTION -->
            <div class="card shadow-sm mb-4">

                <div class="card-header text-white bg-success">
                    <h3>✶ GIỚI THIỆU</h3>
                </div>

                <div class="card-body">

                    <p>{!! $event->description !!}</p>

                    <h5 class="mt-4 text-success">ĐIỂM NỔI BẬT</h5>

                    <ul>
                        @foreach(explode('|', $event->highlights ?? '') as $item)
                            <li>{{ $item }}</li>
                        @endforeach
                    </ul>

                    @if($event->terms)
                    <div class="mt-4">
                        <h5 class="text-danger">ĐIỀU KHOẢN</h5>
                        <div class="p-3 bg-light">
                            {!! $event->terms !!}
                        </div>
                    </div>
                    @endif

                </div>
            </div>

        </div>

        <!-- RIGHT BOOKING -->
        <div class="col-lg-4">

            <div class="card shadow-sm sticky-top" style="top:100px;">

                <div class="card-header text-white bg-success">
                    <h3>THÔNG TIN VÉ</h3>
                </div>

                <div class="card-body">

                    <h4 class="text-danger">
                        {{ $event->price }}
                    </h4>

                    <button class="btn btn-danger w-100 mt-3">
                        🎫 ĐẶT NGAY
                    </button>

                    <div class="mt-4 p-3 bg-light small">

                        <strong>LƯU Ý:</strong><br>
                        • Vé không hoàn trả<br>
                        • Đến trước 30 phút<br>
                        • Không mang đồ cấm

                    </div>

                </div>

            </div>

        </div>

    </div>

</main>

<!-- RELATED -->
<section class="py-5 bg-light">

    <div class="container">

        <h2 class="text-center text-danger mb-5">
            🎵 BẠN CÓ THỂ THÍCH
        </h2>

        <div class="row">

            @foreach($related as $item)
                <div class="col-md-3">
                    <a href="{{ url('event/'.$item->slug) }}">
                        <img src="{{ asset($item->image) }}"
                             class="img-fluid rounded">
                        <h6 class="mt-2">{{ $item->title }}</h6>
                    </a>
                </div>
            @endforeach

        </div>

    </div>

</section>

@endsection