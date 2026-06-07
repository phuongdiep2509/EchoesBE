<?php $__env->startSection('title', (($concert->title ?? $event->title ?? 'Chi tiết')) . ' | Echoes'); ?>

<?php $__env->startSection('styles'); ?>
<link rel="stylesheet" href="<?php echo e(asset('assets/css/eventDetail.css')); ?>">
<link rel="stylesheet" href="<?php echo e(asset('assets/css/eventDetailRe.css')); ?>">
<style>
/* ── Breadcrumb bar ───────────────────────────── */
.detail-breadcrumb {
    background: var(--color-green, #46462a);
    color: #fff;
    padding: 12px 0;
    margin-top: 100px;   /* clear fixed header */
}
.detail-breadcrumb .inner {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
    font-size: 0.78rem;
    letter-spacing: 1px;
    text-transform: uppercase;
    display: flex;
    align-items: center;
    gap: 6px;
    flex-wrap: wrap;
}
.detail-breadcrumb a { color: rgba(255,255,255,.75); text-decoration: none; }
.detail-breadcrumb a:hover { color: #fff; }
.detail-breadcrumb .sep { color: rgba(255,255,255,.4); }
.detail-breadcrumb .current { color: #fff; font-weight: 600; }

/* ── Main wrapper ─────────────────────────────── */
.detail-wrap {
    max-width: 1200px;
    margin: 32px auto 60px;
    padding: 0 20px;
    display: grid;
    grid-template-columns: 1fr 340px;
    gap: 32px;
    align-items: start;
}
@media (max-width: 900px) {
    .detail-wrap { grid-template-columns: 1fr; }
}

/* ── Poster ───────────────────────────────────── */
.detail-poster {
    border-radius: 12px;
    overflow: hidden;
    margin-bottom: 20px;
    position: relative;
}
.detail-poster img {
    width: 100%;
    display: block;
    max-height: 480px;
    object-fit: cover;
}
.detail-poster .status-pill {
    position: absolute;
    top: 14px; right: 14px;
    background: var(--color-red, #74070d);
    color: #fff;
    font-size: 0.7rem;
    font-weight: 700;
    letter-spacing: 1px;
    text-transform: uppercase;
    padding: 5px 12px;
    border-radius: 999px;
}

/* ── Section header bar ───────────────────────── */
.section-bar {
    background: var(--color-green, #46462a);
    color: #fff;
    padding: 10px 16px;
    border-radius: 4px;
    font-size: 0.9rem;
    font-weight: 700;
    letter-spacing: 1px;
    text-transform: uppercase;
    margin-bottom: 16px;
    display: flex;
    align-items: center;
    gap: 8px;
}

/* ── Info card ────────────────────────────────── */
.detail-info-card {
    background: #fff;
    border-radius: 12px;
    border: 1px solid rgba(0,0,0,.07);
    padding: 24px;
    margin-bottom: 20px;
}
.detail-title {
    color: var(--color-red, #74070d);
    font-size: 1.9rem;
    font-weight: 900;
    margin-bottom: 20px;
    line-height: 1.15;
}
.detail-meta-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
    margin-bottom: 0;
}
.detail-meta-item { display: flex; flex-direction: column; gap: 2px; }
.detail-meta-label {
    font-size: 0.72rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    color: #888;
}
.detail-meta-value {
    font-size: 0.95rem;
    font-weight: 600;
    color: #222;
    display: flex;
    align-items: center;
    gap: 6px;
}
.detail-meta-value .star { color: var(--color-red, #74070d); font-size: 0.8rem; }

/* ── Text sections ────────────────────────────── */
.detail-section {
    background: #fff;
    border-radius: 12px;
    border: 1px solid rgba(0,0,0,.07);
    padding: 20px 24px;
    margin-bottom: 20px;
}
.detail-section p {
    font-size: 0.95rem;
    color: #444;
    line-height: 1.75;
    margin-bottom: 14px;
}
.detail-section p:last-child { margin-bottom: 0; }
.highlights-list {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    flex-direction: column;
    gap: 8px;
}
.highlights-list li {
    font-size: 0.9rem;
    color: #333;
    display: flex;
    gap: 10px;
    padding-bottom: 8px;
    border-bottom: 1px solid rgba(0,0,0,.05);
}
.highlights-list li:last-child { border-bottom: none; padding-bottom: 0; }

/* Terms section inside detail-section */
.terms-block { margin-top: 12px; }
.terms-block h4 {
    font-size: 0.82rem;
    font-weight: 700;
    text-transform: uppercase;
    color: var(--color-red, #74070d);
    margin-bottom: 6px;
}
.terms-block p, .terms-block ul {
    font-size: 0.83rem;
    color: #555;
    line-height: 1.6;
}
.terms-block ul { padding-left: 16px; margin: 4px 0; }

/* ── RIGHT: Booking card ──────────────────────── */
.booking-card-new {
    background: #fff;
    border-radius: 12px;
    border: 2px solid var(--color-green, #46462a);
    overflow: hidden;
    position: sticky;
    top: calc(100px + 16px);
}
.booking-card-header {
    background: var(--color-green, #46462a);
    color: #fff;
    padding: 14px 20px;
    font-size: 1rem;
    font-weight: 700;
    letter-spacing: 1px;
    text-transform: uppercase;
    text-align: center;
}
.booking-card-body { padding: 20px; }

/* Price table */
.price-section-title {
    color: var(--color-red, #74070d);
    font-weight: 700;
    font-size: 0.85rem;
    text-align: center;
    margin-bottom: 10px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
.price-table { width: 100%; border-collapse: collapse; margin-bottom: 6px; }
.price-table tr {
    cursor: pointer;
    transition: background .15s;
}
.price-table tr:hover { background: rgba(116,7,13,.04); }
.price-table tr.selected { background: rgba(116,7,13,.08); }
.price-table td {
    padding: 11px 12px;
    font-size: 0.9rem;
    border-bottom: 1px solid rgba(0,0,0,.05);
}
.price-table td:last-child {
    text-align: right;
    font-weight: 700;
    color: #a00a12;
    white-space: nowrap;
}
.price-table td:first-child { font-weight: 600; color: #222; }
.price-table tr:last-child td { border-bottom: none; }
.price-min {
    font-size: 0.8rem;
    color: #888;
    text-align: center;
    padding: 6px 0 12px;
    border-top: 1px solid rgba(0,0,0,.05);
}

/* Buy button */
.btn-buy-now {
    display: block;
    width: 100%;
    padding: 14px;
    background: linear-gradient(135deg, #a00a12, #74070d);
    color: #fff;
    font-size: 1rem;
    font-weight: 800;
    text-align: center;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    letter-spacing: 1px;
    text-transform: uppercase;
    text-decoration: none;
    box-shadow: 0 6px 20px rgba(116,7,13,.3);
    transition: opacity .2s, transform .2s;
    margin-bottom: 10px;
}
.btn-buy-now:hover { opacity: .9; transform: translateY(-2px); text-decoration: none; color: #fff; }

/* Notes */
.note-box {
    background: #fdf8f8;
    border-radius: 8px;
    padding: 14px;
    font-size: 0.8rem;
    color: #555;
    border: 1px solid rgba(116,7,13,.08);
    margin-top: 10px;
}
.note-box .note-title {
    font-weight: 700;
    color: var(--color-red, #74070d);
    margin-bottom: 6px;
    display: flex;
    align-items: center;
    gap: 5px;
}
.note-box ul { list-style: none; padding: 0; margin: 0; line-height: 1.9; }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>


<?php
    if (!isset($concert) && isset($event)) { $concert = $event; }
    if (!isset($event) && isset($concert)) { $event = $concert; }
    $concertMissing = empty($concert);
?>

<?php if($concertMissing): ?>
<div style="max-width:800px;margin:120px auto 60px;padding:0 20px;text-align:center">
    <h1 style="color:var(--color-red,#74070d);margin-bottom:12px">Không tìm thấy sự kiện</h1>
    <p style="color:#555">Thông tin sự kiện chưa có sẵn. Vui lòng <a href="<?php echo e(url('/concert')); ?>">quay lại danh sách</a>.</p>
</div>
<?php else: ?>


<div class="detail-breadcrumb">
    <div class="inner">
        <a href="<?php echo e(url('/')); ?>">TRANG CHỦ</a>
        <span class="sep">/</span>
        <a href="<?php echo e(url('/concert')); ?>">CONCERT</a>
        <span class="sep">/</span>
        <span class="current"><?php echo e(\Illuminate\Support\Str::limit($concert->title ?? '', 60)); ?></span>
    </div>
</div>


<div class="detail-wrap">

    
    <div>

        
        <div class="detail-poster">
            <?php if(!empty($concert->image)): ?>
                <img src="<?php echo e(asset($concert->image)); ?>" alt="<?php echo e($concert->title); ?>">
            <?php else: ?>
                <div style="width:100%;height:360px;background:var(--color-beige,#e1cfac);
                            display:flex;align-items:center;justify-content:center;font-size:3rem">
                    🎵
                </div>
            <?php endif; ?>
            <?php
                $statusLabel = match($concert->status ?? '') {
                    'SapDienRa' => 'SẮP DIỄN RA',
                    'DangMoBan' => 'ĐANG MỞ BÁN',
                    'DaKetThuc' => 'ĐÃ KẾT THÚC',
                    'DaHuy'     => 'ĐÃ HỦY',
                    default     => 'MỞ BÁN',
                };
            ?>
            <span class="status-pill"><?php echo e($statusLabel); ?></span>
        </div>

        
        <div class="detail-info-card">
            <div class="section-bar">✶ THÔNG TIN SỰ KIỆN</div>

            <h1 class="detail-title"><?php echo e($concert->title); ?></h1>

            <div class="detail-meta-grid">
                <div class="detail-meta-item">
                    <span class="detail-meta-label">✶ THỜI GIAN</span>
                    <span class="detail-meta-value">
                        <?php if(!empty($concert->event_date) && $concert->event_date !== 'Đang cập nhật'): ?>
                            <?php echo e(\Carbon\Carbon::parse($concert->event_date)->format('H:i, d/m/Y')); ?>

                        <?php else: ?>
                            Đang cập nhật
                        <?php endif; ?>
                    </span>
                </div>
                <div class="detail-meta-item">
                    <span class="detail-meta-label">✶ ĐỊA ĐIỂM</span>
                    <span class="detail-meta-value">
                        <?php echo e($concert->location ?? ($concert->city ?? 'Đang cập nhật')); ?>

                    </span>
                </div>
                <div class="detail-meta-item">
                    <span class="detail-meta-label">✶ THỜI LƯỢNG</span>
                    <span class="detail-meta-value">
                        <?php if(!empty($concert->event_date) && !empty($concert->event_end)
                            && $concert->event_date !== 'Đang cập nhật'): ?>
                            <?php echo e(\Carbon\Carbon::parse($concert->event_date)->format('H:i')); ?>

                            – <?php echo e(\Carbon\Carbon::parse($concert->event_end)->format('H:i')); ?>

                        <?php else: ?>
                            Đang cập nhật
                        <?php endif; ?>
                    </span>
                </div>
                <div class="detail-meta-item">
                    <span class="detail-meta-label">✶ THỂ LOẠI</span>
                    <span class="detail-meta-value">
                        <?php echo e($concert->event_type ?? 'Concert âm nhạc'); ?>

                    </span>
                </div>
            </div>
        </div>

        
        <?php if(!empty($concert->description)): ?>
        <div class="detail-section">
            <div class="section-bar">✶ GIỚI THIỆU</div>
            <p><?php echo nl2br(e($concert->description)); ?></p>

            <?php if(!empty($concert->highlights)): ?>
                <p style="font-weight:700;color:#222;margin-bottom:8px">ĐIỂM NỔI BẬT</p>
                <ul class="highlights-list">
                    <?php $__currentLoopData = preg_split('/[\n|•]+/', $concert->highlights, -1, PREG_SPLIT_NO_EMPTY); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if(trim($item)): ?>
                            <li><span style="color:var(--color-red,#74070d)">✦</span> <?php echo e(trim($item)); ?></li>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        
        <?php if(!empty($concert->terms)): ?>
        <div class="detail-section">
            <div class="section-bar">🗂 ĐIỀU KIỆN & ĐIỀU KHOẢN</div>
            <div class="terms-block">
                <?php echo nl2br(e($concert->terms)); ?>

            </div>
        </div>
        <?php endif; ?>

    </div>

    
    <div>
    <div class="booking-card-new">

        <div class="booking-card-header">THÔNG TIN VÉ</div>

        <div class="booking-card-body">

            <?php if(isset($hangVe) && $hangVe->count() > 0): ?>
                <p class="price-section-title">GIÁ VÉ</p>

                <table class="price-table" id="priceTable">
                    <?php $__currentLoopData = $hangVe; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $hv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr class="<?php echo e($loop->first ? 'selected' : ''); ?>"
                        onclick="selectRow(this, <?php echo e((int)$hv->price); ?>, '<?php echo e(addslashes($hv->ticket_name)); ?>')">
                        <td><?php echo e($hv->ticket_name); ?></td>
                        <td><?php echo e(number_format($hv->price, 0, ',', '.')); ?> ₫</td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </table>

                <p class="price-min">
                    Giá vé từ <?php echo e(number_format($hangVe->min('price') ?? 0, 0, ',', '.')); ?> ₫
                </p>
            <?php else: ?>
                <p style="text-align:center;color:#aaa;padding:16px 0;font-size:0.875rem">
                    Thông tin vé đang được cập nhật
                </p>
            <?php endif; ?>

            
            <?php if(!empty($concert->id)): ?>
                <a href="<?php echo e(url('/booking/' . $concert->id)); ?>" class="btn-buy-now">
                    🎫 ĐẶT NGAY
                </a>
                <a href="<?php echo e(url('/booking/' . $concert->id . '?gift=1')); ?>"
                   style="display:block;text-align:center;color:var(--color-green,#46462a);
                          font-size:0.875rem;font-weight:600;padding:8px;
                          border:2px solid var(--color-green,#46462a);border-radius:8px;
                          text-decoration:none;transition:all .2s"
                   onmouseover="this.style.background='var(--color-green,#46462a)';this.style.color='#fff'"
                   onmouseout="this.style.background='';this.style.color='var(--color-green,#46462a)'">
                    🎁 TẶNG VÉ
                </a>
            <?php else: ?>
                <button class="btn-buy-now" disabled style="opacity:.5;cursor:not-allowed">
                    🎫 SẮP MỞ BÁN
                </button>
            <?php endif; ?>

            
            <div class="note-box">
                <div class="note-title">💡 LƯU Ý QUAN TRỌNG:</div>
                <ul>
                    <li>• Vé đã mua không được hoàn trả</li>
                    <li>• Vui lòng đến trước 30 phút</li>
                    <li>• Không mang đồ uống có cồn, rượu bia, các chất gây nghiện</li>
                    <li>• Không dẫn theo thú cưng</li>
                </ul>
            </div>

        </div>
    </div>
    </div>

</div>


<?php if(isset($related) && $related->count() > 0): ?>
<section style="padding:48px 0 60px;background:var(--color-yellow,#f0efeb)">
    <div style="max-width:1200px;margin:0 auto;padding:0 20px">

        <div style="display:flex;align-items:center;gap:14px;margin-bottom:30px">
            <div style="flex:1;height:1px;background:rgba(116,7,13,.2)"></div>
            <h2 style="font-size:1.1rem;color:var(--color-red,#74070d);
                       letter-spacing:2px;text-transform:uppercase;white-space:nowrap">
                🎵 BẠN CÓ THỂ THÍCH
            </h2>
            <div style="flex:1;height:1px;background:rgba(116,7,13,.2)"></div>
        </div>

        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:20px">
            <?php $__currentLoopData = $related; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e(url('/concert/' . $r->id)); ?>"
                   style="display:block;background:#fff;border-radius:12px;overflow:hidden;
                          text-decoration:none;color:inherit;
                          box-shadow:0 3px 12px rgba(0,0,0,.07);
                          transition:transform .2s,box-shadow .2s"
                   onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='0 8px 24px rgba(0,0,0,.13)'"
                   onmouseout="this.style.transform='';this.style.boxShadow='0 3px 12px rgba(0,0,0,.07)'">
                    <div style="aspect-ratio:4/3;overflow:hidden;background:#e1cfac">
                        <img src="<?php echo e(asset($r->image ?? 'assets/images/concert/default.png')); ?>"
                             alt="<?php echo e($r->title); ?>"
                             style="width:100%;height:100%;object-fit:cover">
                    </div>
                    <div style="padding:12px 14px 14px">
                        <p style="font-size:0.85rem;font-weight:700;
                                  color:var(--color-green,#46462a);
                                  margin:0 0 6px;line-height:1.3;
                                  display:-webkit-box;-webkit-line-clamp:2;
                                  -webkit-box-orient:vertical;overflow:hidden">
                            <?php echo e($r->title); ?>

                        </p>
                        <?php if(!empty($r->event_date)): ?>
                        <p style="font-size:0.75rem;color:#aaa;margin:0 0 8px">
                            <?php echo e(\Carbon\Carbon::parse($r->event_date)->format('d/m/Y')); ?>

                        </p>
                        <?php endif; ?>
                        <span style="display:inline-block;background:var(--color-green,#46462a);
                                     color:#fff;font-size:0.72rem;font-weight:700;
                                     padding:4px 12px;border-radius:999px">XEM NGAY</span>
                    </div>
                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php endif; ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
function selectRow(tr, price, name) {
    document.querySelectorAll('#priceTable tr').forEach(r => r.classList.remove('selected'));
    tr.classList.add('selected');
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>