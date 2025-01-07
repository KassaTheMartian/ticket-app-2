<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TicketUpdatedEmail extends Mailable
{
    use Queueable, SerializesModels;
    public $data;
    public $emailSubject;
    public function __construct($data, $emailSubject)
    {
        $this->data = $data;
        $this->emailSubject = $emailSubject;
    }
    public function build()
    {
        return $this->subject($this->emailSubject)
                    ->view('emails.ticket_updated')
                    ->with(['data' => $this->data, 'emailSubject' => $this->emailSubject]);
    }
}
