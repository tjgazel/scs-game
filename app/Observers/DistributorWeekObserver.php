<?php

namespace App\Observers;

use App\Models\DistributorWeek;
use App\Services\WholesalerService;

class DistributorWeekObserver
{
	/**
	 * @var WholesalerService
	 */
	private $wholesalerService;

	/**
	 * DistributorWeekObserver constructor.
	 * @param WholesalerService $wholesalerService
	 */
	public function __construct(WholesalerService $wholesalerService)
	{
		$this->wholesalerService = $wholesalerService;
	}


	/**
	 * @param DistributorWeek $model
	 */
	public function created(DistributorWeek $model)
	{
		if ($model->distributor->distributorWeeks()->count() > 1) {
			$this->wholesalerService->nextWeek($model->distributor->wholesaler);
		}
	}
}
