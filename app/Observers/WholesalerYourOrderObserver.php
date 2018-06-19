<?php

namespace App\Observers;


use App\Events\DistributorInactivePlayer;
use App\Events\DistributorNewOrderEvent;
use App\Events\RetailerInactivePlayer;
use App\Models\DistributorYourOrder;
use App\Models\RetailerYourOrder;
use App\Models\WholesalerYourOrder;
use App\Models\SessionDB;
use Carbon\Carbon;

class WholesalerYourOrderObserver
{

	/**
	 * Listen to the Retailer created event.
	 *
	 * @param WholesalerYourOrder $model
	 */
	public function created(WholesalerYourOrder $model)
	{
		$wholesalerCountOrder = $model->wholesaler->yourOrders()->count();
		$maxWait = $model->wholesaler->game->max_wait;
		$now = Carbon::now();
		$retailerMaxWait = false;
		$distributorMaxWait = false;

		if (isset($model->wholesaler->retailer->user->session_id)) {
			if ($last_activity = SessionDB::where('id', $model->wholesaler->retailer->user->session_id)
				->first()->last_activity) {
				$lastActivity = $now->diffInMinutes(Carbon::createFromTimestamp($last_activity));
				$retailerMaxWait = ($lastActivity > $maxWait) ? true : false;
			}
		}

		if ($model->wholesaler->retailer->autoplayer || $retailerMaxWait) {
			$retailerCountOrder = $model->wholesaler->retailer->yourOrders()->count();
			if ($wholesalerCountOrder > $retailerCountOrder) {
				RetailerYourOrder::create([
					'retailer_id' => $model->wholesaler->retailer->id,
					'your_order' => orderCalculate($model->wholesaler->game->max_weeks,
						$model->wholesaler->retailer->retailerWeeks()->count())
				]);
				broadcast(new RetailerInactivePlayer($model->wholesaler->game->id));
			}
		}

		if (isset($model->wholesaler->distributor->user->session_id)) {
			if ($last_activity = SessionDB::where('id', $model->wholesaler->distributor->user->session_id)
				->first()->last_activity) {
				$lastActivity = $now->diffInMinutes(Carbon::createFromTimestamp($last_activity));
				$distributorMaxWait = ($lastActivity > $maxWait) ? true : false;
			}
		}

		if ($model->wholesaler->distributor->autoplayer || $distributorMaxWait) {
			$distributorCountOrder = $model->wholesaler->distributor->yourOrders()->count();
			if ($wholesalerCountOrder > $distributorCountOrder) {
				DistributorYourOrder::create([
					'distributor_id' => $model->wholesaler->distributor->id,
					'your_order' => orderCalculate($model->wholesaler->game->max_weeks,
						$model->wholesaler->distributor->distributorWeeks()->count())
				]);
				broadcast(new DistributorInactivePlayer($model->wholesaler->game->id));
			}
		} else {
			broadcast(new DistributorNewOrderEvent($model->wholesaler->game->id));
		}

	}
}
