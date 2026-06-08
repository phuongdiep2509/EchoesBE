@extends('admin.layouts.app')

@section('title', 'Quản lý Merchandise')

@section('styles')
<style>
/* ── Side panel (slide-in from left) ── */
.side-panel-overlay {
    position: fixed; inset: 0;
    background: rgba(0,0,0,.45);
    z-index: 1040;
    opacity: 0; visibility: hidden;
    transition: opacity .25s, visibility .25s;
}
.side-panel-overlay.open { opacity: 1; visibility: visible; }

.side-panel {
    position: fixed;
    top: 0; left: 0;
    width: 480px; max-width: 95vw;
    height: 100vh;
    background: #fff;
    z-index: 1050;
    transform: translateX(-100%);
    transition: transform .3s cubic-bezier(.4,0,.2,1);
    display: flex; flex-direction: column;
    box-shadow: 8px 0 40px rgba(0,0,0,.18);
}
.side-panel.open { transform: translateX(0); }

.side-panel-header {
    background: var(--echoes-green, #46462a);
    color: white;
    padding: 18px 24px;
    display: flex; align-items: center;
    justify-content: space-between;
    flex-shrink: 0;
}
.side-panel-header h5 { margin: 0; font-weight: 700; letter-spacing: .5px; }
.side-panel-close {
    background: none; border: none;
    color: rgba(255,255,255,.75); font-size: 1.25rem;
    cursor: pointer; transition: color .15s;
}
.side-panel-close:hover { color: white; }

.side-panel-body { flex: 1; overflow-y: auto; padding: 28px 24px; }

.side-panel-footer {
    padding: 16px 24px;
    border-top: 1px solid #eee;
    display: flex; gap: 12px;
    flex-shrink: 0;
}

/* product image in detail panel */
.panel-product-img {
    width: 100%;
    max-height: 240px;
    object-fit: contain;
    border-radius: 10px;
    background: var(--echoes-cream, #f0efeb);
    margin-bottom: 20px;
}

.panel-meta dt {
    font-size: .72rem;
    text-transform: uppercase;
    letter-spacing: .8px;
    color: #888;
    margin-top: 14px;
}
.panel-meta dd {
    font-size: .95rem;
    font-weight: 600;
    color: #222;
    margin: 0;
}

/* search bar */
.merch-search {
    border: 1px solid var(--echoes-beige, #e1cfac);
    border-radius: 8px;
    padding: 8px 14px;
    font-family: var(--font);
    font-size: .875rem;
    width: 260px;
    outline: none;
    transition: border-color .15s;
}
.merch-search:focus { border-color: var(--echoes-green, #46462a); }

/* thumbnail in table */
.merch-thumb {
    width: 56px; height: 56px;
    object-fit: cover;
    border-radius: 8px;
    background: var(--echoes-cream, #f0efeb);
}
.merch-thumb-placeholder {
    width: 56px; height: 56px;
    border-radius: 8px;
    background: var(--echoes-cream, #f0efeb);
    display: flex; align-items: center;
    justify-content: center;
    font-size: 1.4rem; color: #bbb;
}
</style>
@endsection

@section('content')

{{-- Page header --}}
<div class="admin-page-header">
    <h2><i class="fas fa-shopping-bag me-2"></i>Quản lý Merchandise</h2>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-4">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- Toolbar --}}
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
    <input type="text" class="merch-search" id="merchSearch"
           placeholder="Tìm tên sản phẩm..."
           oninput="filterTable(this.value)">

    <button class="btn btn-primary" onclick="openAddPanel()">
        <i class="fas fa-plus me-1"></i> Thêm sản phẩm
    </button>
</div>

{{-- Table --}}
<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover mb-0" id="merchTable">
            <thead>
                <tr>
                    <th style="width:70px">Ảnh</th>
                    <th>Tên sản phẩm</th>
                    <th>Giá bán</th>
                    <th>Tồn kho</th>
                    <th>Trạng thái</th>
                    <th style="width:120px;text-align:center">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $p)
                @php
                    $imgPath = !empty($p->AnhSanPham) ? 'assets/images/merch/' . $p->AnhSanPham : '';
                    $hasImg  = $imgPath && file_exists(public_path($imgPath));
                    $inStock = ($p->SoLuongTon ?? 0) > 0;
                    $isLow   = $inStock && $p->SoLuongTon <= 5;
                @endphp
                <tr data-name="{{ strtolower($p->TenMerch) }}">
                    <td>
                        @if($hasImg)
                            <img src="{{ asset($imgPath) }}" class="merch-thumb" alt="{{ $p->TenMerch }}">
                        @else
                            <div class="merch-thumb-placeholder">🛍️</div>
                        @endif
                    </td>
                    <td>
                        <div style="font-weight:600">{{ $p->TenMerch }}</div>
                        @if(!empty($p->MoTa))
                            <div style="font-size:.78rem;color:#888;margin-top:2px;
                                        overflow:hidden;text-overflow:ellipsis;white-space:nowrap;max-width:280px">
                                {{ $p->MoTa }}
                            </div>
                        @endif
                    </td>
                    <td style="font-weight:700;color:var(--echoes-red,#74070d)">
                        {{ number_format($p->GiaBan, 0, ',', '.') }}₫
                    </td>
                    <td>
                        <span style="font-weight:600;color:{{ $isLow ? 'var(--echoes-red,#74070d)' : '#222' }}">
                            {{ $p->SoLuongTon ?? 0 }}
                        </span>
                        @if(!$inStock)
                            <span class="badge ms-1" style="background:#f8d7da;color:#721c24;font-size:.7rem">Hết</span>
                        @elseif($isLow)
                            <span class="badge ms-1" style="background:#fff3cd;color:#856404;font-size:.7rem">Sắp hết</span>
                        @endif
                    </td>
                    <td>
                        @php
                            $stBg    = $p->TrangThai === 'DangBan' ? '#d4edda' : '#f0f0f0';
                            $stColor = $p->TrangThai === 'DangBan' ? '#155724' : '#555';
                            $stLabel = $p->TrangThai === 'DangBan' ? 'Đang bán' : 'Ngừng bán';
                        @endphp
                        <span style="background:{{ $stBg }};color:{{ $stColor }};
                                     font-size:.75rem;font-weight:700;padding:4px 10px;
                                     border-radius:999px">
                            {{ $stLabel }}
                        </span>
                    </td>
                    <td style="text-align:center">
                        <button class="btn btn-sm btn-primary"
                                onclick="openDetailPanel({{ $p->MaMerch }}, this)">
                            <i class="fas fa-eye me-1"></i> Chi tiết
                        </button>
                    </td>
                </tr>

                {{-- Hidden data for JS --}}
                @php $priceFormatted = number_format($p->GiaBan, 0, ',', '.'); @endphp
                <script>
                window._merch = window._merch || {};
                window._merch[{{ $p->MaMerch }}] = {
                    id:      {{ $p->MaMerch }},
                    name:    @json($p->TenMerch),
                    price:   @json($priceFormatted),
                    stock:   {{ $p->SoLuongTon ?? 0 }},
                    status:  @json($p->TrangThai),
                    img:     @json($hasImg ? asset($imgPath) : ''),
                    mota:    @json($p->MoTa ?? ''),
                    doitra:  @json($p->ChinhSachDoiTra ?? ''),
                    baoquan: @json($p->HuongDanBaoQuan ?? ''),
                    editUrl: @json(route('admin.merchandise.edit', $p->MaMerch)),
                    toggleUrl: @json(route('admin.merchandise.toggle', $p->MaMerch)),
                };
                </script>

                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-5">
                        <div style="font-size:2rem;margin-bottom:8px">🛍️</div>
                        Chưa có sản phẩm nào.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- ═══════ SIDE PANEL: Detail ═══════ --}}
<div class="side-panel-overlay" id="overlayDetail" onclick="closePanels()"></div>
<div class="side-panel" id="panelDetail">
    <div class="side-panel-header">
        <h5><i class="fas fa-box-open me-2"></i>Chi tiết sản phẩm</h5>
        <button class="side-panel-close" onclick="closePanels()"><i class="fas fa-times"></i></button>
    </div>
    <div class="side-panel-body" id="panelDetailBody">
        {{-- filled by JS --}}
    </div>
    <div class="side-panel-footer" style="gap:12px;display:flex;flex-wrap:wrap">
        <form id="toggleMerchStatusForm" method="POST" style="margin:0;flex:1 1 auto;min-width:160px" onsubmit="return confirmToggleStatus()">
            @csrf
            @method('PATCH')
            <button type="submit" id="btnToggleMerchStatus" class="btn btn-outline-warning w-100">
                <i class="fas fa-eye-slash me-1"></i> Ẩn sản phẩm
            </button>
        </form>
        <a id="btnEditMerch" href="#" class="btn btn-primary flex-fill">
            <i class="fas fa-pen me-1"></i> Chỉnh sửa
        </a>
        <button class="btn btn-outline-secondary" onclick="closePanels()">Đóng</button>
    </div>
</div>

{{-- ═══════ SIDE PANEL: Add / Edit (modal-style form) ═══════ --}}
<div class="side-panel-overlay" id="overlayAdd" onclick="closePanels()"></div>
<div class="side-panel" id="panelAdd">
    <div class="side-panel-header">
        <h5><i class="fas fa-plus-circle me-2"></i>Thêm sản phẩm</h5>
        <button class="side-panel-close" onclick="closePanels()"><i class="fas fa-times"></i></button>
    </div>
    <div class="side-panel-body">
        <form method="POST" action="{{ route('admin.merchandise.store') }}" id="addMerchForm" onsubmit="return confirmSaveProduct('Bạn có chắc muốn lưu sản phẩm mới này không?')">
            @csrf

            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label">Tên sản phẩm <span class="text-danger">*</span></label>
                    <input type="text" name="TenMerch" class="form-control"
                           placeholder="Nhập tên sản phẩm" required>
                </div>

                <div class="col-6">
                    <label class="form-label">Giá bán (₫) <span class="text-danger">*</span></label>
                    <input type="number" name="GiaBan" class="form-control"
                           placeholder="500000" min="0" step="1000" required>
                </div>

                <div class="col-6">
                    <label class="form-label">Tồn kho</label>
                    <input type="number" name="SoLuongTon" class="form-control"
                           placeholder="0" min="0" value="0">
                </div>

                <div class="col-12">
                    <label class="form-label">Mô tả</label>
                    <textarea name="MoTa" class="form-control" rows="3"
                              placeholder="Mô tả ngắn về sản phẩm..."></textarea>
                </div>

                <div class="col-12">
                    <label class="form-label">Màu sắc (tùy chọn)</label>
                    <input type="text" name="MauSac" class="form-control"
                           placeholder="Nhập màu sắc bằng tiếng Việt">
                </div>

                <div class="col-12">
                    <label class="form-label">Size (tùy chọn)</label>
                    <input type="text" name="Size" class="form-control"
                           placeholder="Nhập size bằng tiếng Việt">
                </div>

                <div class="col-12">
                    <label class="form-label">URL ảnh (nhập từng URL mỗi dòng)</label>
                    <textarea name="AnhSanPham" class="form-control" rows="3"
                              placeholder="Nhập..."></textarea>
                </div>

                <div class="col-12">
                    <label class="form-label">Trạng thái</label>
                    <select name="TrangThai" class="form-select">
                        <option value="DangBan">Đang bán</option>
                        <option value="NgungBan">Ngừng bán</option>
                    </select>
                </div>

                <div class="col-12">
                    <label class="form-label">Chính sách đổi trả</label>
                    <textarea name="ChinhSachDoiTra" class="form-control" rows="2"
                              placeholder="Mô tả chính sách..."></textarea>
                </div>

                <div class="col-12">
                    <label class="form-label">Hướng dẫn bảo quản</label>
                    <textarea name="HuongDanBaoQuan" class="form-control" rows="2"
                              placeholder="Hướng dẫn..."></textarea>
                </div>
            </div>
        </form>
    </div>
    <div class="side-panel-footer">
        <button type="submit" form="addMerchForm" class="btn btn-primary flex-fill">
            <i class="fas fa-save me-1"></i> Lưu
        </button>
        <button class="btn btn-outline-secondary" onclick="closePanels()">Hủy</button>
    </div>
</div>

@endsection

@section('scripts')
<script>
// ── Table search ──────────────────────────────────
function filterTable(q) {
    const qL = q.toLowerCase();
    document.querySelectorAll('#merchTable tbody tr[data-name]').forEach(row => {
        row.style.display = row.dataset.name.includes(qL) ? '' : 'none';
    });
}

// ── Open / close panels ───────────────────────────
function openAddPanel() {
    document.getElementById('overlayAdd').classList.add('open');
    document.getElementById('panelAdd').classList.add('open');
    document.body.style.overflow = 'hidden';
}

function openDetailPanel(id) {
    const d = window._merch[id];
    if (!d) return;

    const statusLabel = d.status === 'DangBan' ? 'Đang bán' : 'Ngừng bán';
    const statusBg    = d.status === 'DangBan' ? '#d4edda' : '#f0f0f0';
    const statusColor = d.status === 'DangBan' ? '#155724' : '#555';

    document.getElementById('panelDetailBody').innerHTML = `
        ${d.img
            ? `<img src="${d.img}" class="panel-product-img" alt="${d.name}">`
            : `<div style="width:100%;height:180px;background:var(--echoes-cream,#f0efeb);
                           border-radius:10px;display:flex;align-items:center;
                           justify-content:center;font-size:3rem;margin-bottom:20px">🛍️</div>`
        }
        <dl class="panel-meta">
            <dt>Tên sản phẩm</dt>
            <dd>${d.name}</dd>

            <dt>Giá bán</dt>
            <dd style="color:var(--echoes-red,#74070d)">${d.price}₫</dd>

            <dt>Tồn kho</dt>
            <dd>${d.stock} sản phẩm</dd>

            <dt>Trạng thái</dt>
            <dd>
                <span style="background:${statusBg};color:${statusColor};
                             font-size:.75rem;font-weight:700;padding:4px 10px;
                             border-radius:999px">${statusLabel}</span>
            </dd>

            ${d.mota ? `<dt>Mô tả</dt><dd style="font-weight:400;color:#555;line-height:1.6">${d.mota}</dd>` : ''}
            ${d.doitra ? `<dt>Chính sách đổi trả</dt><dd style="font-weight:400;color:#555;line-height:1.6">${d.doitra}</dd>` : ''}
            ${d.baoquan ? `<dt>Hướng dẫn bảo quản</dt><dd style="font-weight:400;color:#555;line-height:1.6">${d.baoquan}</dd>` : ''}
        </dl>
    `;

    document.getElementById('btnEditMerch').href = d.editUrl;
    const toggleForm = document.getElementById('toggleMerchStatusForm');
    toggleForm.action = d.toggleUrl;

    const toggleButton = document.getElementById('btnToggleMerchStatus');
    if (d.status === 'DangBan') {
        toggleButton.className = 'btn btn-outline-warning w-100';
        toggleButton.innerHTML = '<i class="fas fa-eye-slash me-1"></i> Ẩn sản phẩm';
    } else {
        toggleButton.className = 'btn btn-outline-success w-100';
        toggleButton.innerHTML = '<i class="fas fa-eye me-1"></i> Hiện sản phẩm';
    }

    document.getElementById('overlayDetail').classList.add('open');
    document.getElementById('panelDetail').classList.add('open');
    document.body.style.overflow = 'hidden';
}

function confirmToggleStatus() {
    const button = document.getElementById('btnToggleMerchStatus');
    if (!button) return true;
    const isHide = button.textContent.includes('Ẩn');
    return confirm(isHide
        ? 'Bạn có chắc muốn ẩn sản phẩm này không?'
        : 'Bạn có chắc muốn hiện sản phẩm này không?');
}

function confirmSaveProduct(message = 'Bạn có chắc muốn lưu thông tin sản phẩm này không?') {
    return confirm(message);
}

function closePanels() {
    ['overlayDetail','overlayAdd','panelDetail','panelAdd'].forEach(id => {
        document.getElementById(id)?.classList.remove('open');
    });
    document.body.style.overflow = '';
}

// ESC key closes panels
document.addEventListener('keydown', e => { if (e.key === 'Escape') closePanels(); });
</script>
@endsection
