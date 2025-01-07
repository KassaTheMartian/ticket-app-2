<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Account Created</title>
</head>
<body style="font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #f9f9f9;">
    <div style="max-width: 600px; margin: 20px auto; background: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
        <div style="background-color: #343a40; padding: 20px; text-align: center;">
            <h1 style="margin: 0; font-size: 24px; color: #ffffff;">Welcome, {{ $data['name'] }}!</h1>
        </div>
        <div style="padding: 20px;">
            <p style="margin: 10px 0; line-height: 1.6;">Congratulations! Your administrator account has been successfully created in our system.</p>
            <p style="margin: 10px 0; line-height: 1.6;">Here are your account details:</p>
            <ul style="list-style-type: none; padding: 0; margin: 0;">
                <li style="margin: 5px 0; padding: 8px; background: #f9f9f9; border-radius: 4px;"><strong>Email:</strong> {{ $data['email'] }}</li>
                <li style="margin: 5px 0; padding: 8px; background: #f9f9f9; border-radius: 4px;"><strong>Password:</strong> {{ $data['password'] }}</li>
                <li style="margin: 5px 0; padding: 8px; background: #f9f9f9; border-radius: 4px;"><strong>Role:</strong> Administrator</li>
            </ul>
            <p style="margin: 10px 0; line-height: 1.6;">As an administrator, you have access to manage users, configure system settings, and oversee the overall functionality of the platform. Please keep your credentials secure to maintain the integrity of the system.</p>
            <a href="https://your-admin-dashboard.com" style="display: inline-block; padding: 10px 20px; margin-top: 20px; background-color: #007BFF; color: #ffffff; text-decoration: none; border-radius: 5px; font-size: 14px;">Access Admin Dashboard</a>
            <p style="margin: 10px 0; line-height: 1.6;">If you encounter any issues or require assistance, feel free to contact the IT support team.</p>
        </div>
        <div style="text-align: center; padding: 20px; background-color: #f4f4f4; font-size: 12px; color: #888;">
            <p style="margin: 0;">Best regards,</p>
            <p style="margin: 0;">Tickies</p>
        </div>
    </div>
</body>
</html>
