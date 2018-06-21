<?php
namespace Gmf\Sys\Http\Controllers\App;

use GAuth;
use Gmf\Sys\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use Gmf\Sys\Models;

class ConfigController extends Controller
{
  /**
   * 应用服务配置，授权
   * [{appId}]
   */
  public function config(Request $request)
  {
    $input = $request->all();
    Validator::make($input, [
      'appId' => 'required',
    ])->validate();
    $app = Models\App\App::where('id', $input['appId'])->orWhere('openid', $input['appId'])->first();
    if (empty($app)) {
      throw new \Exception('没有此应用.');
    }
    $entId = $request->input('entId', GAuth::entId());
    $token = app('Gmf\Sys\Bp\AppConfig')->config([
      'appId' => $app->id,
      'userId' => GAuth::id(),
      'entId' => $entId,
    ]);
    if (empty($token['host'])) {
      $token['host'] = $request->getSchemeAndHttpHost();
    }
    return $this->toJson($token);
  }
}
