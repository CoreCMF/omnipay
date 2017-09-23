<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class OmnipayConfigsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('omnipay_configs', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string('driver',30)         ->comment('驱动');
            $table->string('appId',60)          ->comment('AppId') ->nullable();
            $table->string('sellerID',80)       ->comment('商家ID')->nullable();
            $table->string('returnUrl',160)     ->comment('回调URl')->nullable();
            $table->string('notifyUrl',160)     ->comment('通知URl')->nullable();
            $table->string('privateKey',80)     ->comment('密钥文件路径')->nullable();
            $table->string('publicKey',80)      ->comment('公钥文件路径')->nullable();
            $table->string('certPassword',80)   ->comment('银联商家密钥文件密码')->nullable();
            $table->boolean('status')           ->comment('开关状态')->default(false);
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
        Schema::dropIfExists('omnipay_configs');
    }
}
