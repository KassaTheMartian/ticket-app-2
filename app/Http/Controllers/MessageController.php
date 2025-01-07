<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\Ticket;
use App\Mail\TicketReplied;
use Illuminate\Support\Facades\Mail;
use App\Events\NewMessageSent;
use Illuminate\Validation\ValidationException;
use App\Models\EmailSetting;
use App\Jobs\SendEmail;

class MessageController extends Controller
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
            'sender_id' => auth()->id(),
            'sender_type' => 'App\Models\User',
        ]);
        
        if ($request->hasFile('attachments')) {
            $message->saveAttachments($request->file('attachments'));
        }

        $ticket = Ticket::find($request->ticket_id);
        $customer = $ticket->customer;
        $data = [
            'customer_name' => $customer->name,
            'ticket_id' => $request->ticket_id,
            'ticket_title' => $ticket->title,
            'message' => $request->message,
            'ticket_status' => $ticket->status,
        ];

        // // Lấy cấu hình email hiện tại từ database
        // $emailSettings = EmailSetting::firstOrFail();

        // // Dispatch Job
        // dispatch(new SendEmail(
        //     $emailSettings->toArray(),
        //     $customer->email,
        //     new TicketReplied($data)
        // ));
        Mail::to($customer->email)->queue(new TicketReplied($data));

        return $message;
    }
}