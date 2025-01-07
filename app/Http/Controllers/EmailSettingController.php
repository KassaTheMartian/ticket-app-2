<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EmailSetting;
use Illuminate\Support\Facades\Artisan;

class EmailSettingController extends Controller
{
    public function edit()
    {
        $settings = EmailSetting::first();
        return view('admin.email_settings.edit', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
            'from_address' => 'required|email',
            'from_name' => 'required|string',
        ]);

        EmailSetting::updateOrCreate(['id' => 1], $data);

        return back()->with('success', 'Email settings updated successfully!');
    }

    public function sendTestEmail(Request $request)
    {
        $settings = EmailSetting::first();

        try {
            \Mail::raw('This is a test email from your application.', function ($message) use ($settings) {
                $message->to($settings->from_address)
                        ->subject('Test Email');
            });

            return back()->with('success', 'Test email sent successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to send test email. Please check your email settings.');
        }
    }
}
