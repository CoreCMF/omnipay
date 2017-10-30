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
            $table->string('gateway', 30)         ->comment('网关');
            $table->string('driver', 30)          ->comment('驱动');
            $table->string('app_id', 60)          ->comment('AppId')->nullable();
            $table->string('seller_id', 80)       ->comment('商家ID')->nullable();
            $table->string('return_url', 160)     ->comment('回调URl')->nullable();
            $table->string('notify_url', 160)     ->comment('通知URl')->nullable();
            $table->string('private_key', 80)     ->comment('密钥文件路径')->nullable();
            $table->string('public_key', 80)      ->comment('公钥文件路径')->nullable();
            $table->string('other', 160)          ->comment('其他')->nullable();
            $table->boolean('status')            ->comment('开关状态')->default(false);
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
