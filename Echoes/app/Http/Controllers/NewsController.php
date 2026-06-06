<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    // ─── PUBLIC ──────────────────────────────────────────

    public function index()
    {
        $articles = DB::table('tin_tuc_bai_viet as t')
            ->leftJoin('danh_muc_bai_viet as dm', 't.MaDanhMuc', '=', 'dm.MaDanhMuc')
            ->select([
                't.MaBaiViet   as id',
                't.TieuDe      as title',
                't.AnhDaiDien  as image',
                't.NgayDang    as published_at',
                'dm.TenDanhMuc as category',
            ])
            ->orderBy('t.NgayDang', 'desc')
            ->get();

        $featured = $articles->take(3);

        return view('pages.news', compact('articles', 'featured'));
    }

    public function show($id)
    {
        $article = DB::table('tin_tuc_bai_viet as t')
            ->leftJoin('danh_muc_bai_viet as dm', 't.MaDanhMuc', '=', 'dm.MaDanhMuc')
            ->where('t.MaBaiViet', $id)
            ->select([
                't.MaBaiViet    as id',
                't.TieuDe       as title',
                't.NoiDung      as content',
                't.AnhDaiDien   as image',
                't.NgayDang     as published_at',
                'dm.TenDanhMuc  as category',
            ])
            ->first();

        if (!$article) abort(404);

        $related = DB::table('tin_tuc_bai_viet')
            ->where('MaBaiViet', '!=', $id)
            ->orderBy('NgayDang', 'desc')
            ->take(3)
            ->select(['MaBaiViet as id', 'TieuDe as title', 'AnhDaiDien as image', 'NgayDang as published_at'])
            ->get();

        return view('pages.news-detail', compact('article', 'related'));
    }

    // ─── ADMIN ───────────────────────────────────────────

    public function adminIndex()
    {
        $articles = DB::table('tin_tuc_bai_viet as t')
            ->leftJoin('danh_muc_bai_viet as dm', 't.MaDanhMuc', '=', 'dm.MaDanhMuc')
            ->select([
                't.MaBaiViet   as id',
                't.TieuDe      as title',
                't.AnhDaiDien  as image',
                't.NgayDang    as published_at',
                'dm.TenDanhMuc as category',
            ])
            ->orderBy('t.NgayDang', 'desc')
            ->get();

        return view('admin.news.index', compact('articles'));
    }

    public function adminCreate()
    {
        $categories = DB::table('danh_muc_bai_viet')->select('MaDanhMuc', 'TenDanhMuc')->get();
        $events     = DB::table('su_kien')->select('MaSuKien', 'TenSuKien')->get();

        return view('admin.news.create', compact('categories', 'events'));
    }

    public function adminStore(Request $request)
    {
        $request->validate([
            'TieuDe'     => 'required|string|max:255',
            'NoiDung'    => 'required',
            'MaDanhMuc'  => 'required|integer',
            'MaNhanVien' => 'required|integer',
        ]);

        DB::table('tin_tuc_bai_viet')->insert([
            'MaNhanVien'       => $request->MaNhanVien,
            'TieuDe'           => $request->TieuDe,
            'NoiDung'          => $request->NoiDung,
            'AnhDaiDien'       => $request->AnhDaiDien,
            'NgayDang'         => now(),
            'MaDanhMuc'        => $request->MaDanhMuc,
            'MaSuKienLienQuan' => $request->MaSuKienLienQuan ?: null,
        ]);

        return redirect()->route('admin.news.index')->with('success', 'Đã thêm bài viết.');
    }

    public function adminEdit($id)
    {
        $article    = DB::table('tin_tuc_bai_viet')->where('MaBaiViet', $id)->first();
        if (!$article) abort(404);

        $categories = DB::table('danh_muc_bai_viet')->select('MaDanhMuc', 'TenDanhMuc')->get();
        $events     = DB::table('su_kien')->select('MaSuKien', 'TenSuKien')->get();

        return view('admin.news.edit', compact('article', 'categories', 'events'));
    }

    public function adminUpdate(Request $request, $id)
    {
        DB::table('tin_tuc_bai_viet')->where('MaBaiViet', $id)->update([
            'TieuDe'           => $request->TieuDe,
            'NoiDung'          => $request->NoiDung,
            'AnhDaiDien'       => $request->AnhDaiDien,
            'MaDanhMuc'        => $request->MaDanhMuc,
            'MaSuKienLienQuan' => $request->MaSuKienLienQuan ?: null,
        ]);

        return redirect()->route('admin.news.index')->with('success', 'Đã cập nhật bài viết.');
    }

    public function adminDestroy($id)
    {
        DB::table('tin_tuc_bai_viet')->where('MaBaiViet', $id)->delete();
        return redirect()->route('admin.news.index')->with('success', 'Đã xóa bài viết.');
    }
}
