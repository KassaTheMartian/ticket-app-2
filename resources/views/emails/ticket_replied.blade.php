<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Message Notification</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; margin: 0; padding: 0; background-color: #f4f6f9; color: #333;">
    <div style="max-width: 600px; margin: 20px auto; background: #ffffff; border-radius: 8px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); overflow: hidden;">
        <div style="background: #0056b3; padding: 20px; text-align: center;">
            <h1 style="color: #ffffff; font-size: 24px; margin: 0;">New Message Notification</h1>
        </div>
        
        <div style="padding: 30px;">
            <p style="margin: 0 0 15px;">Hello {{ $data['customer_name'] }},</p>
            <p style="margin: 0 0 15px;">You have received a new message in your support ticket.</p>

            <div style="background: #f8f9fa; border-left: 4px solid #0056b3; padding: 15px; margin: 20px 0;">
                <p style="margin: 5px 0;"><strong>Ticket ID:</strong> #{{ $data['ticket_id'] }}</p>
                <p style="margin: 5px 0;"><strong>Subject:</strong> {{ $data['ticket_title'] }}</p>
                <p style="margin: 5px 0;"><strong>Status:</strong> {{ $data['ticket_status'] }}</p>
            </div>

            @if(isset($data['message']))
            <div style="background: #f8f9fa; border: 1px solid #e9ecef; border-radius: 4px; padding: 15px; margin: 15px 0; font-style: italic; color: #666;">
                "{!! Str::limit($data['message'], 150) !!}"
            </div>
            @endif

            <center>
                <a href="{{ route('customer.tickets.show', ['id' => $data['ticket_id']]) }}" style="display: inline-block; padding: 12px 25px; background-color: #0056b3; color: #ffffff; text-decoration: none; border-radius: 5px; font-weight: bold; margin: 20px 0;">
                    View Full Message
                </a>
            </center>

            <p style="margin: 20px 0 0;">Best regards,<br>The Tickies Support Team</p>
        </div>

        <div style="text-align: center; padding: 20px; background: #f8f9fa; color: #666; font-size: 12px; border-top: 1px solid #e9ecef;">
            <p style="margin: 0;">© {{ date('Y') }} Tickies. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
