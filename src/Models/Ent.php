<?php

namespace Gmf\Sys\Models;
use Gmf\Sys\Traits\HasGuard;
use Gmf\Sys\Traits\Snapshotable;
use Illuminate\Database\Eloquent\Model;

class Ent extends Model {
	use Snapshotable, HasGuard;
	protected $table = 'gmf_sys_ents';
	public $incrementing = false;
	protected $keyType = 'string';
	protected $fillable = ['id', 'code', 'name', 'memo'];
}
