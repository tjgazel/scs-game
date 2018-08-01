<?php

namespace App\Observers;

use App\Models\ManufacturerWeek;
use App\Services\DistributorService;

class ManufacturerWeekObserver
{
	/**
	 * @var DistributorService
	 */
	private $distributorService;

	/**
	 * ManufacturerWeekObserver constructor.
	 * @param DistributorService $distributorService
	 */
	public function __construct(DistributorService $distributorService)
	{
		$this->distributorService = $distributorService;
	}


	/**
	 * @param ManufacturerWeek $model
	 */
	public function created(ManufacturerWeek $model)
	{
		if ($model->manufacturer->manufacturerWeeks()->count() > 1) {
			$this->distributorService->nextWeek($model->manufacturer->distributor);
		}
	}


}
