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

    public function addToCart(Request $request, $id)
    {
        $data = $request->validate([
            'SoLuong' => ['required', 'integer', 'min:1'],
        ]);

        $product = DB::table('merchandise')
            ->where('MaMerch', $id)
            ->where('TrangThai', 'DangBan')
            ->first();

        if (!$product) {
            return back()->with('error', 'San pham khong ton tai hoac da ngung ban.');
        }

        $quantity = (int) $data['SoLuong'];
        $cart = session('merchandise_cart', []);
        $currentQuantity = (int) ($cart[$product->MaMerch]['SoLuong'] ?? 0);

        if ($product->SoLuongTon !== null && $currentQuantity + $quantity > (int) $product->SoLuongTon) {
            return back()->with('error', 'So luong san pham trong kho khong du.');
        }

        $cart[$product->MaMerch] = [
            'MaMerch' => (int) $product->MaMerch,
            'SoLuong' => $currentQuantity + $quantity,
        ];

        session(['merchandise_cart' => $cart]);

        return redirect()->route('cart')->with('success', 'Da them merchandise vao gio hang.');
    }

    public function removeFromCart(Request $request, $id)
    {
        $cart = session('merchandise_cart', []);
        unset($cart[(int) $id]);
        session(['merchandise_cart' => $cart]);

        return redirect()->route('cart')->with('success', 'Đã xóa sản phẩm khỏi giỏ hàng.');
    }

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

    public function adminToggleStatus($id)
    {
        $product = DB::table('merchandise')->where('MaMerch', $id)->first();
        if (!$product) {
            abort(404);
        }

        $newStatus = $product->TrangThai === 'DangBan' ? 'NgungBan' : 'DangBan';
        DB::table('merchandise')->where('MaMerch', $id)->update(['TrangThai' => $newStatus]);

        $message = $newStatus === 'NgungBan'
            ? 'Đã ẩn sản phẩm.'
            : 'Đã hiển thị lại sản phẩm.';

        return redirect()->route('admin.merchandise.index')->with('success', $message);
    }

    public function adminDestroy($id)
    {
        DB::table('merchandise')->where('MaMerch', $id)->delete();
        return redirect()->route('admin.merchandise.index')->with('success', 'Đã xóa sản phẩm.');
    }
}
