@props(['name', 'price', 'image', 'stock', 'link'])

<div class="music-item">

    <div class="card-top">
        <div class="poster-container">

            <a href="{{ $link }}">
                <img src="{{ asset($image) }}" alt="{{ $name }}" class="poster-img">
                <button class="btn-buy">XEM NGAY</button>
            </a>

        </div>
    </div>

    <div class="card-bottom">
        <h3 class="event-name">{{ $name }}</h3>

        <p class="price">
            {{ number_format($price, 0, ',', '.') }}₫
        </p>

        <p class="date" style="color: {{ $stock > 0 ? 'var(--color-green)' : '#cc0000' }}">
            {{ $stock > 0 ? 'Còn hàng (' . $stock . ')' : 'Hết hàng' }}
        </p>
    </div>

</div>
