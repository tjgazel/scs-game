<?php

namespace App\Http\Controllers;

use App\Events\DistributorNewOrderEvent;
use App\Events\WholesalerWeekEvent;
use App\Models\Wholesaler;
use App\Models\WholesalerWeek;
use App\Models\WholesalerYourOrder;
use Illuminate\Http\Request;

class WholesalerController extends Controller
{
	public function index($gameId)
	{
		$model = Wholesaler::where('game_id', $gameId)->first();
		$model->user_id = auth()->user()->id;
		$model->autoplayer = false;
		$model->save();
		$wholesaler = $model;

		return view('wholesaler', compact('wholesaler'));
	}

	public function stakeholder($gameId)
	{
		/**    @var Wholesaler $model */
		$model = Wholesaler::where('game_id', $gameId)->first();
		$modelWeekCount = $model->wholesalerWeeks()->count();
		$distributorWeekCount = $model->distributor->distributorWeeks()->count();

		return [
			'week' => $modelWeekCount - 1,
			'maxWeeks' => $model->game->max_weeks,
			'weekLog' => $model->wholesalerWeeks->last(),
			'lastBackOrder' => $model->wholesalerWeeks[$modelWeekCount - 2]->back_order ?? 0,
			'incomingWeekOne' => $model->distributor->distributorWeeks->last()->delivery,
			'incomingWeekTwo' => $model->distributor->distributorWeeks[$distributorWeekCount - 2]->delivery ?? 0
		];
	}

	public function weekLog($gameId)
	{
		$model = Wholesaler::where('game_id', $gameId)->first();

		return $model->wholesalerWeeks;
	}

	public function yourOrder(Request $request, $gameId)
	{
		$request->validate(['your_order' => 'required|integer']);

		$model = Wholesaler::where('game_id', $gameId)->first();

		WholesalerYourOrder::create([
			'wholesaler_id' => $model->id,
			'your_order' => $request->get('your_order')
		]);

		broadcast(new DistributorNewOrderEvent($model->game->id));

		return ['message' => 'Ok'];
	}

	public function nextWeek($gameId)
	{
		$model = Wholesaler::where('game_id', $gameId)->first();
		$distributorWeekCount = $model->distributor->distributorWeeks()->count();

		$incoming = isset($model->distributor->distributorWeeks[$distributorWeekCount -3]->delivery) ?
			$model->distributor->distributorWeeks[$distributorWeekCount -3]->delivery : 0;
		$available = $model->wholesalerWeeks->last()->inventory + $incoming;
		$toShip = $model->wholesalerWeeks->last()->back_order + $model->retailer->yourOrders->last()->your_order;
		$delivery = ($available - $toShip >= 0) ? $toShip : $available;
		$backOrder = ($available - $toShip < 0) ? (($available - $toShip) + (-2 * ($available - $toShip))) : 0;
		$inventory = ($available - $delivery >= 0) ? ($available - $delivery) : 0;
		$costStock = $model->game->cost_stock * $inventory;
		$costDelay = $model->game->cost_delay * $backOrder;
		$cost = $costStock + $costDelay;

		$wholesalerWeek = WholesalerWeek::create([
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

		broadcast(new WholesalerWeekEvent($model->game->id));

		return $wholesalerWeek;
	}

	public function gameOut($gameId)
	{
		$model = Wholesaler::where('game_id', $gameId)->first();
		$model->user_id = null;
		$model->autoplayer = true;
		$model->save();

		toastr()->info('Até logo!');

		return redirect()->route('games.index');
	}
}
