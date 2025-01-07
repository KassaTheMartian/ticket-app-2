<?php
namespace App\Services;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use App\Models\EmailSetting;
use Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport;

class MailSettingService
{
    public function updateAndResetMailer()
    {
        $config = EmailSetting::first();
        if (!$config) {
            return;
        }

        // Chỉ cập nhật username và password
        Config::set('mail.mailers.smtp.username', $config->username);
        Config::set('mail.mailers.smtp.password', $config->password);
        Config::set('mail.from.address', $config->from_address);
        Config::set('mail.from.name', $config->from_name);

        // Cập nhật from address nếu nó giống với username
        if ($config->username) {
            Config::set('mail.from.address', $config->username);
        }
    }
}