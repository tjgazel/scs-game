<?php

namespace App\Http\Controllers;

use App\Events\ManufacturerInactivePlayer;
use App\Models\Manufacturer;
use App\Models\ManufacturerYourOrder;
use App\Models\SessionDB;
use App\Services\ManufacturerService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ManufacturerController extends Controller
{
	/**
	 * @var ManufacturerService
	 */
	private $service;

	public function __construct(ManufacturerService $service)
	{
		$this->service = $service;
	}

	public function index($gameId)
	{
		$model = Manufacturer::where('game_id', $gameId)->first();
		$manufacturer = $model;

		if ($model->autoplayer) {
			$model->user_id = auth()->user()->id;
			$model->autoplayer = false;
			$model->save();

		} elseif ($model->user_id != auth()->user()->id) {
			$last_activity = SessionDB::where('id', $model->user->session_id)->first()->last_activity;
			$now = Carbon::now();

			if ($now->diffInMinutes(Carbon::createFromTimestamp($last_activity)) > 1) {
				$model->user_id = auth()->user()->id;
				$model->autoplayer = false;
				$model->save();
				broadcast(new ManufacturerInactivePlayer($gameId));
			}else{
				toastr()->error('Já existe um usuário jogando como Fabricante!');
				return redirect()->back();
			}
		}

		return view('manufacturer', compact('manufacturer'));
	}

	public function stakeholder($gameId)
	{
		$model = Manufacturer::where('game_id', $gameId)->first();
		$modelWeekCount = $model->manufacturerWeeks()->count();

		return [
			'week' => $modelWeekCount - 1,
			'maxWeeks' => $model->game->max_weeks,
			'maxWait' => $model->game->max_wait,
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
		$manufacturerWeek = $this->service->nextWeek($model);

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
