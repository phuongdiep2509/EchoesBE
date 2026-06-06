@props(['title', 'description' => '', 'image', 'link'])

<div class="news-item-horizontal" onclick="location.href='{{ $link }}'">

    <div class="news-image-wrapper">
        <img src="{{ asset($image) }}" alt="{{ $title }}">
    </div>

    <div class="news-content-wrapper">
        <h3 class="news-title-big">{{ $title }}</h3>
        @if($description)
            <p class="news-desc-big">{{ $description }}</p>
        @endif
    </div>

</div>
