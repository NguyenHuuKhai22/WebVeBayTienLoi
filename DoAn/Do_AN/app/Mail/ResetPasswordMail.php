<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public $hoTen;
    public $newPassword;

    public function __construct($hoTen, $newPassword)
    {
        $this->hoTen = $hoTen;
        $this->newPassword = $newPassword;
    }

    public function build()
    {
        return $this->subject('Mật khẩu mới của bạn')
                    ->view('admin.emails.reset_password')
                    ->with([
                        'hoTen' => $this->hoTen,
                        'newPassword' => $this->newPassword,
                    ]);
    }
}