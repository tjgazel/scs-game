<?php

namespace App\Http\Controllers;

use App\Events\WholesalerInactivePlayer;
use App\Events\WholesalerWeekEvent;
use App\Models\SessionDB;
use App\Models\Wholesaler;
use App\Models\WholesalerYourOrder;
use App\Services\WholesalerService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class WholesalerController extends Controller
{
	/**
	 * @var WholesalerService
	 */
	private $service;

	/**
	 * WholesalerController constructor.
	 * @param WholesalerService $service
	 */
	public function __construct(WholesalerService $service)
	{
		$this->service = $service;
	}

	public function index($gameId)
	{
		$model = Wholesaler::where('game_id', $gameId)->first();
		$wholesaler = $model;

		if ($model->autoplayer) {
			$model->user_id = auth()->user()->id;
			$model->autoplayer = false;
			$model->save();

		} elseif ($model->user_id != auth()->user()->id) {
			$last_activity = SessionDB::where('id', $model->user->session_id)->first()->last_activity;
			$now = Carbon::now();

			if ($now->diffInMinutes(Carbon::createFromTimestamp($last_activity)) > 5) {
				$model->user_id = auth()->user()->id;
				$model->autoplayer = false;
				$model->save();
				broadcast(new WholesalerInactivePlayer($gameId));
			} else {
				toastr()->error('Já existe um usuário jogando como Atacadista!');
				return redirect()->back();
			}
		}

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

		return ['message' => 'Ok'];
	}

	public function nextWeek($gameId)
	{
		$wholesalerWeek = $this->service->nextWeek($gameId);
		broadcast(new WholesalerWeekEvent($gameId));

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
