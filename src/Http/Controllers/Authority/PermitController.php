<?php
namespace Gmf\Sys\Http\Controllers\Authority;

use Gmf\Sys\Http\Controllers\Controller;
use Gmf\Sys\Models\Authority\Permit;
use Illuminate\Http\Request;
use Validator;

class PermitController extends Controller {
	public function show(Request $request, string $id) {
		$query = Permit::where('ent_id', $request->oauth_ent_id);
		$data = $query->where('id', $id)->orWhere('code', $id)->first();
		return $this->toJson($data);
	}
	public function store(Request $request) {
		$input = array_only($request->all(), ['code', 'name', 'memo']);
		$validator = Validator::make($input, [
			'code' => 'required',
			'name' => 'required',
		]);
		if ($validator->fails()) {
			return $this->toError($validator->errors());
		}
		$entId = $request->oauth_ent_id;
		$data['ent_id'] = $entId;
		$data = Permit::create($input);
		return $this->show($request, $data->id);
	}
	/**
	 * PUT/PATCH
	 * @param  Request $request [description]
	 * @param  [type]  $id      [description]
	 * @return [type]           [description]
	 */
	public function update(Request $request, $id) {
		$input = array_only($request->all(), ['code', 'name', 'memo']);
		$validator = Validator::make($input, [
			'code' => 'required',
			'name' => 'required',
		]);
		if ($validator->fails()) {
			return $this->toError($validator->errors());
		}
		$entId = $request->oauth_ent_id;
		$data = Permit::updateOrCreate(['id' => $id], $input);
		return $this->show($request, $data->id);
	}
	public function destroy(Request $request, $id) {
		$ids = explode(",", $id);
		Permit::destroy($ids);
		return $this->toJson(true);
	}
}