<?php

namespace App\Services;

use App\Models\Distributor;
use App\Models\DistributorWeek;

class DistributorService
{
	public function nextWeek($gameId)
	{
		$model = Distributor::where('game_id', $gameId)->first();
		$manufacturerWeekCount = $model->manufacturer->manufacturerWeeks()->count();

		$incoming = isset($model->manufacturer->manufacturerWeeks[$manufacturerWeekCount - 3]->delivery) ?
			$model->manufacturer->manufacturerWeeks[$manufacturerWeekCount - 3]->delivery : 0;
		$available = $model->distributorWeeks->last()->inventory + $incoming;
		$toShip = $model->distributorWeeks->last()->back_order + $model->wholesaler->yourOrders->last()->your_order;
		$delivery = ($available - $toShip >= 0) ? $toShip : $available;
		$backOrder = ($available - $toShip < 0) ? (($available - $toShip) + (-2 * ($available - $toShip))) : 0;
		$inventory = ($available - $delivery >= 0) ? ($available - $delivery) : 0;
		$costStock = $model->game->cost_stock * $inventory;
		$costDelay = $model->game->cost_delay * $backOrder;
		$cost = $costStock + $costDelay;

		return DistributorWeek::create([
			'distributor_id' => $model->id,
			'incoming' => $incoming,
			'available' => $available,
			'new_order' => $model->wholesaler->yourOrders->last()->your_order,
			'to_ship' => $toShip,
			'delivery' => $delivery,
			'back_order' => $backOrder,
			'inventory' => $inventory,
			'your_order' => $model->yourOrders->last()->your_order,
			'cost' => $cost
		]);
	}
}
