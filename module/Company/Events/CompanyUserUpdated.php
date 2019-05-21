<?php

namespace Module\Company\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;



class CompanyUserUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $model;
    public $changes;



    public function __construct($model, $changes)
    {
        $this->model = $model;
        $this->changes = $changes;
    }



    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
