<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WholesalerWeek extends Model
{
	public $timestamps = false;

	protected $fillable = [
		'wholesaler_id',
		'incoming',
		'available',
		'new_order',
		'to_ship',
		'delivery',
		'back_order',
		'inventory',
		'your_order',
		'cost'
	];

	public function wholesaler()
	{
		return $this->belongsTo(Wholesaler::class);
	}
}
