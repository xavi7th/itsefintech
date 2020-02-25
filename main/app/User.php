<?php

namespace App;

use Illuminate\Support\Facades\Auth;
use Watson\Rememberable\Rememberable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements JWTSubject
{
	use Notifiable, SoftDeletes, Rememberable;

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
		'password', 'remember_token',
	];

	/**
	 * The attributes that should be cast to native types.
	 *
	 * @var array
	 */
	protected $casts = [
		'email_verified_at' => 'datetime',
	];


	/**
	 * Returns the dashboard route of the authenticated user
	 *
	 * @return void
	 */
	static function dashboardRoute(): string
	{
		if (Auth::admin()) {
			return 'admin.dashboard';
		} else if (Auth::normalAdmin()) {
			return 'normaladmin.dashboard';
		} else if (Auth::accountant()) {
			return 'accountant.dashboard';
		} else if (Auth::accountOfficer()) {
			return 'accountofficer.dashboard';
		} else if (Auth::salesRep()) {
			return 'salesrep.dashboard';
		} else if (Auth::cardAdmin()) {
			return 'cardadmin.dashboard';
		} else if (Auth::customerSupport()) {
			return 'customersupport.dashboard';
		} else {
			abort(403, 'Invalid user');
		}
	}

	/**
	 * Route notifications for the Nexmo channel.
	 *
	 * @param  \Illuminate\Notifications\Notification  $notification
	 * @return string
	 */
	public function routeNotificationForNexmo($notification)
	{
		return $this->phone;
	}

	public function routeNotificationForTwilio()
	{
		return $this->phone;
	}

	public function toFlare(): array
	{
		// Only `id` will be sent to Flare.
		return [
			'id' => $this->id
		];
	}


	/**
	 * Get the identifier that will be stored in the subject claim of the JWT.
	 *
	 * @return mixed
	 */
	public function getJWTIdentifier()
	{
		return $this->getKey();
	}

	/**
	 * Return a key value array, containing any custom claims to be added to the JWT.
	 *
	 * @return array
	 */
	public function getJWTCustomClaims()
	{
		return [];
	}
}
