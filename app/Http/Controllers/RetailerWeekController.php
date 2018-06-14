<?php

namespace App\Http\Controllers;

use App\Models\Retailer;
use App\Models\RetailerWeek;
use Illuminate\Http\Request;

class RetailerWeekController extends Controller
{
	private $newOrder;

	public function __construct()
	{
		$this->newOrder = random_int(5, 40);
	}

	public function nextWeek(Request $request, $gameId)
	{
		$request->validate(['your_order' => 'required|integer']);

		$retailer = Retailer::where('game_id', $gameId)->first();

		if ($retailer->wholesaler->autoplayer) {

		}

		$incoming = (int)$retailer->wholesaler->wholesalerWeeks->last()->delivery;
		$available = $retailer->retailerWeeks->last()->inventory + $incoming;
		$toShip = $retailer->retailerWeeks->last()->back_order + $this->newOrder;
		$delivery = ($available - $toShip >= 0) ? $toShip : $available;
		$backOrder = ($available - $toShip < 0) ? (($available - $toShip) + (-2 * ($available - $toShip))) : 0;
		$inventory = ($available - $delivery >= 0) ? ($available - $delivery) : 0;
		$costStock = $retailer->game->cost_stock * $inventory;
		$costDelay = $retailer->game->cost_delay * $backOrder;
		$cost = $costStock + $costDelay;

		$data = [
			'retailer_id' => $retailer->id,
			'incoming' => $incoming,
			'available' => $available,
			'new_order' => $this->newOrder,
			'to_ship' => $toShip,
			'delivery' => $delivery,
			'back_order' => $backOrder,
			'inventory' => $inventory,
			'your_order' => $request->get('your_order'),
			'cost' => $cost
		];

		return RetailerWeek::create($data);
	}


}
