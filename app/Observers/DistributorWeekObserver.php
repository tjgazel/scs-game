<?php

namespace App\Observers;

use App\Models\DistributorWeek;
use App\Models\SessionDB;
use App\Services\WholesalerService;
use Carbon\Carbon;

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
			$maxWait = $model->distributor->game->max_wait;
			$now = Carbon::now();
			$wholesalerMaxWait = false;

			if (isset($model->distributor->wholesaler->user->session_id)) {
				if ($last_activity = SessionDB::where('id', $model->distributor->wholesaler->user->session_id)
					->first()->last_activity) {
					$lastActivity = $now->diffInMinutes(Carbon::createFromTimestamp($last_activity));
					$wholesalerMaxWait = ($lastActivity > $maxWait) ? true : false;
				}
			}

			if ($model->distributor->wholesaler->autoplayer || $wholesalerMaxWait){
				$this->wholesalerService->nextWeek($model->distributor->game->id);
			}
		}
	}
}
