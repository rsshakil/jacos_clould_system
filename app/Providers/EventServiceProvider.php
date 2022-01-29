<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        Event::listen(\Illuminate\Auth\Events\Login::class, function ($event) {
            Log::info("login informations: {$event->user->name},{$event->user->id},".Auth::user()->CompanyName);
        });

        Event::listen(\Illuminate\Auth\Events\Logout::class, function ($event) {
            Log::info("logout informations: {$event->user->name},{$event->user->id},".Auth::user()->CompanyName);
        });

        //
    }
}
