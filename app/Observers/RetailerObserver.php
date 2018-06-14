<?php

namespace App\Observers;

use App\Models\Retailer;
use App\Models\RetailerWeek;

/**
 * Class RetailerObserver
 * @package App\Observers
 */
class RetailerObserver
{

	/**
	 * Listen to the Retailer created event.
	 *
	 * @param Retailer $retailer
	 */
	public function created(Retailer $retailer)
	{
		$initialInventory = random_int(50, 75);
		$costStock = $initialInventory * $retailer->game->cost_stock;

		$data = [
			'retailer_id' => $retailer->id,
			'incoming' => 0,
			'available' => $initialInventory,
			'new_order' => 0,
			'to_ship' => 0,
			'delivery' => 0,
			'back_order' => 0,
			'inventory' => $initialInventory,
			'your_order' => 0,
			'cost' => $costStock
		];

		RetailerWeek::create($data);
	}
}
