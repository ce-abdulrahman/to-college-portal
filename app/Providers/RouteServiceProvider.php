<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const ADMIN_DASHBOARD = '/sadm/dshbd';
    public const CENTER_DASHBOARD = '/cntr/dshbd';
    public const TEACHER_DASHBOARD = '/teacher/dashboard';
    public const HOME = '/dashboard';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        RateLimiter::for('api', function ($request) {
            return Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip());
        });

        // app/Providers/RouteServiceProvider.php
        $this->routes(function () {
            Route::middleware('api')
            ->prefix('api/v1')
            ->group(base_path('routes/api.php'));

            // ⬇️ هیچ پریفیکسی مەدە بۆ web.php
            Route::middleware('web')
            ->group(base_path('routes/web.php'));

            // admin.php خۆی لەخۆیدا prefix('admin') هەیە، پێویست نیە لێرە دابمەزرێت
            Route::middleware('web')
            ->group(base_path('routes/admin.php'));
        });
    }
}
