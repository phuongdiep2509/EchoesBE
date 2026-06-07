@extends('layouts.app')

@section('title', $article->title ?? 'Tin tức')

@section('content')
<main class="container my-5" style="max-width: 1000px;">
    <nav class="mb-4">
        <a href="{{ url('/') }}">Trang chủ</a> /
        <a href="{{ url('/news') }}">Tin tức</a> /
        <span>{{ $article->title }}</span>
    </nav>

    <article>
        <h1 class="text-danger mb-3">{{ $article->title }}</h1>
        <p class="text-muted">{{ $article->category }} - {{ $article->published_at }}</p>
        @if($article->image)
            <img src="{{ asset($article->image) }}" class="img-fluid rounded shadow mb-4" alt="{{ $article->title }}">
        @endif
        <div>{!! $article->content !!}</div>
    </article>

    @if($related->isNotEmpty())
        <hr class="my-5">
        <h3>Tin liên quan</h3>
        <div class="row">
            @foreach($related as $item)
                <div class="col-md-4">
                    <a href="{{ url('/news/'.$item->id) }}">
                        @if($item->image)
                            <img src="{{ asset($item->image) }}" class="img-fluid rounded" alt="{{ $item->title }}">
                        @endif
                        <h6 class="mt-2">{{ $item->title }}</h6>
                    </a>
                </div>
            @endforeach
        </div>
    @endif
</main>
@endsection
