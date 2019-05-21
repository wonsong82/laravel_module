<?php

namespace  Module\Application\EventListeners;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Module\Application\Constants\ActivityLogType;

class PermissionEventListener
{
    public function __construct()
    {

    }


    public function handle($event)
    {
        $method = 'handle' . class_basename($event);
        if(method_exists($this, $method)){
            $this->$method($event);
        }
    }


    public function handlePermissionCreated($event)
    {
        $event->model->logActivity(
            ActivityLogType::INFO,
            __('application::permission.log.created.title'),
            __('application::permission.log.created.text', ['name' => $event->model->name])
        );
    }


    public function handlePermissionDeleted($event)
    {
        $event->model->logActivity(
            ActivityLogType::DANGER,
            __('application::permission.log.deleted.title'),
            __('application::permission.log.deleted.text', ['name' => $event->model->name])
        );
    }



    public function handlePermissionUpdated($event)
    {
        $permissionChanges = collect($event->changes['permission']->changes)->map(function($change){
            return __('application::permission.log.updated.detail', [
                'field' => __("application::permission.field.{$change->name}"),
                'from' => $change->from,
                'to' => $change->to
            ]);
        });

        $changes = collect([])
            ->merge($permissionChanges)
            ->toArray();

        if(count($changes)){
            $event->model->logActivity(
                ActivityLogType::SUCCESS,
                __('application::permission.log.updated.title'),
                __('application::permission.log.updated.text', ['name' => $event->model->name]),
                implode('<br>', $changes)
            );
        }
    }
}
