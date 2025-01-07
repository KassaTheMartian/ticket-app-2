<?php

namespace App\Jobs;

use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;


class SendEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    public $emailSettings;
    public $mailable;
    public $recipient;
    public function __construct($emailSettings, $recipient, Mailable $mailable)
    {
        $this->emailSettings = $emailSettings;
        $this->recipient = $recipient;
        $this->mailable = $mailable;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
    // Cập nhật cấu hình runtime
        config([
            'mail.mailers.smtp.username' => $this->emailSettings['username'],
            'mail.mailers.smtp.password' => $this->emailSettings['password'],
            'mail.from.address' => $this->emailSettings['from_address'],
            'mail.from.name' => $this->emailSettings['from_name'],
        ]);

        // Gửi email
        Mail::to($this->recipient)->send($this->mailable);    
    }
}
