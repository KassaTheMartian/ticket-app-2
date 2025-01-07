<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Ticket extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'description',
        'ticket_type_id',
        'customer_id',
        'department_id',
        'priority',
        'status',
    ];
    public function ticketType()
    {
        return $this->belongsTo(TicketType::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function logs()
    {
        return $this->hasMany(TicketLog::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'attachable')
        ->withoutGlobalScopes();   
    }
    
    public function __toString()
    {
        return sprintf(
            "ID: %d\n- Title: %s\n- Status: %s\n- Priority: %s\n- Type: %s\n- Customer: %s\n- Department: %s", 
            $this->id, 
            $this->title, 
            $this->status, 
            $this->priority, 
            $this->ticketType->name ?? 'Undefined',
            $this->customer->name ?? 'Undefined',
            $this->department->name ?? 'Undefined'
        );
    }
    public function logAction($userId, $action)
    {
        TicketLog::create([
            'ticket_id' => $this->id,
            'user_id' => $userId,
            'action' => $action,
        ]);
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
    // Accessor để format id
    protected function formattedId(): Attribute 
    {
        return Attribute::make(
            get: fn () => 'TIC-' . str_pad($this->getOriginal('id'), 6, '0', STR_PAD_LEFT)
        );
    }
}
