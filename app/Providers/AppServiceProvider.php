<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\EmailSetting;
use Illuminate\Support\Facades\Config;
use App\Services\MailSettingService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(MailSettingService $mailSettingService)
    {
        try {
            $mailSettingService->updateAndResetMailer();
        } catch (\Exception $e) {
            // Log error if needed
        }
    }
}
