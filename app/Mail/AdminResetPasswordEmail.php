<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminResetPasswordEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;
    public function __construct($data)
    {
        $this->data = $data;
    }
    public function build()
    {
        return $this->subject('Admin reset password notification')
                    ->view('emails.admin_reset_password')
                    ->with(['data' => $this->data]);
    }
}
