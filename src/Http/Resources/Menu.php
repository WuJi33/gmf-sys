<?php
namespace Gmf\Sys\Http\Resources;
use Closure;
use Gmf\Sys\Builder;
use Illuminate\Http\Resources\Json\Resource;

class Menu extends Resource {
	private $callback;

	public function withCallback(Closure $callback = null) {
		$this->callback = $callback;
		return $this;
	}
	/**
	 * Transform the resource into an array.
	 *
	 * @param  \Illuminate\Http\Request
	 * @return array
	 */
	public function toArray($request) {
		if (empty($this->id)) {
			return false;
		}

		$rtn = new Builder;
		Common::toField($this, $rtn, ['id', 'root_id', 'parent_id', 'code', 'name', 'memo', 'uri', 'icon', 'style', 'tag', 'params', 'sequence', 'is_leaf', 'created_at']);

		if (!is_null($this->callback)) {
			$flag = call_user_func($this->callback, $rtn, $this);
			if ($flag === 0) {
				return false;
			}
		}
		return $rtn;
	}
}
