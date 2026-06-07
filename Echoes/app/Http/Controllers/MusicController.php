<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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
        $event = $this->findMusicByKey($id);

        if (!$event) {
            $event = $this->fallbackMusic($id);
        }

        $eventId = $event->id ?? null;

        $hangVe = $eventId ? DB::table('hang_ve as hv')
            ->join('khu_vuc_su_kien as kv', 'hv.MaKhuVuc', '=', 'kv.MaKhuVuc')
            ->where('kv.MaSuKien', $eventId)
            ->select([
                'hv.MaHangVe             as id',
                'kv.TenKhuVuc           as zone',
                'hv.TenHangVe           as ticket_name',
                'hv.GiaVe               as price',
                'hv.SoLuongMoBan        as total',
                'hv.SoLuongDaBan        as sold',
                'hv.QuyenLoi            as benefits',
                'hv.ThoiGianMoBan       as open_at',
                'hv.ThoiGianKetThucBan  as close_at',
            ])
            ->get() : collect();

        $artists = $eventId ? DB::table('tham_gia_bieu_dien as tg')
            ->join('nghe_si as ns', 'tg.MaNgheSi', '=', 'ns.MaNgheSi')
            ->where('tg.MaSuKien', $eventId)
            ->select([
                'ns.TenNgheSi        as name',
                'ns.NgheDanh         as stage_name',
                'ns.AnhDaiDien       as avatar',
                'tg.ThuTuBieuDien    as order',
                'tg.ThoiGianBieuDien as perform_at',
            ])
            ->orderBy('tg.ThuTuBieuDien')
            ->get() : collect();

        $related = $eventId ? $this->musicQuery()
            ->whereIn('sk.TrangThai', ['SapDienRa', 'DangMoBan'])
            ->where('sk.MaSuKien', '!=', $eventId)
            ->orderBy('sk.ThoiGianBatDau', 'asc')
            ->take(4)
            ->get() : collect();

        return view('pages.music-detail', compact('event', 'hangVe', 'artists', 'related'));
    }

    private function findMusicByKey($key)
    {
        if (ctype_digit((string) $key)) {
            return $this->musicQuery()->where('sk.MaSuKien', (int) $key)->first();
        }

        $aliases = [
            'concert-bon-canh-chim-troi' => 'Bốn Cánh Chim Trời',
            'a-tale-of-two-christmas' => 'A Tale Of Two Christmas',
            'when-i-remember-this-life' => 'When I Remember This Life',
            'concert-the-rose' => 'The Rose',
        ];

        $needle = Str::slug($aliases[$key] ?? $key);

        return $this->musicQuery()
            ->whereIn('sk.TrangThai', ['SapDienRa', 'DangMoBan'])
            ->get()
            ->first(function ($item) use ($needle) {
                return Str::contains(Str::slug($item->title), $needle)
                    || Str::contains($needle, Str::slug($item->title));
            });
    }

    private function fallbackMusic($key): object
    {
        $titles = [
            'concert-bon-canh-chim-troi' => 'Bốn Cánh Chim Trời',
            'a-tale-of-two-christmas' => 'A Tale Of Two Christmas',
            'when-i-remember-this-life' => 'When I Remember This Life',
            'concert-the-rose' => 'The Rose',
        ];

        return (object) [
            'id' => null,
            'title' => $titles[$key] ?? Str::headline(str_replace('-', ' ', (string) $key)),
            'image' => 'assets/images/music/lc16.1.jpg',
            'description' => 'Thông tin sự kiện sẽ được cập nhật từ trang admin khi dữ liệu được thêm vào hệ thống.',
            'highlights' => null,
            'event_date' => 'Đang cập nhật',
            'event_end' => null,
            'status' => 'SapDienRa',
            'location' => 'Đang cập nhật',
            'address' => 'Đang cập nhật',
            'city' => null,
            'event_type' => 'Nhạc sống',
        ];
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
