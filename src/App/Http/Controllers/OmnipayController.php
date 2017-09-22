<?php

namespace CoreCMF\Omnipay\App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OmnipayController extends Controller
{
    public function pay($service)
    {
        $gateway = resolve('omnipay')->gateway($service);
        switch ($service) {
          case 'alipay':
            $response = $this->alipay($gateway);
            break;
          case 'wechat':
            $response = $this->wechat($gateway);
            break;
          case 'unionpay':
            $response = $this->unionpay($gateway);
            break;
        }
        dd( $response->getData());
        $response->redirect();
    }
    /**
     * [alipay 支付宝购买]
     * @param  [type] $gateway [description]
     * @return [type]          [description]
     */
    protected function alipay($gateway)
    {
        if (config('omnipay.debug')) {
          $gateway->sandbox();
        }
        $order = [
          'out_trade_no' => date('YmdHis') . mt_rand(1000,9999),
          'subject' => 'Alipay Test',
          'total_amount' => '0.01',
          'product_code' => 'FAST_INSTANT_TRADE_PAY',
        ];
        return $gateway->purchase()->setBizContent($order)->send();
    }
    /**
     * [wechat 微信支付购买]
     * @param  [type] $gateway [description]
     * @return [type]          [description]
     */
    protected function wechat($gateway)
    {
        $order = [
          'body'              => 'The test order',
          'out_trade_no'      => date('YmdHis').mt_rand(1000, 9999),
          'total_fee'         => 1, //=0.01
          'spbill_create_ip'  => '127.0.0.1',
          'fee_type'          => 'CNY'
        ];
        return $gateway->purchase($order)->send();
    }
    /**
     * [unionpay 银联支付购买]
     * @param  [type] $gateway [description]
     * @return [type]          [description]
     */
    protected function unionpay($gateway)
    {
        $order = [
            'orderId'   => date('YmdHis'), //Your order ID
            'txnTime'   => date('YmdHis'), //Should be format 'YmdHis'
            'orderDesc' => 'My order title', //Order Title
            'txnAmt'    => '100', //Order Total Fee
        ];
        return $gateway->purchase($order)->send();
    }

    public function callback($service, Request $request)
    {
        $gateway = resolve('omnipay')->gateway($service);
        switch ($service) {
          case 'alipay':
            $options = [
                'params' => $request->all()
            ];
            break;
          default:
            $options = [
                'request_params' => $request->all()
            ];
            break;
        }
        $response = $gateway->completePurchase($options)->send();
        dd($response);
        $response =get_class_methods($response);
        dd($response);
    }
}
