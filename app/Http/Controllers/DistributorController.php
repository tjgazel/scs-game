<?php

namespace App\Http\Controllers;

use App\Events\DistributorInactivePlayer;
use App\Models\Distributor;
use App\Models\DistributorYourOrder;
use App\Models\SessionDB;
use App\Services\DistributorService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DistributorController extends Controller
{
	/**
	 * @var DistributorService
	 */
	private $service;

	/**
	 * DistributorController constructor.
	 * @param DistributorService $service
	 */
	public function __construct(DistributorService $service)
	{
		$this->service = $service;
	}

	public function index($gameId)
	{
		$model = Distributor::where('game_id', $gameId)->first();
		$distributor = $model;

		if (!$model->game->status) {
			return redirect()->route('games.show', ['game' => $gameId]);
		}

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
				broadcast(new DistributorInactivePlayer($gameId));
			}else{
				toastr()->error('Já existe um usuário jogando como Distribuidor!');
				return redirect()->back();
			}
		}

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
			'maxWait' => $model->game->max_wait,
			'weekLog' => $model->distributorWeeks->last(),
			'lastBackOrder' => $model->distributorWeeks[$modelWeekCount - 2]->back_order ?? 0,
			'incomingWeekOne' => $model->manufacturer->manufacturerWeeks->last()->delivery,
			'incomingWeekTwo' => $model->manufacturer->manufacturerWeeks[$manufacturerWeekCount - 2]->delivery ?? 0
		];
	}

	public function weekLog($gameId)
	{
		$model = Distributor::where('game_id', $gameId)->first();

		return response()->json([
			'week_log' => $model->distributorWeeks->toArray(),
			'cost_stock' => $model->game->cost_stock,
			'cost_delay' => $model->game->cost_delay
		]);
	}

	public function yourOrder(Request $request, $gameId)
	{
		$request->validate(['your_order' => 'required|integer']);

		$model = Distributor::where('game_id', $gameId)->first();

		DistributorYourOrder::create([
			'distributor_id' => $model->id,
			'your_order' => $request->get('your_order')
		]);

		return ['message' => 'Ok'];
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
