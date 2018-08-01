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
			$game = $model->retailer->game;

			if($model->retailer->yourOrders()->count() >= $game->max_weeks){
				$game->status = false;
				$game->save();
			}

			broadcast(new RetailerWeekEvent($game->id));
		}
	}
}
