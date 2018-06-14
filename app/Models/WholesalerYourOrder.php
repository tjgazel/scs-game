<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WholesalerYourOrder extends Model
{
	public $timestamps = false;

	protected $fillable = ['wholesaler_id', 'your_order'];

	public function wholesaler()
	{
		return $this->belongsTo(Wholesaler::class);
	}
}
