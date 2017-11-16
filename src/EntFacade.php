<?php
namespace Gmf\Sys;
use Illuminate\Support\Facades\Facade;

/**
 * UuidFacade
 *
 */
class EntFacade extends Facade {
	/**
	 * Get the registered name of the component.
	 *
	 * @return string
	 */
	protected static function getFacadeAccessor() {
		return 'ent';
	}
}