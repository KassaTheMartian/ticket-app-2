<!DOCTYPE html>
<html>
<head>
    <title>Password Reset</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f9f9f9; margin: 0; padding: 0;">
    <div style="max-width: 600px; margin: 20px auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
        <div style="background-color: #072544; color: #ffffff; text-align: center; padding: 20px;">
            <h1 style="color: #ffffff; margin: 0; font-size: 24px;">Password Reset Request</h1>
        </div>
        <div style="padding: 20px; color: #333;">
            <p style="margin: 10px 0; line-height: 1.6;">Hello, <strong>{{ $data['name'] }}</strong></p>
            <p style="margin: 10px 0; line-height: 1.6;">You are receiving this email because we received a password reset request for your account.</p>
            <p style="margin: 10px 0; line-height: 1.6;">Click the button below to reset your password:</p>
            <a href="{{ route('admin.auth.password.reset', ['token' => $data['token'], 'email' => $data['email']]) }}" 
                style="display: inline-block; margin: 20px 0; padding: 10px 20px; background-color: #007BFF; color: #ffffff; text-decoration: none; border-radius: 5px; font-size: 14px;">
                Reset Password
            </a>
            <p style="margin: 10px 0; line-height: 1.6;">If the button above does not work, copy and paste the link below into your browser:</p>
            <p style="margin: 10px 0; line-height: 1.6;">
                <a href="{{ route('admin.auth.password.reset', ['token' => $data['token'], 'email' => $data['email']]) }}" 
                    style="color: #007BFF; text-decoration: none;">
                    {{ route('admin.auth.password.reset', ['token' => $data['token'], 'email' => $data['email']]) }}
                </a>
            </p>
            <p style="margin: 10px 0; line-height: 1.6;">If you did not request a password reset, no further action is required.</p>
        </div>
        <div style="text-align: center; padding: 15px; background-color: #f4f4f4; font-size: 12px; color: #888;">
            <p style="margin: 0;">Regards,</p>
            <p style="margin: 0;"><strong>Admin Panel - Tickies</strong></p>
        </div>
    </div>
</body>
</html>
