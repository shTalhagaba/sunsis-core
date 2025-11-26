<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class NewUserNotificationToSupportEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $totalActiveUsers;
    public $totalInActiveUsers;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($user, $totalActiveUsers, $totalInActiveUsers)
    {
        $this->user = $user;
        $this->totalActiveUsers = $totalActiveUsers;
        $this->totalInActiveUsers = $totalInActiveUsers;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
