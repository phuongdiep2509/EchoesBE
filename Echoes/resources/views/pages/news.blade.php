@extends('layouts.app')

@section('title', 'Tin tức & Khuyến mãi | Echoes')

@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/News.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/NewsRe.css') }}">
@endsection

@section('content')

<div class="container" style="padding-top: 40px; padding-bottom: 60px">

    {{-- BREADCRUMB --}}
    <nav style="margin-bottom: 24px; font-size: 0.875rem; color: #888">
        <a href="{{ url('/') }}" style="color: inherit">TRANG CHỦ</a>
        <span style="margin: 0 6px">/</span>
        <span style="color: var(--color-green, #46462a); font-weight: 600">TIN TỨC &amp; KHUYẾN MÃI</span>
    </nav>

    <div style="display: flex; gap: 40px; align-items: flex-start; flex-wrap: wrap">

        {{-- MAIN: danh sách tin tức --}}
        <section style="flex: 1; min-width: 280px">

            <h2 style="font-size: 1.25rem; font-weight: 700; border-bottom: 2px solid var(--color-green, #46462a); padding-bottom: 8px; margin-bottom: 20px">
                TIN TỨC
            </h2>

            <div class="news-list">
                @forelse($articles as $a)
                    <x-news-card
                        :title="$a->title"
                        :image="$a->image ?? 'https://images.unsplash.com/photo-1497493292307-31c376b6e479?auto=format&fit=crop&w=1200&q=80'"
                        :link="url('/news/' . $a->id)"
                    />
                @empty
                    <p style="color: #999; padding: 40px 0; text-align: center">
                        Chưa có bài viết nào.
                    </p>
                @endforelse
            </div>

        </section>

        {{-- SIDEBAR: tin nổi bật --}}
        <aside style="width: 280px; flex-shrink: 0">

            <h2 style="font-size: 1.1rem; font-weight: 700; color: var(--color-red, #74070d); border-bottom: 2px solid var(--color-red, #74070d); padding-bottom: 8px; margin-bottom: 20px">
                TIN NỔI BẬT
            </h2>

            <div style="display: flex; flex-direction: column; gap: 16px">
                @forelse($featured as $f)
                    <a href="{{ url('/news/' . $f->id) }}"
                       style="display: flex; gap: 12px; align-items: center; color: inherit">
                        @php $fImage = $f->image ?? 'https://images.unsplash.com/photo-1497493292307-31c376b6e479?auto=format&fit=crop&w=1200&q=80'; @endphp
                        <img src="{{ preg_match('/^https?:\/\//i', $fImage) ? $fImage : asset($fImage) }}"
                             alt="{{ $f->title }}"
                             style="width: 80px; height: 60px; object-fit: cover; border-radius: 6px; flex-shrink: 0">
                        <div>
                            <p style="font-size: 0.8125rem; font-weight: 600; line-height: 1.4; color: var(--color-green, #46462a); margin: 0">
                                {{ $f->title }}
                            </p>
                            <p style="font-size: 0.75rem; color: #999; margin: 4px 0 0">
                                {{ \Carbon\Carbon::parse($f->published_at)->format('d/m/Y') }}
                            </p>
                        </div>
                    </a>
                @empty
                    <p style="color: #999; font-size: 0.875rem">Chưa có bài nổi bật.</p>
                @endforelse
            </div>

        </aside>

    </div>

</div>

@endsection
