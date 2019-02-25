<?php

namespace App\Events;

use App\Models\AppUser;
use App\Models\Clinique;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class SaveUser
{
    use InteractsWithSockets, SerializesModels;

    /**
     * 所要添加的用户
     * @var AppUser
     */
    public $user;

    /**
     * 所要添加到的诊所
     * @var Clinique
     */
    public $clinique;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(AppUser $user, $clinique)
    {
        $this->user = $user;
        $this->clinique = $clinique;
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
