<?php

namespace App\Http\Controllers;

use App\Models\Concert;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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

    public function index(Request $request)
    {
        $search = $request->get('search');
        $query = Concert::orderBy('ThoiGianBatDau', 'desc');
        $concerts = $query->get();

        if ($search) {
            $searchTerm = mb_strtolower($search, 'UTF-8');
            $concerts = $concerts->filter(function ($concert) use ($searchTerm) {
                return mb_stripos(mb_strtolower($concert->TenSuKien, 'UTF-8'), $searchTerm) !== false;
            });
        }

        return view('admin.concerts.index', compact('concerts', 'search'));
    }

    public function create()
    {
        $banToChucs = DB::table('ban_to_chuc')->get();
        $diaDiems = DB::table('dia_diem_to_chuc')->get();
        $loaiSuKiens = \App\Models\LoaiSuKien::all();
        return view('admin.concerts.create', compact('banToChucs', 'diaDiems', 'loaiSuKiens'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'TenSuKien'       => 'required|string|max:255',
            'ThoiGianBatDau'  => 'required|date',
            'ThoiGianKetThuc' => 'required|date|after:ThoiGianBatDau',
            'TrangThai'       => 'required|in:SapDienRa,DangMoBan,DaKetThuc,DaHuy',
            'BanToChuc'       => 'required|string',
            'DiaDiem'         => 'required|string',
            'MaLoaiSuKien'    => 'required|integer',
        ]);

        $tenBanToChuc = trim($request->BanToChuc);
        $btc = DB::table('ban_to_chuc')->where('TenToChuc', $tenBanToChuc)->first();
        if ($btc) {
            $maBTC = $btc->MaBTC;
        } else {
            $maxBtcId = DB::table('ban_to_chuc')->max('MaBTC') ?? 0;
            $maBTC = $maxBtcId + 1;
            DB::table('ban_to_chuc')->insert([
                'MaBTC' => $maBTC,
                'TenToChuc' => $tenBanToChuc
            ]);
        }

        $tenDiaDiem = trim($request->DiaDiem);
        $dd = DB::table('dia_diem_to_chuc')->where('TenDiaDiem', $tenDiaDiem)->first();
        if ($dd) {
            $maDiaDiem = $dd->MaDiaDiem;
        } else {
            $maxDdId = DB::table('dia_diem_to_chuc')->max('MaDiaDiem') ?? 0;
            $maDiaDiem = $maxDdId + 1;
            DB::table('dia_diem_to_chuc')->insert([
                'MaDiaDiem' => $maDiaDiem,
                'TenDiaDiem' => $tenDiaDiem,
                'DiaChiChiTiet' => 'Đang cập nhật',
                'ThanhPho' => 'Đang cập nhật',
                'SucChuaToiDa' => 0
            ]);
        }

        $maxId = Concert::max('MaSuKien') ?? 0;

        $concert = new Concert();
        $concert->MaSuKien = $maxId + 1;
        $concert->MaBTC = $maBTC;
        $concert->MaDiaDiem = $maDiaDiem;
        $concert->MaLoaiSuKien = $request->MaLoaiSuKien;
        $concert->TenSuKien = $request->TenSuKien;
        $concert->AnhBia = $request->AnhBia;
        $concert->MoTa = $request->MoTa;
        $concert->DiemNoiBat = $request->DiemNoiBat;
        $concert->DieuKienVaDieuKhoan = $request->DieuKienVaDieuKhoan;
        $concert->ThoiGianBatDau = $request->ThoiGianBatDau;
        $concert->ThoiGianKetThuc = $request->ThoiGianKetThuc;
        $concert->TrangThai = $request->TrangThai;
        $concert->save();

        return redirect()->route('admin.concerts.index')->with('success', 'Đã thêm sự kiện thành công.');
    }

    public function edit($id)
    {
        $concert = Concert::findOrFail($id);
        $banToChucs = DB::table('ban_to_chuc')->get();
        $diaDiems = DB::table('dia_diem_to_chuc')->get();
        $loaiSuKiens = \App\Models\LoaiSuKien::all();
        return view('admin.concerts.edit', compact('concert', 'banToChucs', 'diaDiems', 'loaiSuKiens'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'TenSuKien'       => 'required|string|max:255',
            'ThoiGianBatDau'  => 'required|date',
            'ThoiGianKetThuc' => 'required|date|after:ThoiGianBatDau',
            'TrangThai'       => 'required|in:SapDienRa,DangMoBan,DaKetThuc,DaHuy',
            'BanToChuc'       => 'required|string',
            'DiaDiem'         => 'required|string',
            'MaLoaiSuKien'    => 'required|integer',
        ]);

        $tenBanToChuc = trim($request->BanToChuc);
        $btc = DB::table('ban_to_chuc')->where('TenToChuc', $tenBanToChuc)->first();
        if ($btc) {
            $maBTC = $btc->MaBTC;
        } else {
            $maxBtcId = DB::table('ban_to_chuc')->max('MaBTC') ?? 0;
            $maBTC = $maxBtcId + 1;
            DB::table('ban_to_chuc')->insert([
                'MaBTC' => $maBTC,
                'TenToChuc' => $tenBanToChuc
            ]);
        }

        $tenDiaDiem = trim($request->DiaDiem);
        $dd = DB::table('dia_diem_to_chuc')->where('TenDiaDiem', $tenDiaDiem)->first();
        if ($dd) {
            $maDiaDiem = $dd->MaDiaDiem;
        } else {
            $maxDdId = DB::table('dia_diem_to_chuc')->max('MaDiaDiem') ?? 0;
            $maDiaDiem = $maxDdId + 1;
            DB::table('dia_diem_to_chuc')->insert([
                'MaDiaDiem' => $maDiaDiem,
                'TenDiaDiem' => $tenDiaDiem,
                'DiaChiChiTiet' => 'Đang cập nhật',
                'ThanhPho' => 'Đang cập nhật',
                'SucChuaToiDa' => 0
            ]);
        }

        $concert = Concert::findOrFail($id);
        $concert->update(array_merge($request->only([
            'MaLoaiSuKien', 'TenSuKien', 'AnhBia', 'MoTa',
            'DiemNoiBat', 'DieuKienVaDieuKhoan',
            'ThoiGianBatDau', 'ThoiGianKetThuc', 'TrangThai',
        ]), [
            'MaBTC' => $maBTC,
            'MaDiaDiem' => $maDiaDiem,
        ]));

        return redirect()->route('admin.concerts.index')->with('success', 'Đã cập nhật sự kiện.');
    }

    public function cancel($id)
    {
        $concert = Concert::findOrFail($id);
        $concert->TrangThai = 'DaHuy';
        $concert->save();

        return redirect()->route('admin.concerts.index')->with('success', 'Đã hủy sự kiện thành công.');
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
        $concert = $this->findConcertByKey($id);

        if (!$concert) {
            $concert = $this->fallbackConcert($id);
        }

        $event = $concert;
        $eventId = $concert->id ?? null;

        // Lấy hạng vé của concert này (qua khu vực)
        $hangVe = $eventId ? DB::table('hang_ve as hv')
            ->join('khu_vuc_su_kien as kv', 'hv.MaKhuVuc', '=', 'kv.MaKhuVuc')
            ->where('kv.MaSuKien', $eventId)
            ->select([
                'hv.MaHangVe as id',
                'kv.TenKhuVuc as zone',
                'hv.TenHangVe as ticket_name',
                'hv.GiaVe      as price',
                'hv.SoLuongMoBan  as total',
                'hv.SoLuongDaBan  as sold',
                'hv.QuyenLoi   as benefits',
                'hv.ThoiGianMoBan      as open_at',
                'hv.ThoiGianKetThucBan as close_at',
            ])
            ->get() : collect();

        // Nghệ sĩ biểu diễn
        $artists = $eventId ? DB::table('tham_gia_bieu_dien as tg')
            ->join('nghe_si as ns', 'tg.MaNgheSi', '=', 'ns.MaNgheSi')
            ->where('tg.MaSuKien', $eventId)
            ->select([
                'ns.TenNgheSi   as name',
                'ns.NgheDanh    as stage_name',
                'ns.AnhDaiDien  as avatar',
                'tg.ThuTuBieuDien as order',
                'tg.ThoiGianBieuDien as perform_at',
            ])
            ->orderBy('tg.ThuTuBieuDien')
            ->get() : collect();

        $related = $eventId ? $this->concertQuery()
            ->whereIn('sk.TrangThai', ['SapDienRa', 'DangMoBan'])
            ->where('sk.MaSuKien', '!=', $eventId)
            ->orderBy('sk.ThoiGianBatDau', 'asc')
            ->take(4)
            ->get() : collect();

        return view('pages.concert-detail', compact('concert', 'event', 'hangVe', 'artists', 'related'));
    }

    private function findConcertByKey($key)
    {
        if (ctype_digit((string) $key)) {
            return $this->concertQuery()->where('sk.MaSuKien', (int) $key)->first();
        }

        $aliases = [
            'YConcert' => 'Y CONCERT 2025',
            'nhung-thanh-pho-mo-mang' => 'Những Thành Phố Mơ Màng',
            'anh-trai-say-hi-2025' => 'ANH TRAI SAY HI',
            'mr-siro-concert' => 'Ai Cũng Giấu Trong Lòng Tảng Băng',
        ];

        $needle = Str::slug($aliases[$key] ?? $key);

        return $this->concertQuery()
            ->whereIn('sk.TrangThai', ['SapDienRa', 'DangMoBan'])
            ->get()
            ->first(function ($item) use ($needle) {
                return Str::contains(Str::slug($item->title), $needle)
                    || Str::contains($needle, Str::slug($item->title));
            });
    }

    private function fallbackConcert($key): object
    {
        $titles = [
            'YConcert' => 'Y Concert 2025',
            'nhung-thanh-pho-mo-mang' => 'Những Thành Phố Mơ Màng Year End 2025',
            'anh-trai-say-hi-2025' => 'Anh Trai Say Hi 2025 Concert',
            'mr-siro-concert' => 'Ai Cũng Giấu Trong Lòng Tảng Băng',
        ];

        return (object) [
            'id' => null,
            'title' => $titles[$key] ?? Str::headline(str_replace('-', ' ', (string) $key)),
            'image' => 'assets/images/concert/hot1.png',
            'description' => 'Thông tin sự kiện sẽ được cập nhật từ trang admin khi dữ liệu được thêm vào hệ thống.',
            'highlights' => null,
            'event_date' => 'Đang cập nhật',
            'event_end' => null,
            'status' => 'SapDienRa',
            'location' => 'Đang cập nhật',
            'address' => 'Đang cập nhật',
            'city' => null,
        ];
    }
}
