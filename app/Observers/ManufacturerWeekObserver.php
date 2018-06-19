<?php

namespace App\Observers;

use App\Events\ManufacturerWeekEvent;
use App\Models\ManufacturerWeek;
use App\Models\SessionDB;
use App\Services\DistributorService;
use Carbon\Carbon;

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
			$maxWait = $model->manufacturer->game->max_wait;
			$now = Carbon::now();
			$distributorMaxWait = false;

			if (isset($model->manufacturer->distributor->user->session_id)) {
				if ($last_activity = SessionDB::where('id', $model->manufacturer->distributor->user->session_id)
					->first()->last_activity) {
					$lastActivity = $now->diffInMinutes(Carbon::createFromTimestamp($last_activity));
					$distributorMaxWait = ($lastActivity > $maxWait) ? true : false;
				}
			}

			if ($model->manufacturer->distributor->autoplayer || $distributorMaxWait){
				$this->distributorService->nextWeek($model->manufacturer->game->id);
			}

			broadcast(new ManufacturerWeekEvent($model->manufacturer->game->id));
		}
	}
}
