<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index()
    {
        // Lấy sự kiện hot (Concert âm nhạc sắp diễn ra, sắp xếp theo thời gian gần nhất)
        $hotEvents = DB::table('su_kien as sk')
            ->leftJoin('dia_diem_to_chuc as dd', 'sk.MaDiaDiem', '=', 'dd.MaDiaDiem')
            ->leftJoin('loai_su_kien as ls', 'sk.MaLoaiSuKien', '=', 'ls.MaLoaiSuKien')
            ->leftJoin('hang_ve as hv', function ($join) {
                $join->on('hv.MaHangVe', '=',
                    DB::raw('(SELECT MaHangVe FROM hang_ve hv2
                              JOIN khu_vuc_su_kien kv2 ON kv2.MaKhuVuc = hv2.MaKhuVuc
                              WHERE kv2.MaSuKien = sk.MaSuKien
                              ORDER BY hv2.GiaVe ASC LIMIT 1)'));
            })
            ->whereIn('sk.TrangThai', ['SapDienRa', 'DangMoBan'])
            ->select([
                'sk.MaSuKien',
                'sk.TenSuKien',
                'sk.AnhBia',
                'sk.ThoiGianBatDau',
                'sk.TrangThai',
                'dd.TenDiaDiem',
                'dd.ThanhPho',
                'ls.TenLoai',
                'hv.GiaVe as GiaVeThapNhat',
            ])
            ->orderBy('sk.ThoiGianBatDau')
            ->limit(4)
            ->get()
            ->map(fn($e) => $this->formatEvent($e));

        // Nhạc sống (loại sự kiện có chứa "nhạc")
        $latestMusic = DB::table('su_kien as sk')
            ->leftJoin('dia_diem_to_chuc as dd', 'sk.MaDiaDiem', '=', 'dd.MaDiaDiem')
            ->leftJoin('loai_su_kien as ls', 'sk.MaLoaiSuKien', '=', 'ls.MaLoaiSuKien')
            ->leftJoin('hang_ve as hv', function ($join) {
                $join->on('hv.MaHangVe', '=',
                    DB::raw('(SELECT MaHangVe FROM hang_ve hv2
                              JOIN khu_vuc_su_kien kv2 ON kv2.MaKhuVuc = hv2.MaKhuVuc
                              WHERE kv2.MaSuKien = sk.MaSuKien
                              ORDER BY hv2.GiaVe ASC LIMIT 1)'));
            })
            ->whereIn('sk.TrangThai', ['SapDienRa', 'DangMoBan'])
            ->where('ls.TenLoai', 'like', '%nhạc%')
            ->select([
                'sk.MaSuKien',
                'sk.TenSuKien',
                'sk.AnhBia',
                'sk.ThoiGianBatDau',
                'sk.TrangThai',
                'dd.TenDiaDiem',
                'dd.ThanhPho',
                'ls.TenLoai',
                'hv.GiaVe as GiaVeThapNhat',
            ])
            ->orderBy('sk.ThoiGianBatDau')
            ->limit(4)
            ->get()
            ->map(fn($e) => $this->formatEvent($e));

        // Concert âm nhạc (loại không phải nhạc sống)
        $latestConcerts = DB::table('su_kien as sk')
            ->leftJoin('dia_diem_to_chuc as dd', 'sk.MaDiaDiem', '=', 'dd.MaDiaDiem')
            ->leftJoin('loai_su_kien as ls', 'sk.MaLoaiSuKien', '=', 'ls.MaLoaiSuKien')
            ->leftJoin('hang_ve as hv', function ($join) {
                $join->on('hv.MaHangVe', '=',
                    DB::raw('(SELECT MaHangVe FROM hang_ve hv2
                              JOIN khu_vuc_su_kien kv2 ON kv2.MaKhuVuc = hv2.MaKhuVuc
                              WHERE kv2.MaSuKien = sk.MaSuKien
                              ORDER BY hv2.GiaVe ASC LIMIT 1)'));
            })
            ->whereIn('sk.TrangThai', ['SapDienRa', 'DangMoBan'])
            ->where('ls.TenLoai', 'not like', '%nhạc%')
            ->select([
                'sk.MaSuKien',
                'sk.TenSuKien',
                'sk.AnhBia',
                'sk.ThoiGianBatDau',
                'sk.TrangThai',
                'dd.TenDiaDiem',
                'dd.ThanhPho',
                'ls.TenLoai',
                'hv.GiaVe as GiaVeThapNhat',
            ])
            ->orderBy('sk.ThoiGianBatDau')
            ->limit(4)
            ->get()
            ->map(fn($e) => $this->formatEvent($e));

        return view('pages.home', compact('hotEvents', 'latestMusic', 'latestConcerts'));
    }

    private function formatEvent(object $e): object
    {
        $isMusic = str_contains(strtolower($e->TenLoai ?? ''), 'nhạc');
        $folder  = $isMusic ? 'assets/images/music/' : 'assets/images/concert/';

        // Tìm ảnh trong đúng thư mục, nếu không có thì thử thư mục còn lại
        $imgPath = null;
        if ($e->AnhBia) {
            $primary   = public_path($folder . $e->AnhBia);
            $secondary = public_path(($isMusic ? 'assets/images/concert/' : 'assets/images/music/') . $e->AnhBia);
            if (file_exists($primary)) {
                $imgPath = $folder . $e->AnhBia;
            } elseif (file_exists($secondary)) {
                $imgPath = ($isMusic ? 'assets/images/concert/' : 'assets/images/music/') . $e->AnhBia;
            }
        }

        return (object) [
            'id'       => $e->MaSuKien,
            'title'    => $e->TenSuKien,
            'image'    => $imgPath,
            'location' => trim(($e->TenDiaDiem ?? '') . ($e->ThanhPho ? ' - ' . $e->ThanhPho : '')),
            'date'     => $e->ThoiGianBatDau
                ? Carbon::parse($e->ThoiGianBatDau)->format('d/m/Y - H:i')
                : 'Đang cập nhật',
            'price'    => $e->GiaVeThapNhat
                ? 'Từ ' . number_format($e->GiaVeThapNhat, 0, ',', '.') . 'đ'
                : 'Liên hệ',
            'type'     => $e->TenLoai ?? 'Sự kiện',
            'link'     => url(($isMusic ? '/music/' : '/concert/') . $e->MaSuKien),
        ];
    }
}
