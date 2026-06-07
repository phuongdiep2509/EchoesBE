@extends('layouts.app')

@section('title', ($article->title ?? 'Tin tức') . ' | Echoes')

@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/News.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/NewsRe.css') }}">
@endsection

@section('content')

{{-- Breadcrumb --}}
<div style="margin-top:100px;background:var(--color-green,#46462a)">
    <div style="max-width:1200px;margin:0 auto;padding:12px 20px;
                font-size:0.8rem;letter-spacing:1px;text-transform:uppercase">
        <a href="{{ url('/') }}"
           style="color:rgba(255,255,255,0.7);text-decoration:none">TRANG CHỦ</a>
        <span style="color:rgba(255,255,255,0.4);margin:0 8px">/</span>
        <a href="{{ url('/news') }}"
           style="color:rgba(255,255,255,0.7);text-decoration:none">TIN TỨC</a>
        <span style="color:rgba(255,255,255,0.4);margin:0 8px">/</span>
        <span style="color:white;font-weight:600">
            {{ \Illuminate\Support\Str::limit($article->title ?? '', 60) }}
        </span>
    </div>
</div>

<div style="max-width:1200px;margin:0 auto;padding:48px 20px 80px;
            display:flex;gap:48px;align-items:flex-start;flex-wrap:wrap">

    {{-- ─── MAIN ARTICLE ─────────────────────────── --}}
    <article style="flex:1;min-width:0">

        {{-- Category + Date --}}
        <div style="display:flex;align-items:center;gap:12px;margin-bottom:16px;flex-wrap:wrap">
            @if(!empty($article->category))
                <span style="background:var(--color-red,#74070d);color:white;
                             font-size:0.7rem;font-weight:700;letter-spacing:1.5px;
                             text-transform:uppercase;padding:4px 12px;border-radius:999px">
                    {{ $article->category }}
                </span>
            @endif
            @if(!empty($article->published_at))
                <span style="font-size:0.8125rem;color:#999;display:flex;align-items:center;gap:5px">
                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none"
                         stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/>
                        <line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
                    </svg>
                    {{ \Carbon\Carbon::parse($article->published_at)->format('d/m/Y') }}
                </span>
            @endif
        </div>

        {{-- Title --}}
        <h1 id="news-title"
            style="font-size:2.25rem;font-weight:800;color:#1a1a1a;
                   line-height:1.25;margin-bottom:28px">
            {{ $article->title }}
        </h1>

        {{-- Featured image --}}
        @if(!empty($article->image))
            <figure style="margin:0 0 36px">
                <img src="{{ asset($article->image) }}"
                     alt="{{ $article->title }}"
                     style="width:100%;max-height:480px;object-fit:cover;
                            border-radius:14px;
                            box-shadow:0 8px 32px rgba(0,0,0,0.12)">
            </figure>
        @endif

        {{-- Content --}}
        <div class="news-detail-body"
             style="font-size:1rem;color:#333;line-height:1.8">
            {!! $article->content !!}
        </div>

        {{-- Back button --}}
        <div style="margin-top:48px;padding-top:28px;
                    border-top:1px solid rgba(0,0,0,0.1)">
            <a href="{{ url('/news') }}" class="btn-back">
                ← Quay lại tin tức
            </a>
        </div>

    </article>

    {{-- ─── SIDEBAR ────────────────────────────────── --}}
    <aside style="width:300px;flex-shrink:0;position:sticky;
                  top:calc(100px + 24px)">

        {{-- Related articles --}}
        @if(isset($related) && $related->count() > 0)
            <div style="background:#fff;border-radius:16px;padding:24px;
                        box-shadow:0 4px 20px rgba(0,0,0,0.06);margin-bottom:28px">

                <h3 style="font-size:1rem;font-weight:700;
                           color:var(--color-red,#74070d);
                           border-bottom:2px solid var(--color-red,#74070d);
                           padding-bottom:10px;margin-bottom:18px;
                           letter-spacing:0.5px">
                    TIN LIÊN QUAN
                </h3>

                <div style="display:flex;flex-direction:column;gap:16px">
                    @foreach($related as $r)
                        <a href="{{ url('/news/' . $r->id) }}"
                           style="display:flex;gap:12px;align-items:flex-start;
                                  color:inherit;text-decoration:none;
                                  padding-bottom:16px;
                                  border-bottom:1px solid rgba(0,0,0,0.06)"
                           onmouseover="this.querySelector('p').style.color='var(--color-red,#74070d)'"
                           onmouseout="this.querySelector('p').style.color='var(--color-green,#46462a)'">

                            {{-- Thumbnail --}}
                            @if(!empty($r->image))
                                <img src="{{ asset($r->image) }}"
                                     alt="{{ $r->title }}"
                                     style="width:72px;height:54px;object-fit:cover;
                                            border-radius:8px;flex-shrink:0">
                            @else
                                <div style="width:72px;height:54px;border-radius:8px;
                                            background:var(--color-beige,#e1cfac);
                                            display:flex;align-items:center;justify-content:center;
                                            flex-shrink:0;font-size:1.2rem">
                                    📰
                                </div>
                            @endif

                            <div style="min-width:0">
                                <p style="font-size:0.8125rem;font-weight:600;
                                          color:var(--color-green,#46462a);
                                          line-height:1.4;margin:0 0 4px;
                                          transition:color 0.2s;
                                          display:-webkit-box;-webkit-line-clamp:2;
                                          -webkit-box-orient:vertical;overflow:hidden">
                                    {{ $r->title }}
                                </p>
                                @if(!empty($r->published_at))
                                    <span style="font-size:0.75rem;color:#bbb">
                                        {{ \Carbon\Carbon::parse($r->published_at)->format('d/m/Y') }}
                                    </span>
                                @endif
                            </div>

                        </a>
                    @endforeach
                </div>

            </div>
        @endif

        {{-- Share box --}}
        <div style="background:#fff;border-radius:16px;padding:24px;
                    box-shadow:0 4px 20px rgba(0,0,0,0.06)">

            <h3 style="font-size:1rem;font-weight:700;
                       color:var(--color-green,#46462a);
                       margin-bottom:16px;letter-spacing:0.5px">
                CHIA SẺ BÀI VIẾT
            </h3>

            <div style="display:flex;gap:10px">
                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}"
                   target="_blank" rel="noopener"
                   style="display:flex;align-items:center;gap:7px;
                          background:#1877f2;color:white;
                          padding:9px 16px;border-radius:8px;
                          font-size:0.8rem;font-weight:600;text-decoration:none;
                          transition:opacity 0.2s"
                   onmouseover="this.style.opacity='0.85'"
                   onmouseout="this.style.opacity='1'">
                    <svg width="14" height="14" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/>
                    </svg>
                    Facebook
                </a>

                <button onclick="copyLink()"
                        style="display:flex;align-items:center;gap:7px;
                               background:var(--color-green,#46462a);color:white;
                               padding:9px 16px;border-radius:8px;border:none;
                               font-size:0.8rem;font-weight:600;cursor:pointer;
                               transition:opacity 0.2s"
                        onmouseover="this.style.opacity='0.85'"
                        onmouseout="this.style.opacity='1'">
                    <svg width="14" height="14" fill="none" stroke="currentColor"
                         stroke-width="2" viewBox="0 0 24 24">
                        <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/>
                        <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/>
                    </svg>
                    <span id="copyBtnText">Sao chép</span>
                </button>
            </div>

        </div>

    </aside>

</div>

{{-- Toast copy --}}
<div id="copyToast"
     style="position:fixed;bottom:30px;left:50%;transform:translateX(-50%) translateY(20px);
            background:var(--color-green,#46462a);color:white;
            padding:12px 24px;border-radius:999px;font-weight:600;
            font-size:0.875rem;opacity:0;pointer-events:none;
            transition:opacity 0.3s,transform 0.3s;z-index:9999">
    ✓ Đã sao chép liên kết
</div>

@endsection

@section('scripts')
<script>
function copyLink() {
    navigator.clipboard.writeText(window.location.href).then(() => {
        const toast = document.getElementById('copyToast');
        toast.style.opacity = '1';
        toast.style.transform = 'translateX(-50%) translateY(0)';
        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transform = 'translateX(-50%) translateY(20px)';
        }, 2500);
    });
}
</script>
@endsection
