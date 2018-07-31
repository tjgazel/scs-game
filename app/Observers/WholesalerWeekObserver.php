<?php

namespace App\Observers;

use App\Models\WholesalerWeek;
use App\Services\RetailerService;

class WholesalerWeekObserver
{
	/**
	 * @var RetailerService
	 */
	private $retailerService;

	/**
	 * WholesalerWeekObserver constructor.
	 * @param RetailerService $retailerService
	 */
	public function __construct(RetailerService $retailerService)
	{
		$this->retailerService = $retailerService;
	}


	/**
	 * @param WholesalerWeek $model
	 */
	public function created(WholesalerWeek $model)
	{
		if ($model->wholesaler->wholesalerWeeks()->count() > 1) {
//			broadcast(new WholesalerWeekEvent($model->wholesaler->game->id));
			$this->retailerService->nextWeek($model->wholesaler->retailer);
		}
	}
}
