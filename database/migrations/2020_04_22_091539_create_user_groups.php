<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserGroups extends Migration
{
//`mygroupIdx` bigint(20) NOT NULL AUTO_INCREMENT,
//`memberIdx` bigint(20) DEFAULT NULL,
//`mygroupName` varchar(128) DEFAULT '' COMMENT '分组名称',
//`weight` tinyint(2) DEFAULT '1' COMMENT '好友分组的排列顺序 越小越靠前',
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_groups', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id')->default(0)->comment('用户ID');
            $table->string('group_name', 50)->default('')->comment('分组名称');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_groups');
    }
}
