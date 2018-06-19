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
		$startInventory = startInventory();
		$costStock = $startInventory * $manufacturer->game->cost_stock;

		$data = [
			'manufacturer_id' => $manufacturer->id,
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

		ManufacturerWeek::create($data);
	}
}
