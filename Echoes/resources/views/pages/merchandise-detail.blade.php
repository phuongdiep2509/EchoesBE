@extends('layouts.app')

@section('title', ($product->TenMerch ?? 'Chi tiết sản phẩm') . ' | Echoes')

@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/merchandiseDetail.css') }}">
@endsection

@section('content')

{{-- Breadcrumb --}}
<div style="margin-top:100px;background:var(--color-green,#46462a)">
    <div style="max-width:1200px;margin:0 auto;padding:12px 20px;
                font-size:0.8rem;letter-spacing:1px;text-transform:uppercase">
        <a href="{{ url('/') }}"
           style="color:rgba(255,255,255,0.7);text-decoration:none">TRANG CHỦ</a>
        <span style="color:rgba(255,255,255,0.4);margin:0 8px">/</span>
        <a href="{{ url('/merchandise') }}"
           style="color:rgba(255,255,255,0.7);text-decoration:none">MERCHANDISE</a>
        <span style="color:rgba(255,255,255,0.4);margin:0 8px">/</span>
        <span style="color:white;font-weight:600">{{ $product->TenMerch }}</span>
    </div>
</div>

{{-- Main product detail --}}
<div class="product-detail" style="max-width:1200px;margin:0 auto;padding:40px 20px 60px">

    {{-- ─── LEFT: Image ─── --}}
    <div class="product-image" style="background:linear-gradient(160deg,#e8f4e8,#f0efeb,#e1cfac);
                                       border-radius:20px;overflow:hidden;
                                       display:flex;align-items:center;justify-content:center;
                                       min-height:460px;position:relative;padding:30px">

        @php
            $inStock = ($product->SoLuongTon ?? 0) > 0;
            $isLow   = $inStock && $product->SoLuongTon <= 10;
        @endphp

        <span class="stock-badge-large {{ $inStock ? '' : 'out' }}">
            @if(!$inStock)
                Hết hàng
            @elseif($isLow)
                Sắp hết ({{ $product->SoLuongTon }})
            @else
                Còn hàng
            @endif
        </span>

        @if(!empty($product->AnhSanPham))
            <img src="{{ asset($product->AnhSanPham) }}"
                 alt="{{ $product->TenMerch }}"
                 style="max-width:380px;width:100%;
                        box-shadow:0 20px 50px rgba(0,0,0,0.18);
                        border-radius:12px;object-fit:contain;cursor:zoom-in"
                 onclick="document.getElementById('imageModal').style.display='flex'">
        @else
            <div style="width:280px;height:280px;border-radius:12px;
                        background:rgba(70,70,42,0.1);
                        display:flex;align-items:center;justify-content:center;font-size:4rem">
                🛍️
            </div>
        @endif

    </div>

    {{-- ─── RIGHT: Info ─── --}}
    <div class="product-info" style="background:#faf9f2;border-radius:20px;padding:48px 44px">

        <p style="color:var(--color-green,#46462a);font-size:0.8rem;
                  letter-spacing:2px;text-transform:uppercase;margin-bottom:12px;font-weight:600">
            ✶ MERCHANDISE
        </p>

        <h1 style="font-size:2rem;font-weight:900;color:#1a1a1a;
                   line-height:1.2;margin-bottom:20px">
            {{ $product->TenMerch }}
        </h1>

        <div style="font-size:1.75rem;font-weight:800;
                    color:var(--color-red,#74070d);margin-bottom:24px">
            {{ number_format($product->GiaBan, 0, ',', '.') }}₫
        </div>

        @if(!empty($product->MoTa))
            <p style="font-size:0.9375rem;color:#555;line-height:1.7;margin-bottom:28px">
                {!! nl2br(e($product->MoTa)) !!}
            </p>
        @endif

        {{-- Quantity --}}
        <div class="quantity">
            <span style="font-weight:600;font-size:0.875rem;color:#333">Số lượng</span>
            <div class="qty-box">
                <button type="button" onclick="changeQty(-1)">−</button>
                <input type="number" id="qtyInput" value="1" min="1"
                       max="{{ $product->SoLuongTon ?? 99 }}" readonly>
                <button type="button" onclick="changeQty(1)">+</button>
            </div>
            @if($inStock)
                <span style="font-size:0.8rem;color:#888">
                    Còn {{ $product->SoLuongTon }} sản phẩm
                </span>
            @endif
        </div>

        {{-- CTA Buttons --}}
        <form method="POST" action="{{ route('cart.add') }}">
            @csrf
            <input type="hidden" name="MaMerch" value="{{ $product->MaMerch }}">
            <input type="hidden" name="SoLuong" id="formQty" value="1">

            <button type="submit"
                    class="buy-now"
                    {{ !$inStock ? 'disabled' : '' }}>
                @if($inStock)
                    🛒 &nbsp; THÊM VÀO GIỎ HÀNG
                @else
                    HẾT HÀNG
                @endif
            </button>
        </form>

        {{-- Tabs: Mô tả / Đổi trả / Bảo quản --}}
        <div class="tabs" style="margin-top:36px">
            <div class="tab-buttons">
                <button class="active" onclick="switchTab(this,'tab-desc')">Mô tả</button>
                @if(!empty($product->ChinhSachDoiTra))
                    <button onclick="switchTab(this,'tab-return')">Đổi trả</button>
                @endif
                @if(!empty($product->HuongDanBaoQuan))
                    <button onclick="switchTab(this,'tab-care')">Bảo quản</button>
                @endif
            </div>

            <div id="tab-desc" class="tab-content active">
                <p>{{ $product->MoTa ?? 'Thông tin sản phẩm sẽ được cập nhật.' }}</p>
            </div>

            @if(!empty($product->ChinhSachDoiTra))
                <div id="tab-return" class="tab-content">
                    <p>{!! nl2br(e($product->ChinhSachDoiTra)) !!}</p>
                </div>
            @endif

            @if(!empty($product->HuongDanBaoQuan))
                <div id="tab-care" class="tab-content">
                    <p>{!! nl2br(e($product->HuongDanBaoQuan)) !!}</p>
                </div>
            @endif
        </div>

    </div>

</div>

{{-- ─── Related products ─── --}}
@if(isset($related) && $related->count() > 0)
<section style="padding:60px 0 80px;background:var(--color-yellow,#f0efeb)">
    <div style="max-width:1200px;margin:0 auto;padding:0 20px">

        <div style="display:flex;align-items:center;gap:16px;margin-bottom:36px">
            <div style="flex:1;height:2px;background:linear-gradient(to right,transparent,rgba(70,70,42,0.3))"></div>
            <h2 style="color:var(--color-green,#46462a);font-size:1.4rem;
                       letter-spacing:2px;white-space:nowrap">🛍️ SẢN PHẨM LIÊN QUAN</h2>
            <div style="flex:1;height:2px;background:linear-gradient(to left,transparent,rgba(70,70,42,0.3))"></div>
        </div>

        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:24px">
            @foreach($related as $r)
                <a href="{{ url('/merchandise/' . $r->MaMerch) }}"
                   style="text-decoration:none;color:inherit;display:block;
                          background:#fff;border-radius:16px;overflow:hidden;
                          box-shadow:0 4px 20px rgba(0,0,0,0.07);
                          transition:transform 0.25s ease,box-shadow 0.25s ease"
                   onmouseover="this.style.transform='translateY(-6px)';this.style.boxShadow='0 12px 30px rgba(0,0,0,0.14)'"
                   onmouseout="this.style.transform='';this.style.boxShadow='0 4px 20px rgba(0,0,0,0.07)'">

                    <div style="aspect-ratio:1;overflow:hidden;background:var(--color-beige,#e1cfac)">
                        <img src="{{ asset($r->AnhSanPham ?? 'assets/images/merch/default.png') }}"
                             alt="{{ $r->TenMerch }}"
                             style="width:100%;height:100%;object-fit:cover;
                                    transition:transform 0.4s ease"
                             onmouseover="this.style.transform='scale(1.06)'"
                             onmouseout="this.style.transform=''">
                    </div>

                    <div style="padding:14px 16px 18px">
                        <h4 style="font-size:0.875rem;font-weight:700;
                                   color:var(--color-green,#46462a);
                                   margin-bottom:6px;line-height:1.3;
                                   display:-webkit-box;-webkit-line-clamp:2;
                                   -webkit-box-orient:vertical;overflow:hidden">
                            {{ $r->TenMerch }}
                        </h4>
                        <p style="font-size:0.9rem;font-weight:700;
                                  color:var(--color-red,#74070d);margin-bottom:10px">
                            {{ number_format($r->GiaBan, 0, ',', '.') }}₫
                        </p>
                        <span style="display:inline-block;padding:5px 14px;
                                     background:var(--color-green,#46462a);color:white;
                                     border-radius:999px;font-size:0.75rem;font-weight:600">
                            XEM NGAY
                        </span>
                    </div>

                </a>
            @endforeach
        </div>

    </div>
</section>
@endif

{{-- Image zoom modal --}}
@if(!empty($product->AnhSanPham))
<div id="imageModal" class="image-modal" onclick="this.style.display='none'"
     style="display:none">
    <span class="close" onclick="document.getElementById('imageModal').style.display='none'">&times;</span>
    <img src="{{ asset($product->AnhSanPham) }}" alt="{{ $product->TenMerch }}">
</div>
@endif

{{-- Toast --}}
<div class="toast" id="toast">✓ Đã thêm vào giỏ hàng</div>

@endsection

@section('scripts')
<script>
// Quantity control
function changeQty(delta) {
    const input = document.getElementById('qtyInput');
    const formInput = document.getElementById('formQty');
    const max = parseInt(input.max) || 99;
    let val = Math.max(1, Math.min(max, parseInt(input.value || 1) + delta));
    input.value = val;
    if (formInput) formInput.value = val;
}

// Tab switch
function switchTab(btn, id) {
    document.querySelectorAll('.tab-buttons button').forEach(b => b.classList.remove('active'));
    document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
    btn.classList.add('active');
    const tab = document.getElementById(id);
    if (tab) tab.classList.add('active');
}

// Toast on cart add
document.querySelector('form[action*="cart"]')?.addEventListener('submit', function(e) {
    e.preventDefault();
    const toast = document.getElementById('toast');
    if (toast) {
        toast.classList.add('show');
        setTimeout(() => toast.classList.remove('show'), 2500);
    }
    // Uncomment to actually submit: this.submit();
});
</script>
@endsection
