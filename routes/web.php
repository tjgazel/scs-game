<?php


Route::get('/', 'HomeController@index')->name('home')->middleware('removeAllGameSessions');
Route::get('/game-instructions', 'HomeController@gameInstructions')->name('game-instructions')->middleware('removeAllGameSessions');

Auth::routes();

Route::middleware('auth')->group(function () {
	Route::resource('users', 'UserController')->middleware('removeAllGameSessions');

	Route::resource('games', 'GameController')->except(['create', 'edit', 'destroy'])->middleware('removeAllGameSessions');

	Route::get('games/{gameId}/retailer', 'RetailerController@index')->name('retailer.index');
	Route::get('games/{gameId}/retailer/stakeholder', 'RetailerController@stakeholder')->name('retailer.stakeholder');
	Route::get('games/{gameId}/retailer/week-log', 'RetailerController@weekLog')->name('retailer.weeklog');
	Route::post('games/{gameId}/retailer/your-order', 'RetailerController@yourOrder')->name('retailer.your-order');
	Route::post('games/{gameId}/retailer/game-out', 'RetailerController@gameOut')->name('retailer.gameout');

	Route::get('games/{gameId}/wholesaler', 'WholesalerController@index')->name('wholesaler.index');
	Route::get('games/{gameId}/wholesaler/stakeholder', 'WholesalerController@stakeholder')->name('wholesaler.stakeholder');
	Route::get('games/{gameId}/wholesaler/week-log', 'WholesalerController@weekLog')->name('wholesaler.weeklog');
	Route::post('games/{gameId}/wholesaler/your-order', 'WholesalerController@yourOrder')->name('wholesaler.your-order');
	Route::post('games/{gameId}/wholesaler/game-out', 'WholesalerController@gameOut')->name('wholesaler.gameout');

	Route::get('games/{gameId}/distributor', 'DistributorController@index')->name('distributor.index');
	Route::get('games/{gameId}/distributor/stakeholder', 'DistributorController@stakeholder')->name('distributor.stakeholder');
	Route::get('games/{gameId}/distributor/week-log', 'DistributorController@weekLog')->name('distributor.weeklog');
	Route::post('games/{gameId}/distributor', 'DistributorController@yourOrder')->name('distributor.your-order');
	Route::post('games/{gameId}/distributor/game-out', 'DistributorController@gameOut')->name('distributor.gameout');

	Route::get('games/{gameId}/manufacturer', 'ManufacturerController@index')->name('manufacturer.index');
	Route::get('games/{gameId}/manufacturer/stakeholder', 'ManufacturerController@stakeholder')->name('manufacturer.stakeholder');
	Route::get('games/{gameId}/manufacturer/week-log', 'ManufacturerController@weekLog')->name('manufacturer.weeklog');
	Route::post('games/{gameId}/manufacturer/your-order', 'ManufacturerController@yourOrder')->name('manufacturer.your-order');
	Route::post('games/{gameId}/manufacturer/game-out', 'ManufacturerController@gameOut')->name('manufacturer.gameout');
});
