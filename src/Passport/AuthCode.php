<?php

namespace Gmf\Sys\Passport;

use Gmf\Sys\Traits\HasGuard;
use Gmf\Sys\Traits\Snapshotable;
use Illuminate\Database\Eloquent\Model;

class AuthCode extends Model {
	use Snapshotable, HasGuard;

	public $incrementing = false;
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'gmf_oauth_auth_codes';

	/**
	 * The guarded attributes on the model.
	 *
	 * @var array
	 */
	protected $guarded = [];

	/**
	 * The attributes that should be cast to native types.
	 *
	 * @var array
	 */
	protected $casts = [
		'revoked' => 'bool',
	];

	/**
	 * The attributes that should be mutated to dates.
	 *
	 * @var array
	 */
	protected $dates = [
		'expires_at',
	];

	/**
	 * Get the client that owns the authentication code.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function client() {
		return $this->belongsTo(Client::class);
	}
}
