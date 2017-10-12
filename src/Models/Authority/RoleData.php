<?php

namespace Gmf\Sys\Models\Authority;
use Gmf\Sys\Traits\HasGuard;
use Gmf\Sys\Traits\Snapshotable;
use Illuminate\Database\Eloquent\Model;

class RoleData extends Model {
	use Snapshotable, HasGuard;
	protected $table = 'gmf_sys_authority_role_datas';
	public $incrementing = false;
	protected $fillable = ['role_id', 'entity_id'];
}
