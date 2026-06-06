@props(['title', 'location', 'price', 'date', 'image', 'link'])

<div class="music-item">

    <div class="card-top">
        <div class="poster-container">

            <a href="{{ $link }}">
                <img src="{{ asset($image) }}" class="poster-img" alt="{{ $title }}">
                <button class="btn-buy">MUA NGAY</button>
            </a>

        </div>
    </div>

    <div class="card-bottom">
        <h3 class="event-name">{{ $title }}</h3>
        <h4 class="event-address">{{ $location }}</h4>
        <p class="price">{{ $price }}</p>
        <p class="date">
            <img src="{{ asset('assets/images/index/calendar-icon.png') }}" alt="">
            {{ $date }}
        </p>
    </div>

</div>
