<?php

namespace App\Services;

use App\Models\Retailer;
use App\Models\RetailerWeek;

class RetailerService
{
	public function nextWeek($gameId)
	{
		$model = Retailer::where('game_id', $gameId)->first();
		$wholesalerWeekCount = $model->wholesaler->wholesalerWeeks()->count();
		$newOrder = orderCalculate($model->game->max_weeks, $wholesalerWeekCount);

		$incoming = isset($model->wholesaler->wholesalerWeeks[$wholesalerWeekCount - 3]->delivery) ?
			$model->wholesaler->wholesalerWeeks[$wholesalerWeekCount - 3]->delivery : 0;
		$available = $model->retailerWeeks->last()->inventory + $incoming;
		$toShip = $model->retailerWeeks->last()->back_order + $newOrder;
		$delivery = ($available - $toShip >= 0) ? $toShip : $available;
		$backOrder = ($available - $toShip < 0) ? (($available - $toShip) + (-2 * ($available - $toShip))) : 0;
		$inventory = ($available - $delivery >= 0) ? ($available - $delivery) : 0;
		$costStock = $model->game->cost_stock * $inventory;
		$costDelay = $model->game->cost_delay * $backOrder;
		$cost = $costStock + $costDelay;

		return RetailerWeek::create([
			'retailer_id' => $model->id,
			'incoming' => $incoming,
			'available' => $available,
			'new_order' => $newOrder,
			'to_ship' => $toShip,
			'delivery' => $delivery,
			'back_order' => $backOrder,
			'inventory' => $inventory,
			'your_order' => $model->yourOrders->last()->your_order,
			'cost' => $cost
		]);
	}
}
