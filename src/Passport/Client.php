<?php

namespace Gmf\Sys\Passport;
use Gmf\Sys\Traits\HasGuard;
use Gmf\Sys\Traits\Snapshotable;
use Illuminate\Database\Eloquent\Model;

class Client extends Model {
	use Snapshotable, HasGuard;

	public $incrementing = false;
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'gmf_oauth_clients';

	/**
	 * The guarded attributes on the model.
	 *
	 * @var array
	 */
	protected $guarded = [];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = [
		'secret',
	];

	/**
	 * The attributes that should be cast to native types.
	 *
	 * @var array
	 */
	protected $casts = [
		'personal_access_client' => 'bool',
		'password_client' => 'bool',
		'revoked' => 'bool',
	];

	/**
	 * Get all of the authentication codes for the client.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function authCodes() {
		return $this->hasMany(AuthCode::class);
	}

	/**
	 * Get all of the tokens that belong to the client.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function tokens() {
		return $this->hasMany(Token::class);
	}

	/**
	 * Determine if the client is a "first party" client.
	 *
	 * @return bool
	 */
	public function firstParty() {
		return $this->personal_access_client || $this->password_client;
	}
}
