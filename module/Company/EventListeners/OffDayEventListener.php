<?php

namespace Module\Company\EventListeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Module\Application\ActivityLog;
use Module\Application\Constants\ActivityLogType;

class OffDayEventListener
{

    public function __construct()
    {

    }


    public function handle($event)
    {

        $method = 'handle'.class_basename($event);
        if(method_exists($this, $method)){
            $this->$method($event);
        }
    }



    public function handleOffDayCreated($event)
    {
        $event->model->logActivity(
            ActivityLogType::INFO,
            'OffDay added.',
            sprintf(
                'New OffDay "%s" has been added.',
                date('Y-m-d',strtotime($event->model->date))
            )
        );
    }

    public function handleOffDayDeleted($event)
    {
        $event->model->logActivity(
            ActivityLogType::INFO,
            'OffDay deleted.',
            sprintf(
                'OffDay "%s" has been deleted.',
                date('Y-m-d',strtotime($event->model->date))
            )
        );
    }

}
