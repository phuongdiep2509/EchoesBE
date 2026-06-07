<?php

namespace App\Http\Controllers;

use App\Models\Concert;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ConcertController extends Controller
{
    // ─── Helper: resolve image path ──────────────────────
    private function resolveImage(string $filename, string $folder = 'music'): string
    {
        if (empty($filename)) return '';

        // Already a full path
        if (str_contains($filename, '/')) return $filename;

        // Try the given folder first, then the other one
        $folders = [$folder, $folder === 'music' ? 'concert' : 'music'];

        foreach ($folders as $f) {
            $base = "assets/images/{$f}/{$filename}";
            $disk = public_path($base);
            if (file_exists($disk)) return $base;

            // Extension mismatch: try common extensions
            $name = pathinfo($filename, PATHINFO_FILENAME);
            foreach (['jpg','jpeg','png','webp','gif','avif'] as $ext) {
                $try = "assets/images/{$f}/{$name}.{$ext}";
                if (file_exists(public_path($try))) return $try;
            }
        }

        // Fallback: just prepend the primary folder
        return "assets/images/{$folder}/{$filename}";
    }

    // ─── Helper: query cơ bản join địa điểm ─────────────
    // NOTE: Không join khu_vuc/hang_ve ở đây vì gây duplicate rows.
    // Hạng vé được query riêng trong show() và booking().
    private function concertQuery()
    {
        return DB::table('su_kien as sk')
            ->leftJoin('dia_diem_to_chuc as dd', 'sk.MaDiaDiem', '=', 'dd.MaDiaDiem')
            ->leftJoin('loai_su_kien as ls', 'sk.MaLoaiSuKien', '=', 'ls.MaLoaiSuKien')
            ->select([
                'sk.MaSuKien            as id',
                'sk.TenSuKien           as title',
                'sk.AnhBia              as image',
                'sk.MoTa                as description',
                'sk.DiemNoiBat          as highlights',
                'sk.ThoiGianBatDau      as event_date',
                'sk.ThoiGianKetThuc     as event_end',
                'sk.TrangThai           as status',
                'sk.DieuKienVaDieuKhoan as terms',
                'dd.TenDiaDiem          as location',
                'dd.DiaChiChiTiet       as address',
                'dd.ThanhPho            as city',
                'ls.TenLoai             as event_type',
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

    // ─── Booking page ────────────────────────────────────
    public function booking($id)
    {
        $concert = $this->concertQuery()
            ->where('sk.MaSuKien', $id)
            ->first();

        if (!$concert) abort(404);

        $hangVe = DB::table('hang_ve as hv')
            ->join('khu_vuc_su_kien as kv', 'hv.MaKhuVuc', '=', 'kv.MaKhuVuc')
            ->where('kv.MaSuKien', $id)
            ->select([
                'kv.MaKhuVuc          as zone_id',
                'kv.TenKhuVuc         as zone',
                'hv.MaHangVe          as ticket_id',
                'hv.TenHangVe         as ticket_name',
                'hv.GiaVe             as price',
                'hv.SoLuongMoBan      as total',
                'hv.SoLuongDaBan      as sold',
                'hv.QuyenLoi          as benefits',
                'hv.ThoiGianMoBan     as open_at',
                'hv.ThoiGianKetThucBan as close_at',
            ])
            ->get();

        $gheNgoi = DB::table('ghe_ngoi as g')
            ->join('khu_vuc_su_kien as kv', 'g.MaKhuVuc', '=', 'kv.MaKhuVuc')
            ->where('kv.MaSuKien', $id)
            ->select([
                'g.MaGhe      as seat_id',
                'g.HangGhe    as row',
                'g.SoGhe      as number',
                'g.TrangThai  as status',
                'g.MaKhuVuc   as zone_id',
                'kv.TenKhuVuc as zone',
            ])
            ->orderBy('g.HangGhe')
            ->orderByRaw('CAST(g.SoGhe AS UNSIGNED)')
            ->get();

        return view('pages.booking', compact('concert', 'hangVe', 'gheNgoi'));
    }

    public function publicIndex()
    {
        $concerts = $this->concertQuery()
            ->where('sk.MaLoaiSuKien', 1)                          // loại 1 = concert
            ->whereIn('sk.TrangThai', ['SapDienRa', 'DangMoBan'])
            ->orderBy('sk.ThoiGianBatDau', 'asc')
            ->get()
            ->each(fn($c) => $c->image = $this->resolveImage($c->image ?? '', 'concert'));

        return view('pages.concert', compact('concerts'));
    }

    public function show($id)
    {
        $concert = $this->findConcertByKey($id);

        if (!$concert) {
            $concert = $this->fallbackConcert($id);
        }

        // Fix image path
        if (!empty($concert->image)) {
            $concert->image = $this->resolveImage($concert->image, 'concert');
        }

        $event   = $concert;
        $eventId = $concert->id ?? null;

        $hangVe = $eventId ? DB::table('hang_ve as hv')
            ->join('khu_vuc_su_kien as kv', 'hv.MaKhuVuc', '=', 'kv.MaKhuVuc')
            ->where('kv.MaSuKien', $eventId)
            ->select([
                'hv.MaHangVe           as id',
                'kv.TenKhuVuc          as zone',
                'hv.TenHangVe          as ticket_name',
                'hv.GiaVe              as price',
                'hv.SoLuongMoBan       as total',
                'hv.SoLuongDaBan       as sold',
                'hv.QuyenLoi           as benefits',
                'hv.ThoiGianMoBan      as open_at',
                'hv.ThoiGianKetThucBan as close_at',
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

        $related = $eventId ? $this->concertQuery()
            ->where('sk.MaLoaiSuKien', 1)
            ->whereIn('sk.TrangThai', ['SapDienRa', 'DangMoBan'])
            ->where('sk.MaSuKien', '!=', $eventId)
            ->orderBy('sk.ThoiGianBatDau', 'asc')
            ->take(4)
            ->get()
            ->each(fn($r) => $r->image = $this->resolveImage($r->image ?? '', 'concert'))
            : collect();

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
