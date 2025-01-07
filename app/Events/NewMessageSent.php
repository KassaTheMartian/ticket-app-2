<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Message;

class NewMessageSent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    /**
     * Create a new event instance.
     */
    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('ticket.' . $this->message->ticket_id);
    }

    public function broadcastWith()
    {
        $sender = $this->message->sender;
        $profilePicture = $sender->profile_picture ? asset('storage/' . $sender->profile_picture) : null;

        return [
            'id' => $this->message->id,
            'ticket_id' => $this->message->ticket_id,
            'message' => $this->message->message,
            'sender_id' => $this->message->sender_id,
            'sender_type' => $this->message->sender_type,
            'sender_name' => $sender->name,
            'profile_picture' => $profilePicture,
            'created_at' => $this->message->created_at->format('h:i A'),
        ];
    }
}
