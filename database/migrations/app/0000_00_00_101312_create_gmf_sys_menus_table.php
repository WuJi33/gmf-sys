<?php

use Gmf\Sys\Database\Metadata;
use Illuminate\Database\Migrations\Migration;

class CreateGmfSysMenusTable extends Migration
{
  public $mdID = "c8e847c009cb11e7bc1d095fc7d1b98a";
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    $md = Metadata::create($this->mdID);
    $md->mdEntity('gmf.sys.menu')->comment('菜单')->tableName('gmf_sys_menus')->model('Gmf\\Sys\\Menu');

    $md->string('id', 100)->primary();
    $md->entity('app', 'gmf.sys.app')->nullable()->comment('应用');
    $md->entity('root', 'gmf.sys.menu')->nullable()->comment('最上级菜单Id');
    $md->entity('parent', 'gmf.sys.menu')->nullable()->comment('上级菜单');
    //public
    $md->string('scope')->nullable()->comment('范围');
    $md->string('code')->comment('编码');
    $md->string('name')->nullable()->comment('名称');
    $md->text('memo')->nullable()->comment('备注');
    $md->text('params')->nullable()->comment('参数');
    $md->string('tag')->nullable()->comment('标识');
    $md->string('uri')->nullable()->comment('URI导航标识');
    $md->string('icon')->nullable()->comment('图标');
    $md->text('style')->nullable()->comment('样式');

    $md->integer('sequence')->default('0')->comment('顺序号');
    $md->integer('hide')->default(0)->comment('隐藏');    
    $md->boolean('is_leaf')->default(1)->comment('叶子');
    $md->timestamps();

    $md->build();

  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Metadata::dropIfExists($this->mdID);
  }
}
