<?php

namespace Module\Company\Events;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;



class OffDayCreated
{

    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $model;

    public function __construct($model)
    {
        //
        $this->model=$model;
    }

    public function handle($event)
    {
        //
    }
}
