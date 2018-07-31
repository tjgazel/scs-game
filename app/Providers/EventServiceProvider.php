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
		'App\Events\ManufacturerYourOrderEvent' => [],
		'App\Events\ManufacturerWeekEvent' => [],
		'App\Events\ManufacturerInactivePlayer' => [],
		'App\Events\DistributorYourOrderEvent' => [],
		'App\Events\DistributorWeekEvent' => [],
		'App\Events\DistributorInactivePlayer' => [],
		'App\Events\WholesalerYourOrderEvent' => [],
		'App\Events\WholesalerWeekEvent' => [],
		'App\Events\WholesalerInactivePlayer' => [],
		'App\Events\RetailerYourOrderEvent' => [],
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
