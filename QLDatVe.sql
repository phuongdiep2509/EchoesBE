-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th6 08, 2026 lúc 12:46 PM
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

--
-- Đang đổ dữ liệu cho bảng `ban_to_chuc`
--

INSERT INTO `ban_to_chuc` (`MaBTC`, `TenToChuc`, `MoTa`, `Logo`, `Email`, `SoDienThoai`) VALUES
(1, 'Yeah1 Entertainment', 'Đơn vị tổ chức các sự kiện âm nhạc lớn tại Việt Nam', 'yeah1.png', 'contact@yeah1.vn', '02838221234'),
(2, 'Bến Thành', 'null', 'benthanh.jpg', 'null', '0909999999'),
(3, 'WANXING ENTERTAINMENT', NULL, 'wanxing.png', NULL, NULL),
(4, 'SIROCON Entertainment', NULL, 'mrsiro.jpg', NULL, NULL),
(5, '\r\nNHÀ HÁT GIAO HƯỞNG NHẠC VŨ KỊCH TP. HỒ CHÍ MINH', NULL, 'hbso.jpg', NULL, NULL),
(6, '\r\nNhững Thành Phố Mơ Màng', 'Những Thành Phố Mơ Màng là chuỗi sự kiện âm nhạc độc đáo tại Việt Nam, nơi hội tụ nhiều nghệ sĩ với phong cách đa dạng.', 'ntpmm.jpg', NULL, NULL),
(7, '\r\nBLACK SWAN LABEL', 'GIỮA MỘT VẠN TOUR - CHAPTER THREE: LIVE EXPERIANCE IN HA NOI', 'blackswan.png', NULL, NULL),
(8, 'Def Jam Recording', NULL, 'defjam.png', NULL, NULL),
(9, 'Arrow Records', NULL, 'arrow.png', NULL, NULL),
(10, 'ST319', NULL, 'st319.png', NULL, NULL),
(11, 'VietCharm', NULL, 'vietcharm.png', NULL, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('laravel-cache-424f74a6a7ed4d4ed4761507ebcd209a6ef0937b', 'i:1;', 1780911461),
('laravel-cache-424f74a6a7ed4d4ed4761507ebcd209a6ef0937b:timer', 'i:1780911461;', 1780911461);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
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

--
-- Đang đổ dữ liệu cho bảng `chi_tiet_giu_cho`
--

INSERT INTO `chi_tiet_giu_cho` (`MaGiuCho`, `MaHangVe`, `SoLuong`) VALUES
(1, 8, 1),
(2, 17, 1),
(3, 8, 1),
(4, 5, 1),
(5, 5, 1);

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

--
-- Đang đổ dữ liệu cho bảng `danh_muc_bai_viet`
--

INSERT INTO `danh_muc_bai_viet` (`MaDanhMuc`, `TenDanhMuc`) VALUES
(2, 'Khuyến mãi'),
(3, 'Thông báo'),
(1, 'Tin tức');

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

--
-- Đang đổ dữ liệu cho bảng `dia_diem_to_chuc`
--

INSERT INTO `dia_diem_to_chuc` (`MaDiaDiem`, `TenDiaDiem`, `DiaChiChiTiet`, `ThanhPho`, `SucChuaToiDa`, `MoTa`) VALUES
(1, 'Sân vận động Mỹ Đình', 'Đường Lê Đức Thọ, Nam Từ Liêm', 'Hà Nội', 40000, 'Sân vận động quốc gia'),
(2, 'Trung tâm Hội nghị Quốc gia', '57 Phạm Hùng, Nam Từ Liêm', 'Hà Nội', 3500, 'Địa điểm tổ chức concert và hội nghị'),
(3, 'Bảo tàng Hà Nội', 'Đường Phạm Hùng, Từ Liêm, Hà Nội', 'Hà Nội', 1000, NULL),
(4, 'Quảng trường Châu Âu', 'Vinhomes Royal Island Vũ Yên, TP. Hải Phòng', 'Hải Phòng', 1000, NULL),
(5, 'Nhà Hát Hòa Bình', '240, Đường 3 Tháng 2, Phường Hòa Hưng, Thành phố Hồ Chí Minh', 'TP. Hồ Chí Minh', 1000, NULL),
(6, 'Nhà Thi đấu Tây Hồ', '101 Xuân La, quận Tây Hồ, Hà Nội', 'Hà Nội', 1000, NULL),
(7, 'Khuôn viên Dinh Độc Lập', '108 Nguyễn Du, Phường Sài Gòn, Thành phố Hồ Chí Minh', 'TP Hồ Chí Minh', 1000, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `don_hang`
--

CREATE TABLE `don_hang` (
  `MaDonHang` int(11) NOT NULL,
  `MaKhachHang` int(11) NOT NULL,
  `NgayDat` datetime NOT NULL,
  `TongTien` decimal(12,2) NOT NULL,
  `TrangThai` enum('ChoThanhToan','DaThanhToan','DaHuy') NOT NULL,
  `MaKhuyenMai` int(11) DEFAULT NULL,
  `SoTienGiam` decimal(12,2) DEFAULT 0.00,
  `TongThanhToan` decimal(12,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `don_hang`
--

INSERT INTO `don_hang` (`MaDonHang`, `MaKhachHang`, `NgayDat`, `TongTien`, `TrangThai`, `MaKhuyenMai`, `SoTienGiam`, `TongThanhToan`) VALUES
(1, 1, '2026-06-08 10:19:04', 1600000.00, 'DaHuy', NULL, 0.00, NULL),
(2, 1, '2026-06-08 10:26:50', 2400000.00, 'DaThanhToan', NULL, 0.00, NULL),
(3, 1, '2026-06-08 10:29:21', 1600000.00, 'DaHuy', NULL, 0.00, NULL),
(4, 1, '2026-06-08 10:35:44', 3500000.00, 'DaThanhToan', NULL, 0.00, NULL),
(5, 1, '2026-06-08 10:39:13', 3500000.00, 'DaThanhToan', NULL, 0.00, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
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

--
-- Đang đổ dữ liệu cho bảng `giu_cho_ve`
--

INSERT INTO `giu_cho_ve` (`MaGiuCho`, `MaKhachHang`, `ThoiGianBatDau`, `ThoiGianHetHan`, `TrangThai`) VALUES
(1, 1, '2026-06-08 10:19:02', '2026-06-08 10:29:02', 'DaThanhToan'),
(2, 1, '2026-06-08 10:26:45', '2026-06-08 10:36:45', 'DaThanhToan'),
(3, 1, '2026-06-08 10:29:19', '2026-06-08 10:39:19', 'DaThanhToan'),
(4, 1, '2026-06-08 10:35:43', '2026-06-08 10:45:43', 'DaThanhToan'),
(5, 1, '2026-06-08 10:39:11', '2026-06-08 10:49:11', 'DaThanhToan');

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

--
-- Đang đổ dữ liệu cho bảng `hang_ve`
--

INSERT INTO `hang_ve` (`MaHangVe`, `MaKhuVuc`, `TenHangVe`, `GiaVe`, `SoLuongMoBan`, `SoLuongDaBan`, `ThoiGianMoBan`, `ThoiGianKetThucBan`, `QuyenLoi`) VALUES
(1, 1, 'V1', 5500000.00, 100, 0, '2026-06-01 00:00:00', '2026-06-12 23:59:59', 'Check-in riêng, quà tặng độc quyền'),
(2, 2, 'A1', 3500000.00, 200, 0, '2026-06-01 00:00:00', '2026-06-12 23:59:59', 'Vé vào cổng'),
(3, 3, 'B1', 2500000.00, 200, 0, '2026-06-01 00:00:00', '2026-06-13 23:59:59', NULL),
(4, 1, 'V2', 5500000.00, 100, 0, '2026-06-01 00:00:00', '2026-06-13 23:59:59', NULL),
(5, 2, 'A2', 3500000.00, 200, 2, '2026-06-01 00:00:00', '2026-06-13 23:59:59', NULL),
(6, 3, 'B2', 2500000.00, 200, 0, '2026-06-01 00:00:00', '2026-06-13 23:59:59', NULL),
(7, 4, 'Trực tuyến', 650000.00, 500, 0, '2026-06-01 00:00:00', '2026-06-20 23:59:59', NULL),
(8, 5, 'Trực tuyến', 1600000.00, 500, 2, '2026-06-01 00:00:00', '2026-06-07 23:59:59', NULL),
(9, 6, 'VIP 1', 4225000.00, 200, 0, '2026-06-01 00:00:00', '2026-06-07 23:59:59', NULL),
(10, 7, 'CAT 1', 3445000.00, 200, 0, '2026-06-01 00:00:00', '2026-06-07 23:59:59', NULL),
(11, 8, 'CAT 2', 2210000.00, 200, 0, '2026-06-01 00:00:00', '2026-06-07 23:59:59', NULL),
(12, 9, 'CAT 3', 1495000.00, 200, 0, '2026-06-01 00:00:00', '2026-06-07 23:59:59', NULL),
(13, 10, 'CAT 4', 975000.00, 200, 0, '2026-06-01 00:00:00', '2026-06-13 23:59:59', NULL),
(14, 11, 'Day dứt nỗi đau', 2800000.00, 200, 0, '2026-06-01 00:00:00', '2026-06-20 23:59:59', NULL),
(15, 11, 'Khóc cùng em 1', 1200000.00, 200, 0, '2026-06-01 00:00:00', '2026-06-20 23:59:59', NULL),
(16, 12, 'Khóc cùng em 2', 800000.00, 200, 0, '2026-06-01 00:00:00', '2026-06-20 23:59:59', NULL),
(17, 12, 'Dưới những cơn mưa', 2400000.00, 200, 1, '2026-06-01 00:00:00', '2026-06-20 23:59:59', NULL),
(18, 13, 'VIP', 1199000.00, 200, 0, '2026-06-01 00:00:00', '2026-07-11 23:59:59', NULL),
(19, 14, 'STANDARD', 855000.00, 200, 0, '2026-06-01 00:00:00', '2026-07-11 23:59:59', NULL),
(20, 15, 'SWAN', 3000000.00, 200, 0, '2026-06-01 00:00:00', '2026-08-07 23:59:59', NULL),
(21, 15, 'SWORD', 2200000.00, 200, 0, '2026-06-01 00:00:00', '2026-08-07 23:59:59', NULL),
(22, 16, 'BALLERINA', 1600000.00, 200, 0, '2026-06-01 00:00:00', '2026-08-07 23:59:59', NULL),
(23, 16, 'MOONLIGHT', 860000.00, 200, 0, '2026-06-01 00:00:00', '2026-08-07 23:59:59', NULL),
(24, 17, 'FIRST CLASS', 2701000.00, 200, 0, '2026-06-01 00:00:00', '2026-07-04 23:59:59', NULL),
(25, 18, 'PREMIUM', 1701000.00, 200, 0, '2026-06-01 00:00:00', '2026-07-04 23:59:59', NULL),
(26, 19, 'ECO', 701000.00, 200, 0, '2026-06-01 00:00:00', '2026-07-04 23:59:59', NULL),
(27, 20, 'Ngày xuân', 1791000.00, 200, 0, '2026-06-01 00:00:00', '2026-06-19 23:59:59', NULL),
(28, 20, 'Ngày hạ', 1341000.00, 200, 0, '2026-06-01 00:00:00', '2026-06-19 23:59:59', NULL),
(29, 20, 'Ngày thu', 981000.00, 200, 0, '2026-06-01 00:00:00', '2026-06-19 23:59:59', NULL),
(30, 20, 'Ngày đông', 719100.00, 200, 0, '2026-06-01 00:00:00', '2026-06-19 23:59:59', NULL),
(31, 21, 'BRIDE', 5500000.00, 200, 0, '2026-06-01 00:00:00', '2026-06-27 23:59:59', NULL),
(32, 21, 'MUSE', 4200000.00, 200, 0, '2026-06-01 00:00:00', '2026-06-27 23:59:59', NULL),
(33, 22, 'PRINCESS', 3200000.00, 200, 0, '2026-06-01 00:00:00', '2026-06-27 23:59:59', NULL),
(34, 22, 'QUEEN', 1900000.00, 200, 0, '2026-06-01 00:00:00', '2026-06-27 23:59:59', NULL),
(35, 23, 'The Sun', 2699000.00, 200, 0, '2026-06-01 00:00:00', '2026-07-03 23:59:59', NULL),
(36, 23, 'The Moon', 2199000.00, 200, 0, '2026-06-01 00:00:00', '2026-07-03 23:59:59', NULL),
(37, 24, 'The Joy', 1699000.00, 200, 0, '2026-06-01 00:00:00', '2026-07-03 23:59:59', NULL),
(38, 24, 'The Love', 899000.00, 200, 0, '2026-06-01 00:00:00', '2026-07-03 23:59:59', NULL),
(39, 25, 'VIP', 1950000.00, 200, 0, '2026-06-01 00:00:00', '2026-06-05 23:59:59', NULL),
(40, 26, 'CAT', 1000000.00, 200, 0, '2026-06-01 00:00:00', '2026-06-05 23:59:59', NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
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
(1, 4, '2002-04-18', 'Nam', 'Cầu Giấy, Hà Nội'),
(2, 5, '2001-11-30', 'Nu', 'Hai Bà Trưng, Hà Nội'),
(3, 6, '2003-08-22', 'Nu', 'Long Biên, Hà Nội'),
(4, 7, '1999-12-15', 'Nam', 'Đống Đa, Hà Nội');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `khuyen_mai`
--

CREATE TABLE `khuyen_mai` (
  `MaKhuyenMai` int(11) NOT NULL,
  `MaCode` varchar(50) DEFAULT NULL,
  `TenKhuyenMai` varchar(255) DEFAULT NULL,
  `GiaTriGiam` decimal(10,2) DEFAULT NULL,
  `LoaiGiam` enum('PHAN_TRAM','SO_TIEN') DEFAULT NULL,
  `GiaTriDonHangToiThieu` decimal(12,2) DEFAULT NULL,
  `NgayBatDau` datetime DEFAULT NULL,
  `NgayKetThuc` datetime DEFAULT NULL,
  `SoLuongToiDa` int(11) DEFAULT NULL,
  `SoLuongDaDung` int(11) DEFAULT 0,
  `TrangThai` enum('HOAT_DONG','NGUNG') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `khuyen_mai`
--

INSERT INTO `khuyen_mai` (`MaKhuyenMai`, `MaCode`, `TenKhuyenMai`, `GiaTriGiam`, `LoaiGiam`, `GiaTriDonHangToiThieu`, `NgayBatDau`, `NgayKetThuc`, `SoLuongToiDa`, `SoLuongDaDung`, `TrangThai`) VALUES
(1, 'SALE10', 'Giảm 10% toàn bộ đơn hàng', 10.00, 'PHAN_TRAM', 100000.00, '2026-06-01 00:00:00', '2026-06-30 23:59:59', 1000, 0, 'HOAT_DONG'),
(2, 'GIAM50K', 'Giảm 50k cho đơn từ 200k', 50000.00, 'SO_TIEN', 200000.00, '2026-06-01 00:00:00', '2026-06-15 23:59:59', 500, 0, 'HOAT_DONG'),
(3, 'FLASH20', 'Flash Sale giảm 20%', 20.00, 'PHAN_TRAM', 150000.00, '2026-06-08 00:00:00', '2026-06-08 23:59:59', 200, 0, 'HOAT_DONG'),
(4, 'STUDENT15', 'Giảm 15% cho sinh viên', 15.00, 'PHAN_TRAM', 80000.00, '2026-06-01 00:00:00', '2026-12-31 23:59:59', 2000, 0, 'HOAT_DONG'),
(5, 'SUMMER', 'Giảm 50k chào mừng mùa hè!', 50000.00, 'SO_TIEN', 400000.00, '2026-06-01 00:00:00', '2026-07-31 23:59:59', 300, 0, 'HOAT_DONG'),
(6, 'WINTER25', 'Giảm giá chào mùa đông!', 20.00, 'PHAN_TRAM', 100000.00, '2025-01-01 00:00:00', '2025-02-01 00:00:00', 100, 100, 'NGUNG');

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

--
-- Đang đổ dữ liệu cho bảng `khu_vuc_su_kien`
--

INSERT INTO `khu_vuc_su_kien` (`MaKhuVuc`, `MaSuKien`, `TenKhuVuc`, `SucChua`, `MoTa`) VALUES
(1, 1, 'VIP', 500, 'Khu vực gần sân khấu'),
(2, 1, 'ZONE A', 3000, 'Khu vực tiêu chuẩn'),
(3, 1, 'ZONE B', 300, 'Ghế ngồi cao cấp'),
(4, 2, 'Trực tuyến', 1000, 'Vé được áp dụng cho hình thức trực tuyến'),
(5, 3, 'Trực tuyến', 1000, 'Vé được áp dụng cho hình thức trực tuyến'),
(6, 4, 'VIP', 1000, 'Gần sân khấu'),
(7, 4, 'CAT 1', 1000, 'Ghế ngồi'),
(8, 4, 'CAT 2', 1000, 'Ghế ngồi'),
(9, 4, 'CAT 3', 1000, 'Ghế ngồi'),
(10, 4, 'CAT 4', 1000, 'Ghế ngồi'),
(11, 5, 'Tầng 1', 1000, 'Ghế ngồi'),
(12, 5, 'Tầng 2', 1000, 'Ghế ngồi'),
(13, 6, 'VIP', 1000, 'Đứng gần sân khấu'),
(14, 6, 'STANDARD', 1000, 'Vé đứng'),
(15, 7, 'Tầng 1', 1000, 'Ghế ngồi'),
(16, 7, 'Tầng 2', 1000, 'Ghế ngồi'),
(17, 8, 'FIRST CLASS', 1000, 'Ghế ngồi'),
(18, 8, 'PREMIUM', 1000, 'Ghế ngồi'),
(19, 8, 'ECO', 1000, 'Ghế ngồi'),
(20, 9, 'Ngày', 1000, 'Ghế ngồi'),
(21, 10, 'Tầng 1', 1000, 'Ghế ngồi'),
(22, 10, 'Tầng 2', 1000, 'Ghế ngồi'),
(23, 11, 'Tầng 1', 1000, 'Ghế ngồi'),
(24, 11, 'Tầng 2', 1000, 'Ghế ngồi'),
(25, 12, 'VIP', 1000, 'Ghế ngồi'),
(26, 12, 'CAT', 1000, 'Ghế ngồi');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `loai_su_kien`
--

CREATE TABLE `loai_su_kien` (
  `MaLoaiSuKien` int(11) NOT NULL,
  `TenLoai` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `loai_su_kien`
--

INSERT INTO `loai_su_kien` (`MaLoaiSuKien`, `TenLoai`) VALUES
(1, 'Concert'),
(2, 'Nhạc sống');

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

--
-- Đang đổ dữ liệu cho bảng `merchandise`
--

INSERT INTO `merchandise` (`MaMerch`, `TenMerch`, `MoTa`, `GiaBan`, `SoLuongTon`, `AnhSanPham`, `TrangThai`, `ChinhSachDoiTra`, `HuongDanBaoQuan`) VALUES
(1, 'Castle Veil Bandana – \"Wonder Hallows\"', 'Khăn bandana phong cách Gothic với họa tiết Wonder Hallows độc đáo.', 105000.00, 100, 'merch1.png', 'NgungBan', 'Đổi trong 7 ngày', 'Giặt ở nhiệt độ thường'),
(2, 'Echo Keyring – \"Wonder Hallows\"', 'Móc khóa Echo với thiết kế Wonder Hallows tinh tế.\r\nChất liệu: Kim loại mạ vàng\r\nKích thước: 5 × 3 cm', 85000.00, 0, 'merch2.png', 'NgungBan', 'Đổi trong 7 ngày', 'Tránh va đập mạnh'),
(3, 'Anh Trai \"Say Hi\" Bandana / The Last Chapter', 'Khăn bandana chính thức từ concert Anh Trai Say Hi.', 179000.00, 50, 'merch3.png', 'DangBan', NULL, NULL),
(4, 'Anh Trai \"Say Hi\" Lightstick set / Eternal Shine', 'Lightstick chính thức với hiệu ứng ánh sáng đặc biệt.\r\n\r\nChất liệu: Nhựa ABS + LED\r\nKích thước: 25 cm (dài', 499000.00, 50, 'merch4.png', 'DangBan', NULL, NULL),
(5, 'Em Xinh \"Say Hi\" Lightstick set / The real Aura', 'Lightstick Em Xinh với aura đặc biệt và màu sắc rực rỡ.\r\n\r\nChất liệu: Nhựa ABS + LED RGB\r\nKích thước: 25 cm (dài)', 499000.00, 50, 'merch5.png', 'DangBan', NULL, NULL),
(6, 'EM XINH \"Say Hi\" Cap / Xinh Sập Xình Sập Sàn', 'Mũ lưỡi trai Em Xinh với slogan độc đáo và thiết kế trendy.\r\n\r\nChất liệu: Cotton twill\r\nKích thước: Free size (56-60 cm)', 249000.00, 50, 'merch6.png', 'NgungBan', NULL, NULL);

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
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1);

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

--
-- Đang đổ dữ liệu cho bảng `nghe_si`
--

INSERT INTO `nghe_si` (`MaNgheSi`, `TenNgheSi`, `NgheDanh`, `QuocGia`, `TieuSu`, `AnhDaiDien`) VALUES
(1, 'Nguyễn Thanh Tùng', 'Sơn Tùng M-TP', 'Việt Nam', 'Ca sĩ, nhạc sĩ nổi tiếng Việt Nam', 'sontung.jpg'),
(2, 'Trần Minh Hiếu', 'HIEUTHUHAI', 'Việt Nam', 'Rapper và ca sĩ trẻ nổi bật', 'hieuthuhai.jpg'),
(3, 'Trần Anh Quân', 'MONO', 'Việt Nam', 'Ca sĩ nhạc Pop', 'mono.jpg');

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
(1, 2, 'Chuyên viên Quản lý sự kiện', '1998-05-12', 'Nu', 'Thanh Xuân, Hà Nội', '2024-01-10'),
(2, 3, 'Chuyên viên Chăm sóc khách hàng', '2000-09-20', 'Nam', 'Nam Từ Liêm, Hà Nội', '2024-02-15');

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
-- Cấu trúc bảng cho bảng `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('PJxuqJpYYbz4mIkL1CVrz6D0sWHRZaFtaLGPJF3c', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiRUJiczVxSFl5YURpWVdSSmkzY2F6bk1mWGxzemcyQ3cxQlc5UjdGciI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTg6Imh0dHA6Ly9sb2NhbGhvc3Q6ODA4MC9FY2hvZXNCRS9FY2hvZXMvcHVibGljL21lcmNoYW5kaXNlLzQiO3M6NToicm91dGUiO3M6MTY6Im1lcmNoYW5kaXNlLnNob3ciO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO3M6MTE6Ik1hS2hhY2hIYW5nIjtpOjE7fQ==', 1780915392);

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
  `TrangThai` enum('SapDienRa','DangMoBan','DaKetThuc','DaHuy') NOT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `AnhSeatMap` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `su_kien`
--

INSERT INTO `su_kien` (`MaSuKien`, `MaBTC`, `MaDiaDiem`, `MaLoaiSuKien`, `TenSuKien`, `AnhBia`, `MoTa`, `DiemNoiBat`, `DieuKienVaDieuKhoan`, `ThoiGianBatDau`, `ThoiGianKetThuc`, `TrangThai`, `slug`, `AnhSeatMap`) VALUES
(1, 3, 5, 2, '2026 KIM SUNG KYU LIVE [LV4: LEAP to VECTOR] IN HO CHI MINH CITY', 'kimsungkyu.png', 'Ôi gì mà hot thế? Hóa ra là SungKyu chốt kèo đến Hồ Chí Minh phá đảo mùa hè thêm phần sôi động cùng các INSPIRITs đấy!\r\n\r\nCòn các INSPIRITs thì sao? Đã sẵn sàng cùng SungKyu tạo nên mùa hè đáng nhớ vào ngày 13/6 chưa nào?~ 😎\r\n\r\nHãy sẵn sàng để có thể hết mình tận hưởng những khoảnh khắc tuyệt vời tại 2026 KIM SUNG KYU LIVE [LV4: LEAP to VECTOR] IN HO CHI MINH CITY vào lúc 19h00 ngày 13.06.2026 nhé!\r\nQuyết tâm tỏa sáng trong mắt SungKyu nào mấy bà ơiiii 🖤', '', '* Vé tham dự sự kiện chỉ có thể được mua thông qua các nền tảng của Ticketbox (trang web và ứng dụng Ticketbox)\r\n* 1 (MỘT) vé chỉ có hiệu lực cho 1 (MỘT) người sở hữu vé cho 1 (MỘT) lần vào bên trong khu vực diễn ra sự kiện.\r\n* Mỗi tài khoản giới hạn tối đa 4 vé.\r\n* Vé KHÔNG được hoàn trả, đổi ngày hoặc chỉnh sửa trong bất kỳ trường hợp nào.\r\n* Trẻ dưới 7 tuổi không được tham gia sự kiện. Trẻ dưới 12 tuổi cần có người lớn đi cùng. \r\n* Vui lòng không mua vé từ bất kỳ nguồn nào khác để tránh trường hợp vé giả hoặc lừa đảo, BTC không chịu trách nhiệm giải quyết các trường hợp này.\r\n* Khán giả có trách nhiệm tự bảo quản vé của mình. BTC từ chối giải quyết các trường hợp có nhiều hơn 1 người check-in cùng 1 mã vé. Theo quy định, BTC sẽ chấp nhận cho phép người sở hữu vé chính chủ theo thông tin khai báo trên Ticketbox được tham dự sự kiện.\r\n* Người tham dự sự kiện vui lòng xuất trình email nhận vé hoặc đơn hàng có trên app Ticketbox để checkin đổi Benefits và nhận Vòng Tay trước khi vào hội trường diễn ra sự kiện.\r\n* Đảm bảo có Vòng Tay và xuất trình được vé điện tử khi được yêu cầu bất cứ lúc nào trước khi vào hội trường diễn ra sự kiện.\r\n* Ban quản lý và nhân viên có quyền từ chối cho vào và/hoặc mời ra ngoài đối với bất kỳ cá nhân nào, vào bất kỳ lúc nào nếu người đó vi phạm bất kỳ Điều khoản & Quy định nào của ban tổ chức.\r\n* Trong mọi trường hợp, quyết định của Ban tổ chức là quyết định cuối cùng.', '2026-06-13 19:00:00', '2026-06-13 23:00:00', 'DangMoBan', 'kimsungkyu', 'kimsungkyu.jpg'),
(2, 5, 4, 2, '[HBSO] Hòa Nhạc A NIGHT OF TCHAIKOVSKY MUSSORGSKY & BARTOK', 'tchaikovsky.png\r\n', 'PYOTR TCHAIKOVSKY \"Romeo and Juliet\" Fantasy Overture, TH. 42, CW 39\r\n\r\n \r\n\r\nBELLA BARTOK Viola Concerto, Sz. 120, BB 128\r\n\r\nI. Moderato\r\n\r\nIl. Adagio religioso\r\n\r\nIll. Allegro vivace\r\n\r\nViola: PHẠM VŨ THIÊN BÁO\r\n\r\n \r\n\r\nGiải lao | Intermission\r\n\r\n \r\n\r\nMODEST MUSSORGSKY / MAURICE RAVEL Pictures at an Exhibition\r\n\r\nPromenade\r\n\r\n1. The Gnome Promenade\r\n\r\nIl. The Old Castle\r\n\r\nPromenade\r\n\r\nIll. Tuileries (Children\'s Quarrel after Games)\r\n\r\nIV. Cattle\r\n\r\nPromenade\r\n\r\nV. Ballet of Unhatched Chicks\r\n\r\nVI. Samuel Goldenberg and Schmuÿle\r\n\r\nPromenade\r\n\r\nVII. Limoges. The Market (The Great News)\r\n\r\nVIII. Catacombs (Roman Tomb)\r\n\r\nIX. The Hut on Hen\'s Legs (Baba Yaga)\r\n\r\nX. The Bogatyr Gates (In the Capital of Kiev)\r\n\r\n \r\n\r\nBiểu diễn | Performing: PHẠM VŨ THIÊN BẢO\r\n\r\nDàn nhạc Giao hưởng HBSO | HBSO Symphony Orchestra Chỉ huy | Conductor: KALLE KUUSAVA\r\n\r\n', '', '', '2026-06-20 20:00:00', '2026-06-20 22:00:00', 'DangMoBan', NULL, NULL),
(3, 11, 7, 1, 'VIETCHARM: KHI DI SẢN HÓA VŨ ĐIỆU - Live The Legacy', 'vietcharm.jpg', NULL, NULL, NULL, '2026-06-08 12:00:00', '2026-06-08 13:30:00', 'DangMoBan', 'vietcharm', NULL),
(4, 2, 1, 1, 'K-PULSE HANOI 2026', 'kpulse.png', NULL, NULL, NULL, '2026-06-07 19:00:00', '2026-06-07 22:30:00', 'DaKetThuc', 'kpulse', 'kpulse.png'),
(5, 4, 5, 2, 'Mr Siro - Fan Concert - Encore Ai Cũng Giấu Trong Lòng Tảng Băng - HCM', 'mrsiro.jpg', 'Những giai điệu lại sẽ được vang lên thêm một lần nữa vì chính tình yêu của các bạn. Cảm ơn các bạn đã đồng lòng, thay phiên nhau động viên lẫn đốc thúc BTC mấy ngày qua.\r\nHy vọng lần này chúng ta sẽ không bỏ lỡ nhau.\r\n\r\nMR SIRO | FAN CONCERT - ENCORE AI CŨNG GIẤU TRONG LÒNG TẢNG BĂNG\r\n\r\nTHỨ BẢY | 20.06.26 | 19:00- 22:00 \r\n\r\nHỒ CHÍ MINH | NHÀ HÁT HÒA BÌNH\r\n240 Đường 3 Tháng 2 - Phường Hòa Hưng - TP Hồ Chi Minh\r\n🎵 NHỮNG LƯU Ý QUAN TRỌNG CHO ĐÊM CONCERT 🎵\r\nĐể chúng ta có một đêm nhạc trọn vẹn cảm xúc và những kỉ niệm đáng nhớ nhất, Khán Giả tham gia concert hãy dành chút thời gian xem qua các quy định dưới đây nhé:\r\n🎫 Cảnh Báo Mua Vé \r\n• Mua vé an toàn: Chỉ mua vé duy nhất tại website/app Ticketbox. Không nên mua \"vé chợ đen\"/ vé pass để tránh rủi ro lừa đảo, vì BTC sẽ không thể hỗ trợ các trường hợp này. \r\n• Số lượng: Mỗi tài khoản được mua tối đa 06 vé. Một vé dành cho một bạn và chỉ có giá trị cho một lần vào cổng thôi nhé.\r\n• Chính sách: Vé đã mua KHÔNG thể hoàn trả, đổi ngày hoặc chỉnh sửa. Các bạn nhớ cân nhắc kỹ lịch trình trước khi quyết định mua vé nha. \r\n• Bảo mật: Hãy tự bảo quản mã QR vé của mình thật kỹ, không post công khai ở bất cứ đâu. BTC sẽ ưu tiên quyền vào cổng cho chính chủ (theo thông tin trên Ticketbox) nếu có tranh chấp mã vé.', NULL, NULL, '2026-06-20 19:00:00', '2026-06-20 22:30:00', 'DangMoBan', NULL, 'mrsiro.jpeg'),
(6, 6, 2, 1, '[Hà Nội] Những Thành Phố Mơ Màng Summer 2026', 'ntpmm.png', 'Nhà Hát Mơ Màng chính thức mở cửa và bản giao hưởng mùa hè của Những Thành Phố Mơ Màng đang chuẩn bị cất lời. Sẽ là một mùa hè sôi động, kỳ diệu, bùng nổ và vô vàn điều bất ngờ dành cho cư dân. 10 nghệ sĩ cùng kết hợp với dàn liveband chất lượng sẽ mang tới một trải nghiệm cực kỳ mãn nhãn và đặc biệt cho mùa hè năm nay!', NULL, NULL, '2026-07-12 16:00:00', '2026-07-12 22:30:00', 'DangMoBan', 'ntpmm', 'ntpmm.jpg'),
(7, 7, 2, 1, '\'GIỮA MỘT VẠN TOUR\' - PHÙNG KHÁNH LINH | CHAPTER 3: LIVE EXPERIENCE IN HÀ NỘI', 'gmvt.png', NULL, NULL, NULL, '2026-08-08 19:00:00', '2026-08-08 22:30:00', 'DangMoBan', 'gmvt', 'gmvt.jpeg'),
(8, 8, 6, 1, 'Mason Nguyen - Fan Meeting in Hanoi', 'mason.webp', NULL, NULL, NULL, '2026-07-05 15:30:00', '2026-07-05 22:30:00', 'DangMoBan', 'mason', 'mason.jpg'),
(9, 9, 4, 1, '\"Từng Ngày\" - Live concert in Hải Phòng', 'tungngay.webp', NULL, NULL, NULL, '2026-06-20 17:00:00', '2026-06-20 22:30:00', 'DangMoBan', 'tungngay', 'tungngay.jpg'),
(10, 3, 5, 1, '“OUR 20th MOMENT 2026” – Lee Je Hoon Fanmeeting In Ho Chi Minh City', 'leejehoon.webp', NULL, NULL, NULL, '2026-06-28 17:00:00', '2026-06-28 23:30:00', 'DangMoBan', 'leejehoon', NULL),
(11, 10, 5, 1, 'ÁNH SÁNG MÀN ĐÊM - GREY D\'S LIVE CONCERT DAY 2 IN HCMC', 'greyd.webp', 'Khi màn đêm mở lối, thế giới của GREY D sẽ thức tỉnh.\r\n\r\n\r\n\r\nKhông chỉ là một live concert, ÁNH SÁNG • MÀN ĐÊM là cánh cổng dẫn khán giả bước vào mê cung của âm nhạc, ánh sáng và những bí mật chưa từng được kể. Những ca khúc quen thuộc từ album sẽ trở lại trong phiên bản live hoàn toàn mới - mãnh liệt hơn, chân thật hơn và đầy bất ngờ.\r\n\r\n\r\n\r\nMỗi sân khấu là một chương truyện.\r\n\r\nMỗi giai điệu là một dấu vết dẫn đường.\r\n\r\n\r\n\r\nVà tấm vé trên tay bạn chính là chìa khóa để bước vào thế giới ấy - nơi câu chuyện âm nhạc của GREY D được mở ra.', NULL, NULL, '2026-07-04 20:00:00', '2026-07-04 23:30:00', 'DangMoBan', NULL, 'greyd.jpg'),
(12, 1, 7, 1, 'THE GRAND HỒ TRÀM MUSIC CONCERT - BLU NIGHTFALL', 'thegrandhotram.jpeg', NULL, NULL, NULL, '2026-06-06 19:00:00', '2026-06-06 22:30:00', 'DaKetThuc', 'thegrandhotram', 'thegrandhotram.jpeg');

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
  `VaiTro` enum('Admin','KhachHang','NhanVien') NOT NULL,
  `TrangThai` enum('HoatDong','NgungHoatDong') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `tai_khoan`
--

INSERT INTO `tai_khoan` (`MaTaiKhoan`, `TenDangNhap`, `MatKhau`, `HoTen`, `Email`, `SoDienThoai`, `VaiTro`, `TrangThai`) VALUES
(1, 'quan.nguyen', '$2y$12$ivlBxyFgxIl0mBw9Fi9dg.ksYcUcjEXP/MeAoMIxc4ExSmENlRnnO', 'Nguyễn Minh Quân', 'quan.nguyen@echoes.vn', '0901234567', 'Admin', 'HoatDong'),
(2, 'ha.tran', '$2y$12$5AHOZ8oyXOgFE4w2Az6t6eYcZXs3JEMOG39GMCmH6Mqmz7EHJ.T0G', 'Trần Thu Hà', 'ha.tran@echoes.vn', '0912345678', 'NhanVien', 'HoatDong'),
(3, 'nam.le', '$2y$12$5AHOZ8oyXOgFE4w2Az6t6eYcZXs3JEMOG39GMCmH6Mqmz7EHJ.T0G', 'Lê Hoàng Nam', 'nam.le@echoes.vn', '0923456789', 'NhanVien', 'HoatDong'),
(4, 'anh.pham', '$2y$12$jBGBZL8a9Z5T3lXU5B6wPupolEADoxgap6PwJXx3/A.fBJy64leVG', 'Phạm Đức Anh', 'anhpham@gmail.com', '0934567890', 'KhachHang', 'HoatDong'),
(5, 'my.nguyen', '$2y$12$jBGBZL8a9Z5T3lXU5B6wPupolEADoxgap6PwJXx3/A.fBJy64leVG', 'Nguyễn Thảo My', 'thaomy.nguyen@gmail.com', '0945678901', 'KhachHang', 'HoatDong'),
(6, 'linh.do', '$2y$12$jBGBZL8a9Z5T3lXU5B6wPupolEADoxgap6PwJXx3/A.fBJy64leVG', 'Đỗ Khánh Linh', 'linhdo@gmail.com', '0961234567', 'KhachHang', 'HoatDong'),
(7, 'tung.bui', '$2y$12$jBGBZL8a9Z5T3lXU5B6wPupolEADoxgap6PwJXx3/A.fBJy64leVG', 'Bùi Minh Tùng', 'tungbui@gmail.com', '0973456789', 'KhachHang', 'HoatDong');

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

--
-- Đang đổ dữ liệu cho bảng `thanh_toan`
--

INSERT INTO `thanh_toan` (`MaThanhToan`, `MaDonHang`, `PhuongThucThanhToan`, `SoTien`, `ThoiGianThanhToan`, `MaGiaoDich`, `TrangThai`) VALUES
(1, 2, 'ChuyenKhoanQR', 2400000.00, '2026-06-08 10:27:15', 'ECHOES-ORDER-2-102654-F4MC', 'ThanhCong'),
(2, 3, 'ChuyenKhoanQR', 1600000.00, '2026-06-08 10:30:24', 'ECHOES-ORDER-3-102923-ADTK', 'ThatBai'),
(3, 4, 'ChuyenKhoanQR', 3500000.00, '2026-06-08 10:35:53', 'ECHOES-ORDER-4-103546-4EJW', 'ThanhCong'),
(4, 5, 'ChuyenKhoanQR', 3500000.00, '2026-06-08 10:40:15', 'ECHOES-ORDER-5-103914-VPXK', 'ThatBai'),
(5, 5, 'ChuyenKhoanQR', 3500000.00, '2026-06-08 10:42:24', 'ECHOES-ORDER-5-104209-OTXS', 'ThanhCong');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `the_loai_am_nhac`
--

CREATE TABLE `the_loai_am_nhac` (
  `MaTheLoai` int(11) NOT NULL,
  `TenTheLoai` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `the_loai_am_nhac`
--

INSERT INTO `the_loai_am_nhac` (`MaTheLoai`, `TenTheLoai`) VALUES
(2, 'Ballad'),
(4, 'EDM'),
(1, 'Pop'),
(3, 'Rap');

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

--
-- Đang đổ dữ liệu cho bảng `tin_tuc_bai_viet`
--

INSERT INTO `tin_tuc_bai_viet` (`MaBaiViet`, `MaNhanVien`, `TieuDe`, `NoiDung`, `AnhDaiDien`, `NgayDang`, `MaDanhMuc`, `MaSuKienLienQuan`) VALUES
(1, 1, 'SKY WAVE 2026 chính thức mở bán', 'Ban tổ chức công bố thời gian mở bán vé chính thức.', 'skywave_news.jpg', '2026-06-07 19:53:49', 1, 1),
(2, 1, 'Echoes Concert 2026 chính thức mở bán vé', 'Sự kiện âm nhạc lớn nhất năm 2026 đã chính thức mở bán vé trên hệ thống Echoes...', 'news1.jpg', '2026-06-01 10:00:00', 1, 1),
(3, 1, 'Cập nhật lineup nghệ sĩ tham gia Echoes Live', 'Ban tổ chức vừa công bố danh sách nghệ sĩ biểu diễn với nhiều tên tuổi nổi bật...', 'news2.jpg', '2026-06-03 09:30:00', 1, 1),
(4, 2, 'Chính sách hoàn vé mới cho sự kiện 2026', 'Hệ thống đã cập nhật chính sách hoàn vé nhằm đảm bảo quyền lợi khách hàng...', 'news3.jpg', '2026-06-05 14:00:00', 2, NULL),
(5, 2, 'Hướng dẫn đặt vé nhanh trên hệ thống Echoes', 'Người dùng có thể đặt vé chỉ với 3 bước đơn giản trên website...', 'news4.jpg', '2026-06-06 08:15:00', 2, NULL),
(6, 1, 'Merchandise độc quyền tại Echoes Concert', 'Các sản phẩm merchandise giới hạn sẽ được bán trực tiếp tại sự kiện...', 'news5.jpg', '2026-06-07 11:45:00', 3, 1),
(7, 2, 'Lưu ý khi check-in sự kiện âm nhạc', 'Khán giả cần mang theo vé điện tử và CCCD để check-in nhanh chóng...', 'news6.jpg', '2026-06-08 16:20:00', 1, 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
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

--
-- Đang đổ dữ liệu cho bảng `ve`
--

INSERT INTO `ve` (`MaVe`, `MaDonHang`, `MaHangVe`, `MaGhe`, `MaSuKien`, `MaQR`, `MaVeDienTu`, `TrangThai`, `ThoiGianCheckIn`) VALUES
(1, 1, 8, NULL, 3, 'a1069c92b213bb12828146ad0a9a59c431cc57e02d849c98fb7040d1793b4ec9', 'VE-1-8-S16VU8LJ-01', 'DaHuy', NULL),
(2, 2, 17, NULL, 5, '808ceb19cf6ad692da51a85433ad560b8c4f3d819e938b7bf22b59f1c91aa34f', 'VE-2-17-LBULKU8E-01', 'ChoSuDung', NULL),
(3, 3, 8, NULL, 3, 'a456d917ba3dfee27b5c9f990a1b3d98a4eb050866b31a5d5530014306448319', 'VE-3-8-GHVTCRKF-01', 'DaHuy', NULL),
(4, 4, 5, NULL, 1, '8c26f1068fc6ce503d6f798d2c6f9b2b1b221d92cd4fb7cb16caa6b0dbc8930f', 'VE-4-5-QVWHKEQU-01', 'ChoSuDung', NULL),
(5, 5, 5, NULL, 1, '937a8bc2b58b43888dc82ae73fbedf20176a3a882e7bb79716d632e78171343c', 'VE-5-5-PDDI0GVW-01', 'ChoSuDung', NULL);

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
-- Chỉ mục cho bảng `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Chỉ mục cho bảng `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

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
  ADD KEY `MaKhachHang` (`MaKhachHang`),
  ADD KEY `fk_donhang_khuyenmai` (`MaKhuyenMai`);

--
-- Chỉ mục cho bảng `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

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
-- Chỉ mục cho bảng `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Chỉ mục cho bảng `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `khach_hang`
--
ALTER TABLE `khach_hang`
  ADD PRIMARY KEY (`MaKhachHang`),
  ADD UNIQUE KEY `MaTaiKhoan` (`MaTaiKhoan`);

--
-- Chỉ mục cho bảng `khuyen_mai`
--
ALTER TABLE `khuyen_mai`
  ADD PRIMARY KEY (`MaKhuyenMai`),
  ADD UNIQUE KEY `MaCode` (`MaCode`);

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
-- Chỉ mục cho bảng `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Chỉ mục cho bảng `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Chỉ mục cho bảng `su_kien`
--
ALTER TABLE `su_kien`
  ADD PRIMARY KEY (`MaSuKien`),
  ADD UNIQUE KEY `slug` (`slug`),
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
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

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
  MODIFY `MaBTC` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT cho bảng `danh_muc_bai_viet`
--
ALTER TABLE `danh_muc_bai_viet`
  MODIFY `MaDanhMuc` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `dia_diem_to_chuc`
--
ALTER TABLE `dia_diem_to_chuc`
  MODIFY `MaDiaDiem` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT cho bảng `don_hang`
--
ALTER TABLE `don_hang`
  MODIFY `MaDonHang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `ghe_ngoi`
--
ALTER TABLE `ghe_ngoi`
  MODIFY `MaGhe` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `giu_cho_ve`
--
ALTER TABLE `giu_cho_ve`
  MODIFY `MaGiuCho` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `hang_ve`
--
ALTER TABLE `hang_ve`
  MODIFY `MaHangVe` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT cho bảng `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `khach_hang`
--
ALTER TABLE `khach_hang`
  MODIFY `MaKhachHang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `khuyen_mai`
--
ALTER TABLE `khuyen_mai`
  MODIFY `MaKhuyenMai` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `khu_vuc_su_kien`
--
ALTER TABLE `khu_vuc_su_kien`
  MODIFY `MaKhuVuc` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT cho bảng `loai_su_kien`
--
ALTER TABLE `loai_su_kien`
  MODIFY `MaLoaiSuKien` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `merchandise`
--
ALTER TABLE `merchandise`
  MODIFY `MaMerch` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `nghe_si`
--
ALTER TABLE `nghe_si`
  MODIFY `MaNgheSi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `nhan_vien`
--
ALTER TABLE `nhan_vien`
  MODIFY `MaNhanVien` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `su_kien`
--
ALTER TABLE `su_kien`
  MODIFY `MaSuKien` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT cho bảng `tai_khoan`
--
ALTER TABLE `tai_khoan`
  MODIFY `MaTaiKhoan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT cho bảng `thanh_toan`
--
ALTER TABLE `thanh_toan`
  MODIFY `MaThanhToan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `the_loai_am_nhac`
--
ALTER TABLE `the_loai_am_nhac`
  MODIFY `MaTheLoai` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `tin_tuc_bai_viet`
--
ALTER TABLE `tin_tuc_bai_viet`
  MODIFY `MaBaiViet` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `ve`
--
ALTER TABLE `ve`
  MODIFY `MaVe` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

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
  ADD CONSTRAINT `don_hang_ibfk_1` FOREIGN KEY (`MaKhachHang`) REFERENCES `khach_hang` (`MaKhachHang`),
  ADD CONSTRAINT `fk_donhang_khuyenmai` FOREIGN KEY (`MaKhuyenMai`) REFERENCES `khuyen_mai` (`MaKhuyenMai`);

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
