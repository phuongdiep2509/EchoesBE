<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TaiKhoan;
use App\Models\KhachHang;
use App\Models\NhanVien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TaiKhoanController extends Controller
{
    // ─── Danh sách tất cả tài khoản ──────────────────
    public function index(Request $request)
    {
        $query = TaiKhoan::query();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('TenDangNhap', 'like', "%$search%")
                  ->orWhere('HoTen', 'like', "%$search%")
                  ->orWhere('Email', 'like', "%$search%")
                  ->orWhere('SoDienThoai', 'like', "%$search%");
            });
        }

        if ($vaiTro = $request->input('vai_tro')) {
            $query->where('VaiTro', $vaiTro);
        }

        if ($trangThai = $request->input('trang_thai')) {
            $query->where('TrangThai', $trangThai);
        }

        $danhSach = $query->orderBy('MaTaiKhoan', 'desc')->paginate(15)->withQueryString();

        return view('admin.tai-khoan.index', compact('danhSach'));
    }

    // ─── Form tạo tài khoản ───────────────────────────
    public function create()
    {
        return view('admin.tai-khoan.create');
    }

    // ─── Lưu tài khoản mới ───────────────────────────
    public function store(Request $request)
    {
        $request->validate([
            'TenDangNhap' => 'required|string|max:50|unique:TAI_KHOAN,TenDangNhap|regex:/^[a-zA-Z0-9_]+$/',
            'HoTen'       => 'required|string|max:255',
            'Email'       => 'required|email|max:255|unique:TAI_KHOAN,Email',
            'SoDienThoai' => 'nullable|string|max:15',
            'VaiTro'      => 'required|in:Admin,KhachHang,NhanVien',
            'MatKhau'     => 'required|string|min:8|max:32',
            // Trường riêng cho NhanVien
            'ChucVu'      => 'required_if:VaiTro,NhanVien|nullable|string|max:100',
            'NgayVaoLam'  => 'nullable|date',
        ], [
            'TenDangNhap.required' => 'Vui lòng nhập tên đăng nhập.',
            'TenDangNhap.unique'   => 'Tên đăng nhập đã tồn tại.',
            'TenDangNhap.regex'    => 'Chỉ chứa chữ cái, số và dấu gạch dưới.',
            'HoTen.required'       => 'Vui lòng nhập họ tên.',
            'Email.required'       => 'Vui lòng nhập email.',
            'Email.unique'         => 'Email đã tồn tại.',
            'MatKhau.required'     => 'Vui lòng nhập mật khẩu.',
            'VaiTro.required'      => 'Vui lòng chọn vai trò.',
            'ChucVu.required_if'   => 'Vui lòng nhập chức vụ cho nhân viên.',
        ]);

        $taiKhoan = TaiKhoan::create([
            'TenDangNhap' => trim($request->TenDangNhap),
            'MatKhau'     => Hash::make($request->MatKhau),
            'HoTen'       => trim($request->HoTen),
            'Email'       => strtolower(trim($request->Email)),
            'SoDienThoai' => $request->SoDienThoai,
            'VaiTro'      => $request->VaiTro,
            'TrangThai'   => 'HoatDong',
        ]);

        // Tạo bản ghi chi tiết theo vai trò
        if ($request->VaiTro === 'KhachHang') {
            KhachHang::create([
                'MaTaiKhoan' => $taiKhoan->MaTaiKhoan,
                'NgaySinh'   => $request->NgaySinh,
                'GioiTinh'   => $request->GioiTinh,
                'DiaChi'     => $request->DiaChi,
            ]);
        } elseif ($request->VaiTro === 'NhanVien') {
            NhanVien::create([
                'MaTaiKhoan' => $taiKhoan->MaTaiKhoan,
                'ChucVu'     => $request->ChucVu,
                'NgaySinh'   => $request->NgaySinh,
                'GioiTinh'   => $request->GioiTinh,
                'DiaChi'     => $request->DiaChi,
                'NgayVaoLam' => $request->NgayVaoLam,
            ]);
        }

        return redirect()->route('admin.tai-khoan.index')
                         ->with('success', 'Tạo tài khoản thành công.');
    }

    // ─── Chi tiết tài khoản ───────────────────────────
    public function show(TaiKhoan $taiKhoan)
    {
        $taiKhoan->load(['khachHang', 'nhanVien']);
        return view('admin.tai-khoan.show', compact('taiKhoan'));
    }

    // ─── Form chỉnh sửa ──────────────────────────────
    public function edit(TaiKhoan $taiKhoan)
    {
        $taiKhoan->load(['khachHang', 'nhanVien']);
        return view('admin.tai-khoan.edit', compact('taiKhoan'));
    }

    // ─── Cập nhật tài khoản ──────────────────────────
    public function update(Request $request, TaiKhoan $taiKhoan)
    {
        $request->validate([
            'HoTen'       => 'required|string|max:255',
            'Email'       => 'required|email|max:255|unique:TAI_KHOAN,Email,' . $taiKhoan->MaTaiKhoan . ',MaTaiKhoan',
            'SoDienThoai' => 'nullable|string|max:15',
            'ChucVu'      => 'required_if:VaiTro,NhanVien|nullable|string|max:100',
        ], [
            'HoTen.required'     => 'Vui lòng nhập họ tên.',
            'Email.required'     => 'Vui lòng nhập email.',
            'Email.unique'       => 'Email đã được sử dụng.',
            'ChucVu.required_if' => 'Vui lòng nhập chức vụ.',
        ]);

        $taiKhoan->update([
            'HoTen'       => trim($request->HoTen),
            'Email'       => strtolower(trim($request->Email)),
            'SoDienThoai' => $request->SoDienThoai,
        ]);

        // Đổi mật khẩu nếu có nhập
        if ($request->filled('MatKhau')) {
            $request->validate(['MatKhau' => 'min:8|max:32']);
            $taiKhoan->update(['MatKhau' => Hash::make($request->MatKhau)]);
        }

        // Cập nhật bảng chi tiết
        if ($taiKhoan->VaiTro === 'KhachHang') {
            $taiKhoan->khachHang()->updateOrCreate(
                ['MaTaiKhoan' => $taiKhoan->MaTaiKhoan],
                [
                    'NgaySinh' => $request->NgaySinh,
                    'GioiTinh' => $request->GioiTinh,
                    'DiaChi'   => $request->DiaChi,
                ]
            );
        } elseif ($taiKhoan->VaiTro === 'NhanVien') {
            $taiKhoan->nhanVien()->updateOrCreate(
                ['MaTaiKhoan' => $taiKhoan->MaTaiKhoan],
                [
                    'ChucVu'     => $request->ChucVu,
                    'NgaySinh'   => $request->NgaySinh,
                    'GioiTinh'   => $request->GioiTinh,
                    'DiaChi'     => $request->DiaChi,
                    'NgayVaoLam' => $request->NgayVaoLam,
                ]
            );
        }

        return redirect()->route('admin.tai-khoan.index')
                         ->with('success', 'Cập nhật tài khoản thành công.');
    }

    // ─── Khóa / Kích hoạt tài khoản ──────────────────
    public function toggleTrangThai(TaiKhoan $taiKhoan)
    {
        // Không cho khóa chính mình
        if ($taiKhoan->MaTaiKhoan === auth()->id()) {
            return back()->with('error', 'Không thể khóa tài khoản đang đăng nhập.');
        }

        $trangThaiMoi = $taiKhoan->TrangThai === 'HoatDong' ? 'NgungHoatDong' : 'HoatDong';
        $taiKhoan->update(['TrangThai' => $trangThaiMoi]);

        $msg = $trangThaiMoi === 'HoatDong' ? 'Tài khoản đã được kích hoạt.' : 'Tài khoản đã bị khóa.';
        return back()->with('success', $msg);
    }
}
