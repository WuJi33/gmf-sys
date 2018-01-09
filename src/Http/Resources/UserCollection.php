<?php

namespace Gmf\Sys\Http\Resources;
use Closure;
use Gmf\Sys\Builder;
use Illuminate\Http\Resources\Json\ResourceCollection;

class UserCollection extends ResourceCollection {
	private $itemCallback;
	public function __construct($resource, Closure $itemCallback = null) {
		parent::__construct($resource);
		$this->itemCallback = $itemCallback;
	}
	/**
	 * Transform the resource into an array.
	 *
	 * @param  \Illuminate\Http\Request
	 * @return array
	 */
	public function toArray($request) {
		$rtn = [];
		foreach ($this->collection as $key => $value) {
			$v = $this->formatItem($value);
			if ($v) {
				$rtn[] = $v;
			}
		}
		return $rtn;
	}
	private function formatItem($value) {
		$rtn = new Builder;
		$rtn->id($value->id)
			->name($value->name)
			->nick_name($value->nick_name)
			->avatar($value->avatar)
			->cover($value->cover);

		if (empty($rtn->avatar)) {
			$rtn->avatar('/assets/vendor/gmf-sys/avatar/1.jpg');
		}
		if (empty($rtn->cover)) {
			$rtn->cover('/assets/vendor/gmf-sys/cover/1.jpg');
		}
		if (empty($rtn->nick_name)) {
			$rtn->nick_name($rtn->name);
		}
		if (!is_null($this->itemCallback)) {
			$flag = call_user_func($this->itemCallback, $rtn, $value);
			if ($flag === 0) {
				return false;
			}
		}
		return $rtn;
	}
}
