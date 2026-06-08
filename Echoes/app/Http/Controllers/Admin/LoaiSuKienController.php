<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LoaiSuKien;
use Illuminate\Http\Request;

class LoaiSuKienController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $query = LoaiSuKien::query();

        $categories = $query->get();

        if ($search) {
            $searchTerm = mb_strtolower($search, 'UTF-8');
            $categories = $categories->filter(function ($category) use ($searchTerm) {
                return mb_stripos(mb_strtolower($category->TenLoai, 'UTF-8'), $searchTerm) !== false;
            });
        }

        return view('admin.loai-su-kien.index', compact('categories', 'search'));
    }

    public function create()
    {
        return view('admin.loai-su-kien.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'TenLoai' => 'required|string|max:100',
        ]);

        $maxId = LoaiSuKien::max('MaLoaiSuKien') ?? 0;

        $category = new LoaiSuKien();
        $category->MaLoaiSuKien = $maxId + 1;
        $category->TenLoai = $request->TenLoai;
        $category->save();

        return redirect()->route('admin.loai-su-kien.index')->with('success', 'Đã thêm danh mục sự kiện thành công.');
    }

    public function show($id)
    {
        $category = LoaiSuKien::with('concerts')->findOrFail($id);
        return view('admin.loai-su-kien.show', compact('category'));
    }

    public function edit($id)
    {
        $category = LoaiSuKien::findOrFail($id);
        return view('admin.loai-su-kien.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'TenLoai' => 'required|string|max:100',
        ]);

        $category = LoaiSuKien::findOrFail($id);
        $category->update($request->only('TenLoai'));

        return redirect()->route('admin.loai-su-kien.index')->with('success', 'Đã cập nhật danh mục sự kiện thành công.');
    }
}
