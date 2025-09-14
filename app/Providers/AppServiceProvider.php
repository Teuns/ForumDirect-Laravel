<?php

namespace App\Providers;

use App\Listeners\SendChatMessage;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Laravel\Reverb\Events\MessageReceived;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->environment() === 'production') {
            $this->app['request']->server->set('HTTPS', true);
        }

//        Event::listen(
//            MessageReceived::class,
//            SendChatMessage::class
//        );
    }
}
