<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class PulseServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if (! app()?->isLocal()) {
            Gate::define('viewPulse', function (User $user) {
                return in_array($user->email, [
                    //TODO: Implement gate logic for Pulse
                ]);
            });
        }
    }
}
