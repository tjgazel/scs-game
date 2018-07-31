<?php

namespace App\Observers;


use App\Events\WholesalerInactivePlayer;
use App\Events\RetailerYourOrderEvent;
use App\Models\RetailerYourOrder;
use App\Models\WholesalerYourOrder;
use App\Services\ManufacturerService;

class RetailerYourOrderObserver
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
	 * @param RetailerYourOrder $model
	 */
	public function created(RetailerYourOrder $model)
	{
		broadcast(new RetailerYourOrderEvent($model->retailer->game->id));

		$retailerCountOrder = $model->retailer->yourOrders()->count();

		if (isAutoPlayer($model->retailer->wholesaler)) {
			$wholesalerCountOrder = $model->retailer->wholesaler->yourOrders()->count();

			if ($retailerCountOrder > $wholesalerCountOrder) {
				broadcast(new WholesalerInactivePlayer($model->retailer->game->id));

				WholesalerYourOrder::create([
					'wholesaler_id' => $model->retailer->wholesaler->id,
					'your_order' => orderCalculate($model->retailer->game->max_weeks,
						$model->retailer->wholesaler->wholesalerWeeks()->count())
				]);
			}
		}

		sleep(2);
		$this->manufacturerService->nextWeek($model->retailer->wholesaler->distributor->manufacturer);
	}
}
