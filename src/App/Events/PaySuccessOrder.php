<?php
namespace CoreCMF\Omnipay\App\Events;

use Illuminate\Queue\SerializesModels;
use CoreCMF\Omnipay\App\Models\Order;

class SuccessOrder
{
    use SerializesModels;

    public $order;

    /**
     * 创建一个新的事件实例.
     *
     * @param  Order  $order
     * @return void
     */
    public function __construct($order)
    {
        $this->order = $order;
    }
}
