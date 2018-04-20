<?php

namespace Gmf\Sys\Http\Middleware;

use Gmf\Sys\Exceptions\MissingScopeException;
use Illuminate\Auth\AuthenticationException;

class CheckScopes {
	/**
	 * Handle the incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @param  mixed  ...$scopes
	 * @return \Illuminate\Http\Response
	 * @throws \Illuminate\Auth\AuthenticationException
	 * @throws \Gmf\Passport\Exceptions\MissingScopeException
	 */
	public function handle($request, $next, ...$scopes) {
		if (!$request->user() || !$request->user()->token()) {
			throw new AuthenticationException;
		}

		foreach ($scopes as $scope) {
			if (!$request->user()->tokenCan($scope)) {
				throw new MissingScopeException($scope);
			}
		}

		return $next($request);
	}
}
