<?php

namespace App\Mail;

use App\Models\DonHang;
use App\Models\ThanhToan;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class OrderPaymentSuccessMail extends Mailable
{
    use Queueable, SerializesModels;

    public DonHang $order;
    public Collection $ticketItems;
    public ThanhToan $payment;

    public function __construct(DonHang $order, Collection $ticketItems, ThanhToan $payment)
    {
        $this->order = $order;
        $this->ticketItems = $ticketItems;
        $this->payment = $payment;
    }

    public function build(): self
    {
        return $this
            ->subject('Echoes - Xác nhận đặt vé thành công')
            ->view('emails.order-payment-success')
            ->with([
                'order' => $this->order,
                'ticketItems' => $this->ticketItems,
                'payment' => $this->payment,
                'brandName' => 'Echoes',
            ]);
    }
}
