<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Manufacturer extends Model
{
	public $timestamps = false;

	protected $fillable = ['game_id', 'autoplayer'];

	public function game()
	{
		return $this->belongsTo(Game::class);
	}

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function distributor()
	{
		return $this->hasOne(Distributor::class);
	}

	public function manufacturerWeeks()
	{
		return $this->hasMany(ManufacturerWeek::class);
	}

	public function yourOrders()
	{
		return $this->hasMany(ManufacturerYourOrder::class);
	}
}
