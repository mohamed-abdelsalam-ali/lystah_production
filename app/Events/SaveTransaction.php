<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use app\Http\Controllers\Store\TransactionController;
class SaveTransaction  implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $data;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
        // $this->data = $data;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new channel('SaveTransactionch');
    }

    public function broadcastAs()
    {
        return 'SaveTransaction';
    }
    public function broadcastWith()
    {

// return 'nnnnnnnnnnnn';
        $inbox_data=app('App\Http\Controllers\Store\TransactionController')->inbox_admin();
        return ['data'=>$inbox_data];
    }
}
