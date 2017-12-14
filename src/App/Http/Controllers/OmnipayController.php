<?php

namespace CoreCMF\Omnipay\App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use CoreCMF\Omnipay\App\Models\Order;
use CoreCMF\Socialite\App\Models\User as SocialiteUser;

class OmnipayController extends Controller
{
    private $orderModel;
    private $request;
    private $order;
    private $socialiteUserModel;

    public function __construct(Order $orderPro, SocialiteUser $socialiteUserPro, Request $request)
    {
        $this->orderModel = $orderPro;
        $this->socialiteUserModel = $socialiteUserPro;
        $this->request = $request;
    }
    public function pay($gatewayNmae)
    {
        $createOrder = [
            'order_id'      => date('YmdHis') . mt_rand(100000, 999999),
            'uid'     => Auth::id(),
            'name'    => '测试订单[驱动:'.$gatewayNmae.']',
            'fee'     => 0.01,
            'gateway' => $gatewayNmae
        ];
        $orderId = $this->orderModel->createOrder($createOrder)->order_id;//创建支付订单
        // $orderId = $this->request->orderId;
        $order = $this->orderModel->getOrder($orderId);
        if (!$order) {
            return '没有找到订单';
        } elseif ($order->status == 'paid') {
            return '订单已付款';
        }
        $gateway = resolve('omnipay')->gateway($gatewayNmae);
        switch ($gatewayNmae) {
          case 'alipay':
            $response = $this->alipay($gateway, $order);
            break;
          case 'wechat':
            $response = $this->wechat($gateway, $order);
            break;
          case 'unionpay':
            $response = $this->unionpay($gateway, $order);
            break;
        }
        return $response;
    }
    /**
     * [alipay 支付宝购买]
     * @param  [type] $gateway [description]
     * @return [type]          [description]
     */
    protected function alipay($gateway, $order)
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
    protected function wechat($gateway, $order)
    {
        $openId = $this->socialiteUserModel->getSocialiteId(Auth::id(), 'wechat');
        $wechatOrder = [
          'open_id'           => $openId,
          'out_trade_no'      => $order->order_id,
          'body'              => $order->name,
          'total_fee'         => $order->fee*100, //=0.01
          'spbill_create_ip'  => '127.0.0.1',
          'fee_type'          => 'CNY',
        ];
        $response = $gateway->purchase($wechatOrder)->send();
        $wechat = [
          'appOrder' => $response->getAppOrderData(),
          'jsOrder'  => $response->getJsOrderData(),
          'webOrder' => $response->getCodeUrl(),
        ];

        $builderAsset = resolve('builderAsset');
        $builderAsset->config('wechat', $wechat);
        $builderAsset->config('order', $order);
        view()->share('resources', $builderAsset->response());//视图共享数据
        return view('core::index', [ 'model' => 'omnipay']);
    }
    /**
     * [unionpay 银联支付购买]
     * @param  [type] $gateway [description]
     * @return [type]          [description]
     */
    protected function unionpay($gateway, $order)
    {
        $order = [
            'orderId'   => $order->order_id, //Your order ID
            'txnTime'   => date('YmdHis', $order->created_at->getTimestamp()), //Should be format 'YmdHis'
            'orderDesc' => $order->name, //Order Title
            'txnAmt'    => $order->fee * 100, //Order Total Fee
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
        $this->completePurchase($gatewayNmae);
        $order = $this->orderModel->getOrder($this->order['order_id']);
        $builderAsset = resolve('builderAsset');
        $builderAsset->config('order', $order);
        view()->share('resources', $builderAsset->response());//视图共享数据
        return view('core::index', [ 'model' => 'omnipay']);
    }
    /**
     * [notify 异步通知处理]
     * @param  [type]  $gatewayNmae [description]
     * @return [type]           [description]
     */
    public function notify($gatewayNmae)
    {
        if ($this->completePurchase($gatewayNmae)) {
            return 'success';
        } else {
            return 'fail';
        }
    }
    /**
     * [completePurchase 处理支付返回数据]
     * @param  [type] $gatewayNmae [description]
     * @return [type]              [description]
     */
    protected function completePurchase($gatewayNmae)
    {
        $gateway = resolve('omnipay')->gateway($gatewayNmae);

        if ($gatewayNmae == 'wechat') {
            $options = [
              'request_params' => file_get_contents('php://input')
          ];
        } else {
            $options = [
              'params' => $this->request->all(),
              'request_params' => $this->request->all()
          ];
        }
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
                $data = $response->getRequestData();
                $order = [
                    'order_id'  => $data['out_trade_no'],
                    'fee'       => $data['total_fee']*0.01,
                    'query_id'  => $data['transaction_id'],
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
            $order['status'] = 'paid';
            $this->setOrder($order);
            return $this->orderModel->payOrder($order);
        } else {
            return false;
        }
    }
    /**
     * [setOrder 设置当前订单]
     * @param [type] $order [description]
     */
    protected function setOrder($order)
    {
        $this->order = $order;
    }
}
