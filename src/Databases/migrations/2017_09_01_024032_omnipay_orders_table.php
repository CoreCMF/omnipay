<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class OmnipayOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('omnipay_orders', function (Blueprint $table) {
            $table->string('id')                 ->comment('订单编号')->unique();
            $table->bigInteger('uid')            ->comment('用户ID')->default(0)->unsigned();
            $table->string('name',275)           ->comment('订单名称');
            $table->decimal('fee',11, 2)         ->comment('订单金额')->unsigned()->default(0);
            $table->string('gateway',30)         ->comment('支付网关');
            $table->string('query_id')           ->comment('网关订单编号')->nullable();
            $table->string('status',10)          ->comment('订单状态')->default('unpaid');
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
        Schema::dropIfExists('omnipay_orders');
    }
}
