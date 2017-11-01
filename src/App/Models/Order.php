<?php

namespace CoreCMF\Omnipay\App\Models;

use Illuminate\Database\Eloquent\Model;
use CoreCMF\Omnipay\App\Events\OrderStatusUpdated;
use CoreCMF\Omnipay\App\Events\PaySuccessOrder;
use CoreCMF\Omnipay\App\Events\CreateOrder;

class Order extends Model
{
    public $table = 'omnipay_orders';

    protected $fillable = ['order_id','uid','name','fee','gateway'];

    protected $appends = ['showStatus','showGateway'];
    /**
     * [getShowStatusAttribute 显示付款状态]
     * @return [type] [description]
     */
    public function getShowStatusAttribute()
    {
        if (array_key_exists('status', $this->attributes)) {
            switch ($this->attributes['status']) {
              case 'unpaid':
                return '未付款';
                break;
              case 'paid':
                return '已付款';
                break;
              case 'refund':
                return '已退款';
                break;
              case 'close':
                return '已关闭';
                break;
            }
        }
    }
    /**
     * [getShowGatewayAttribute 显示支付方式]
     * @return [type] [description]
     */
    public function getShowGatewayAttribute()
    {
        return asset('vendor/omnipay/assets/'.$this->attributes['gateway'].'.png');
    }
    /**
     * [createOrder 创建订单]
     * @return [type] [description]
     */
    public function createOrder($order)
    {
        event(new CreateOrder($order));//通过事件事件 创建支付订单
    }
    /**
     * [getOrder 根据订单id获取订单]
     * @param  [type] $orderId [description]
     * @return [type]          [description]
     */
    public function getOrder($id)
    {
        $order = $this->where('order_id', $id)->first();
        if (!$order) {
            $order = $this->where('id', $id)->first();
        }
        return $order;
    }
    /**
     * [paySuccess 支付成功后处理]
     * @param  [type] $completeOrder [description]
     * @return [type]                [description]
     */
    public function payOrder($completeOrder)
    {
        $update = $this->where('order_id', $completeOrder['order_id'])->where('fee', $completeOrder['fee'])->update($completeOrder);
        if ($update) {
            $order = $this->where('order_id', $completeOrder['order_id'])->where('fee', $completeOrder['fee'])->first();
            event(new PaySuccessOrder($order)); //支付完成事件
            event(new OrderStatusUpdated($order)); //支付完成广播事件
            return true;
        } else {
            return false;
        }
    }
    /**
     * [refund 退款]
     * @return [type] [description]
     */
    public function refund($order)
    {
        $gatewayNmae = $order['gateway'];
        $gateway = resolve('omnipay')->gateway($gatewayNmae);
        switch ($gatewayNmae) {
            case 'alipay':
                $response = $this->alipayRefund($order, $gateway);
                break;
            case 'wechat':
                $response = $this->wechatRefund($order, $gateway);
                break;
            case 'unionpay':
                $response = $this->unionpayRefund($order, $gateway);
                break;
        }
        if ($response->isSuccessful()) {
            $this->where('order_id', $order['order_id'])->update(['status' => 'refund']);
        }
        return $response->isSuccessful();
    }
    /**
     * [alipayRefund 支付宝退款]
     * @param  [type] $order   [description]
     * @param  [type] $gateway [description]
     * @return [type]          [description]
     */
    protected function alipayRefund($order, $gateway)
    {
        if (config('omnipay.debug')) {
            $gateway->sandbox();
        }
        $biz = [
          'out_trade_no' => $order['order_id'],
          'refund_amount' => $order['fee']
        ];
        return $gateway->refund()->setBizContent($biz)->send();
    }
    /**
     * [wechatRefund 微信退款]
     * @param  [type] $order   [description]
     * @param  [type] $gateway [description]
     * @return [type]          [description]
     */
    protected function wechatRefund($order, $gateway)
    {
        // 获取成交金额
        $totalFee = $this->getOrder($order['order_id'])['fee'];
        $biz = [
          'out_trade_no' => $order['order_id'],
          'out_refund_no' => $order['order_id'],
          'total_fee' => $totalFee * 100,
          'refund_fee' => $order['fee'] * 100,
        ];
        return $gateway->refund($biz)->send();
    }
    /**
     * [unionpayRefund 银联退款]
     * @param  [type] $order   [description]
     * @param  [type] $gateway [description]
     * @return [type]          [description]
     */
    protected function unionpayRefund($order, $gateway)
    {
        $biz = [
          'orderId' => 'T'.$order['order_id'],
          'txnTime' => date('YmdHis', strtotime($order['created_at']['date'])),
          'txnAmt' => $order['fee'] * 100,
          'queryId' => $order['query_id'],
        ];
        return $gateway->refund($biz)->send();
    }
}
