<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Review;

class ReviewNotification extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Review $review) {}

    public function build()
    {
        return $this->subject('📩 Review Baru Menunggu Persetujuan - TuquParcel')
                    ->view('emails.review-notification');
    }
}
