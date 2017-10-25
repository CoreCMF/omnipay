<?php

namespace CoreCMF\Omnipay\App\Models;

use Illuminate\Database\Eloquent\Model;
use CoreCMF\Omnipay\App\Events\OrderStatusUpdated;

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
        if (array_key_exists('status',$this->attributes)) {
          return ($this->attributes['status'] == 'unpaid')? '已付款': '代付款';
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
            event(new OrderStatusUpdated($order)); //支付完成事件
            return true;
        }else{
            return false;
        }
    }
}
