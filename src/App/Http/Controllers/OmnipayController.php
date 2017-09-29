<?php

namespace CoreCMF\Omnipay\App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use CoreCMF\Omnipay\App\Models\Order;

class OmnipayController extends Controller
{
    private $orderModel;

    public function __construct(Order $orderPro){
       $this->orderModel = $orderPro;
    }
    public function pay($gatewayNmae)
    {
        // $createOrder = [
        //     'order_id'      => '20170929090814572607',
        //     'uid'     => Auth::id(),
        //     'name'    => '测试订单[驱动:'.$gatewayNmae.']',
        //     'fee'     => 16.8,
        //     'gateway' => $gatewayNmae
        // ];
        // $order = $this->orderModel->create($createOrder);//订单写入数据库

        $order_id = '20170929090814572607';
        $order = $this->orderModel->where('order_id', $order_id)->first();
        $gateway = resolve('omnipay')->gateway($gatewayNmae);
        switch ($gatewayNmae) {
          case 'alipay':
            $this->alipay($gateway,$order);
            break;
          case 'wechat':
            $this->wechat($gateway,$order);
            break;
          case 'unionpay':
            $this->unionpay($gateway,$order);
            break;
        }
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
          'out_trade_no' => $order->order_id,
          'subject' => $order->name,
          'total_amount' => $order->fee,
          'product_code' => 'FAST_INSTANT_TRADE_PAY',
        ];
        $response = $gateway->purchase()->setBizContent($order)->send();
        $response->redirect();
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
          'out_trade_no'      => $order->order_id,
          'body'              => $order->name,
          'total_fee'         => $order->fee*100, //=0.01
          'spbill_create_ip'  => '127.0.0.1',
          'fee_type'          => 'CNY',
        ];
        $response = $gateway->purchase($order)->send();
        dd( $response->getData());
    }
    /**
     * [unionpay 银联支付购买]
     * @param  [type] $gateway [description]
     * @return [type]          [description]
     */
    protected function unionpay($gateway,$order)
    {
        $order = [
            'orderId'   => $order->order_id, //Your order ID
            'txnTime'   => date('YmdHis',strtotime($order->created_at)), //Should be format 'YmdHis'
            'orderDesc' => $order->name, //Order Title
            'txnAmt'    => $order->fee*100, //Order Total Fee
        ];
        $response = $gateway->purchase($order)->send();
        $response->redirect();
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
