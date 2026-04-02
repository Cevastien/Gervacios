<?php

namespace App\Providers;

use App\Models\Booking;
use App\Models\QueueEntry;
use App\Models\Table;
use App\Policies\BookingPolicy;
use App\Policies\QueueEntryPolicy;
use App\Policies\TablePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        QueueEntry::class => QueueEntryPolicy::class,
        Table::class => TablePolicy::class,
        Booking::class => BookingPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
    }
}
