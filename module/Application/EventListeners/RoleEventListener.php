<?php

namespace  Module\Application\EventListeners;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Module\Application\Constants\ActivityLogType;

class RoleEventListener
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


    public function handleRoleCreated($event)
    {
        $event->model->logActivity(
            ActivityLogType::INFO,
            __('application::role.log.created.title'),
            __('application::role.log.created.text', ['name' => $event->model->name])
        );
    }


    public function handleRoleDeleted($event)
    {
        $event->model->logActivity(
            ActivityLogType::DANGER,
            __('application::role.log.deleted.title'),
            __('application::role.log.deleted.text', ['name' => $event->model->name])
        );
    }



    public function handleRoleUpdated($event)
    {
        $roleChanges = collect($event->changes['role']->changes)->map(function($change){
            return __('application::role.log.updated.detail', [
                'field' => __("application::role.field.{$change->name}"),
                'from' => $change->from,
                'to' => $change->to
            ]);
        });


        $permissionChanges = [];
        foreach($event->changes['permissions']->changes as $change){
            switch($change['type']){
                case 'create':
                    $permissionChanges[] = __('application::role.log.updated.permission.created', ['name' => $change['model']->name]);
                    break;
                case 'delete':
                    $permissionChanges[] = __('application::role.log.updated.permission.deleted', ['name' => $change['model']->name]);
                    break;
            }
        }


        $changes = collect([])
            ->merge($roleChanges)
            ->merge($permissionChanges)
            ->toArray();

        if(count($changes)){
            $event->model->logActivity(
                ActivityLogType::SUCCESS,
                __('application::role.log.updated.title'),
                __('application::role.log.updated.text', ['name' => $event->model->name]),
                implode('<br>', $changes)
            );
        }
    }
}
