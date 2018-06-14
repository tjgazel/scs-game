<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RetailerWeek extends Model
{
	public $timestamps = false;

	protected $fillable = [
		'retailer_id',
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

	public function retailer()
	{
		return $this->belongsTo(Retailer::class);
	}
}
