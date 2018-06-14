<?php

namespace App\Observers;

use App\Models\Manufacturer;
use App\Models\ManufacturerWeek;

/**
 * Class ManufacturerObserver
 * @package App\Observers
 */
class ManufacturerObserver
{

	/**
	 * Listen to the Manufacturer created event.
	 *
	 * @param Manufacturer $manufacturer
	 */
	public function created(Manufacturer $manufacturer)
	{
		$initialInventory = random_int(50, 75);
		$costStock = $initialInventory * $manufacturer->game->cost_stock;

		$data = [
			'manufacturer_id' => $manufacturer->id,
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

		ManufacturerWeek::create($data);
	}
}
