<?php

namespace Gmf\Sys\Models;
use Gmf\Sys\Traits\HasGuard;
use Gmf\Sys\Traits\Snapshotable;
use Illuminate\Database\Eloquent\Model;

class LnsItem extends Model {
	use Snapshotable, HasGuard;
	public $timestamps = false;
	protected $table = 'gmf_sys_lns_items';
	protected $fillable = ['type', 'code', 'name', 'field', 'filter'];
}
