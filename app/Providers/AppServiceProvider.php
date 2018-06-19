<?php

namespace App\Providers;

use App\Models\DistributorWeek;
use App\Models\DistributorYourOrder;
use App\Models\Manufacturer;
use App\Models\Distributor;
use App\Models\ManufacturerWeek;
use App\Models\ManufacturerYourOrder;
use App\Models\RetailerWeek;
use App\Models\RetailerYourOrder;
use App\Models\Wholesaler;
use App\Models\Retailer;
use App\Models\WholesalerWeek;
use App\Models\WholesalerYourOrder;
use App\Observers\DistributorWeekObserver;
use App\Observers\DistributorYourOrderObserver;
use App\Observers\ManufacturerObserver;
use App\Observers\DistributorObserver;
use App\Observers\ManufacturerWeekObserver;
use App\Observers\ManufacturerYourOrderObserver;
use App\Observers\RetailerWeekObserver;
use App\Observers\RetailerYourOrderObserver;
use App\Observers\WholesalerObserver;
use App\Observers\RetailerObserver;
use App\Observers\WholesalerWeekObserver;
use App\Observers\WholesalerYourOrderObserver;
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
        RetailerYourOrder::observe(RetailerYourOrderObserver::class);
        WholesalerYourOrder::observe(WholesalerYourOrderObserver::class);
        DistributorYourOrder::observe(DistributorYourOrderObserver::class);
        ManufacturerYourOrder::observe(ManufacturerYourOrderObserver::class);
		ManufacturerWeek::observe(ManufacturerWeekObserver::class);
		DistributorWeek::observe(DistributorWeekObserver::class);
		WholesalerWeek::observe(WholesalerWeekObserver::class);
		RetailerWeek::observe(RetailerWeekObserver::class);
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
