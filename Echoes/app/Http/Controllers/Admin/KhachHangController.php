<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KhachHang;
use App\Models\TaiKhoan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class KhachHangController extends Controller
{
    public function index(Request $request)
    {
        $query = KhachHang::with('taiKhoan');

        if ($search = $request->input('search')) {
            $query->whereHas('taiKhoan', function ($q) use ($search) {
                $q->where('HoTen', 'like', "%$search%")
                  ->orWhere('Email', 'like', "%$search%")
                  ->orWhere('TenDangNhap', 'like', "%$search%")
                  ->orWhere('SoDienThoai', 'like', "%$search%");
            });
        }

        if ($trangThai = $request->input('trang_thai')) {
            $query->whereHas('taiKhoan', fn($q) => $q->where('TrangThai', $trangThai));
        }

        if ($gioiTinh = $request->input('gioi_tinh')) {
            $query->where('GioiTinh', $gioiTinh);
        }

        $danhSach = $query->orderBy('MaKhachHang', 'desc')->paginate(15)->withQueryString();

        return view('admin.khach-hang.index', compact('danhSach'));
    }

    public function show(KhachHang $khachHang)
    {
        $khachHang->load('taiKhoan');
        return view('admin.khach-hang.show', compact('khachHang'));
    }

    public function create()
    {
        return view('admin.khach-hang.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'TenDangNhap' => 'required|string|max:50|unique:TAI_KHOAN,TenDangNhap|regex:/^[a-zA-Z0-9_]+$/',
            'HoTen'       => 'required|string|max:255',
            'Email'       => 'required|email|max:255|unique:TAI_KHOAN,Email',
            'SoDienThoai' => 'nullable|string|max:15',
            'MatKhau'     => 'required|string|min:8|max:32',
            'NgaySinh'    => 'nullable|date|before:today',
            'GioiTinh'    => 'nullable|in:Nam,Nu,Khac',
            'DiaChi'      => 'nullable|string|max:500',
        ], [
            'TenDangNhap.required' => 'Vui lòng nhập tên đăng nhập.',
            'TenDangNhap.unique'   => 'Tên đăng nhập đã tồn tại.',
            'TenDangNhap.regex'    => 'Chỉ chứa chữ cái, số và dấu gạch dưới.',
            'HoTen.required'       => 'Vui lòng nhập họ tên.',
            'Email.required'       => 'Vui lòng nhập email.',
            'Email.unique'         => 'Email đã tồn tại.',
            'MatKhau.required'     => 'Vui lòng nhập mật khẩu.',
            'NgaySinh.before'      => 'Ngày sinh phải trước hôm nay.',
        ]);

        $taiKhoan = TaiKhoan::create([
            'TenDangNhap' => trim($request->TenDangNhap),
            'MatKhau'     => Hash::make($request->MatKhau),
            'HoTen'       => trim($request->HoTen),
            'Email'       => strtolower(trim($request->Email)),
            'SoDienThoai' => $request->SoDienThoai,
            'VaiTro'      => 'KhachHang',
            'TrangThai'   => 'HoatDong',
        ]);

        KhachHang::create([
            'MaTaiKhoan' => $taiKhoan->MaTaiKhoan,
            'NgaySinh'   => $request->NgaySinh,
            'GioiTinh'   => $request->GioiTinh,
            'DiaChi'     => $request->DiaChi,
        ]);

        return redirect()->route('admin.khach-hang.index')
                         ->with('success', 'Thêm khách hàng thành công.');
    }

    public function edit(KhachHang $khachHang)
    {
        $khachHang->load('taiKhoan');
        return view('admin.khach-hang.edit', compact('khachHang'));
    }

    public function update(Request $request, KhachHang $khachHang)
    {
        $tk = $khachHang->taiKhoan;

        $request->validate([
            'HoTen'       => 'required|string|max:255',
            'Email'       => 'required|email|max:255|unique:TAI_KHOAN,Email,' . $tk->MaTaiKhoan . ',MaTaiKhoan',
            'SoDienThoai' => 'nullable|string|max:15',
            'NgaySinh'    => 'nullable|date|before:today',
            'GioiTinh'    => 'nullable|in:Nam,Nu,Khac',
            'DiaChi'      => 'nullable|string|max:500',
        ], [
            'HoTen.required'  => 'Vui lòng nhập họ tên.',
            'Email.required'  => 'Vui lòng nhập email.',
            'Email.unique'    => 'Email đã được sử dụng.',
            'NgaySinh.before' => 'Ngày sinh phải trước hôm nay.',
        ]);

        $tk->update([
            'HoTen'       => trim($request->HoTen),
            'Email'       => strtolower(trim($request->Email)),
            'SoDienThoai' => $request->SoDienThoai,
        ]);

        if ($request->filled('MatKhau')) {
            $request->validate(['MatKhau' => 'min:8|max:32']);
            $tk->update(['MatKhau' => Hash::make($request->MatKhau)]);
        }

        $khachHang->update([
            'NgaySinh' => $request->NgaySinh,
            'GioiTinh' => $request->GioiTinh,
            'DiaChi'   => $request->DiaChi,
        ]);

        return redirect()->route('admin.khach-hang.index')
                         ->with('success', 'Cập nhật khách hàng thành công.');
    }

    public function toggleTrangThai(KhachHang $khachHang)
    {
        $tk = $khachHang->taiKhoan;
        $moiTrangThai = $tk->TrangThai === 'HoatDong' ? 'NgungHoatDong' : 'HoatDong';
        $tk->update(['TrangThai' => $moiTrangThai]);

        $msg = $moiTrangThai === 'HoatDong' ? 'Tài khoản đã được kích hoạt.' : 'Tài khoản đã bị khóa.';
        return back()->with('success', $msg);
    }
}
