<?php
namespace CoreCMF\Omnipay\Databases\seeds;

use DB;
use Illuminate\Database\Seeder;

class ConfigTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('omnipay_configs')->insert([
          'gateway' => 'alipay',
          'driver' => 'Alipay_AopPage',
          'return_url'=> route('Omnipay.callback',['gatewayNmae' => 'alipay']),
          'notify_url'=> route('Omnipay.notify',['gatewayNmae' => 'alipay']),
        ]);
        DB::table('omnipay_configs')->insert([
          'gateway' => 'wechat',
          'driver' => 'WechatPay',
          'return_url'=> route('Omnipay.callback',['gatewayNmae' => 'wechat']),
          'notify_url'=> route('Omnipay.notify',['gatewayNmae' => 'wechat']),
        ]);
        DB::table('omnipay_configs')->insert([
          'gateway' => 'unionpay',
          'driver' => 'UnionPay_Express',
          'return_url'=> route('Omnipay.callback',['gatewayNmae' => 'unionpay']),
          'notify_url'=> route('Omnipay.notify',['gatewayNmae' => 'unionpay']),
        ]);
    }
}
