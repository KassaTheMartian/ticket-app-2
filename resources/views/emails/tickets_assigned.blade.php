<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Ticket Notification</title>
</head>
<body style="font-family: 'Segoe UI', Arial, sans-serif; line-height: 1.6; margin: 0; padding: 20px; background-color: #f0f2f5; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
        <!-- Header Section -->
        <div style="background: linear-gradient(135deg, #2e7d32 0%, #4caf50 100%); color: #ffffff; text-align: center; padding: 30px 20px;">
            <h1 style="margin: 0; font-size: 28px; font-weight: 600; letter-spacing: 0.5px; color: #ffffff;">New Ticket Alert</h1>
            <p style="margin: 10px 0 0; font-size: 16px; opacity: 0.9;">A new ticket has been assigned to your department</p>
        </div>

        <!-- Content Section -->
        <div style="padding: 30px 40px; color: #2d3748;">
            <p style="margin: 0 0 20px; font-size: 16px;">Dear <span style="color: #2e7d32; font-weight: 600;">{{ $data['staff_name'] }}</span>,</p>
            
            <p style="margin: 0 0 25px; font-size: 16px; line-height: 1.6;">A new support ticket has been submitted to your department. Please review the details below:</p>

            <!-- Ticket Details Box -->
            <div style="background-color: #f8fafc; border-left: 4px solid #2e7d32; border-radius: 6px; padding: 25px; margin-bottom: 25px;">
                <div style="margin-bottom: 15px;">
                    <div style="font-weight: 600; color: #2e7d32; font-size: 18px; margin-bottom: 5px;">Ticket Information</div>
                    <div style="width: 40px; height: 3px; background-color: #2e7d32; margin-bottom: 15px;"></div>
                </div>

                <table style="width: 100%; border-collapse: collapse;">
                    <tr style="border-bottom: 1px solid #e2e8f0;">
                        <td style="padding: 12px 0; color: #718096; width: 130px;">Customer:</td>
                        <td style="padding: 12px 0; color: #2d3748; font-weight: 500;">{{ $data['customer'] }}</td>
                    </tr>
                    <tr style="border-bottom: 1px solid #e2e8f0;">
                        <td style="padding: 12px 0; color: #718096; width: 130px;">Title:</td>
                        <td style="padding: 12px 0; color: #2d3748; font-weight: 500;">{{ $data['title'] }}</td>
                    </tr>
                    <tr style="border-bottom: 1px solid #e2e8f0;">
                        <td style="padding: 12px 0; color: #718096;">Ticket Type:</td>
                        <td style="padding: 12px 0; color: #2d3748; font-weight: 500;">{{ $data['ticket_type'] }}</td>
                    </tr>
                    <tr style="border-bottom: 1px solid #e2e8f0;">
                        <td style="padding: 12px 0; color: #718096;">Priority:</td>
                        <td style="padding: 12px 0;">
                            <span style="background-color: {{ $data['priority'] === 'high' ? '#fee2e2' : ($data['priority'] === 'medium' ? '#fef3c7' : '#e0f2fe') }}; 
                                   color: {{ $data['priority'] === 'high' ? '#dc2626' : ($data['priority'] === 'medium' ? '#d97706' : '#0369a1') }}; 
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
                    <tr style="border-bottom: 1px solid #e2e8f0;">
                        <td style="padding: 12px 0; color: #718096;">Description:</td>
                        <td style="padding: 12px 0; color: #2d3748;">{!! Str::limit($data['description'], 300) !!}</td>
                    </tr>
                    <tr>
                        <td style="padding: 12px 0; color: #718096;">Received Time:</td>
                        <td style="padding: 12px 0; color: #2d3748;">{{ $data['created_at'] }}</td>
                    </tr>
                </table>
            </div>

            <!-- Action Button -->
            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ $data['ticket_url'] }}" 
                   style="display: inline-block; 
                          background-color: #2e7d32; 
                          color: #ffffff; 
                          padding: 12px 24px; 
                          text-decoration: none; 
                          border-radius: 6px; 
                          font-weight: 500;
                          transition: background-color 0.3s ease;">
                    View Ticket Details
                </a>
            </div>

            <p style="margin: 25px 0 10px; color: #475569;">Please review and respond to this ticket as soon as possible according to our SLA guidelines.</p>
            
            <p style="margin: 25px 0 10px; color: #475569;">Best regards,</p>
            <p style="margin: 0; font-weight: 600; color: #2e7d32;">Tickies System</p>
        </div>

        <!-- Footer Section -->
        <div style="text-align: center; padding: 20px; background-color: #f8fafc; border-top: 1px solid #e2e8f0;">
            <p style="margin: 0; font-size: 14px; color: #64748b;">&copy; {{ date('Y') }} Tickies. All rights reserved.</p>
        </div>
    </div>
</body>
</html>