<?php

namespace App\Http\Controllers;

use App\Events\ManufacturerWeekEvent;
use App\Models\Manufacturer;
use App\Models\ManufacturerWeek;
use App\Models\ManufacturerYourOrder;
use Illuminate\Http\Request;

class ManufacturerController extends Controller
{
	public function index($gameId)
	{
		$model = Manufacturer::where('game_id', $gameId)->first();
		$model->user_id = auth()->user()->id;
		$model->autoplayer = false;
		$model->save();
		$manufacturer = $model;

		return view('manufacturer', compact('manufacturer'));
	}

	public function stakeholder($gameId)
	{
		$model = Manufacturer::where('game_id', $gameId)->first();
		$modelWeekCount = $model->manufacturerWeeks()->count();

		return [
			'week' => $modelWeekCount - 1,
			'maxWeeks' => $model->game->max_weeks,
			'weekLog' => $model->manufacturerWeeks->last(),
			'lastBackOrder' => $model->manufacturerWeeks[$modelWeekCount - 2]->back_order ?? 0,
			'incomingWeekOne' => $model->manufacturerWeeks->last()->your_order,
			'incomingWeekTwo' => $model->manufacturerWeeks[$modelWeekCount - 2]->your_order ?? 0
		];
	}

	public function weekLog($gameId)
	{
		$model = Manufacturer::where('game_id', $gameId)->first();

		return $model->manufacturerWeeks;
	}

	public function yourOrder(Request $request, $gameId)
	{
		$request->validate(['your_order' => 'required|integer']);

		$model = Manufacturer::where('game_id', $gameId)->first();

		ManufacturerYourOrder::create([
			'manufacturer_id' => $model->id,
			'your_order' => $request->get('your_order')
		]);

		return ['message' => 'success'];
	}

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

		$manufacturerWeek = ManufacturerWeek::create([
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

		broadcast(new ManufacturerWeekEvent($model->game->id));

		return $manufacturerWeek;
	}

	public function gameOut($gameId)
	{
		$model = Manufacturer::where('game_id', $gameId)->first();
		$model->user_id = null;
		$model->autoplayer = true;
		$model->save();

		toastr()->info('Até logo!');

		return redirect()->route('games.index');
	}
}
