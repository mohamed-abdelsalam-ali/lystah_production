<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use App\Http\Controllers\POSController;

use Illuminate\Queue\SerializesModels;

class StoreTranaction  implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $datax;

    /**
     * Create a new event instance.
     */
    public function __construct($store_id)
    {
        //

        $this->datax = $store_id;
    }
    public function broadcastOn()
    {
        return new channel('StoreTransactionch');
    }

    public function broadcastAs()
    {

        return 'StoreTranaction';
    }
    public function broadcastWith()
    {
        $store_inbox=app('App\Http\Controllers\POSController')->store_inbox($this->datax);
       // echo($store_inbox[0]);
        return ['data'=>$store_inbox];
    }

}
