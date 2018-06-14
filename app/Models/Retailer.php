<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Retailer extends Model
{
    public $timestamps = false;

	protected $fillable = ['game_id', 'wholesaler_id', 'autoplayer'];

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
		return $this->belongsTo(Wholesaler::class);
	}

	public function retailerWeeks()
	{
		return $this->hasMany(RetailerWeek::class);
	}

	public function yourOrders()
	{
		return $this->hasMany(RetailerYourOrder::class);
	}
}
