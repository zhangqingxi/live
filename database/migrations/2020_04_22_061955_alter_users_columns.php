<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterUsersColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedTinyInteger('status')->default(1)->comment('用户在线状态 1在线 0隐身');
            $table->unsignedTinyInteger('sex')->default(0)->comment('性别 0保密 1男 0女');
            $table->string('sign')->default('')->comment('个性签名');
            $table->string('avatar')->default('')->comment('用户头像');
            $table->string('token')->unique()->comment('用户token');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->dropColumn('sign');
            $table->dropColumn('avatar');
            $table->dropColumn('sex');
            $table->dropColumn('token');
        });
    }
}
