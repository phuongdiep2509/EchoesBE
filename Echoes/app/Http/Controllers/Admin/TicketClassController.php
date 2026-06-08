<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Concert;
use App\Models\KhuVuc;
use App\Models\TicketClass;
use Illuminate\Http\Request;
use App\Events\TicketQuantityUpdated;

class TicketClassController extends Controller
{
    public function index(Request $request)
    {
        $concert_id = $request->get('concert_id');
        $query = TicketClass::with(['khuVuc.concert']);
        
        if ($concert_id) {
            $query->whereHas('khuVuc', function($q) use ($concert_id) {
                $q->where('MaSuKien', $concert_id);
            });
        }
        
        $tickets = $query->get();
        $concerts = Concert::all();
        
        return view('admin.hang-ve.index', compact('tickets', 'concerts', 'concert_id'));
    }

    public function create()
    {
        $concerts = Concert::all();
        $khuVucs = KhuVuc::select('TenKhuVuc')->distinct()->pluck('TenKhuVuc');
        return view('admin.hang-ve.create', compact('concerts', 'khuVucs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'MaSuKien' => 'required|exists:su_kien,MaSuKien',
            'KhuVuc'   => 'required|string|max:255',
            'TenHangVe' => 'required|string|max:100',
            'GiaVe' => 'required|numeric',
            'SoLuongMoBan' => 'required|integer|min:1',
            'QuyenLoi' => 'nullable|string',
            'ThoiGianMoBan' => 'nullable|date',
            'ThoiGianKetThucBan' => 'nullable|date|after:ThoiGianMoBan',
        ]);

        $data = $request->all();
        $maxId = TicketClass::max('MaHangVe') ?? 0;

        // Auto find or create KhuVuc for this concert
        $maSuKien = $data['MaSuKien'];
        $tenKhuVuc = trim($data['KhuVuc']);
        $khuVuc = KhuVuc::where('MaSuKien', $maSuKien)->where('TenKhuVuc', $tenKhuVuc)->first();
        if (!$khuVuc) {
            $maxKhuVucId = KhuVuc::max('MaKhuVuc') ?? 0;
            $khuVuc = new KhuVuc();
            $khuVuc->MaKhuVuc = $maxKhuVucId + 1;
            $khuVuc->MaSuKien = $maSuKien;
            $khuVuc->TenKhuVuc = $tenKhuVuc;
            $khuVuc->SucChua = 1000;
            $khuVuc->save();
        }
        
        $ticket = new TicketClass();
        $ticket->MaHangVe = $maxId + 1;
        $ticket->MaKhuVuc = $khuVuc->MaKhuVuc;
        $ticket->TenHangVe = $data['TenHangVe'];
        $ticket->GiaVe = $data['GiaVe'];
        $ticket->SoLuongMoBan = $data['SoLuongMoBan'];
        $ticket->SoLuongDaBan = 0;
        $ticket->ThoiGianMoBan = $data['ThoiGianMoBan'] ?? null;
        $ticket->ThoiGianKetThucBan = $data['ThoiGianKetThucBan'] ?? null;
        $ticket->QuyenLoi = $data['QuyenLoi'] ?? null;
        $ticket->save();

        return redirect()->route('admin.hang-ve.index')->with('success', 'Đã thêm hạng vé thành công.');
    }

    public function show($id)
    {
        $ticket = TicketClass::with(['khuVuc.concert'])->findOrFail($id);
        return view('admin.hang-ve.show', compact('ticket'));
    }

    public function edit($id)
    {
        $ticket = TicketClass::findOrFail($id);
        $concerts = Concert::all();
        $khuVucs = KhuVuc::select('TenKhuVuc')->distinct()->pluck('TenKhuVuc');
        return view('admin.hang-ve.edit', compact('ticket', 'concerts', 'khuVucs'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'KhuVuc'    => 'required|string|max:255',
            'TenHangVe' => 'required|string|max:100',
            'GiaVe' => 'required|numeric',
            'SoLuongMoBan' => 'required|integer|min:0',
            'SoLuongDaBan' => 'required|integer|min:0|lte:SoLuongMoBan',
        ]);

        $ticket = TicketClass::findOrFail($id);
        
        $tenKhuVuc = trim($request->KhuVuc);
        $maSuKien = $ticket->khuVuc->MaSuKien;

        $khuVuc = KhuVuc::where('MaSuKien', $maSuKien)->where('TenKhuVuc', $tenKhuVuc)->first();
        if (!$khuVuc) {
            $maxKhuVucId = KhuVuc::max('MaKhuVuc') ?? 0;
            $khuVuc = new KhuVuc();
            $khuVuc->MaKhuVuc = $maxKhuVucId + 1;
            $khuVuc->MaSuKien = $maSuKien;
            $khuVuc->TenKhuVuc = $tenKhuVuc;
            $khuVuc->SucChua = 1000;
            $khuVuc->save();
        }

        $ticket->MaKhuVuc = $khuVuc->MaKhuVuc;
        $ticket->TenHangVe = $request->TenHangVe;
        $ticket->GiaVe = $request->GiaVe;
        $ticket->SoLuongMoBan = $request->SoLuongMoBan;
        $ticket->SoLuongDaBan = $request->SoLuongDaBan;
        $ticket->save();

        // Lấy concert id để broadcast
        $eventId = $ticket->khuVuc->MaSuKien ?? null;

        if ($eventId) {
            broadcast(new TicketQuantityUpdated(
                $ticket->MaHangVe,
                $ticket->SoLuongDaBan,
                $ticket->SoLuongMoBan,
                $eventId
            ))->toOthers();
        }

        return redirect()->route('admin.hang-ve.index')->with('success', 'Đã cập nhật hạng vé và đồng bộ realtime.');
    }

    public function updateStatus(Request $request, $id)
    {
        try {
            $request->validate([
                'TrangThai' => 'required|in:DangMoBan,TamDung,HetVe,DaHuy',
            ]);

            $ticket = TicketClass::findOrFail($id);
            $ticket->TrangThai = $request->TrangThai;
            $ticket->save();

            if ($request->wantsJson()) {
                return response()->json(['success' => true, 'message' => 'Đã cập nhật trạng thái hạng vé thành công.']);
            }

            return redirect()->route('admin.hang-ve.index')->with('success', 'Đã cập nhật trạng thái hạng vé.');
        } catch (\Exception $e) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Thay đổi trạng thái thất bại: ' . $e->getMessage()], 400);
            }
            return redirect()->route('admin.hang-ve.index')->with('error', 'Thay đổi trạng thái thất bại.');
        }
    }
}
