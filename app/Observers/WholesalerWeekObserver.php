<?php

namespace App\Observers;

use App\Models\WholesalerWeek;
use App\Models\SessionDB;
use App\Services\RetailerService;
use Carbon\Carbon;

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
			$maxWait = $model->wholesaler->game->max_wait;
			$now = Carbon::now();
			$retailerMaxWait = false;

			if (isset($model->wholesaler->retailer->user->session_id)) {
				if ($last_activity = SessionDB::where('id', $model->wholesaler->retailer->user->session_id)
					->first()->last_activity) {
					$lastActivity = $now->diffInMinutes(Carbon::createFromTimestamp($last_activity));
					$retailerMaxWait = ($lastActivity > $maxWait) ? true : false;
				}
			}

			if ($model->wholesaler->retailer->autoplayer || $retailerMaxWait) {
				$this->retailerService->nextWeek($model->wholesaler->game->id);
			}
		}
	}
}
