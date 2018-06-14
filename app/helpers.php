<?php

if (!function_exists('removeAllGameSessions')) {
	function removeAllGameSessions($userId)
	{
		if ($retailers = \App\Models\Retailer::where('user_id', $userId)->get()) {
			foreach ($retailers as $retailer) {
				$retailer->user_id = null;
				$retailer->autoplayer = true;
				$retailer->save();
			}
		}

		if ($wholesalers = \App\Models\Wholesaler::where('user_id', $userId)->get()) {
			foreach ($wholesalers as $wholesaler) {
				$wholesaler->user_id = null;
				$wholesaler->autoplayer = true;
				$wholesaler->save();
			}
		}

		if ($distributors = \App\Models\Distributor::where('user_id', $userId)->get()) {
			foreach ($distributors as $distributor) {
				$distributor->user_id = null;
				$distributor->autoplayer = true;
				$distributor->save();
			}
		}

		if ($manufacturers = \App\Models\Manufacturer::where('user_id', $userId)->get()) {
			foreach ($manufacturers as $manufacturer) {
				$manufacturer->user_id = null;
				$manufacturer->autoplayer = true;
				$manufacturer->save();
			}
		}
	}
}
