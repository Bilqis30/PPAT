<?php

namespace App\Providers;

use App\Models\Document;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

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
    public function boot(): void
    {
        // Strict Login Rate Limiter: Max 5 attempts per minute per email + IP
        RateLimiter::for('login', function (Request $request) {
            $email = Str::transliterate(Str::lower((string) $request->input('email')));

            return Limit::perMinute(5)->by($email.'|'.$request->ip());
        });

        // View Composer for Topbar Notifications
        View::composer('layouts.app', function ($view): void {
            if (Auth::check()) {
                try {
                    $recentNotifications = Document::with('user')
                        ->latest()
                        ->take(5)
                        ->get();
                    $notificationCount = Document::where('created_at', '>=', now()->subDays(7))->count();

                    $view->with([
                        'topbarNotifications' => $recentNotifications,
                        'topbarNotificationCount' => $notificationCount,
                    ]);
                } catch (\Throwable $e) {
                    $view->with([
                        'topbarNotifications' => collect(),
                        'topbarNotificationCount' => 0,
                    ]);
                }
            }
        });
    }
}
