<?php

namespace App\Mail;

use App\Models\VeTang;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class TicketGiftNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public VeTang $gift;
    public object|null $ticket;
    public string $receiveUrl;

    public function __construct(int $giftId)
    {
        $this->gift = VeTang::with('nguoiTang')->findOrFail($giftId);
        $this->ticket = DB::table('ve as v')
            ->join('su_kien as sk', 'v.MaSuKien', '=', 'sk.MaSuKien')
            ->join('hang_ve as hv', 'v.MaHangVe', '=', 'hv.MaHangVe')
            ->leftJoin('khu_vuc_su_kien as kv', 'hv.MaKhuVuc', '=', 'kv.MaKhuVuc')
            ->leftJoin('dia_diem_to_chuc as dd', 'sk.MaDiaDiem', '=', 'dd.MaDiaDiem')
            ->leftJoin('ghe_ngoi as gn', 'v.MaGhe', '=', 'gn.MaGhe')
            ->where('v.MaVe', $this->gift->MaVe)
            ->select([
                'v.MaVe',
                'v.MaVeDienTu',
                'sk.TenSuKien',
                'sk.AnhBia',
                'sk.ThoiGianBatDau',
                'sk.ThoiGianKetThuc',
                'dd.TenDiaDiem',
                'dd.DiaChiChiTiet',
                'dd.ThanhPho',
                'hv.TenHangVe',
                'kv.TenKhuVuc',
                'gn.HangGhe',
                'gn.SoGhe',
            ])
            ->first();

        $this->receiveUrl = route('ticket-gifts.receive', $this->gift->TokenNhanVe);
    }

    public function build(): self
    {
        return $this
            ->subject('Echoes - Bạn vừa được tặng một vé sự kiện')
            ->view('emails.ticket-gift-notification');
    }
}
