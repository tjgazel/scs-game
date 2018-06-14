<?php

namespace App\Http\Middleware;

use App\Models\Distributor;
use App\Models\Manufacturer;
use App\Models\Retailer;
use App\Models\Wholesaler;
use Closure;
use Illuminate\Support\Facades\Auth;

class RemoveAllGameSessions
{
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @param  \Closure                 $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		if (Auth::check()) {
			if ($retailers = Retailer::where('user_id', Auth::user()->id)->get()) {
				foreach ($retailers as $retailer) {
					$retailer->user_id = null;
					$retailer->autoplayer = true;
					$retailer->save();
				}
			}

			if ($wholesalers = Wholesaler::where('user_id', Auth::user()->id)->get()) {
				foreach ($wholesalers as $wholesaler) {
					$wholesaler->user_id = null;
					$wholesaler->autoplayer = true;
					$wholesaler->save();
				}
			}

			if ($distributors = Distributor::where('user_id', Auth::user()->id)->get()) {
				foreach ($distributors as $distributor) {
					$distributor->user_id = null;
					$distributor->autoplayer = true;
					$distributor->save();
				}
			}

			if ($manufacturers = Manufacturer::where('user_id', Auth::user()->id)->get()) {
				foreach ($manufacturers as $manufacturer) {
					$manufacturer->user_id = null;
					$manufacturer->autoplayer = true;
					$manufacturer->save();
				}
			}
		}

		return $next($request);
	}
}
