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