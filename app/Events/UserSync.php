<?php

namespace App\Events;

use App\Models\AppUser;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

/**
 * 用户同步事件 用户修改个人信息
 * Class UserSync
 * @package App\Events
 */
class UserSync
{
    use InteractsWithSockets, SerializesModels;

    /**
     * 所要同步的用户
     * @var AppUser
     */
    public $user;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(AppUser $user)
    {
        $this->user = $user;
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
