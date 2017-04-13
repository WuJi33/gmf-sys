<?php

namespace Gmf\Sys\Models;
use Gmf\Sys\Traits\HasGuard;
use Gmf\Sys\Traits\Snapshotable;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model {
	use Snapshotable, HasGuard;
	protected $table = 'gmf_sys_profiles';
	public $incrementing = false;
	protected $fillable = ['id', 'code', 'name', 'memo', 'scope_enum'];
	public function values() {
		return $this->hasMany('Gmf\Sys\Models\ProfileValue');
	}
}
