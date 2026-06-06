<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class MusicController extends Controller
{
    // ─── Helper query ────────────────────────────────────
    private function musicQuery()
    {
        return DB::table('su_kien as sk')
            ->leftJoin('dia_diem_to_chuc as dd', 'sk.MaDiaDiem', '=', 'dd.MaDiaDiem')
            ->leftJoin('loai_su_kien as ls', 'sk.MaLoaiSuKien', '=', 'ls.MaLoaiSuKien')
            ->select([
                'sk.MaSuKien        as id',
                'sk.TenSuKien       as title',
                'sk.AnhBia          as image',
                'sk.MoTa            as description',
                'sk.DiemNoiBat      as highlights',
                'sk.ThoiGianBatDau  as event_date',
                'sk.ThoiGianKetThuc as event_end',
                'sk.TrangThai       as status',
                'sk.MaDiaDiem       as dia_diem_id',
                'sk.MaBTC           as btc_id',
                'sk.MaLoaiSuKien    as loai_id',
                'dd.TenDiaDiem      as location',
                'dd.DiaChiChiTiet   as address',
                'dd.ThanhPho        as city',
                'ls.TenLoai         as event_type',
            ]);
    }

    // ─── PUBLIC ──────────────────────────────────────────

    public function index()
    {
        $events = $this->musicQuery()
            ->whereIn('sk.TrangThai', ['SapDienRa', 'DangMoBan'])
            ->orderBy('sk.ThoiGianBatDau', 'asc')
            ->get();

        return view('pages.music', compact('events'));
    }

    public function show($id)
    {
        $event = $this->musicQuery()
            ->where('sk.MaSuKien', $id)
            ->first();

        if (!$event) abort(404);

        $hangVe = DB::table('hang_ve as hv')
            ->join('khu_vuc_su_kien as kv', 'hv.MaKhuVuc', '=', 'kv.MaKhuVuc')
            ->where('kv.MaSuKien', $id)
            ->select([
                'kv.TenKhuVuc           as zone',
                'hv.TenHangVe           as ticket_name',
                'hv.GiaVe               as price',
                'hv.SoLuongMoBan        as total',
                'hv.SoLuongDaBan        as sold',
                'hv.QuyenLoi            as benefits',
                'hv.ThoiGianMoBan       as open_at',
                'hv.ThoiGianKetThucBan  as close_at',
            ])
            ->get();

        $artists = DB::table('tham_gia_bieu_dien as tg')
            ->join('nghe_si as ns', 'tg.MaNgheSi', '=', 'ns.MaNgheSi')
            ->where('tg.MaSuKien', $id)
            ->select([
                'ns.TenNgheSi        as name',
                'ns.NgheDanh         as stage_name',
                'ns.AnhDaiDien       as avatar',
                'tg.ThuTuBieuDien    as order',
                'tg.ThoiGianBieuDien as perform_at',
            ])
            ->orderBy('tg.ThuTuBieuDien')
            ->get();

        $related = $this->musicQuery()
            ->whereIn('sk.TrangThai', ['SapDienRa', 'DangMoBan'])
            ->where('sk.MaSuKien', '!=', $id)
            ->orderBy('sk.ThoiGianBatDau', 'asc')
            ->take(4)
            ->get();

        return view('pages.music-detail', compact('event', 'hangVe', 'artists', 'related'));
    }

    // ─── ADMIN ───────────────────────────────────────────

    public function adminIndex()
    {
        $events = $this->musicQuery()
            ->orderBy('sk.ThoiGianBatDau', 'desc')
            ->get();

        return view('admin.music.index', compact('events'));
    }

    public function adminCreate()
    {
        $diaDiems = DB::table('dia_diem_to_chuc')->select('MaDiaDiem', 'TenDiaDiem')->get();
        $loaiSuKiens = DB::table('loai_su_kien')->select('MaLoaiSuKien', 'TenLoai')->get();
        $banToChuc = DB::table('ban_to_chuc')->select('MaBTC', 'TenToChuc')->get();

        return view('admin.music.create', compact('diaDiems', 'loaiSuKiens', 'banToChuc'));
    }

    public function adminStore(Request $request)
    {
        $request->validate([
            'TenSuKien'       => 'required|string|max:255',
            'ThoiGianBatDau'  => 'required|date',
            'ThoiGianKetThuc' => 'required|date|after:ThoiGianBatDau',
            'TrangThai'       => 'required|in:SapDienRa,DangMoBan,DaKetThuc,DaHuy',
            'MaBTC'           => 'required|integer',
            'MaDiaDiem'       => 'required|integer',
            'MaLoaiSuKien'    => 'required|integer',
        ]);

        DB::table('su_kien')->insert([
            'MaBTC'               => $request->MaBTC,
            'MaDiaDiem'           => $request->MaDiaDiem,
            'MaLoaiSuKien'        => $request->MaLoaiSuKien,
            'TenSuKien'           => $request->TenSuKien,
            'AnhBia'              => $request->AnhBia ?? '',
            'MoTa'                => $request->MoTa,
            'DiemNoiBat'          => $request->DiemNoiBat,
            'DieuKienVaDieuKhoan' => $request->DieuKienVaDieuKhoan,
            'ThoiGianBatDau'      => $request->ThoiGianBatDau,
            'ThoiGianKetThuc'     => $request->ThoiGianKetThuc,
            'TrangThai'           => $request->TrangThai,
        ]);

        return redirect()->route('admin.music.index')->with('success', 'Đã thêm sự kiện nhạc sống.');
    }

    public function adminEdit($id)
    {
        $event = DB::table('su_kien')->where('MaSuKien', $id)->first();
        if (!$event) abort(404);

        $diaDiems    = DB::table('dia_diem_to_chuc')->select('MaDiaDiem', 'TenDiaDiem')->get();
        $loaiSuKiens = DB::table('loai_su_kien')->select('MaLoaiSuKien', 'TenLoai')->get();
        $banToChuc   = DB::table('ban_to_chuc')->select('MaBTC', 'TenToChuc')->get();

        return view('admin.music.edit', compact('event', 'diaDiems', 'loaiSuKiens', 'banToChuc'));
    }

    public function adminUpdate(Request $request, $id)
    {
        DB::table('su_kien')->where('MaSuKien', $id)->update([
            'MaBTC'               => $request->MaBTC,
            'MaDiaDiem'           => $request->MaDiaDiem,
            'MaLoaiSuKien'        => $request->MaLoaiSuKien,
            'TenSuKien'           => $request->TenSuKien,
            'AnhBia'              => $request->AnhBia,
            'MoTa'                => $request->MoTa,
            'DiemNoiBat'          => $request->DiemNoiBat,
            'DieuKienVaDieuKhoan' => $request->DieuKienVaDieuKhoan,
            'ThoiGianBatDau'      => $request->ThoiGianBatDau,
            'ThoiGianKetThuc'     => $request->ThoiGianKetThuc,
            'TrangThai'           => $request->TrangThai,
        ]);

        return redirect()->route('admin.music.index')->with('success', 'Đã cập nhật sự kiện nhạc sống.');
    }

    public function adminDestroy($id)
    {
        DB::table('su_kien')->where('MaSuKien', $id)->delete();
        return redirect()->route('admin.music.index')->with('success', 'Đã xóa sự kiện nhạc sống.');
    }
}
