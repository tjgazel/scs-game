<?php

if (!function_exists('startInventory')) {
	function startInventory()
	{
		return random_int(50, 75);
	}
}

if (!function_exists('orderCalculate')) {
	function orderCalculate($maxWeeks, $week)
	{
		if ($maxWeeks == 52 && ($week <= ($maxWeeks / 2) || $week > (($maxWeeks / 2) + 10))) {
			return random_int(5, 40);
		}

		if ($maxWeeks == 26 && ($week <= ($maxWeeks / 2) || $week > (($maxWeeks / 2) + 6))) {
			return random_int(5, 40);
		}

		return random_int(40, 75);
	}
}

if (!function_exists('isAutoPlayer')) {
	function isAutoPlayer($model)
	{
		if (isset($model->user->session_id)) {
			if ($last_activity = \App\Models\SessionDB::where('id', $model->user->session_id)->first()) {
				$now = \Carbon\Carbon::now();
				$lastActivity = $now->diffInMinutes(\Carbon\Carbon::createFromTimestamp($last_activity->last_activity));
				if ($lastActivity <= $model->game->max_wait) {
					return false;
				} else {
					$model->user_id = null;
					$model->autoplayer = true;
					$model->save();

					return true;
				}
			} else {
				$model->user_id = null;
				$model->autoplayer = true;
				$model->save();

				return true;
			}
		}

		return true;
	}
}
