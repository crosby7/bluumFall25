<?php

namespace App\Providers;

use App\Models\TaskCompletion;
use App\Observers\TaskCompletionObserver;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

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
        JsonResource::withoutWrapping();

        ResetPassword::createUrlUsing(function (object $notifiable, string $token) {
            return config('app.frontend_url')."/password-reset/$token?email={$notifiable->getEmailForPasswordReset()}";
        });

        // Configure rate limiters
        $this->configureRateLimiting();

        // Register authorization policies
        Gate::policy(\App\Models\Patient::class, \App\Policies\PatientPolicy::class);
        Gate::policy(\App\Models\TaskSubscription::class, \App\Policies\TaskSubscriptionPolicy::class);
        Gate::policy(\App\Models\Nurse::class, \App\Policies\NursePolicy::class);
        Gate::policy(\App\Models\TaskCompletion::class, \App\Policies\TaskCompletionPolicy::class);
        Gate::policy(\App\Models\Task::class, \App\Policies\TaskPolicy::class);
        Gate::policy(\App\Models\Item::class, \App\Policies\ItemPolicy::class);

        // Register model observers
        TaskCompletion::observe(TaskCompletionObserver::class);
    }

    /**
     * Configure the rate limiters for the application.
     */
    protected function configureRateLimiting(): void
    {
        // Authentication endpoints - strict limit to prevent brute force attacks
        RateLimiter::for('auth', function (Request $request) {
            return Limit::perMinute(5)->by($request->ip());
        });

        // Nurse portal API - higher limit for bulk operations
        RateLimiter::for('nurse-api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        // Patient app API - standard limit for typical usage
        RateLimiter::for('patient-api', function (Request $request) {
            return Limit::perMinute(30)->by($request->user()?->id ?: $request->ip());
        });
    }
}
