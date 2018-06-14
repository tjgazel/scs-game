<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ManufacturerYourOrder extends Model
{
	public $timestamps = false;

	protected $fillable = ['manufacturer_id', 'your_order'];

	public function manufacturer()
	{
		return $this->belongsTo(Manufacturer::class);
	}
}
