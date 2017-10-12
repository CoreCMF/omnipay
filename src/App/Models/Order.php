<?php

namespace CoreCMF\Omnipay\App\Models;

use Auth;
use Schema;
use Illuminate\Database\Eloquent\Model;
use CoreCMF\Omnipay\App\Events\OrderStatusUpdated;

class Order extends Model
{
    public $table = 'omnipay_orders';

    protected $fillable = ['order_id','uid','name','fee','gateway'];

    public $order;

    /**
     * [getOrder 根据订单id获取订单]
     * @param  [type] $orderId [description]
     * @return [type]          [description]
     */
    public function getOrder($orderId)
    {
        return $this->where('order_id', $orderId)->first();
    }
    /**
     * [paySuccess 支付成功后处理]
     * @param  [type] $completeOrder [description]
     * @return [type]                [description]
     */
    public function paySuccess($completeOrder)
    {
        $update = $this->where('order_id', $completeOrder['order_id'])->where('fee', $completeOrder['fee'])->update($completeOrder);
        if ($update) {
            $order = $this->where('order_id', $completeOrder['order_id'])->where('fee', $completeOrder['fee'])->first();
            $this->setOrder($order);
            event(new OrderStatusUpdated(Auth::user(),$this->order)); //支付完成事件
            return true;
        }else{
            return false;
        }
    }

    public function setOrder($order)
    {
        $this->order = $order;
        return $this;
    }
}
