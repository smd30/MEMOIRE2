<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Events\ContractCreated;
use App\Events\ContractExpiring;
use App\Events\PaymentReceived;
use App\Events\SinistreDeclared;
use App\Listeners\SendContractConfirmation;
use App\Listeners\SendContractExpiryNotification;
use App\Listeners\SendPaymentConfirmation;
use App\Listeners\SendSinistreNotification;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],

        // Événements de contrat
        ContractCreated::class => [
            SendContractConfirmation::class,
        ],

        ContractExpiring::class => [
            SendContractExpiryNotification::class,
        ],

        // Événements de paiement
        PaymentReceived::class => [
            SendPaymentConfirmation::class,
        ],

        // Événements de sinistre
        SinistreDeclared::class => [
            SendSinistreNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
