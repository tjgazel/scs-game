<?php

namespace App\Observers;

use App\Models\Wholesaler;
use App\Models\WholesalerWeek;

/**
 * Class WholesalerObserver
 * @package App\Observers
 */
class WholesalerObserver
{

	/**
	 * Listen to the Wholesaler created event.
	 *
	 * @param Wholesaler $wholesaler
	 */
	public function created(Wholesaler $wholesaler)
	{
		$startInventory = startInventory();
		$costStock = $startInventory * $wholesaler->game->cost_stock;

		$data = [
			'wholesaler_id' => $wholesaler->id,
			'incoming' => 0,
			'available' => $startInventory,
			'new_order' => 0,
			'to_ship' => 0,
			'delivery' => 0,
			'back_order' => 0,
			'inventory' => $startInventory,
			'your_order' => 0,
			'cost' => $costStock
		];

		WholesalerWeek::create($data);
	}
}
