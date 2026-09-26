<?php

namespace App\Providers;

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
        \Illuminate\Support\Facades\Gate::define('admin', fn (\App\Models\User $user) => $user->active && $user->isAdmin());
        \Illuminate\Support\Facades\Gate::define('super-admin', fn (\App\Models\User $user) => $user->active && $user->role === 'super_admin');
        \Illuminate\Support\Facades\Gate::define('student', fn (\App\Models\User $user) => $user->active && $user->role === 'student' && $user->student?->isActive());
        foreach (\App\Enums\Permission::cases() as $permission) {
            \Illuminate\Support\Facades\Gate::define($permission->value, fn (\App\Models\User $user) => $user->hasPermission($permission->value));
        }
        \Illuminate\Support\Facades\Gate::policy(\App\Models\CandidateRegistration::class, \App\Policies\CandidateRegistrationPolicy::class);
        \Illuminate\Support\Facades\RateLimiter::for('login', fn (\Illuminate\Http\Request $request) => \Illuminate\Cache\RateLimiting\Limit::perMinute(10)->by($request->ip().'|'.strtolower((string) $request->input('email'))));
        \Illuminate\Support\Facades\RateLimiter::for('sensitive', fn (\Illuminate\Http\Request $request) => \Illuminate\Cache\RateLimiting\Limit::perMinute(30)->by($request->user()?->id ?? $request->ip()));
        \Illuminate\Support\Facades\RateLimiter::for('vote', fn (\Illuminate\Http\Request $request) => \Illuminate\Cache\RateLimiting\Limit::perMinute(10)->by($request->user()?->id ?? $request->ip()));
    }
}
