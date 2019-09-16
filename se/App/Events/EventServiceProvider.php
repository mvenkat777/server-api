<?php

namespace Platform\App\Events;

use Illuminate\Support\ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $listeners = $this->app['config']->get('platform.listeners');

        foreach ($listeners as $listener) {
            $this->app['events']->listen('Platform.*', $listener);
        }
    }
}
