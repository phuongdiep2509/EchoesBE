<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class MerchandiseController extends Controller
{
    // ─── PUBLIC ──────────────────────────────────────────

    public function index()
    {
        $products = DB::table('merchandise')
            ->where('TrangThai', 'DangBan')
            ->orderBy('MaMerch', 'asc')
            ->get();

        return view('pages.merchandise', compact('products'));
    }

    public function show($id)
    {
        $product = DB::table('merchandise')->where('MaMerch', $id)->first();
        if (!$product) abort(404);

        $related = DB::table('merchandise')
            ->where('TrangThai', 'DangBan')
            ->where('MaMerch', '!=', $id)
            ->take(4)
            ->get();

        return view('pages.merchandise-detail', compact('product', 'related'));
    }

    // ─── ADMIN ───────────────────────────────────────────

    public function adminIndex()
    {
        $products = DB::table('merchandise')
            ->orderBy('MaMerch', 'desc')
            ->get();

        return view('admin.merchandise.index', compact('products'));
    }

    public function adminCreate()
    {
        return view('admin.merchandise.create');
    }

    public function adminStore(Request $request)
    {
        $request->validate([
            'TenMerch'   => 'required|string|max:255',
            'GiaBan'     => 'required|numeric|min:0',
            'TrangThai'  => 'required|in:DangBan,NgungBan',
            'AnhSanPham' => 'required|string',
        ]);

        DB::table('merchandise')->insert([
            'TenMerch'        => $request->TenMerch,
            'MoTa'            => $request->MoTa,
            'GiaBan'          => $request->GiaBan,
            'SoLuongTon'      => $request->SoLuongTon ?? 0,
            'AnhSanPham'      => $request->AnhSanPham,
            'TrangThai'       => $request->TrangThai,
            'ChinhSachDoiTra' => $request->ChinhSachDoiTra,
            'HuongDanBaoQuan' => $request->HuongDanBaoQuan,
        ]);

        return redirect()->route('admin.merchandise.index')->with('success', 'Đã thêm sản phẩm.');
    }

    public function adminEdit($id)
    {
        $product = DB::table('merchandise')->where('MaMerch', $id)->first();
        if (!$product) abort(404);

        return view('admin.merchandise.edit', compact('product'));
    }

    public function adminUpdate(Request $request, $id)
    {
        DB::table('merchandise')->where('MaMerch', $id)->update([
            'TenMerch'        => $request->TenMerch,
            'MoTa'            => $request->MoTa,
            'GiaBan'          => $request->GiaBan,
            'SoLuongTon'      => $request->SoLuongTon ?? 0,
            'AnhSanPham'      => $request->AnhSanPham,
            'TrangThai'       => $request->TrangThai,
            'ChinhSachDoiTra' => $request->ChinhSachDoiTra,
            'HuongDanBaoQuan' => $request->HuongDanBaoQuan,
        ]);

        return redirect()->route('admin.merchandise.index')->with('success', 'Đã cập nhật sản phẩm.');
    }

    public function adminDestroy($id)
    {
        DB::table('merchandise')->where('MaMerch', $id)->delete();
        return redirect()->route('admin.merchandise.index')->with('success', 'Đã xóa sản phẩm.');
    }
}
