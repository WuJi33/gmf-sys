<?php

namespace Gmf\Sys\GAuth;
use Auth;
use DB;
use Illuminate\Auth\AuthenticationException;

class GAuthGuard {
	protected $m_ent;
	protected $m_user;
	protected $m_client;

	protected $m_forged = false;
	protected $user_links = [];

	public function SESSION_ENT_KEY() {
		return config('gmf.ent.session');
	}
	public function setForged($forged = true) {
		$this->m_forged = $forged;
	}
	public function forged() {
		return $this->m_forged;
	}
	public function ids() {
		return $this->user_links;
	}
	public function id() {
		return $this->userId();
	}
	public function verified() {
		$u = $this->user();
		if ($u && $u->email_verified) {
			return 'email';
		}

		if ($u && $u->mobile_verified) {
			return 'mobile';
		}

		return false;
	}

	public function check($scope = 'user') {
		if (!$this->id()) {
			throw new AuthenticationException();
		}
	}
	public function checkRole($roles) {
		if (!$this->hasRole($roles)) {
			throw new \Exception('没有角色!');
		}
	}
	public function hasRole($roles) {
		if ($this->id()) {
			if (is_string($roles)) {
				$roles = explode(',', $roles);
			}
			if (DB::table('gmf_sys_authority_role_users as ru')
				->join('gmf_sys_authority_roles as r', 'ru.role_id', '=', 'r.id')
				->select('r.id')
				->where('ru.user_id', $this->id())
				->whereIn('r.code', $roles)
				->where('ru.is_revoked', '0')
				->where('r.is_revoked', '0')
				->first()) {
				return true;
			}
		}
		return false;
	}
	public function checkPermit($permits) {
		if (!$this->canPermit($permits)) {
			throw new \Exception('没有权限!');
		}
	}
	public function canPermit($permits) {
		if ($this->id()) {
			if (is_string($permits)) {
				$permits = explode(',', $permits);
			}
			if (DB::table('gmf_sys_authority_role_users as ru')
				->join('gmf_sys_authority_roles as r', 'ru.role_id', '=', 'r.id')
				->join('gmf_sys_authority_role_permits as rp', 'rp.role_id', '=', 'r.id')
				->join('gmf_sys_authority_permits as p', 'rp.permit_id', '=', 'p.id')
				->select('p.id')
				->where('ru.user_id', $this->id())
				->whereIn('p.code', $permits)
				->where('ru.is_revoked', '0')
				->where('rp.is_revoked', '0')
				->where('p.is_revoked', '0')
				->where('r.is_revoked', '0')
				->first()) {
				return true;
			}
		}
		return false;
	}
	public function ent() {
		return $this->m_ent;
	}
	public function entId() {
		if ($this->ent()) {
			return $this->ent()->id;
		}
		return '';
	}
	public function setEnt($ent) {
		$this->m_ent = $ent;
		return $this;
	}

	public function client() {
		return $this->m_client;
	}
	public function clientId() {
		if ($this->client()) {
			return $this->client()->id;
		}
		return '';
	}
	public function setClient($client) {
		$this->m_client = $client;
		return $this;
	}
	public function user() {
		if ($this->m_user) {
			return $this->m_user;
		}
		if (!$this->forged()) {
			return Auth::user();
		}
		return null;
	}
	public function userId() {
		if ($this->user()) {
			return $this->user()->id;
		}
		return '';
	}
	public function setUser($user) {
		$this->user_links = [];
		$this->m_user = $user;
		if ($user) {
			$links = DB::table('gmf_sys_user_links as l')->where('l.fm_user_id', $user->id)->pluck('to_user_id');
			if ($links->count()) {
				$links = $links->merge(DB::table('gmf_sys_user_links as l')->whereIn('l.fm_user_id', $links->all())->pluck('to_user_id')->all());
			}
			if ($links->count()) {
				$this->user_links = $links->unique()->reject(function ($v) {return empty($v);})->values()->all();
			}
		}
		if (!in_array($user->id, $this->user_links)) {
			$this->user_links[] = $user->id;
		}
		return $this;
	}
}
