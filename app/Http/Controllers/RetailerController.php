<?php

namespace App\Http\Controllers;

use App\Events\RetailerInactivePlayer;
use App\Models\Retailer;
use App\Models\RetailerYourOrder;
use App\Models\SessionDB;
use App\Services\RetailerService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RetailerController extends Controller
{
	/**
	 * @var RetailerService
	 */
	private $service;

	/**
	 * RetailerController constructor.
	 * @param RetailerService $service
	 */
	public function __construct(RetailerService $service)
	{
		$this->service = $service;
	}

	public function index($gameId)
	{
		$model = Retailer::where('game_id', $gameId)->first();
		$retailer = $model;

		if ($model->autoplayer) {
			$model->user_id = auth()->user()->id;
			$model->autoplayer = false;
			$model->save();

		} elseif ($model->user_id != auth()->user()->id) {
			$last_activity = SessionDB::where('id', $model->user->session_id)->first()->last_activity;
			$now = Carbon::now();

			if ($now->diffInMinutes(Carbon::createFromTimestamp($last_activity)) > $model->game->max_wait) {
				$model->user_id = auth()->user()->id;
				$model->autoplayer = false;
				$model->save();
				broadcast(new RetailerInactivePlayer($gameId));
			} else {
				toastr()->error('Já existe um usuário jogando como Varejista!');
				return redirect()->back();
			}
		}

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
			'maxWait' => $model->game->max_wait,
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

		return ['message' => 'Ok'];
	}

	public function nextWeek($gameId)
	{
		$retailerWeek = $this->service->nextWeek($gameId);

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
}
