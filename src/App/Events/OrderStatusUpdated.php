<?php
namespace CoreCMF\Omnipay\App\Events;

use Auth;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class OrderStatusUpdated implements ShouldBroadcast
{
    use SerializesModels;

    // protected $orderModel;
    public $order;
    private $user;

    /**
     * 创建一个新的事件实例.
     *
     * @param  Order  $order
     * @return void
     */
    public function __construct($user,$order)
    {
        $this->user = $user;
        $this->order = $order;
    }
    public function broadcastOn()
    {
        return new PrivateChannel('App.User.'.$this->user->id);
    }
}
