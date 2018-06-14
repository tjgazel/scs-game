<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
	protected $fillable = ['name', 'week', 'max_weeks', 'weeks_delivery', 'cost_stock', 'cost_delay', 'status'];

	public function getCreatedAtAttribute($value)
	{
		return date('d/m/y H:i', strtotime($value));
	}

    public function retailer()
	{
		return $this->hasOne(Retailer::class);
	}

	public function wholesaler()
	{
		return $this->hasOne(Wholesaler::class);
	}

	public function distributor()
	{
		return $this->hasOne(Distributor::class);
	}

	public function manufacturer()
	{
		return $this->hasOne(Manufacturer::class);
	}
}
