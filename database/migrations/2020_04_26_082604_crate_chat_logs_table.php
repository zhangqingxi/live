<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CrateChatLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chat_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('type')->default(0)->comment('聊天类型 1好友 2群聊');
            $table->unsignedInteger('from_user_id')->default(0)->comment('发送者');
            $table->unsignedInteger('to_user_id')->default(0)->comment('接收者');
            $table->string('content')->default('')->comment('内容');
            $table->unsignedInteger('group_id')->default(0)->comment('群聊ID');
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
        Schema::dropIfExists('chat_logs');
    }
}
