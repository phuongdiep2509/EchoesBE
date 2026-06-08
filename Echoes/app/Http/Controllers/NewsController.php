<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class NewsController extends Controller
{
    // ─── PUBLIC ──────────────────────────────────────────

    public function index()
    {
        $articles = DB::table('tin_tuc_bai_viet as t')
            ->leftJoin('danh_muc_bai_viet as dm', 't.MaDanhMuc', '=', 'dm.MaDanhMuc')
            ->select([
                't.MaBaiViet   as id',
                't.TieuDe      as title',
                't.AnhDaiDien  as image',
                't.NgayDang    as published_at',
                'dm.TenDanhMuc as category',
            ])
            ->orderBy('t.NgayDang', 'desc')
            ->get()
            ->map(fn ($article) => (object) array_merge((array) $article, [
                'image' => $this->resolveNewsImageUrl($article->image),
            ]));

        $featured = $articles->take(3);

        return view('pages.news', compact('articles', 'featured'));
    }

    public function show($id)
    {
        $article = $this->findArticleByKey($id);

        if (!$article) {
            $article = $this->fallbackArticle($id);
        } else {
            $article->image = $this->resolveNewsImageUrl($article->image, $id);
        }

        $related = DB::table('tin_tuc_bai_viet')
            ->where('MaBaiViet', '!=', $article->id)
            ->orderBy('NgayDang', 'desc')
            ->take(3)
            ->select(['MaBaiViet as id', 'TieuDe as title', 'AnhDaiDien as image', 'NgayDang as published_at'])
            ->get()
            ->map(fn ($item) => (object) array_merge((array) $item, [
                'image' => $this->resolveNewsImageUrl($item->image),
            ]));

        return view('pages.news-detail', compact('article', 'related'));
    }

    private function findArticleByKey($key)
    {
        $query = DB::table('tin_tuc_bai_viet as t')
            ->leftJoin('danh_muc_bai_viet as dm', 't.MaDanhMuc', '=', 'dm.MaDanhMuc')
            ->select([
                't.MaBaiViet    as id',
                't.TieuDe       as title',
                't.NoiDung      as content',
                't.AnhDaiDien   as image',
                't.NgayDang     as published_at',
                'dm.TenDanhMuc  as category',
            ]);

        if (ctype_digit((string) $key)) {
            return $query->where('t.MaBaiViet', (int) $key)->first();
        }

        $aliases = [
            'atvncg' => 'Anh Trai Vượt Ngàn Chông Gai',
            'tu-hao-ban-sac-viet' => 'Tự Hào Bản Sắc Việt',
            'waterbomb-2025' => 'Waterbomb',
        ];

        $needle = Str::slug($aliases[$key] ?? $key);

        return $query->get()->first(function ($item) use ($needle) {
            return Str::contains(Str::slug($item->title), $needle)
                || Str::contains($needle, Str::slug($item->title));
            });
    }

    private function fallbackArticle($key): object
    {
        $titles = [
            'atvncg' => 'Chuỗi concert Anh Trai Vượt Ngàn Chông Gai chính thức khép lại',
            'tu-hao-ban-sac-viet' => 'Tự Hào Bản Sắc Việt',
            'waterbomb-2025' => 'Waterbomb 2025',
        ];

        return (object) [
            'id' => 0,
            'title' => $titles[$key] ?? Str::headline(str_replace('-', ' ', (string) $key)),
            'content' => 'Nội dung bài viết sẽ được cập nhật từ trang admin khi dữ liệu được thêm vào hệ thống.',
            'image' => $this->resolveNewsImageUrl(null, $key),
            'published_at' => null,
            'category' => 'Tin tức',
        ];
    }

    private function resolveNewsImageUrl(?string $image, ?string $fallbackKey = null): string
    {
        if (!empty($image) && preg_match('/^https?:\/\//i', trim($image))) {
            return $image;
        }

        $mapping = [
            'assets/images/news/atvncg.png' => 'https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4?auto=format&fit=crop&w=1200&q=80',
            'assets/images/news/THBSV.png' => 'https://images.unsplash.com/photo-1526948531399-320e7e40f0ca?auto=format&fit=crop&w=1200&q=80',
            'assets/images/news/wtb3.png' => 'https://images.unsplash.com/photo-1500534314209-a25ddb2bd429?auto=format&fit=crop&w=1200&q=80',
            'atvncg' => 'https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4?auto=format&fit=crop&w=1200&q=80',
            'tu-hao-ban-sac-viet' => 'https://images.unsplash.com/photo-1526948531399-320e7e40f0ca?auto=format&fit=crop&w=1200&q=80',
            'waterbomb-2025' => 'https://images.unsplash.com/photo-1500534314209-a25ddb2bd429?auto=format&fit=crop&w=1200&q=80',
            'skywave_news.jpg' => 'https://images.unsplash.com/photo-1497032205916-ac775f0649ae?auto=format&fit=crop&w=1200&q=80',
            'news1.jpg' => 'https://images.unsplash.com/photo-1500534623283-312aade485b7?auto=format&fit=crop&w=1200&q=80',
            'news2.jpg' => 'https://images.unsplash.com/photo-1504805572947-34fad45aed93?auto=format&fit=crop&w=1200&q=80',
            'news3.jpg' => 'https://images.unsplash.com/photo-1474631245212-32dc3c8310c6?auto=format&fit=crop&w=1200&q=80',
            'news4.jpg' => 'https://images.unsplash.com/photo-1517142089942-ba376ce32a2e?auto=format&fit=crop&w=1200&q=80',
            'news5.jpg' => 'https://images.unsplash.com/photo-1495121605193-b116b5b09f8b?auto=format&fit=crop&w=1200&q=80',
            'news6.jpg' => 'https://images.unsplash.com/photo-1497032205916-ac775f0649ae?auto=format&fit=crop&w=1200&q=80',
        ];

        $normalizedImage = trim((string) $image);
        if (isset($mapping[$normalizedImage])) {
            return $mapping[$normalizedImage];
        }

        if ($fallbackKey !== null && isset($mapping[$fallbackKey])) {
            return $mapping[$fallbackKey];
        }

        if ($normalizedImage !== '') {
            return $this->getDefaultNewsImage();
        }

        return $this->getDefaultNewsImage();
    }

    private function getDefaultNewsImage(): string
    {
        return 'https://images.unsplash.com/photo-1497493292307-31c376b6e479?auto=format&fit=crop&w=1200&q=80';
    }

    // ─── ADMIN ───────────────────────────────────────────

    public function adminIndex()
    {
        $articles = DB::table('tin_tuc_bai_viet as t')
            ->leftJoin('danh_muc_bai_viet as dm', 't.MaDanhMuc', '=', 'dm.MaDanhMuc')
            ->select([
                't.MaBaiViet   as id',
                't.TieuDe      as title',
                't.AnhDaiDien  as image',
                't.NgayDang    as published_at',
                'dm.TenDanhMuc as category',
            ])
            ->orderBy('t.NgayDang', 'desc')
            ->get();

        return view('admin.news.index', compact('articles'));
    }

    public function adminCreate()
    {
        $categories = DB::table('danh_muc_bai_viet')->select('MaDanhMuc', 'TenDanhMuc')->get();
        $events     = DB::table('su_kien')->select('MaSuKien', 'TenSuKien')->get();

        return view('admin.news.create', compact('categories', 'events'));
    }

    public function adminStore(Request $request)
    {
        $request->validate([
            'TieuDe'     => 'required|string|max:255',
            'NoiDung'    => 'required',
            'MaDanhMuc'  => 'required|integer',
            'MaNhanVien' => 'required|integer',
        ]);

        DB::table('tin_tuc_bai_viet')->insert([
            'MaNhanVien'       => $request->MaNhanVien,
            'TieuDe'           => $request->TieuDe,
            'NoiDung'          => $request->NoiDung,
            'AnhDaiDien'       => $request->AnhDaiDien,
            'NgayDang'         => now(),
            'MaDanhMuc'        => $request->MaDanhMuc,
            'MaSuKienLienQuan' => $request->MaSuKienLienQuan ?: null,
        ]);

        return redirect()->route('admin.news.index')->with('success', 'Đã thêm bài viết.');
    }

    public function adminEdit($id)
    {
        $article    = DB::table('tin_tuc_bai_viet')->where('MaBaiViet', $id)->first();
        if (!$article) abort(404);

        $categories = DB::table('danh_muc_bai_viet')->select('MaDanhMuc', 'TenDanhMuc')->get();
        $events     = DB::table('su_kien')->select('MaSuKien', 'TenSuKien')->get();

        return view('admin.news.edit', compact('article', 'categories', 'events'));
    }

    public function adminUpdate(Request $request, $id)
    {
        DB::table('tin_tuc_bai_viet')->where('MaBaiViet', $id)->update([
            'TieuDe'           => $request->TieuDe,
            'NoiDung'          => $request->NoiDung,
            'AnhDaiDien'       => $request->AnhDaiDien,
            'MaDanhMuc'        => $request->MaDanhMuc,
            'MaSuKienLienQuan' => $request->MaSuKienLienQuan ?: null,
        ]);

        return redirect()->route('admin.news.index')->with('success', 'Đã cập nhật bài viết.');
    }

    public function adminDestroy($id)
    {
        DB::table('tin_tuc_bai_viet')->where('MaBaiViet', $id)->delete();
        return redirect()->route('admin.news.index')->with('success', 'Đã xóa bài viết.');
    }
}
