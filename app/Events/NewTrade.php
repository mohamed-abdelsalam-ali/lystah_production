<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewTrade implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $trade;
    /**
     * Create a new event instance.
     *
     * @return void
     */



    public function __construct($trade)
    {
        //
       $this->trade = $trade;
    //    $this->broadcastVia('pusher');
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new channel('Mynotification');
        // return new Channel('trades');
    }
    public function broadcastAs()
    {
        return 'test_event';
    }
    public function broadcastWith()
    {
        return ['message' =>  $this->trade];
    }
}
