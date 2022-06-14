<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
class CommentEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $comment;
    private $id;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($comment, $id)
    {
       $this->comment = $comment;
       $this->id = $id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array 
     */
    public function broadcastOn()
    {
        return new Channel('Comment.'.$this->id);
    }

    // public function broadcastWith()
    // {
    //     return $this->comment;
    // }

}