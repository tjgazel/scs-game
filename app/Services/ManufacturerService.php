<?php

namespace App\Services;

use App\Models\Manufacturer;
use App\Models\ManufacturerWeek;

class ManufacturerService
{
	public function nextWeek($gameId)
	{
		$model = Manufacturer::where('game_id', $gameId)->first();
		$modelWeekCount = $model->manufacturerWeeks()->count();

		$incoming = isset($model->yourOrders[$modelWeekCount - 3]->your_order) ?
			$model->yourOrders[$modelWeekCount - 3]->your_order : 0;
		$available = $model->manufacturerWeeks->last()->inventory + $incoming;
		$toShip = $model->manufacturerWeeks->last()->back_order + $model->distributor->yourOrders->last()->your_order;
		$delivery = ($available - $toShip >= 0) ? $toShip : $available;
		$backOrder = ($available - $toShip < 0) ? (($available - $toShip) + (-2 * ($available - $toShip))) : 0;
		$inventory = ($available - $delivery >= 0) ? ($available - $delivery) : 0;
		$costStock = $model->game->cost_stock * $inventory;
		$costDelay = $model->game->cost_delay * $backOrder;
		$cost = $costStock + $costDelay;

		return ManufacturerWeek::create([
			'manufacturer_id' => $model->id,
			'incoming' => $incoming,
			'available' => $available,
			'new_order' => $model->distributor->yourOrders->last()->your_order,
			'to_ship' => $toShip,
			'delivery' => $delivery,
			'back_order' => $backOrder,
			'inventory' => $inventory,
			'your_order' => $model->yourOrders->last()->your_order,
			'cost' => $cost
		]);
	}

}
