<!DOCTYPE html>
<html>
<head>
    <title>Email Verification</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f9f9f9; margin: 0; padding: 0;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f4f4f4; padding: 20px;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                    <tr>
                        <td style="padding: 20px; text-align: center; background-color: #007bff;">
                            <h1 style="margin: 0; font-size: 24px; color: #ffffff;">Ticket App</h1>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 20px;">
                            <h2 style="margin-top: 0; color: #333;">Hello, {{ $data['name'] }}</h2>
                            <p style="color: #555; line-height: 1.6;">
                                Thank you for registering with us. Please click the button below to verify your email address:
                            </p>
                            <p style="text-align: center;">
                                <a href="{{ route('customer.auth.verify', ['id' => $data['id'], 'verification_code' => $data['verification_code'], 'expire_at' => $data['expire_at']]) }}" 
                                   style="display: inline-block; padding: 10px 20px; font-size: 16px; color: #ffffff; background-color: #007bff; text-decoration: none; border-radius: 5px;">
                                    Verify Email
                                </a>
                            </p>
                            <p style="color: #555; line-height: 1.6;">
                                If you did not create an account, no further action is required.
                            </p>
                            <p style="color: #555; line-height: 1.6;">Regards,<br><strong>Ticket App Team</strong></p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 10px; text-align: center; background-color: #f4f4f4; color: #888; font-size: 12px;">
                            &copy; 2023 Ticket App. All rights reserved.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
