<?php

namespace App\Providers;

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
		'App\Events\ManufacturerNewOrderEvent' => [],
		'App\Events\ManufacturerWeekEvent' => [],
		'App\Events\ManufacturerInactivePlayer' => [],
		'App\Events\DistributorNewOrderEvent' => [],
		'App\Events\DistributorWeekEvent' => [],
		'App\Events\DistributorInactivePlayer' => [],
		'App\Events\WholesalerNewOrderEvent' => [],
		'App\Events\WholesalerWeekEvent' => [],
		'App\Events\WholesalerInactivePlayer' => [],
		'App\Events\RetailerWeekEvent' => [],
		'App\Events\RetailerInactivePlayer' => [],
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
}
