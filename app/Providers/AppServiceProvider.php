<?php

namespace App\Providers;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Inertia\Inertia;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Schema::defaultStringLength(191);

        Gate::define('admin', fn (User $user) => $user->is_admin);

        Inertia::share([
            'logo' => fn () => Setting::get()->logo,
            'appName' => fn () => config('app.name'),
        ]);
    }
}
