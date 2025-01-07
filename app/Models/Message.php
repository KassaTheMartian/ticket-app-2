<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;
    protected $fillable = [
        'ticket_id',
        'message',
        'sender_id',
        'sender_type',
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function sender()
    {
        return $this->morphTo();
    }

    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'attachable')
        ->withoutGlobalScopes();   
    }

    public function saveAttachments($attachments)
    {
        if ($attachments) {
            foreach ($attachments as $file) {
                $path = $file->store('attachments');
                $this->attachments()->create([
                    'filename' => $file->getClientOriginalName(),
                    'path' => $path,
                    'mime_type' => $file->getMimeType(),
                ]);
            }
        }
    }
}
