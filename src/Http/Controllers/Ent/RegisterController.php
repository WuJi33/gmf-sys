<?php
namespace Gmf\Sys\Http\Controllers\Ent;

use Gmf\Sys\Http\Controllers\Controller;
use Gmf\Sys\Models;
use Illuminate\Http\Request;
use Validator;

class RegisterController extends Controller {
  /**
   * 提供企业应用注册服务
   * 注册企业
   */
  public function register(Request $request) {
    $input = $request->all();
    Validator::make($input, [
      'token' => 'required',
      'datas.openid' => 'required',
      'datas.name' => 'required',
    ])->validate();
    $user = $this->userCheck($input);

    $data = array_except($input['datas'], ['id', 'code']);
    $ent = Models\Ent\Ent::where('openid', $data['openid'])->first();
    if ($ent) {
      if (!Models\Ent\EntUser::where('ent_id', $ent->id)->where('user_id', $user->id)->exists()) {
        throw new \Exception('企业已经发布过，请使用原有账号发布!');
      }
      Models\Ent\Ent::where('id', $ent->id)->update(array_only($data, ['name', 'token', 'gateway', 'scope']));
    } else {
      $ent = Models\Ent\Ent::create($data);
      Models\Ent\Ent::addUser($ent->id, $user->id, 'creator');
    }
    return $this->toJson(true);
  }
  private function userCheck($input) {
    $user = Models\User::where('token', $input['token'])->first();
    if (empty($user)) {
      throw new \Exception('用户无效!');
    }
    if ($user->status_enum == 'locked') {
      throw new \Exception('用户被锁定!');
    }
    return $user;
  }
}
