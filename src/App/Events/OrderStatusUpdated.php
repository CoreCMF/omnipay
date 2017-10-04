<?php
namespace CoreCMF\Omnipay\App\Events;

use Auth;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class OrderStatusUpdated implements ShouldBroadcast
{
    use SerializesModels;

    // protected $orderModel;
    public $data;

    /**
     * 创建一个新的事件实例.
     *
     * @param  Order  $order
     * @return void
     */
    public function __construct($order)
    {
        // $this->orderModel = $order;
        $data = ['as'];
    }
    public function broadcastOn()
    {
        return new PrivateChannel('App.User.'.Auth::id());
    }
    public function broadcastWith()
    {
        return ['id' => 1];
    }
}
