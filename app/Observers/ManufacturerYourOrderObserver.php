<?php

namespace App\Observers;

use App\Events\ManufacturerYourOrderEvent;
use App\Events\WholesalerInactivePlayer;
use App\Models\DistributorYourOrder;
use App\Models\Game;
use App\Models\ManufacturerYourOrder;
use App\Services\ManufacturerService;

class ManufacturerYourOrderObserver
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
	 * @param ManufacturerYourOrder $model
	 */
	public function created(ManufacturerYourOrder $model)
	{
		broadcast(new ManufacturerYourOrderEvent($model->manufacturer->game->id));

		$manufacturerCountOrder = $model->manufacturer->yourOrders()->count();

		if (isAutoPlayer($model->manufacturer->distributor)) {
			$distributorCountOrder = $model->manufacturer->distributor->yourOrders()->count();

			if ($manufacturerCountOrder > $distributorCountOrder) {
				broadcast(new WholesalerInactivePlayer($model->manufacturer->game->id));

				DistributorYourOrder::create([
					'distributor_id' => $model->manufacturer->distributor->id,
					'your_order' => orderCalculate($model->manufacturer->game->max_weeks,
						$model->manufacturer->distributor->distributorWeeks()->count())
				]);
			}
		}

		sleep(2);
		$this->manufacturerService->nextWeek($model->manufacturer);
	}
}
