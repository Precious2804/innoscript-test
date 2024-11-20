<?php

namespace App\Providers;

use App\Traits\ApiResponse;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    use ApiResponse;
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
        // Define custom rate limiter for 'api' routes
        RateLimiter::for('limit-rate', function ($request) {
            return Limit::perMinute(30) // Represents 30 Requests are acceptable per minute
                ->response(function () {
                    $data = ['retry_after' => now()->addMinutes(1)->toDateTimeString()];
                    return ApiResponse::errorResponseWithData($data, 'You have exceeded the maximum number of request attempts. Please try again later.', Response::HTTP_TOO_MANY_REQUESTS);
                });
        });
    }
}
