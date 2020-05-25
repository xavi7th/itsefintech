<?php

namespace App\Modules\Admin\Providers;

use App\Modules\Admin\Listeners\NotificationEventSubscriber;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class AdminEventServiceProvider extends ServiceProvider
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
   * The subscriber classes to register.
   *
   * @var array
   */
  protected $subscribe = [
    NotificationEventSubscriber::class,
  ];
}
