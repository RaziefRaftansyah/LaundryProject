<?php

namespace App\Providers;

use App\Models\User;
use App\Payments\Gateways\DummyGateway;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(DummyGateway::class, fn () => new DummyGateway(
            secret: (string) config('payment.gateways.dummy.secret'),
            expiryMinutes: (int) config('payment.expiry_minutes', 60),
            failRate: (int) config('payment.gateways.dummy.fail_rate', 0),
        ));
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Vite::prefetch(concurrency: 3);

        // Setiap permission di config/permissions.php menjadi Gate native (can:transaksi.void.approve, dst.)
        foreach (collect(config('permissions.roles', []))->flatten()->unique() as $permission) {
            Gate::define($permission, fn (User $user) => $user->hasPermission($permission));
        }

        // Nilai outlet dari config/laundry.php dibagikan ke semua view,
        // sehingga view tidak perlu membaca config berulang-ulang.
        $outlet = config('laundry', []);
        View::share([
            'storeName'   => $outlet['store_name'] ?? 'Laundry Coin',
            'city'        => $outlet['city'] ?? '',
            'address'     => $outlet['address'] ?? '',
            'phone'       => $outlet['phone'] ?? '',
            'whatsapp'    => \App\Support\PhoneNumber::digits((string) ($outlet['whatsapp'] ?? '')),
            'email'       => $outlet['email'] ?? '',
            'hoursDay'    => $outlet['operating_hours']['weekday'] ?? '',
            'hoursEnd'    => $outlet['operating_hours']['weekend'] ?? '',
            'hoursNote'   => $outlet['operating_hours']['notes'] ?? '',
            'hoursKnown'  => ! \App\Support\Placeholder::is($outlet['operating_hours']['weekday'] ?? '') || ! \App\Support\Placeholder::is($outlet['operating_hours']['weekend'] ?? ''),
            'mapsUrl'     => $outlet['google_maps_url'] ?? 'https://maps.google.com',
        ]);
    }
}
