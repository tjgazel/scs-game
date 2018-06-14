<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DistributorYourOrder extends Model
{
	public $timestamps = false;

	protected $fillable = ['distributor_id', 'your_order'];

	public function distributor()
	{
		return $this->belongsTo(Distributor::class);
	}
}
