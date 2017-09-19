<?php

namespace CoreCMF\Omnipay\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        //
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
    /**
     * [protected 注册事件订阅者]
     * @var [type]
     */
    protected $subscribe = [
         'CoreCMF\Omnipay\App\Listeners\OmnipayEventSubscriber',
    ];
}
