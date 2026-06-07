-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th6 06, 2026 lúc 10:47 PM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `qldatve`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `ban_to_chuc`
--

CREATE TABLE `ban_to_chuc` (
  `MaBTC` int(11) NOT NULL,
  `TenToChuc` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `MoTa` text DEFAULT NULL,
  `Logo` varchar(255) DEFAULT NULL,
  `Email` varchar(255) DEFAULT NULL,
  `SoDienThoai` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chi_tiet_giu_cho`
--

CREATE TABLE `chi_tiet_giu_cho` (
  `MaGiuCho` int(11) NOT NULL,
  `MaHangVe` int(11) NOT NULL,
  `SoLuong` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `ct_don_hang_merchandise`
--

CREATE TABLE `ct_don_hang_merchandise` (
  `MaDonHang` int(11) NOT NULL,
  `MaMerch` int(11) NOT NULL,
  `SoLuong` int(11) NOT NULL,
  `DonGia` decimal(12,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `danh_muc_bai_viet`
--

CREATE TABLE `danh_muc_bai_viet` (
  `MaDanhMuc` int(11) NOT NULL,
  `TenDanhMuc` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `dia_diem_to_chuc`
--

CREATE TABLE `dia_diem_to_chuc` (
  `MaDiaDiem` int(11) NOT NULL,
  `TenDiaDiem` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `DiaChiChiTiet` varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `ThanhPho` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `SucChuaToiDa` int(11) NOT NULL,
  `MoTa` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `don_hang`
--

CREATE TABLE `don_hang` (
  `MaDonHang` int(11) NOT NULL,
  `MaKhachHang` int(11) NOT NULL,
  `NgayDat` datetime NOT NULL,
  `TongTien` decimal(12,2) NOT NULL,
  `TrangThai` enum('ChoThanhToan','DaThanhToan','DaHuy') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `ghe_ngoi`
--

CREATE TABLE `ghe_ngoi` (
  `MaGhe` int(11) NOT NULL,
  `MaKhuVuc` int(11) NOT NULL,
  `HangGhe` varchar(10) NOT NULL,
  `SoGhe` varchar(10) NOT NULL,
  `TrangThai` enum('Trong','DangGiu','DaBan') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `giu_cho_ve`
--

CREATE TABLE `giu_cho_ve` (
  `MaGiuCho` int(11) NOT NULL,
  `MaKhachHang` int(11) NOT NULL,
  `ThoiGianBatDau` datetime NOT NULL,
  `ThoiGianHetHan` datetime NOT NULL,
  `TrangThai` enum('DangGiuCho','DaHetHan','DaThanhToan','DaHuy') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hang_ve`
--

CREATE TABLE `hang_ve` (
  `MaHangVe` int(11) NOT NULL,
  `MaKhuVuc` int(11) NOT NULL,
  `TenHangVe` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `GiaVe` decimal(12,2) NOT NULL,
  `SoLuongMoBan` int(11) NOT NULL,
  `SoLuongDaBan` int(11) DEFAULT 0,
  `ThoiGianMoBan` datetime NOT NULL,
  `ThoiGianKetThucBan` datetime NOT NULL,
  `QuyenLoi` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `khach_hang`
--

CREATE TABLE `khach_hang` (
  `MaKhachHang` int(11) NOT NULL,
  `MaTaiKhoan` int(11) NOT NULL,
  `NgaySinh` date DEFAULT NULL,
  `GioiTinh` enum('Nam','Nu','Khac') DEFAULT NULL,
  `DiaChi` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `khach_hang`
--

INSERT INTO `khach_hang` (`MaKhachHang`, `MaTaiKhoan`, `NgaySinh`, `GioiTinh`, `DiaChi`) VALUES
(1, 4, '2000-03-15', 'Nam', 'Quận Cầu Giấy, Hà Nội'),
(2, 5, '2001-11-20', 'Nu', 'Quận Hải Châu, Đà Nẵng'),
(3, 6, '1999-07-08', 'Nam', 'TP Thủ Đức, TP Hồ Chí Minh'),
(4, 7, '2005-04-22', 'Nu', 'Long Biên, Hà Nội');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `khu_vuc_su_kien`
--

CREATE TABLE `khu_vuc_su_kien` (
  `MaKhuVuc` int(11) NOT NULL,
  `MaSuKien` int(11) NOT NULL,
  `TenKhuVuc` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `SucChua` int(11) NOT NULL,
  `MoTa` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `loai_su_kien`
--

CREATE TABLE `loai_su_kien` (
  `MaLoaiSuKien` int(11) NOT NULL,
  `TenLoai` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `merchandise`
--

CREATE TABLE `merchandise` (
  `MaMerch` int(11) NOT NULL,
  `TenMerch` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `MoTa` text DEFAULT NULL,
  `GiaBan` decimal(12,2) NOT NULL,
  `SoLuongTon` int(11) DEFAULT 0,
  `AnhSanPham` varchar(255) NOT NULL,
  `TrangThai` enum('DangBan','NgungBan') NOT NULL,
  `ChinhSachDoiTra` text DEFAULT NULL,
  `HuongDanBaoQuan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2026_06_07_000001_add_google_reset_columns_to_tai_khoan', 1),
(2, '2026_06_07_000002_add_remember_token_to_tai_khoan', 2);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `nghe_si`
--

CREATE TABLE `nghe_si` (
  `MaNgheSi` int(11) NOT NULL,
  `TenNgheSi` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `NgheDanh` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `QuocGia` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `TieuSu` text DEFAULT NULL,
  `AnhDaiDien` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `nhan_vien`
--

CREATE TABLE `nhan_vien` (
  `MaNhanVien` int(11) NOT NULL,
  `MaTaiKhoan` int(11) NOT NULL,
  `ChucVu` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `NgaySinh` date DEFAULT NULL,
  `GioiTinh` enum('Nam','Nu','Khac') DEFAULT NULL,
  `DiaChi` varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `NgayVaoLam` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `nhan_vien`
--

INSERT INTO `nhan_vien` (`MaNhanVien`, `MaTaiKhoan`, `ChucVu`, `NgaySinh`, `GioiTinh`, `DiaChi`, `NgayVaoLam`) VALUES
(1, 2, 'Nhân viên nội dung', '1998-05-12', 'Nu', 'Hà Nội', '2024-01-10'),
(2, 3, 'Nhân viên sự kiện', '1996-08-20', 'Nam', 'TP Hồ Chí Minh', '2024-03-15');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `nhan_vien_su_kien`
--

CREATE TABLE `nhan_vien_su_kien` (
  `MaNhanVien` int(11) NOT NULL,
  `MaSuKien` int(11) NOT NULL,
  `VaiTro` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `su_kien`
--

CREATE TABLE `su_kien` (
  `MaSuKien` int(11) NOT NULL,
  `MaBTC` int(11) NOT NULL,
  `MaDiaDiem` int(11) NOT NULL,
  `MaLoaiSuKien` int(11) NOT NULL,
  `TenSuKien` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `AnhBia` varchar(255) NOT NULL,
  `MoTa` text DEFAULT NULL,
  `DiemNoiBat` text DEFAULT NULL,
  `DieuKienVaDieuKhoan` text DEFAULT NULL,
  `ThoiGianBatDau` datetime NOT NULL,
  `ThoiGianKetThuc` datetime NOT NULL,
  `TrangThai` enum('SapDienRa','DangMoBan','DaKetThuc','DaHuy') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `su_kien_tl_am_nhac`
--

CREATE TABLE `su_kien_tl_am_nhac` (
  `MaSuKien` int(11) NOT NULL,
  `MaTheLoai` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tai_khoan`
--

CREATE TABLE `tai_khoan` (
  `MaTaiKhoan` int(11) NOT NULL,
  `TenDangNhap` varchar(50) NOT NULL,
  `MatKhau` varchar(255) NOT NULL,
  `HoTen` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `Email` varchar(255) NOT NULL,
  `SoDienThoai` varchar(15) DEFAULT NULL,
  `GoogleId` varchar(100) DEFAULT NULL,
  `ResetToken` varchar(64) DEFAULT NULL,
  `ResetTokenExpiry` datetime DEFAULT NULL,
  `VaiTro` enum('Admin','KhachHang','NhanVien') NOT NULL,
  `TrangThai` enum('HoatDong','NgungHoatDong') NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `tai_khoan`
--

INSERT INTO `tai_khoan` (`MaTaiKhoan`, `TenDangNhap`, `MatKhau`, `HoTen`, `Email`, `SoDienThoai`, `GoogleId`, `ResetToken`, `ResetTokenExpiry`, `VaiTro`, `TrangThai`, `remember_token`) VALUES
(1, 'minhduc', '$2y$12$l9N2wG9kQEbUhfQZODKycuku.MXgWuSmZkxApUDfkE/6RU9H2t2Zu', 'Nguyễn Minh Đức', 'minhduc@gmail.com', '0901000001', NULL, NULL, NULL, 'Admin', 'HoatDong', NULL),
(2, 'thilan', '$2y$10$m2DkOBDwW/6uNUEWqccRr.BJur7xBDIKEhNGwp.rEasPVviHZszxO', 'Trần Thị Lan', 'lan.nv@gmail.com', '0901000002', NULL, NULL, NULL, 'NhanVien', 'HoatDong', NULL),
(3, 'minhhoang', '$2y$10$U644DyZsVMv3fpavseLevOrbCc789tvjkoEHr3D/KspG1XW.sGS.i', 'Lê Minh Hoàng', 'hoang.nv@gmail.com', '0901000003', NULL, NULL, NULL, 'NhanVien', 'HoatDong', NULL),
(4, 'quocbao', '$2y$10$eYvTbYw0VhloqxgK7Aa89uXUxRNHDdQ8hfRFzGqnodLyEsoGnNCKK', 'Phạm Quốc Bảo', 'bao@gmail.com', '0901000004', NULL, NULL, NULL, 'KhachHang', 'HoatDong', NULL),
(5, 'thimai', '$2y$10$A0UYSpppSB3biNxF7/grAeA5D5T8DEMrnkWLHgsZqKD8dvnGgSF5e', 'Nguyễn Thị Mai', 'mai@gmail.com', '0901000005', NULL, NULL, NULL, 'KhachHang', 'HoatDong', NULL),
(6, 'anhtuan', '$2y$12$RrRoGwUhcb5/2S39l6CQWOtlsI6WnW2WxnkmY/ral8OBlbGeeGr/a', 'Trần Anh Tuấn', 'tuan@gmail.com', '0901000006', NULL, NULL, NULL, 'KhachHang', 'HoatDong', NULL),
(7, 'PhamHue', '$2y$12$OIDW.gUUzNOlWU56vL5wX.T7aM0JNZlJSxHPqK4b2tfANpEAvn52q', 'Phạm Thị Thu Huế', 'phamhue220405@gmail.com', '0949574463', '105928572285458650243', NULL, NULL, 'KhachHang', 'HoatDong', 'rwXzXMly3tBLfh3fmEU3G9EhS3KauHJWWRvpsRFYJaQNMuaF17gSX5gmMPSB');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tham_gia_bieu_dien`
--

CREATE TABLE `tham_gia_bieu_dien` (
  `MaSuKien` int(11) NOT NULL,
  `MaNgheSi` int(11) NOT NULL,
  `ThuTuBieuDien` int(11) NOT NULL,
  `ThoiGianBieuDien` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `thanh_toan`
--

CREATE TABLE `thanh_toan` (
  `MaThanhToan` int(11) NOT NULL,
  `MaDonHang` int(11) NOT NULL,
  `PhuongThucThanhToan` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `SoTien` decimal(12,2) NOT NULL,
  `ThoiGianThanhToan` datetime DEFAULT NULL,
  `MaGiaoDich` varchar(100) DEFAULT NULL,
  `TrangThai` enum('ChoThanhToan','ThanhCong','ThatBai') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `the_loai_am_nhac`
--

CREATE TABLE `the_loai_am_nhac` (
  `MaTheLoai` int(11) NOT NULL,
  `TenTheLoai` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tin_tuc_bai_viet`
--

CREATE TABLE `tin_tuc_bai_viet` (
  `MaBaiViet` int(11) NOT NULL,
  `MaNhanVien` int(11) NOT NULL,
  `TieuDe` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `NoiDung` longtext NOT NULL,
  `AnhDaiDien` varchar(255) DEFAULT NULL,
  `NgayDang` datetime NOT NULL,
  `MaDanhMuc` int(11) NOT NULL,
  `MaSuKienLienQuan` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `ve`
--

CREATE TABLE `ve` (
  `MaVe` int(11) NOT NULL,
  `MaDonHang` int(11) NOT NULL,
  `MaHangVe` int(11) NOT NULL,
  `MaGhe` int(11) DEFAULT NULL,
  `MaSuKien` int(11) NOT NULL,
  `MaQR` varchar(255) NOT NULL,
  `MaVeDienTu` varchar(100) NOT NULL,
  `TrangThai` enum('ChoSuDung','DaSuDung','DaHuy') NOT NULL,
  `ThoiGianCheckIn` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `ve_tang`
--

CREATE TABLE `ve_tang` (
  `MaVeTang` int(11) NOT NULL,
  `MaVe` int(11) NOT NULL,
  `MaTaiKhoanNguoiTang` int(11) NOT NULL,
  `TenNguoiNhan` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `EmailNguoiNhan` varchar(255) NOT NULL,
  `SdtNguoiNhan` varchar(15) DEFAULT NULL,
  `LoaiThiep` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `LoiChuc` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `ban_to_chuc`
--
ALTER TABLE `ban_to_chuc`
  ADD PRIMARY KEY (`MaBTC`);

--
-- Chỉ mục cho bảng `chi_tiet_giu_cho`
--
ALTER TABLE `chi_tiet_giu_cho`
  ADD PRIMARY KEY (`MaGiuCho`,`MaHangVe`),
  ADD KEY `MaHangVe` (`MaHangVe`);

--
-- Chỉ mục cho bảng `ct_don_hang_merchandise`
--
ALTER TABLE `ct_don_hang_merchandise`
  ADD PRIMARY KEY (`MaDonHang`,`MaMerch`),
  ADD KEY `MaMerch` (`MaMerch`);

--
-- Chỉ mục cho bảng `danh_muc_bai_viet`
--
ALTER TABLE `danh_muc_bai_viet`
  ADD PRIMARY KEY (`MaDanhMuc`),
  ADD UNIQUE KEY `TenDanhMuc` (`TenDanhMuc`);

--
-- Chỉ mục cho bảng `dia_diem_to_chuc`
--
ALTER TABLE `dia_diem_to_chuc`
  ADD PRIMARY KEY (`MaDiaDiem`);

--
-- Chỉ mục cho bảng `don_hang`
--
ALTER TABLE `don_hang`
  ADD PRIMARY KEY (`MaDonHang`),
  ADD KEY `MaKhachHang` (`MaKhachHang`);

--
-- Chỉ mục cho bảng `ghe_ngoi`
--
ALTER TABLE `ghe_ngoi`
  ADD PRIMARY KEY (`MaGhe`),
  ADD KEY `MaKhuVuc` (`MaKhuVuc`);

--
-- Chỉ mục cho bảng `giu_cho_ve`
--
ALTER TABLE `giu_cho_ve`
  ADD PRIMARY KEY (`MaGiuCho`),
  ADD KEY `MaKhachHang` (`MaKhachHang`);

--
-- Chỉ mục cho bảng `hang_ve`
--
ALTER TABLE `hang_ve`
  ADD PRIMARY KEY (`MaHangVe`),
  ADD KEY `MaKhuVuc` (`MaKhuVuc`);

--
-- Chỉ mục cho bảng `khach_hang`
--
ALTER TABLE `khach_hang`
  ADD PRIMARY KEY (`MaKhachHang`),
  ADD UNIQUE KEY `MaTaiKhoan` (`MaTaiKhoan`);

--
-- Chỉ mục cho bảng `khu_vuc_su_kien`
--
ALTER TABLE `khu_vuc_su_kien`
  ADD PRIMARY KEY (`MaKhuVuc`),
  ADD KEY `MaSuKien` (`MaSuKien`);

--
-- Chỉ mục cho bảng `loai_su_kien`
--
ALTER TABLE `loai_su_kien`
  ADD PRIMARY KEY (`MaLoaiSuKien`),
  ADD UNIQUE KEY `TenLoai` (`TenLoai`);

--
-- Chỉ mục cho bảng `merchandise`
--
ALTER TABLE `merchandise`
  ADD PRIMARY KEY (`MaMerch`);

--
-- Chỉ mục cho bảng `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `nghe_si`
--
ALTER TABLE `nghe_si`
  ADD PRIMARY KEY (`MaNgheSi`);

--
-- Chỉ mục cho bảng `nhan_vien`
--
ALTER TABLE `nhan_vien`
  ADD PRIMARY KEY (`MaNhanVien`),
  ADD UNIQUE KEY `MaTaiKhoan` (`MaTaiKhoan`);

--
-- Chỉ mục cho bảng `nhan_vien_su_kien`
--
ALTER TABLE `nhan_vien_su_kien`
  ADD PRIMARY KEY (`MaNhanVien`,`MaSuKien`),
  ADD KEY `MaSuKien` (`MaSuKien`);

--
-- Chỉ mục cho bảng `su_kien`
--
ALTER TABLE `su_kien`
  ADD PRIMARY KEY (`MaSuKien`),
  ADD KEY `MaBTC` (`MaBTC`),
  ADD KEY `MaDiaDiem` (`MaDiaDiem`),
  ADD KEY `MaLoaiSuKien` (`MaLoaiSuKien`);

--
-- Chỉ mục cho bảng `su_kien_tl_am_nhac`
--
ALTER TABLE `su_kien_tl_am_nhac`
  ADD PRIMARY KEY (`MaSuKien`,`MaTheLoai`),
  ADD KEY `MaTheLoai` (`MaTheLoai`);

--
-- Chỉ mục cho bảng `tai_khoan`
--
ALTER TABLE `tai_khoan`
  ADD PRIMARY KEY (`MaTaiKhoan`),
  ADD UNIQUE KEY `TenDangNhap` (`TenDangNhap`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- Chỉ mục cho bảng `tham_gia_bieu_dien`
--
ALTER TABLE `tham_gia_bieu_dien`
  ADD PRIMARY KEY (`MaSuKien`,`MaNgheSi`),
  ADD KEY `MaNgheSi` (`MaNgheSi`);

--
-- Chỉ mục cho bảng `thanh_toan`
--
ALTER TABLE `thanh_toan`
  ADD PRIMARY KEY (`MaThanhToan`),
  ADD UNIQUE KEY `MaGiaoDich` (`MaGiaoDich`),
  ADD KEY `MaDonHang` (`MaDonHang`);

--
-- Chỉ mục cho bảng `the_loai_am_nhac`
--
ALTER TABLE `the_loai_am_nhac`
  ADD PRIMARY KEY (`MaTheLoai`),
  ADD UNIQUE KEY `TenTheLoai` (`TenTheLoai`);

--
-- Chỉ mục cho bảng `tin_tuc_bai_viet`
--
ALTER TABLE `tin_tuc_bai_viet`
  ADD PRIMARY KEY (`MaBaiViet`),
  ADD KEY `MaNhanVien` (`MaNhanVien`),
  ADD KEY `MaDanhMuc` (`MaDanhMuc`),
  ADD KEY `MaSuKienLienQuan` (`MaSuKienLienQuan`);

--
-- Chỉ mục cho bảng `ve`
--
ALTER TABLE `ve`
  ADD PRIMARY KEY (`MaVe`),
  ADD UNIQUE KEY `MaQR` (`MaQR`),
  ADD UNIQUE KEY `MaVeDienTu` (`MaVeDienTu`),
  ADD KEY `MaDonHang` (`MaDonHang`),
  ADD KEY `MaHangVe` (`MaHangVe`),
  ADD KEY `MaGhe` (`MaGhe`),
  ADD KEY `MaSuKien` (`MaSuKien`);

--
-- Chỉ mục cho bảng `ve_tang`
--
ALTER TABLE `ve_tang`
  ADD PRIMARY KEY (`MaVeTang`),
  ADD KEY `MaVe` (`MaVe`),
  ADD KEY `MaTaiKhoanNguoiTang` (`MaTaiKhoanNguoiTang`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `ban_to_chuc`
--
ALTER TABLE `ban_to_chuc`
  MODIFY `MaBTC` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `danh_muc_bai_viet`
--
ALTER TABLE `danh_muc_bai_viet`
  MODIFY `MaDanhMuc` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `dia_diem_to_chuc`
--
ALTER TABLE `dia_diem_to_chuc`
  MODIFY `MaDiaDiem` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `don_hang`
--
ALTER TABLE `don_hang`
  MODIFY `MaDonHang` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `ghe_ngoi`
--
ALTER TABLE `ghe_ngoi`
  MODIFY `MaGhe` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `giu_cho_ve`
--
ALTER TABLE `giu_cho_ve`
  MODIFY `MaGiuCho` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `hang_ve`
--
ALTER TABLE `hang_ve`
  MODIFY `MaHangVe` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `khach_hang`
--
ALTER TABLE `khach_hang`
  MODIFY `MaKhachHang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `khu_vuc_su_kien`
--
ALTER TABLE `khu_vuc_su_kien`
  MODIFY `MaKhuVuc` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `loai_su_kien`
--
ALTER TABLE `loai_su_kien`
  MODIFY `MaLoaiSuKien` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `merchandise`
--
ALTER TABLE `merchandise`
  MODIFY `MaMerch` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `nghe_si`
--
ALTER TABLE `nghe_si`
  MODIFY `MaNgheSi` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `nhan_vien`
--
ALTER TABLE `nhan_vien`
  MODIFY `MaNhanVien` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `su_kien`
--
ALTER TABLE `su_kien`
  MODIFY `MaSuKien` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `tai_khoan`
--
ALTER TABLE `tai_khoan`
  MODIFY `MaTaiKhoan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT cho bảng `thanh_toan`
--
ALTER TABLE `thanh_toan`
  MODIFY `MaThanhToan` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `the_loai_am_nhac`
--
ALTER TABLE `the_loai_am_nhac`
  MODIFY `MaTheLoai` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `tin_tuc_bai_viet`
--
ALTER TABLE `tin_tuc_bai_viet`
  MODIFY `MaBaiViet` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `ve`
--
ALTER TABLE `ve`
  MODIFY `MaVe` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `ve_tang`
--
ALTER TABLE `ve_tang`
  MODIFY `MaVeTang` int(11) NOT NULL AUTO_INCREMENT;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `chi_tiet_giu_cho`
--
ALTER TABLE `chi_tiet_giu_cho`
  ADD CONSTRAINT `chi_tiet_giu_cho_ibfk_1` FOREIGN KEY (`MaGiuCho`) REFERENCES `giu_cho_ve` (`MaGiuCho`),
  ADD CONSTRAINT `chi_tiet_giu_cho_ibfk_2` FOREIGN KEY (`MaHangVe`) REFERENCES `hang_ve` (`MaHangVe`);

--
-- Các ràng buộc cho bảng `ct_don_hang_merchandise`
--
ALTER TABLE `ct_don_hang_merchandise`
  ADD CONSTRAINT `ct_don_hang_merchandise_ibfk_1` FOREIGN KEY (`MaDonHang`) REFERENCES `don_hang` (`MaDonHang`),
  ADD CONSTRAINT `ct_don_hang_merchandise_ibfk_2` FOREIGN KEY (`MaMerch`) REFERENCES `merchandise` (`MaMerch`);

--
-- Các ràng buộc cho bảng `don_hang`
--
ALTER TABLE `don_hang`
  ADD CONSTRAINT `don_hang_ibfk_1` FOREIGN KEY (`MaKhachHang`) REFERENCES `khach_hang` (`MaKhachHang`);

--
-- Các ràng buộc cho bảng `ghe_ngoi`
--
ALTER TABLE `ghe_ngoi`
  ADD CONSTRAINT `ghe_ngoi_ibfk_1` FOREIGN KEY (`MaKhuVuc`) REFERENCES `khu_vuc_su_kien` (`MaKhuVuc`);

--
-- Các ràng buộc cho bảng `giu_cho_ve`
--
ALTER TABLE `giu_cho_ve`
  ADD CONSTRAINT `giu_cho_ve_ibfk_1` FOREIGN KEY (`MaKhachHang`) REFERENCES `khach_hang` (`MaKhachHang`);

--
-- Các ràng buộc cho bảng `hang_ve`
--
ALTER TABLE `hang_ve`
  ADD CONSTRAINT `hang_ve_ibfk_1` FOREIGN KEY (`MaKhuVuc`) REFERENCES `khu_vuc_su_kien` (`MaKhuVuc`);

--
-- Các ràng buộc cho bảng `khach_hang`
--
ALTER TABLE `khach_hang`
  ADD CONSTRAINT `khach_hang_ibfk_1` FOREIGN KEY (`MaTaiKhoan`) REFERENCES `tai_khoan` (`MaTaiKhoan`);

--
-- Các ràng buộc cho bảng `khu_vuc_su_kien`
--
ALTER TABLE `khu_vuc_su_kien`
  ADD CONSTRAINT `khu_vuc_su_kien_ibfk_1` FOREIGN KEY (`MaSuKien`) REFERENCES `su_kien` (`MaSuKien`);

--
-- Các ràng buộc cho bảng `nhan_vien`
--
ALTER TABLE `nhan_vien`
  ADD CONSTRAINT `nhan_vien_ibfk_1` FOREIGN KEY (`MaTaiKhoan`) REFERENCES `tai_khoan` (`MaTaiKhoan`);

--
-- Các ràng buộc cho bảng `nhan_vien_su_kien`
--
ALTER TABLE `nhan_vien_su_kien`
  ADD CONSTRAINT `nhan_vien_su_kien_ibfk_1` FOREIGN KEY (`MaNhanVien`) REFERENCES `nhan_vien` (`MaNhanVien`),
  ADD CONSTRAINT `nhan_vien_su_kien_ibfk_2` FOREIGN KEY (`MaSuKien`) REFERENCES `su_kien` (`MaSuKien`);

--
-- Các ràng buộc cho bảng `su_kien`
--
ALTER TABLE `su_kien`
  ADD CONSTRAINT `su_kien_ibfk_1` FOREIGN KEY (`MaBTC`) REFERENCES `ban_to_chuc` (`MaBTC`),
  ADD CONSTRAINT `su_kien_ibfk_2` FOREIGN KEY (`MaDiaDiem`) REFERENCES `dia_diem_to_chuc` (`MaDiaDiem`),
  ADD CONSTRAINT `su_kien_ibfk_3` FOREIGN KEY (`MaLoaiSuKien`) REFERENCES `loai_su_kien` (`MaLoaiSuKien`);

--
-- Các ràng buộc cho bảng `su_kien_tl_am_nhac`
--
ALTER TABLE `su_kien_tl_am_nhac`
  ADD CONSTRAINT `su_kien_tl_am_nhac_ibfk_1` FOREIGN KEY (`MaSuKien`) REFERENCES `su_kien` (`MaSuKien`),
  ADD CONSTRAINT `su_kien_tl_am_nhac_ibfk_2` FOREIGN KEY (`MaTheLoai`) REFERENCES `the_loai_am_nhac` (`MaTheLoai`);

--
-- Các ràng buộc cho bảng `tham_gia_bieu_dien`
--
ALTER TABLE `tham_gia_bieu_dien`
  ADD CONSTRAINT `tham_gia_bieu_dien_ibfk_1` FOREIGN KEY (`MaSuKien`) REFERENCES `su_kien` (`MaSuKien`),
  ADD CONSTRAINT `tham_gia_bieu_dien_ibfk_2` FOREIGN KEY (`MaNgheSi`) REFERENCES `nghe_si` (`MaNgheSi`);

--
-- Các ràng buộc cho bảng `thanh_toan`
--
ALTER TABLE `thanh_toan`
  ADD CONSTRAINT `thanh_toan_ibfk_1` FOREIGN KEY (`MaDonHang`) REFERENCES `don_hang` (`MaDonHang`);

--
-- Các ràng buộc cho bảng `tin_tuc_bai_viet`
--
ALTER TABLE `tin_tuc_bai_viet`
  ADD CONSTRAINT `tin_tuc_bai_viet_ibfk_1` FOREIGN KEY (`MaNhanVien`) REFERENCES `nhan_vien` (`MaNhanVien`),
  ADD CONSTRAINT `tin_tuc_bai_viet_ibfk_2` FOREIGN KEY (`MaDanhMuc`) REFERENCES `danh_muc_bai_viet` (`MaDanhMuc`),
  ADD CONSTRAINT `tin_tuc_bai_viet_ibfk_3` FOREIGN KEY (`MaSuKienLienQuan`) REFERENCES `su_kien` (`MaSuKien`);

--
-- Các ràng buộc cho bảng `ve`
--
ALTER TABLE `ve`
  ADD CONSTRAINT `ve_ibfk_1` FOREIGN KEY (`MaDonHang`) REFERENCES `don_hang` (`MaDonHang`),
  ADD CONSTRAINT `ve_ibfk_2` FOREIGN KEY (`MaHangVe`) REFERENCES `hang_ve` (`MaHangVe`),
  ADD CONSTRAINT `ve_ibfk_3` FOREIGN KEY (`MaGhe`) REFERENCES `ghe_ngoi` (`MaGhe`),
  ADD CONSTRAINT `ve_ibfk_4` FOREIGN KEY (`MaSuKien`) REFERENCES `su_kien` (`MaSuKien`);

--
-- Các ràng buộc cho bảng `ve_tang`
--
ALTER TABLE `ve_tang`
  ADD CONSTRAINT `ve_tang_ibfk_1` FOREIGN KEY (`MaVe`) REFERENCES `ve` (`MaVe`),
  ADD CONSTRAINT `ve_tang_ibfk_2` FOREIGN KEY (`MaTaiKhoanNguoiTang`) REFERENCES `tai_khoan` (`MaTaiKhoan`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
