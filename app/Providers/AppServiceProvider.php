<?php

namespace App\Providers;

use App\Models\Client;
use App\Models\DigitalCard;
use App\Models\DynamicQrCode;
use App\Models\NfcTag;
use App\Models\User;
use App\Observers\ClientObserver;
use App\Observers\DigitalCardObserver;
use App\Observers\DynamicQrCodeObserver;
use App\Observers\NfcTagObserver;
use App\Policies\ClientPolicy;
use App\Policies\DigitalCardPolicy;
use App\Policies\DynamicQrCodePolicy;
use App\Policies\NfcTagPolicy;
use App\Policies\UserPolicy;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
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
        Paginator::useBootstrapFive();

        // Register Morph Map for ScanLog polymorphic relations
        Relation::morphMap([
            'NfcTag' => NfcTag::class,
            'DynamicQrCode' => DynamicQrCode::class,
            'App\Models\NfcTag' => NfcTag::class,
            'App\Models\DynamicQrCode' => DynamicQrCode::class,
        ]);

        // Register Observers for automated client history audit logging
        Client::observe(ClientObserver::class);
        DigitalCard::observe(DigitalCardObserver::class);
        NfcTag::observe(NfcTagObserver::class);
        DynamicQrCode::observe(DynamicQrCodeObserver::class);

        // Register Policies
        Gate::policy(User::class, UserPolicy::class);
        Gate::policy(Client::class, ClientPolicy::class);
        Gate::policy(DigitalCard::class, DigitalCardPolicy::class);
        Gate::policy(DynamicQrCode::class, DynamicQrCodePolicy::class);
        Gate::policy(NfcTag::class, NfcTagPolicy::class);

        // Superadmin bypass
        Gate::before(function ($user, $ability) {
            if ($user instanceof User && $user->isAdmin()) {
                return true;
            }

            return null;
        });

        // Dynamic permission check fallback for abilities
        Gate::after(function ($user, $ability, $result, $arguments) {
            if ($result !== null) {
                return $result;
            }

            if ($user instanceof User) {
                return $user->hasPermission($ability);
            }

            return false;
        });

        // Custom Blade directive: @haspermission / @endhaspermission
        Blade::if('haspermission', function (string $permission) {
            $user = auth('web')->user();

            return $user && ($user->isAdmin() || $user->hasPermission($permission));
        });
    }
}
