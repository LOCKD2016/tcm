<?php

namespace App\Events;

use App\Models\Orders;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

/**
 * 订单支付
 * Class OrderPayment
 * @package App\Events
 */
class OrderPayment
{
    use InteractsWithSockets, SerializesModels;

    /**
     * @var Orders
     */
    public $order;

    /**
     * @var
     */
    public $user;

    /**
     * Create a new event instance.
     * @param Orders $order
     * @return void
     */
    public function __construct(Orders $order)
    {
        $this->order = $order;

        $this->user = $order->user;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
