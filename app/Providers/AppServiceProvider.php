<?php

namespace App\Providers;

use App\Models\Manufacturer;
use App\Models\Distributor;
use App\Models\Wholesaler;
use App\Models\Retailer;
use App\Observers\ManufacturerObserver;
use App\Observers\DistributorObserver;
use App\Observers\WholesalerObserver;
use App\Observers\RetailerObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Manufacturer::observe(ManufacturerObserver::class);
        Distributor::observe(DistributorObserver::class);
        Wholesaler::observe(WholesalerObserver::class);
        Retailer::observe(RetailerObserver::class);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
