<?php

namespace App\Providers;

use App\Enums\Permission;
use App\Models\CandidateRegistration;
use App\Models\User;
use App\Policies\CandidateRegistrationPolicy;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
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
        Gate::define('admin', fn (User $user) => $user->active && $user->isAdmin());
        Gate::define('super-admin', fn (User $user) => $user->active && $user->role === 'super_admin');
        Gate::define('student', fn (User $user) => $user->active && $user->role === 'student' && $user->student?->isActive());
        foreach (Permission::cases() as $permission) {
            Gate::define($permission->value, fn (User $user) => $user->hasPermission($permission->value));
        }
        Gate::policy(CandidateRegistration::class, CandidateRegistrationPolicy::class);
        RateLimiter::for('login', fn (Request $request) => Limit::perMinute(10)->by($request->ip().'|'.strtolower((string) $request->input('email'))));
        RateLimiter::for('sensitive', fn (Request $request) => Limit::perMinute(30)->by($request->user()?->id ?? $request->ip()));
        RateLimiter::for('vote', fn (Request $request) => Limit::perMinute(10)->by($request->user()?->id ?? $request->ip()));
    }
}
