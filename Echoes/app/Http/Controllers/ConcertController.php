<?php

namespace App\Http\Controllers;

use App\Models\Concert;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ConcertController extends Controller
{
    // ─── Helper: query cơ bản join địa điểm ─────────────
    private function concertQuery()
    {
        return DB::table('su_kien as sk')
            ->leftJoin('dia_diem_to_chuc as dd', 'sk.MaDiaDiem', '=', 'dd.MaDiaDiem')
            ->select([
                'sk.MaSuKien   as id',
                'sk.TenSuKien  as title',
                'sk.AnhBia     as image',
                'sk.MoTa       as description',
                'sk.DiemNoiBat as highlights',
                'sk.ThoiGianBatDau  as event_date',
                'sk.ThoiGianKetThuc as event_end',
                'sk.TrangThai  as status',
                'dd.TenDiaDiem as location',
                'dd.DiaChiChiTiet as address',
                'dd.ThanhPho   as city',
            ]);
    }

    // ─── ADMIN ───────────────────────────────────────────

    public function index()
    {
        $concerts = Concert::orderBy('ThoiGianBatDau', 'desc')->get();
        return view('admin.concerts.index', compact('concerts'));
    }

    public function create()
    {
        return view('admin.concerts.create');
    }

    public function store(Request $request)
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

        Concert::create($request->only([
            'MaBTC', 'MaDiaDiem', 'MaLoaiSuKien',
            'TenSuKien', 'AnhBia', 'MoTa',
            'DiemNoiBat', 'DieuKienVaDieuKhoan',
            'ThoiGianBatDau', 'ThoiGianKetThuc', 'TrangThai',
        ]));

        return redirect()->route('admin.concerts.index')->with('success', 'Đã thêm sự kiện thành công.');
    }

    public function edit($id)
    {
        $concert = Concert::findOrFail($id);
        return view('admin.concerts.edit', compact('concert'));
    }

    public function update(Request $request, $id)
    {
        $concert = Concert::findOrFail($id);
        $concert->update($request->only([
            'MaBTC', 'MaDiaDiem', 'MaLoaiSuKien',
            'TenSuKien', 'AnhBia', 'MoTa',
            'DiemNoiBat', 'DieuKienVaDieuKhoan',
            'ThoiGianBatDau', 'ThoiGianKetThuc', 'TrangThai',
        ]));

        return redirect()->route('admin.concerts.index')->with('success', 'Đã cập nhật sự kiện.');
    }

    public function destroy($id)
    {
        Concert::findOrFail($id)->delete();
        return redirect()->route('admin.concerts.index')->with('success', 'Đã xóa sự kiện.');
    }

    // ─── PUBLIC ──────────────────────────────────────────

    public function publicIndex()
    {
        $concerts = $this->concertQuery()
            ->whereIn('sk.TrangThai', ['SapDienRa', 'DangMoBan'])
            ->orderBy('sk.ThoiGianBatDau', 'asc')
            ->get();

        return view('pages.concert', compact('concerts'));
    }

    public function show($id)
    {
        $concert = $this->concertQuery()
            ->where('sk.MaSuKien', $id)
            ->first();

        if (!$concert) abort(404);

        // Lấy hạng vé của concert này (qua khu vực)
        $hangVe = DB::table('hang_ve as hv')
            ->join('khu_vuc_su_kien as kv', 'hv.MaKhuVuc', '=', 'kv.MaKhuVuc')
            ->where('kv.MaSuKien', $id)
            ->select([
                'kv.TenKhuVuc as zone',
                'hv.TenHangVe as ticket_name',
                'hv.GiaVe      as price',
                'hv.SoLuongMoBan  as total',
                'hv.SoLuongDaBan  as sold',
                'hv.QuyenLoi   as benefits',
                'hv.ThoiGianMoBan      as open_at',
                'hv.ThoiGianKetThucBan as close_at',
            ])
            ->get();

        // Nghệ sĩ biểu diễn
        $artists = DB::table('tham_gia_bieu_dien as tg')
            ->join('nghe_si as ns', 'tg.MaNgheSi', '=', 'ns.MaNgheSi')
            ->where('tg.MaSuKien', $id)
            ->select([
                'ns.TenNgheSi   as name',
                'ns.NgheDanh    as stage_name',
                'ns.AnhDaiDien  as avatar',
                'tg.ThuTuBieuDien as order',
                'tg.ThoiGianBieuDien as perform_at',
            ])
            ->orderBy('tg.ThuTuBieuDien')
            ->get();

        $related = $this->concertQuery()
            ->whereIn('sk.TrangThai', ['SapDienRa', 'DangMoBan'])
            ->where('sk.MaSuKien', '!=', $id)
            ->orderBy('sk.ThoiGianBatDau', 'asc')
            ->take(4)
            ->get();

        return view('pages.concert-detail', compact('concert', 'hangVe', 'artists', 'related'));
    }
}
