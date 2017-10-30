<?php
namespace CoreCMF\Omnipay\App\Events;

use Illuminate\Queue\SerializesModels;
use CoreCMF\Omnipay\App\Models\Order;

class CreateOrder
{
    use SerializesModels;

    public $order;
    public $response;

    /**
     * 创建一个新的事件实例.
     *
     * @param  Order  $order
     * @return void
     */
    public function __construct($order)
    {
        $this->order = $order;
        $this->createOrder($order);
    }
    /**
     * [createOrder 创建订单]
     * @param  [type] $order [description]
     * @return [type]        [description]
     */
    public function createOrder($order)
    {
        $orderModel = new Order();
        $this->response = $orderModel->create($order);//订单写入数据库
        if ($this->response) {
            session(['OmnipayOrderId' => $this->order['order_id']]);//记录
        }
    }
}
