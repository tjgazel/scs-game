<?php

namespace App\Observers;

use App\Events\WholesalerInactivePlayer;
use App\Models\DistributorYourOrder;
use App\Models\ManufacturerYourOrder;
use App\Models\SessionDB;
use App\Services\ManufacturerService;
use Carbon\Carbon;

class ManufacturerYourOrderObserver
{
	/**
	 * @var ManufacturerService
	 */
	private $service;

	/**
	 * ManufacturerYourOrderObserver constructor.
	 * @param ManufacturerService $service
	 */
	public function __construct(ManufacturerService $service)
	{
		$this->service = $service;
	}

	/**
	 * Listen to the Retailer created event.
	 *
	 * @param ManufacturerYourOrder $model
	 */
	public function created(ManufacturerYourOrder $model)
	{
		$manufacturerCountOrder = $model->manufacturer->yourOrders()->count();
		$maxWait = $model->manufacturer->game->max_wait;
		$now = Carbon::now();
		$distributorMaxWait = false;
		$manufacturerMaxWait = false;

		if (isset($model->manufacturer->distributor->user->session_id)) {
			if ($last_activity = SessionDB::where('id', $model->manufacturer->distributor->user->session_id)
				->first()->last_activity) {
				$lastActivity = $now->diffInMinutes(Carbon::createFromTimestamp($last_activity));
				$distributorMaxWait = ($lastActivity > $maxWait) ? true : false;
			}
		}

		if ($model->manufacturer->distributor->autoplayer || $distributorMaxWait) {
			$distributorCountOrder = $model->manufacturer->distributor->yourOrders()->count();
			if ($manufacturerCountOrder > $distributorCountOrder) {
				DistributorYourOrder::create([
					'distributor_id' => $model->manufacturer->distributor->id,
					'your_order' => orderCalculate($model->manufacturer->game->max_weeks,
						$model->manufacturer->distributor->distributorWeeks()->count())
				]);
				broadcast(new WholesalerInactivePlayer($model->manufacturer->game->id));
			}
		}

		if (isset($model->manufacturer->user->session_id)) {
			if ($last_activity = SessionDB::where('id', $model->manufacturer->user->session_id)
				->first()->last_activity) {
				$lastActivity = $now->diffInMinutes(Carbon::createFromTimestamp($last_activity));
				$manufacturerMaxWait = ($lastActivity > $maxWait) ? true : false;
			}
		}

		if ($model->manufacturer->distributor->autoplayer || $manufacturerMaxWait) {
			$this->service->nextWeek($model->manufacturer->game->id);
		}

	}

}
