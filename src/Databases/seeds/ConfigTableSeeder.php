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
          'returnUrl'=> route('Omnipay.callback',['service' => 'alipay']),
          'notifyUrl'=> route('Omnipay.notify',['service' => 'alipay']),
        ]);
        DB::table('omnipay_configs')->insert([
          'gateway' => 'wechat',
          'driver' => 'WechatPay',
          'returnUrl'=> route('Omnipay.callback',['service' => 'wechat']),
          'notifyUrl'=> route('Omnipay.notify',['service' => 'wechat']),
        ]);
        DB::table('omnipay_configs')->insert([
          'gateway' => 'unionpay',
          'driver' => 'UnionPay_Express',
          'returnUrl'=> route('Omnipay.callback',['service' => 'unionpay']),
          'notifyUrl'=> route('Omnipay.notify',['service' => 'unionpay']),
        ]);
    }
}
