<?php
$ns = 'Gmf\Sys\Http\Controllers';
Route::prefix('api/sys/sv')->middleware(['api', 'auth:api'])->namespace($ns)->group(function () {
	Route::post('config', 'Sv\ConfigController@config');	
});

//ents
Route::prefix('api/sys')->middleware(['api'])->namespace($ns)->group(function () {
	Route::post('ents/register', 'Ent\RegisterController@register');
});
Route::prefix('api/sys')->middleware(['api', 'auth:api'])->namespace($ns)->group(function () {
  Route::post('ents/publish', 'Ent\PublishController@publish');
  Route::get('/ents/my', 'Ent\EntController@getMyEnts');
	Route::get('/ents/token', 'Ent\EntController@getToken');
  Route::post('/ents/token', 'Ent\EntController@createToken');
  Route::post('/ents/join', 'Ent\EntController@join');
  Route::post('/ents/default', 'Ent\EntController@setDefault');
  Route::resource('ents', 'Ent\EntController', ['only' => ['index', 'show', 'store', 'update', 'destroy']]);
});
//auth
Route::prefix('api/sys/auth')->middleware(['api'])->namespace($ns)->group(function () {
  Route::post('token', 'Auth\TokenController@issueToken');
});
Route::prefix('api/sys/auth')->middleware(['web'])->namespace($ns)->group(function () {
	Route::post('checker', 'AuthController@checker');
	Route::any('show', 'AuthController@getUser');
	Route::get('logged', 'AuthController@getLogged');
	Route::post('register', 'AuthController@register');
	Route::post('login', 'AuthController@login');
	Route::post('vcode-checker', 'AuthController@checkVCode');
	Route::post('vcode-create', 'AuthController@createVCode');
	Route::post('reset', 'AuthController@resetPassword');
	Route::post('login-vcode/{id}', 'AuthController@loginWithVCode');
});
Route::prefix('api/sys/auth')->middleware(['web', 'auth'])->namespace($ns)->group(function () {
	Route::post('/entry-ent/{id}', 'AuthController@entryEnt');
	Route::post('verify-mail', 'AuthController@verifyMail');
	Route::any('logout', 'AuthController@logout');
});
Route::prefix('api/sys/auth')->middleware(['api', 'auth:api'])->namespace($ns)->group(function () {
	Route::post('joins', 'AuthController@addJoins');
	Route::get('joins', 'AuthController@getJoins');
	Route::delete('joins', 'AuthController@removeJoins');
});
//editor

Route::prefix('api/sys')->middleware(['api'])->namespace($ns)->group(function () {
	Route::get('editor/templates', 'EditorController@templates');
});

Route::prefix('api/sys')->middleware(['api'])->namespace($ns)->group(function () {
	Route::get('datas/uid', 'DataController@issueUid');
	Route::get('datas/sn', 'DataController@issueSn');
	Route::resource('datas', 'DataController', ['only' => ['index', 'show']]);
	Route::resource('components', 'ComponentController', ['only' => ['index', 'show']]);
	Route::resource('images', 'ImageController', ['only' => ['show']]);
});

Route::prefix('api/sys')->middleware(['api', 'auth:api'])->namespace($ns)->group(function () {
	Route::post('datas/import', 'DataController@dataImport');

	Route::post('/lns/request', 'LnsController@issueRequest');
	Route::post('/lns/answer', 'LnsController@issueAnswer');
	Route::post('/lns/regist', 'LnsController@storeRegist');

	Route::get('/entities/pager', 'EntityController@pager');
	Route::get('/enums/all', 'EntityController@getAllEnums');
	Route::get('/enums/{enum}', 'EntityController@getEnum');
	Route::resource('entities', 'EntityController', ['only' => ['index', 'show']]);

	Route::get('/queries/{query}/cases', 'QueryController@getCases');
	Route::post('/queries/query/{query?}', 'QueryController@query');
	Route::resource('queries', 'QueryController', ['only' => ['index', 'show']]);

	Route::resource('query-cases', 'QueryCaseController', ['only' => ['show', 'store', 'destroy']]);

	Route::get('/menus/all', 'MenuController@all');
	Route::get('/menus/path/{id}', 'MenuController@getPath');
	Route::resource('menus', 'MenuController', ['only' => ['index', 'show']]);

	Route::resource('languages', 'LanguageController', ['only' => ['index', 'show']]);

	Route::resource('users', 'UserController', ['only' => ['index', 'show']]);

	Route::post('/profiles/batch', 'ProfileController@batchStore');
	Route::resource('profiles', 'ProfileController', ['only' => ['index', 'show', 'store', 'update', 'destroy']]);

	Route::resource('files', 'FileController', ['only' => ['store', 'show']]);

	Route::resource('dtis', 'DtiController', ['only' => ['index', 'show', 'store', 'update', 'destroy']]);
	Route::resource('dti-categories', 'DtiCategoryController', ['only' => ['index', 'show', 'store', 'update', 'destroy']]);
	Route::resource('dti-params', 'DtiParamController', ['only' => ['index', 'show', 'store', 'update', 'destroy']]);

});

Route::prefix('api/sys/authority')->middleware(['api', 'auth:api'])->namespace($ns)->group(function () {
	Route::post('/roles/batch', 'Authority\RoleController@batchStore');
	Route::resource('roles', 'Authority\RoleController', ['only' => ['index', 'show', 'store', 'update', 'destroy']]);

	Route::post('/permits/batch', 'Authority\PermitController@batchStore');
	Route::resource('permits', 'Authority\PermitController', ['only' => ['index', 'show', 'store', 'update', 'destroy']]);

	Route::resource('role-entities', 'Authority\RoleEntityController', ['only' => ['index', 'store', 'destroy']]);

	Route::resource('role-permits', 'Authority\RolePermitController', ['only' => ['index', 'store', 'destroy']]);

	Route::resource('role-menus', 'Authority\RoleMenuController', ['only' => ['index', 'store', 'destroy']]);

	Route::resource('role-users', 'Authority\RoleUserController', ['only' => ['index', 'store', 'destroy']]);

});