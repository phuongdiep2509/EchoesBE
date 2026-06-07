<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NhanVien;
use App\Models\TaiKhoan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class NhanVienController extends Controller
{
    public function index(Request $request)
    {
        $query = NhanVien::with('taiKhoan');

        if ($search = $request->input('search')) {
            $query->where('ChucVu', 'like', "%$search%")
                  ->orWhereHas('taiKhoan', function ($q) use ($search) {
                      $q->where('HoTen', 'like', "%$search%")
                        ->orWhere('Email', 'like', "%$search%")
                        ->orWhere('TenDangNhap', 'like', "%$search%");
                  });
        }

        if ($trangThai = $request->input('trang_thai')) {
            $query->whereHas('taiKhoan', fn($q) => $q->where('TrangThai', $trangThai));
        }

        $danhSach = $query->orderBy('MaNhanVien', 'desc')->paginate(15)->withQueryString();

        return view('admin.nhan-vien.index', compact('danhSach'));
    }

    public function show(NhanVien $nhanVien)
    {
        $nhanVien->load('taiKhoan');
        return view('admin.nhan-vien.show', compact('nhanVien'));
    }

    public function create()
    {
        return view('admin.nhan-vien.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'TenDangNhap' => 'required|string|max:50|unique:TAI_KHOAN,TenDangNhap|regex:/^[a-zA-Z0-9_]+$/',
            'HoTen'       => 'required|string|max:255',
            'Email'       => 'required|email|max:255|unique:TAI_KHOAN,Email',
            'SoDienThoai' => 'nullable|string|max:15',
            'MatKhau'     => 'required|string|min:8|max:32',
            'ChucVu'      => 'required|string|max:100',
            'NgaySinh'    => 'nullable|date|before:today',
            'GioiTinh'    => 'nullable|in:Nam,Nu,Khac',
            'DiaChi'      => 'nullable|string|max:500',
            'NgayVaoLam'  => 'nullable|date',
        ], [
            'TenDangNhap.required' => 'Vui lòng nhập tên đăng nhập.',
            'TenDangNhap.unique'   => 'Tên đăng nhập đã tồn tại.',
            'TenDangNhap.regex'    => 'Chỉ chứa chữ cái, số và dấu gạch dưới.',
            'HoTen.required'       => 'Vui lòng nhập họ tên.',
            'Email.required'       => 'Vui lòng nhập email.',
            'Email.unique'         => 'Email đã tồn tại.',
            'MatKhau.required'     => 'Vui lòng nhập mật khẩu.',
            'ChucVu.required'      => 'Vui lòng nhập chức vụ.',
            'NgaySinh.before'      => 'Ngày sinh phải trước hôm nay.',
        ]);

        $taiKhoan = TaiKhoan::create([
            'TenDangNhap' => trim($request->TenDangNhap),
            'MatKhau'     => Hash::make($request->MatKhau),
            'HoTen'       => trim($request->HoTen),
            'Email'       => strtolower(trim($request->Email)),
            'SoDienThoai' => $request->SoDienThoai,
            'VaiTro'      => 'NhanVien',
            'TrangThai'   => 'HoatDong',
        ]);

        NhanVien::create([
            'MaTaiKhoan' => $taiKhoan->MaTaiKhoan,
            'ChucVu'     => trim($request->ChucVu),
            'NgaySinh'   => $request->NgaySinh,
            'GioiTinh'   => $request->GioiTinh,
            'DiaChi'     => $request->DiaChi,
            'NgayVaoLam' => $request->NgayVaoLam,
        ]);

        return redirect()->route('admin.nhan-vien.index')
                         ->with('success', 'Thêm nhân viên thành công.');
    }

    public function edit(NhanVien $nhanVien)
    {
        $nhanVien->load('taiKhoan');
        return view('admin.nhan-vien.edit', compact('nhanVien'));
    }

    public function update(Request $request, NhanVien $nhanVien)
    {
        $tk = $nhanVien->taiKhoan;

        $request->validate([
            'HoTen'       => 'required|string|max:255',
            'Email'       => 'required|email|max:255|unique:TAI_KHOAN,Email,' . $tk->MaTaiKhoan . ',MaTaiKhoan',
            'SoDienThoai' => 'nullable|string|max:15',
            'ChucVu'      => 'required|string|max:100',
            'NgaySinh'    => 'nullable|date|before:today',
            'GioiTinh'    => 'nullable|in:Nam,Nu,Khac',
            'DiaChi'      => 'nullable|string|max:500',
            'NgayVaoLam'  => 'nullable|date',
        ], [
            'HoTen.required'  => 'Vui lòng nhập họ tên.',
            'Email.required'  => 'Vui lòng nhập email.',
            'Email.unique'    => 'Email đã được sử dụng.',
            'ChucVu.required' => 'Vui lòng nhập chức vụ.',
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

        $nhanVien->update([
            'ChucVu'     => trim($request->ChucVu),
            'NgaySinh'   => $request->NgaySinh,
            'GioiTinh'   => $request->GioiTinh,
            'DiaChi'     => $request->DiaChi,
            'NgayVaoLam' => $request->NgayVaoLam,
        ]);

        return redirect()->route('admin.nhan-vien.index')
                         ->with('success', 'Cập nhật nhân viên thành công.');
    }

    public function toggleTrangThai(NhanVien $nhanVien)
    {
        $tk = $nhanVien->taiKhoan;

        if ($tk->MaTaiKhoan === auth()->id()) {
            return back()->with('error', 'Không thể khóa tài khoản đang đăng nhập.');
        }

        $moiTrangThai = $tk->TrangThai === 'HoatDong' ? 'NgungHoatDong' : 'HoatDong';
        $tk->update(['TrangThai' => $moiTrangThai]);

        $msg = $moiTrangThai === 'HoatDong' ? 'Tài khoản đã được kích hoạt.' : 'Tài khoản đã bị khóa.';
        return back()->with('success', $msg);
    }
}
