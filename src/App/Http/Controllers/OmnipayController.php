<?php

namespace CoreCMF\Omnipay\App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OmnipayController extends Controller
{
    public function pay($gatewayNmae)
    {
        $order = [
            'id' => date('YmdHis') . mt_rand(100000,999999),
            'name' => '测试订单[驱动:'.$gatewayNmae.']',
            'fee' => 16.8,
            'time' => date('YmdHis')
        ];
        $gateway = resolve('omnipay')->gateway($gatewayNmae);
        switch ($gatewayNmae) {
          case 'alipay':
            $response = $this->alipay($gateway,$order);
            break;
          case 'wechat':
            $response = $this->wechat($gateway,$order);
            break;
          case 'unionpay':
            $response = $this->unionpay($gateway,$order);
            break;
        }
        // dd( $response->getData());
        $response->redirect();
    }
    /**
     * [alipay 支付宝购买]
     * @param  [type] $gateway [description]
     * @return [type]          [description]
     */
    protected function alipay($gateway,$order)
    {
        if (config('omnipay.debug')) {
          $gateway->sandbox();
        }
        $order = [
          'out_trade_no' => $order['id'],
          'subject' => $order['name'],
          'total_amount' => $order['fee'],
          'product_code' => 'FAST_INSTANT_TRADE_PAY',
        ];
        return $gateway->purchase()->setBizContent($order)->send();
    }
    /**
     * [wechat 微信支付购买]
     * @param  [type] $gateway [description]
     * @return [type]          [description]
     */
    protected function wechat($gateway,$order)
    {
        $order = [
          'open_id' => 'oEFAEj2KZxrRp2OijMFccnMrfN3Q',
          'out_trade_no'      => $order['id'],
          'body'              => $order['name'],
          'total_fee'         => $order['fee']*100, //=0.01
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
    protected function unionpay($gateway,$order)
    {
        $order = [
            'orderId'   => $order['id'], //Your order ID
            'txnTime'   => $order['time'], //Should be format 'YmdHis'
            'orderDesc' => $order['name'], //Order Title
            'txnAmt'    => $order['fee']*100, //Order Total Fee
        ];
        return $gateway->purchase($order)->send();
    }
    /**
     * [callback 回调处理]
     * @param  [type]   $gatewayNmae [description]
     * @param  Request  $request [description]
     * @return function          [description]
     */
    public function callback($gatewayNmae, Request $request)
    {
        $this->completePurchase($gatewayNmae, $request);
    }
    /**
     * [notify 异步通知处理]
     * @param  [type]  $gatewayNmae [description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function notify($gatewayNmae, Request $request)
    {
        $this->completePurchase($gatewayNmae, $request);
    }
    protected function completePurchase($gatewayNmae, $request)
    {
        $gateway = resolve('omnipay')->gateway($gatewayNmae);
        switch ($gatewayNmae) {
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
