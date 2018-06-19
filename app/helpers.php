<?php

if (!function_exists('startInventory')){
	function startInventory(){
		return random_int(50, 75);
	}
}

if (!function_exists('orderCalculate')){
	function orderCalculate($maxWeeks, $weekCount){
		if ($maxWeeks == 52 && ($weekCount <= ($maxWeeks / 2) || $weekCount > (($maxWeeks / 2) + 10))) {
			return random_int(5, 40);
		}

		if ($maxWeeks == 26 && ($weekCount <= ($maxWeeks / 2) || $weekCount > (($maxWeeks / 2) + 6))) {
			return random_int(5, 40);
		}

		return random_int(40, 75);
	}
}
