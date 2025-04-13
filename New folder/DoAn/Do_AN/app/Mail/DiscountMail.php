<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DiscountMail extends Mailable
{
    use Queueable, SerializesModels;

    public $discountCode;

    public function __construct($discountCode)
    {
        $this->discountCode = $discountCode;
    }

    public function build()
    {
        return $this->subject('Mã Giảm Giá Đầu Tiên Của Bạn')
                    ->view('emails.discount')
                    ->with(['discountCode' => $this->discountCode]);
    }
}
