<?php

use Gmf\Sys\Database\Metadata;
use Illuminate\Database\Migrations\Migration;

class CreateGmfSysAccountsTable extends Migration {
	public $mdID = "416c4510eaaf11e795588733d9134fa5";
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		$md = Metadata::create($this->mdID);
		$md->mdEntity('gmf.sys.account')->comment('账号')->tableName('gmf_sys_accounts');
		$md->string('id', 100)->primary();
		$md->string('name')->nullable()->comment('账号名');
		$md->string('nick_name')->nullable()->comment('显示名称');
		$md->string('type')->nullable()->comment('类型');
		$md->string('avatar', 500)->nullable();
		$md->string('cover', 500)->nullable();
		$md->string('mobile', 20)->nullable();
		$md->string('email', 50)->nullable();
		$md->string('src_id', 50)->nullable()->comment('第三方用户id');
		$md->text('src_url')->nullable()->comment('账号来源地址');

		$md->string('token', 100)->nullable()->comment('授权码');
		$md->bigInteger('expire_time')->nullable()->comment('失效时间');

		$md->text('info')->nullable();
		$md->timestamps();

		$md->build();

	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Metadata::dropIfExists($this->mdID);
	}
}