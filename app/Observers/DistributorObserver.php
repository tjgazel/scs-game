<?php

namespace App\Observers;

use App\Models\Distributor;
use App\Models\DistributorWeek;

/**
 * Class DistributorObserver
 * @package App\Observers
 */
class DistributorObserver
{

	/**
	 * Listen to the Distributor created event.
	 *
	 * @param Distributor $distributor
	 */
	public function created(Distributor $distributor)
	{
		$initialInventory = random_int(50, 75);
		$costStock = $initialInventory * $distributor->game->cost_stock;

		$data = [
			'distributor_id' => $distributor->id,
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

		DistributorWeek::create($data);
	}
}
