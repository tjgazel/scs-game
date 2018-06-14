<?php

namespace App\Http\Controllers;

use App\Models\Distributor;
use App\Models\Game;
use App\Models\Manufacturer;
use App\Models\Retailer;
use App\Models\Wholesaler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class GameController extends Controller
{

	public function index()
	{
		$games = Game::orderBy('created_at', 'desc')->limit(100)->get();

		return view('games.index', compact('games'));
	}

	public function show($id)
	{
		$game = Game::find($id);

		if (!$game->status){
			return view('games.result', compact('game'));
		}

		return view('games.show', compact('game'));
	}

	public function store(Request $request)
	{
		$validator = Validator::make($request->all(), ['name' => 'required|max:30']);

		if ($validator->fails()) {
			toastr()->error($validator->errors()->first('name'));
			return redirect(route('games.index'))->withErrors($validator)->withInput();
		}

		DB::beginTransaction();
		try {
			$game = Game::create([
				'name' => $request->get('name'),
				'max_weeks' => $request->get('max_weeks')
			]);
			$manufacturer = Manufacturer::create(['game_id' => $game->id]);
			$distributor = Distributor::create(['game_id' => $game->id,	'manufacturer_id' => $manufacturer->id]);
			$wholesaler = Wholesaler::create(['game_id' => $game->id, 'distributor_id' => $distributor->id]);
			Retailer::create(['game_id' => $game->id, 'wholesaler_id' => $wholesaler->id]);
		} catch (\Exception $e) {
			DB::rollBack();
			toastr()->error('Não foi possível criar o novo jogo. Por favor tente mais tarde e se perssistir o 
				erro informe ao administrador.',"Ops! algo deu errado.");

			return redirect()->route('games.index');
		}
		DB::commit();

		return redirect()->route('games.show', ['id' => $game->id]);
	}

	public function destroy($id)
	{

	}
}
