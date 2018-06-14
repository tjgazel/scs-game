<?php

namespace App\Http\Controllers;

use App\Events\RetailerWeekEvent;
use App\Events\WholesalerNewOrderEvent;
use App\Models\Retailer;
use App\Models\RetailerWeek;
use App\Models\RetailerYourOrder;
use Illuminate\Http\Request;

class RetailerController extends Controller
{

	public function index($gameId)
	{
		$model = Retailer::where('game_id', $gameId)->first();
		$model->user_id = auth()->user()->id;
		$model->autoplayer = false;
		$model->save();
		$retailer = $model;

		return view('retailer', compact('retailer'));
	}

	public function stakeholder($gameId)
	{
		$model = Retailer::where('game_id', $gameId)->first();
		$modelWeekCount = $model->retailerWeeks()->count();
		$wholesalerWeekCount = $model->wholesaler->wholesalerWeeks()->count();

		return [
			'week' => $model->retailerWeeks()->count() - 1,
			'maxWeeks' => $model->game->max_weeks,
			'weekLog' => $model->retailerWeeks->last(),
			'lastBackOrder' => $model->retailerWeeks[$modelWeekCount - 2]->back_order ?? 0,
			'incomingWeekOne' => $model->wholesaler->wholesalerWeeks->last()->delivery,
			'incomingWeekTwo' => $model->wholesaler->wholesalerWeeks[$wholesalerWeekCount - 2]->delivery ?? 0
		];
	}

	public function yourOrder(Request $request, $gameId)
	{
		$request->validate(['your_order' => 'required|integer']);

		$model = Retailer::where('game_id', $gameId)->first();

		RetailerYourOrder::create([
			'retailer_id' => $model->id,
			'your_order' => $request->get('your_order')
		]);

		broadcast(new WholesalerNewOrderEvent($model->game->id));

		return ['message' => 'Ok'];
	}

	public function nextWeek($gameId)
	{
		$model = Retailer::where('game_id', $gameId)->first();
		$wholesalerWeekCount = $model->wholesaler->wholesalerWeeks()->count();
		$newOrder = $this->newOrderCalculate($model->game->max_weeks, $wholesalerWeekCount);

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

		$retailerWeek = RetailerWeek::create([
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

		broadcast(new RetailerWeekEvent($model->game->id));

		return $retailerWeek;
	}

	public function weekLog($gameId)
	{
		$retailer = Retailer::where('game_id', $gameId)->first();

		return $retailer->retailerWeeks;
	}

	public function gameOut($gameId)
	{
		$model = Retailer::where('game_id', $gameId)->first();
		$model->user_id = null;
		$model->autoplayer = true;
		$model->save();

		toastr()->info('Até logo!');

		return redirect()->route('games.index');
	}

	/**
	 * @param $maxWeeks
	 * @param $weekCount
	 * @return int
	 */
	private function newOrderCalculate($maxWeeks, $weekCount)
	{
		if ($maxWeeks == 52 && ($weekCount <= ($maxWeeks / 2) || $weekCount > (($maxWeeks / 2) + 10))) {
			return random_int(5, 40);
		}

		if ($maxWeeks == 26 && ($weekCount <= ($maxWeeks / 2) || $weekCount > (($maxWeeks / 2) + 6))) {
			return random_int(5, 40);
		}

		return random_int(40, 75);
	}

}
