<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'session_id', 'remember_token',
    ];

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
