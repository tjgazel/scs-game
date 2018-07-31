<?php

namespace App\Observers;


use App\Events\DistributorInactivePlayer;
use App\Events\WholesalerYourOrderEvent;
use App\Events\RetailerInactivePlayer;
use App\Models\DistributorYourOrder;
use App\Models\RetailerYourOrder;
use App\Models\WholesalerYourOrder;
use App\Services\ManufacturerService;

class WholesalerYourOrderObserver
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
	 * @param WholesalerYourOrder $model
	 */
	public function created(WholesalerYourOrder $model)
	{
		broadcast(new WholesalerYourOrderEvent($model->wholesaler->game->id));

		$wholesalerCountOrder = $model->wholesaler->yourOrders()->count();

		if (isAutoPlayer($model->wholesaler->retailer)) {
			$retailerCountOrder = $model->wholesaler->retailer->yourOrders()->count();

			if ($wholesalerCountOrder > $retailerCountOrder) {
				broadcast(new RetailerInactivePlayer($model->wholesaler->game->id));

				RetailerYourOrder::create([
					'retailer_id' => $model->wholesaler->retailer->id,
					'your_order' => orderCalculate($model->wholesaler->game->max_weeks,
						$model->wholesaler->retailer->retailerWeeks()->count())
				]);
			}
		}

		if (isAutoPlayer($model->wholesaler->distributor)) {
			$distributorCountOrder = $model->wholesaler->distributor->yourOrders()->count();

			if ($wholesalerCountOrder > $distributorCountOrder) {
				broadcast(new DistributorInactivePlayer($model->wholesaler->game->id));

				DistributorYourOrder::create([
					'distributor_id' => $model->wholesaler->distributor->id,
					'your_order' => orderCalculate($model->wholesaler->game->max_weeks,
						$model->wholesaler->distributor->distributorWeeks()->count())
				]);
			}
		}

		sleep(2);
		$this->manufacturerService->nextWeek($model->wholesaler->distributor->manufacturer);
	}
}
