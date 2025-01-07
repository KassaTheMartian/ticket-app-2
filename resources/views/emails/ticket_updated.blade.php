<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket Update Notification</title>
</head>
<body style="font-family: 'Segoe UI', Arial, sans-serif; line-height: 1.6; margin: 0; padding: 20px; background-color: #f0f2f5; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
        <!-- Header Section -->
        <div style="background: linear-gradient(135deg, #0056b3 0%, #0088ff 100%); color: #ffffff; text-align: center; padding: 30px 20px;">
            <h1 style="margin: 0; font-size: 28px; font-weight: 600; letter-spacing: 0.5px; color: #ffffff;">{{ $emailSubject }}</h1>
        </div>

        <!-- Content Section -->
        <div style="padding: 30px 40px; color: #2d3748;">
            <p style="margin: 0 0 20px; font-size: 16px;">Dear <span style="color: #0056b3; font-weight: 600;">{{ $data['customer'] }}</span>,</p>
            
            <p style="margin: 0 0 25px; font-size: 16px; line-height: 1.6;">This is to notify you that your ticket has been updated. Below are the updated details:</p>

            <!-- Ticket Details Box -->
            <div style="background-color: #f8fafc; border-left: 4px solid #0056b3; border-radius: 6px; padding: 25px; margin-bottom: 25px;">
                <div style="margin-bottom: 15px;">
                    <div style="font-weight: 600; color: #0056b3; font-size: 18px; margin-bottom: 5px;">Ticket Details</div>
                    <div style="width: 40px; height: 3px; background-color: #0056b3; margin-bottom: 15px;"></div>
                </div>

                <table style="width: 100%; border-collapse: collapse;">
                    <tr style="border-bottom: 1px solid #e2e8f0;">
                        <td style="padding: 12px 0; color: #718096; width: 130px;">Title:</td>
                        <td style="padding: 12px 0; color: #2d3748; font-weight: 500;">{{ $data['title'] }}</td>
                    </tr>
                    <tr style="border-bottom: 1px solid #e2e8f0;">
                        <td style="padding: 12px 0; color: #718096;">Ticket Type:</td>
                        <td style="padding: 12px 0; color: #2d3748; font-weight: 500;">{{ $data['ticket_type'] }}</td>
                    </tr>
                    <tr style="border-bottom: 1px solid #e2e8f0;">
                        <td style="padding: 12px 0; color: #718096;">Department:</td>
                        <td style="padding: 12px 0; color: #2d3748; font-weight: 500;">{{ $data['department'] }}</td>
                    </tr>
                    <tr style="border-bottom: 1px solid #e2e8f0;">
                        <td style="padding: 12px 0; color: #718096;">Priority:</td>
                        <td style="padding: 12px 0;">
                            <span style="background-color: {{ $data['priority'] === 'high' ? '#fee2e2' : ($data['priority'] === 'medium' ? '#fef3c7' : '#dcfce7') }}; 
                                   color: {{ $data['priority'] === 'high' ? '#dc2626' : ($data['priority'] === 'medium' ? '#d97706' : '#15803d') }}; 
                                   padding: 4px 12px; 
                                   border-radius: 12px; 
                                   font-size: 14px; 
                                   font-weight: 500;">
                                {{ ucfirst($data['priority']) }}
                            </span>
                        </td>
                    </tr>
                    <tr style="border-bottom: 1px solid #e2e8f0;">
                        <td style="padding: 12px 0; color: #718096;">Status:</td>
                        <td style="padding: 12px 0;">
                            <span style="background-color: #e0f2fe; 
                                   color: #0369a1; 
                                   padding: 4px 12px; 
                                   border-radius: 12px; 
                                   font-size: 14px; 
                                   font-weight: 500;">
                                {{ ucfirst(str_replace('_', ' ', $data['status'])) }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 12px 0; color: #718096;">Updated At:</td>
                        <td style="padding: 12px 0; color: #2d3748;">{{ $data['updated_at'] }}</td>
                    </tr>
                </table>
            </div>

            <p style="margin: 0 0 10px; color: #475569;">If you have any further questions, feel free to contact our support team.</p>
            
            <p style="margin: 25px 0 10px; color: #475569;">Best regards,</p>
            <p style="margin: 0; font-weight: 600; color: #0056b3;">Tickies</p>
        </div>

        <!-- Footer Section -->
        <div style="text-align: center; padding: 20px; background-color: #f8fafc; border-top: 1px solid #e2e8f0;">
            <p style="margin: 0; font-size: 14px; color: #64748b;">&copy; {{ date('Y') }} Tickies. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
