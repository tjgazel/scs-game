<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wholesaler extends Model
{
    public $timestamps = false;

	protected $fillable = ['game_id', 'distributor_id', 'autoplayer'];

	public function game()
	{
		return $this->belongsTo(Game::class);
	}

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function retailer()
	{
		return $this->hasOne(Retailer::class);
	}

	public function distributor()
	{
		return $this->belongsTo(Distributor::class);
	}

	public function wholesalerWeeks()
	{
		return $this->hasMany(WholesalerWeek::class);
	}

	public function yourOrders()
	{
		return $this->hasMany(WholesalerYourOrder::class);
	}
}
