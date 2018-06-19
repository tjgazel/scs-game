<?php

namespace App\Observers;


use App\Events\WholesalerInactivePlayer;
use App\Events\WholesalerNewOrderEvent;
use App\Models\RetailerYourOrder;
use App\Models\SessionDB;
use App\Models\WholesalerYourOrder;
use Carbon\Carbon;

class RetailerYourOrderObserver
{

	/**
	 * Listen to the Retailer created event.
	 *
	 * @param RetailerYourOrder $model
	 */
	public function created(RetailerYourOrder $model)
	{
		$retailerCountOrder = $model->retailer->yourOrders()->count();
		$maxWait = $model->retailer->game->max_wait;
		$now = Carbon::now();
		$wholesalerMaxWait = false;

		if (isset($model->retailer->wholesaler->user->session_id)) {
			if ($last_activity = SessionDB::where('id', $model->retailer->wholesaler->user->session_id)
				->first()->last_activity) {
				$lastActivity = $now->diffInMinutes(Carbon::createFromTimestamp($last_activity));
				$wholesalerMaxWait = ($lastActivity > $maxWait) ? true : false;
			}
		}

		if ($model->retailer->wholesaler->autoplayer || $wholesalerMaxWait) {
			$wholesalerCountOrder = $model->retailer->wholesaler->yourOrders()->count();
			if ($retailerCountOrder > $wholesalerCountOrder) {
				WholesalerYourOrder::create([
					'wholesaler_id' => $model->retailer->wholesaler->id,
					'your_order' => orderCalculate($model->retailer->game->max_weeks,
						$model->retailer->wholesaler->wholesalerWeeks()->count())
				]);
				broadcast(new WholesalerInactivePlayer($model->retailer->game->id));
			}
		} else {
			broadcast(new WholesalerNewOrderEvent($model->retailer->game->id));
		}
	}
}
