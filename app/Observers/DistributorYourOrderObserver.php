<?php

namespace App\Observers;


use App\Events\DistributorInactivePlayer;
use App\Events\ManufacturerInactivePlayer;
use App\Events\ManufacturerNewOrderEvent;
use App\Models\DistributorYourOrder;
use App\Models\ManufacturerYourOrder;
use App\Models\SessionDB;
use App\Models\WholesalerYourOrder;
use Carbon\Carbon;

class DistributorYourOrderObserver
{

	/**
	 * Listen to the Retailer created event.
	 *
	 * @param DistributorYourOrder $model
	 */
	public function created(DistributorYourOrder $model)
	{
		$distributorCountOrder = $model->distributor->yourOrders()->count();
		$maxWait = $model->distributor->game->max_wait;
		$now = Carbon::now();
		$wholesalerMaxWait = false;
		$manufacturerMaxWait = false;

		if (isset($model->distributor->wholesaler->user->session_id)) {
			if ($last_activity = SessionDB::where('id', $model->distributor->wholesaler->user->session_id)
				->first()->last_activity) {
				$lastActivity = $now->diffInMinutes(Carbon::createFromTimestamp($last_activity));
				$wholesalerMaxWait = ($lastActivity > $maxWait) ? true : false;
			}
		}

		if ($model->distributor->wholesaler->autoplayer || $wholesalerMaxWait){
			$wholesalerCountOrder = $model->distributor->wholesaler->yourOrders()->count();
			if ($distributorCountOrder > $wholesalerCountOrder) {
				WholesalerYourOrder::create([
					'wholesaler_id' => $model->distributor->wholesaler->id,
					'your_order' => orderCalculate($model->distributor->game->max_weeks,
						$model->distributor->wholesaler->wholesalerWeeks()->count())
				]);
				broadcast(new DistributorInactivePlayer($model->distributor->game->id));
			}
		}

		if (isset($model->distributor->manufacturer->user->session_id)) {
			if ($last_activity = SessionDB::where('id', $model->distributor->manufacturer->user->session_id)
				->first()->last_activity) {
				$lastActivity = $now->diffInMinutes(Carbon::createFromTimestamp($last_activity));
				$manufacturerMaxWait = ($lastActivity > $maxWait) ? true : false;
			}
		}

		if ($model->distributor->manufacturer->autoplayer || $manufacturerMaxWait) {
			$manufacturerCountOrder = $model->distributor->manufacturer->yourOrders()->count();
			if ($distributorCountOrder > $manufacturerCountOrder) {
				ManufacturerYourOrder::create([
					'manufacturer_id' => $model->distributor->manufacturer->id,
					'your_order' => orderCalculate($model->distributor->game->max_weeks,
						$model->distributor->manufacturer->manufacturerWeeks()->count())
				]);
				broadcast(new ManufacturerInactivePlayer($model->distributor->game->id));
			}
		} else {
			broadcast(new ManufacturerNewOrderEvent($model->distributor->game->id));
		}

	}
}
