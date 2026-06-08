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
        // For simplicity, we just pass all concerts.
        // In a real app, might want to use ajax to load KhuVuc based on Concert
        $concerts = Concert::with('khuVuc')->get();
        return view('admin.hang-ve.create', compact('concerts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'MaKhuVuc' => 'required|exists:khu_vuc_su_kien,MaKhuVuc',
            'TenHangVe' => 'required|string|max:100',
            'GiaVe' => 'required|numeric',
            'SoLuongMoBan' => 'required|integer|min:1',
            'QuyenLoi' => 'nullable|string',
            'ThoiGianMoBan' => 'nullable|date',
            'ThoiGianKetThucBan' => 'nullable|date|after:ThoiGianMoBan',
        ]);

        $data = $request->all();
        $maxId = TicketClass::max('MaHangVe') ?? 0;
        
        $ticket = new TicketClass();
        $ticket->MaHangVe = $maxId + 1;
        $ticket->MaKhuVuc = $data['MaKhuVuc'];
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

    public function edit($id)
    {
        $ticket = TicketClass::findOrFail($id);
        $concerts = Concert::with('khuVuc')->get();
        return view('admin.hang-ve.edit', compact('ticket', 'concerts'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'TenHangVe' => 'required|string|max:100',
            'GiaVe' => 'required|numeric',
            'SoLuongMoBan' => 'required|integer|min:0',
            'SoLuongDaBan' => 'required|integer|min:0|lte:SoLuongMoBan',
        ]);

        $ticket = TicketClass::findOrFail($id);
        $ticket->update($request->only('TenHangVe', 'GiaVe', 'SoLuongMoBan', 'SoLuongDaBan'));

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

    public function destroy($id)
    {
        $ticket = TicketClass::findOrFail($id);
        $ticket->delete();

        return redirect()->route('admin.hang-ve.index')->with('success', 'Đã xóa hạng vé.');
    }
}
