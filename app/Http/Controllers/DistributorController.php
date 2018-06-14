<?php

namespace App\Http\Controllers;

use App\Events\DistributorWeekEvent;
use App\Events\ManufacturerNewOrderEvent;
use App\Models\Distributor;
use App\Models\DistributorWeek;
use App\Models\DistributorYourOrder;
use Illuminate\Http\Request;

class DistributorController extends Controller
{
	public function index($gameId)
	{
		$model = Distributor::where('game_id', $gameId)->first();
		$model->user_id = auth()->user()->id;
		$model->autoplayer = false;
		$model->save();
		$distributor = $model;

		return view('distributor', compact('distributor'));
	}

	public function stakeholder($gameId)
	{
		/**    @var Distributor $model */
		$model = Distributor::where('game_id', $gameId)->first();
		$modelWeekCount = $model->distributorWeeks()->count();
		$manufacturerWeekCount = $model->manufacturer->manufacturerWeeks()->count();

		return [
			'week' => $modelWeekCount - 1,
			'maxWeeks' => $model->game->max_weeks,
			'weekLog' => $model->distributorWeeks->last(),
			'lastBackOrder' => $model->distributorWeeks[$modelWeekCount - 2]->back_order ?? 0,
			'incomingWeekOne' => $model->manufacturer->manufacturerWeeks->last()->delivery,
			'incomingWeekTwo' => $model->manufacturer->manufacturerWeeks[$manufacturerWeekCount - 2]->delivery ?? 0
		];
	}

	public function weekLog($gameId)
	{
		$model = Distributor::where('game_id', $gameId)->first();

		return $model->distributorWeeks;
	}

	public function yourOrder(Request $request, $gameId)
	{
		$request->validate(['your_order' => 'required|integer']);

		$model = Distributor::where('game_id', $gameId)->first();

		DistributorYourOrder::create([
			'distributor_id' => $model->id,
			'your_order' => $request->get('your_order')
		]);

		broadcast(new ManufacturerNewOrderEvent($model->game->id));

		return ['message' => 'Ok'];
	}

	public function nextWeek($gameId)
	{
		$model = Distributor::where('game_id', $gameId)->first();
		$manufacturerWeekCount = $model->manufacturer->manufacturerWeeks()->count();

		$incoming = isset($model->manufacturer->manufacturerWeeks[$manufacturerWeekCount - 3]->delivery) ?
			$model->manufacturer->manufacturerWeeks[$manufacturerWeekCount - 3]->delivery : 0;
		$available = $model->distributorWeeks->last()->inventory + $incoming;
		$toShip = $model->distributorWeeks->last()->back_order + $model->wholesaler->yourOrders->last()->your_order;
		$delivery = ($available - $toShip >= 0) ? $toShip : $available;
		$backOrder = ($available - $toShip < 0) ? (($available - $toShip) + (-2 * ($available - $toShip))) : 0;
		$inventory = ($available - $delivery >= 0) ? ($available - $delivery) : 0;
		$costStock = $model->game->cost_stock * $inventory;
		$costDelay = $model->game->cost_delay * $backOrder;
		$cost = $costStock + $costDelay;

		$distributorWeek = DistributorWeek::create([
			'distributor_id' => $model->id,
			'incoming' => $incoming,
			'available' => $available,
			'new_order' => $model->wholesaler->yourOrders->last()->your_order,
			'to_ship' => $toShip,
			'delivery' => $delivery,
			'back_order' => $backOrder,
			'inventory' => $inventory,
			'your_order' => $model->yourOrders->last()->your_order,
			'cost' => $cost
		]);

		broadcast(new DistributorWeekEvent($model->game->id));

		return $distributorWeek;
	}

	public function gameOut($gameId)
	{
		$model = Distributor::where('game_id', $gameId)->first();
		$model->user_id = null;
		$model->autoplayer = true;
		$model->save();

		toastr()->info('Até logo!');

		return redirect()->route('games.index');
	}
}
