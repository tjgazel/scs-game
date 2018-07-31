<?php

namespace App\Observers;

use App\Events\ManufacturerInactivePlayer;
use App\Events\DistributorYourOrderEvent;
use App\Events\WholesalerInactivePlayer;
use App\Models\DistributorYourOrder;
use App\Models\ManufacturerYourOrder;
use App\Models\WholesalerYourOrder;
use App\Services\ManufacturerService;

class DistributorYourOrderObserver
{
	/**
	 * @var ManufacturerService
	 */
	private $manufacturerService;

	/**
	 * ManufacturerYourOrderObserver constructor.
	 * @param ManufacturerService $manufacturerService
	 */
	public function __construct(ManufacturerService $manufacturerService)
	{
		$this->manufacturerService = $manufacturerService;
	}

	/**
	 * Listen to the Retailer created event.
	 *
	 * @param DistributorYourOrder $model
	 */
	public function created(DistributorYourOrder $model)
	{
		broadcast(new DistributorYourOrderEvent($model->distributor->game->id));

		$distributorCountOrder = $model->distributor->yourOrders()->count();

		if (isAutoPlayer($model->distributor->wholesaler)){
			$wholesalerCountOrder = $model->distributor->wholesaler->yourOrders()->count();

			if ($distributorCountOrder > $wholesalerCountOrder) {
				broadcast(new WholesalerInactivePlayer($model->distributor->game->id));

				WholesalerYourOrder::create([
					'wholesaler_id' => $model->distributor->wholesaler->id,
					'your_order' => orderCalculate($model->distributor->game->max_weeks,
						$model->distributor->wholesaler->wholesalerWeeks()->count())
				]);
			}
		}

		if (isAutoPlayer($model->distributor->manufacturer)) {
			$manufacturerCountOrder = $model->distributor->manufacturer->yourOrders()->count();

			if ($distributorCountOrder > $manufacturerCountOrder) {
				broadcast(new ManufacturerInactivePlayer($model->distributor->game->id));

				ManufacturerYourOrder::create([
					'manufacturer_id' => $model->distributor->manufacturer->id,
					'your_order' => orderCalculate($model->distributor->game->max_weeks,
						$model->distributor->manufacturer->manufacturerWeeks()->count())
				]);
			}
		}

		sleep(2);
		$this->manufacturerService->nextWeek($model->distributor->manufacturer);

	}
}
