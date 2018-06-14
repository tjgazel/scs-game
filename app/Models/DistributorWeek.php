<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DistributorWeek extends Model
{
	public $timestamps = false;

	protected $fillable = [
		'distributor_id',
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

	public function distributor()
	{
		return $this->belongsTo(Distributor::class);
	}
}
