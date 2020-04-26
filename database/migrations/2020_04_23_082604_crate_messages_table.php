<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CrateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('type')->default(0)->comment('消息类型 1请求添加用户 2申请加群 2系统消息');
            $table->unsignedInteger('from_user_id')->default(0)->comment('发送者');
            $table->unsignedInteger('to_user_id')->default(0)->comment('接收者 0表示为系统消息');
            $table->unsignedTinyInteger('status')->default(0)->comment('消息状态 0待处理 1同意 2拒绝');
            $table->string('remark')->default('')->comment('备注/附加消息');
            $table->unsignedInteger('group_id')->default(0)->comment('分组ID/群聊ID');
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
        Schema::dropIfExists('messages');
    }
}
