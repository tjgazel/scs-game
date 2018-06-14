<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RetailerYourOrder extends Model
{
	public $timestamps = false;

	protected $fillable = ['retailer_id', 'your_order'];

	public function retailer()
	{
		return $this->belongsTo(Retailer::class);
	}
}
