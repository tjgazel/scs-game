<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Distributor extends Model
{
	public $timestamps = false;

	protected $fillable = ['game_id', 'manufacturer_id', 'autoplayer'];

	public function game()
	{
		return $this->belongsTo(Game::class);
	}

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function wholesaler()
	{
		return $this->hasOne(Wholesaler::class);
	}

	public function manufacturer()
	{
		return $this->belongsTo(Manufacturer::class);
	}

	public function distributorWeeks()
	{
		return $this->hasMany(DistributorWeek::class);
	}

	public function yourOrders()
	{
		return $this->hasMany(DistributorYourOrder::class);
	}
}
