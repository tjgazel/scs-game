<?php

namespace App\Services;

use App\Models\Game;
use App\Models\Wholesaler;
use App\Models\WholesalerWeek;

class WholesalerService
{
	public function nextWeek(Wholesaler $model)
	{
		$distributorWeekCount = $model->distributor->distributorWeeks()->count();

		$incoming = isset($model->distributor->distributorWeeks[$distributorWeekCount - 3]->delivery) ?
			$model->distributor->distributorWeeks[$distributorWeekCount - 3]->delivery : 0;
		$available = $model->wholesalerWeeks->last()->inventory + $incoming;
		$toShip = $model->wholesalerWeeks->last()->back_order + $model->retailer->yourOrders->last()->your_order;
		$delivery = ($available - $toShip >= 0) ? $toShip : $available;
		$backOrder = ($available - $toShip < 0) ? (($available - $toShip) + (-2 * ($available - $toShip))) : 0;
		$inventory = ($available - $delivery >= 0) ? ($available - $delivery) : 0;
		$costStock = $model->game->cost_stock * $inventory;
		$costDelay = $model->game->cost_delay * $backOrder;
		$cost = $costStock + $costDelay;

		return WholesalerWeek::create([
			'wholesaler_id' => $model->id,
			'incoming' => $incoming,
			'available' => $available,
			'new_order' => $model->retailer->yourOrders->last()->your_order,
			'to_ship' => $toShip,
			'delivery' => $delivery,
			'back_order' => $backOrder,
			'inventory' => $inventory,
			'your_order' => $model->yourOrders->last()->your_order,
			'cost' => $cost
		]);
	}

	private function validate(Game $game)
	{
		$wholesalerCountOrders = $game->wholesaler->yourOrders()->count();
		$retailerCountOrders = $game->retailer->yourOrders()->count();

		if ($retailerCountOrders == $wholesalerCountOrders) {
			$this->wholesalerService->nextWeek($game->id);
		} else {
			$this->checkOrders($game);
		}
	}
}
