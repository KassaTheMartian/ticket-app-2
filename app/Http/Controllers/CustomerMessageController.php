<?php

namespace App\Http\Controllers;

use App\Mail\AdminTicketReplied;
use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\Ticket;
use App\Mail\TicketReplied;
use Illuminate\Support\Facades\Mail;
use App\Events\NewMessageSent;
use Illuminate\Validation\ValidationException;

class CustomerMessageController extends Controller
{
    public function create(Request $request)
    {
        $this->validateRequest($request);
        $message = $this->storeMessage($request);
        //broadcast(new NewMessageSent($message))->toOthers();

        return redirect()->back()->with('success', 'Message created successfully.');
    }

    private function validateRequest(Request $request)
    {
        $rules = [
            'ticket_id' => 'required|integer|exists:tickets,id',
            'message' => 'nullable|string',
            'attachments.*' => 'nullable|file|mimes:jpeg,png,jpg,gif,doc,docx,pdf|max:10240',
        ];

        $validator = validator($request->all(), $rules);

        $validator->after(function ($validator) use ($request) {
            if (empty($request->message) && !$request->hasFile('attachments')) {
                $validator->errors()->add(
                    'message',
                    'You must provide either a message or at least one attachment.'
                );
            }
        });

        if ($validator->fails()) {
            throw ValidationException::withMessages($validator->errors()->toArray());
        }
    }

    private function storeMessage(Request $request)
    {
        $message = Message::create([
            'ticket_id' => $request->ticket_id,
            'message' => $request->message,
            'sender_id' => auth()->guard('customer')->id(),
            'sender_type' => 'App\Models\Customer',
        ]);

        if ($request->hasFile('attachments')) {
            $message->saveAttachments($request->file('attachments'));
        }
        
        // Send email notification to the user
        $ticket = Ticket::find($request->ticket_id);
        $customer = $ticket->customer;
        $data = [
            'customer_name' => $customer->name,
            'message' => $request->message,
            'ticket_id' => $request->ticket_id,
            'ticket_title' => $ticket->title,
            'ticket_status' => $ticket->status,
            'ticket_priority' => $ticket->priority,
            'updated_at' => $ticket->updated_at,
        ];

        // Send email notification to all users in the ticket messages
        $ticketMessages = $ticket->messages;
        $notifiedUsers = [];

        foreach ($ticketMessages as $ticketMessage) {
            if ($ticketMessage->sender && !in_array($ticketMessage->sender->id, $notifiedUsers)) {
            $user = $ticketMessage->sender;
            $data['user_name'] = $user->name;
            Mail::to($user->email)->queue(new AdminTicketReplied($data));
            $notifiedUsers[] = $user->id;
            }
        }
        return $message;
    }
}
