<?php

namespace App\Observers;

use App\Events\RetailerWeekEvent;
use App\Models\RetailerWeek;

class RetailerWeekObserver
{

	/**
	 * @param RetailerWeek $model
	 */
	public function created(RetailerWeek $model)
	{
		if ($model->retailer->retailerWeeks()->count() > 1) {
			broadcast(new RetailerWeekEvent($model->retailer->game->id));
		}
	}
}
