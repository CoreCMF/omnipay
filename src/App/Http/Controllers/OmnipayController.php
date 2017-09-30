<?php

namespace CoreCMF\Omnipay\App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use CoreCMF\Omnipay\App\Models\Order;

class OmnipayController extends Controller
{
    private $orderModel;
    private $request;

    public function __construct(Order $orderPro, Request $request){
       $this->orderModel = $orderPro;
       $this->request = $request;
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

        $orderId = '20170929090814572608';
        $order = $this->orderModel->getOrder($orderId);
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
     * @return function          [description]
     */
    public function callback($gatewayNmae)
    {
        dd($this->completePurchase($gatewayNmae));
    }
    /**
     * [notify 异步通知处理]
     * @param  [type]  $gatewayNmae [description]
     * @return [type]           [description]
     */
    public function notify($gatewayNmae)
    {
        $this->completePurchase($gatewayNmae);
    }
    /**
     * [completePurchase 处理支付返回数据]
     * @param  [type] $gatewayNmae [description]
     * @return [type]              [description]
     */
    protected function completePurchase($gatewayNmae)
    {
        $gateway = resolve('omnipay')->gateway($gatewayNmae);
        $options = [
            'params' => $this->request->all(),
            'request_params' => $this->request->all()
        ];
        $response = $gateway->completePurchase($options)->send();
        if ($response->isPaid()||$response->isSuccessful()) {
            $data = $response->getData();
            switch ($gatewayNmae) {
              case 'alipay':
                $order = [
                    'order_id'  => $data['out_trade_no'],
                    'fee'       => $data['total_amount'],
                    'query_id'  => $data['trade_no'],
                ];
                break;
              case 'wechat':
                $order = [
                    'order_id'  => $data['out_trade_no'],
                    'fee'       => $data['total_amount'],
                    'query_id'  => $data['trade_no'],
                ];
                break;
              case 'unionpay':
                $order = [
                    'order_id'  => $data['orderId'],
                    'fee'       => $data['txnAmt']*0.01,
                    'query_id'  => $data['queryId'],
                ];
                break;
            }
            $order['gateway'] = $gatewayNmae;
            $order['status'] = 'paid';
            return $this->orderModel->paySuccess($order);
        }else{
            return false;
        }
    }
}
