<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ManufacturerWeek extends Model
{
	public $timestamps = false;

	protected $fillable = [
		'manufacturer_id',
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

	public function manufacturer()
	{
		return $this->belongsTo(Manufacturer::class);
	}
}
