<?php

namespace Gmf\Sys\Models\Corn;
use Gmf\Sys\Traits\HasGuard;
use Gmf\Sys\Traits\Snapshotable;
use Illuminate\Database\Eloquent\Model;

class CronScheme extends Model {
	use Snapshotable, HasGuard;
	protected $table = 'gmf_sys_cron_schemes';
	public $incrementing = false;
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['id'];
}
