<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LowStockNotification extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public $products) {}

    public function build()
    {
        return $this->subject(' ⚠️ Peringatan Stok Rendah - TuquParcel')
                    ->view('emails.low-stock-notification');
    }
}
