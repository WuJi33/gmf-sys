<?php

namespace Gmf\Sys\Models\Ent;
use Gmf\Sys\Traits\HasGuard;
use Gmf\Sys\Traits\Snapshotable;
use Illuminate\Database\Eloquent\Model;

class EntUser extends Model {
  use Snapshotable, HasGuard;
  protected $table = 'gmf_sys_ent_users';
  protected $fillable = ['user_id', 'ent_id', 'token', 'type_enum', 'is_default', 'is_effective', 'revoked'];
  public function ent() {
    return $this->belongsTo(config('gmf.ent.model'));
  }
  public function user() {
    return $this->belongsTo(config('gmf.user.model'));
  }
}
