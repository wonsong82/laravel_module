<?php

namespace  Module\Application\EventListeners;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Module\Application\Constants\ActivityLogType;

class UserEventListener
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


    public function handleUserCreated($event)
    {
        $event->model->logActivity(
            ActivityLogType::INFO,
            __('application::user.log.created.title'),
            __('application::user.log.created.text', ['name' => $event->model->name])
        );
    }


    public function handleUserDeleted($event)
    {
        $event->model->logActivity(
            ActivityLogType::DANGER,
            __('application::user.log.deleted.title'),
            __('application::user.log.deleted.text', ['name' => $event->model->name])
        );
    }



    public function handleUserUpdated($event)
    {
        $userChanges = collect($event->changes['user']->changes)->map(function($change){
            return __('application::user.log.updated.detail', [
                'field' => __("application::user.field.{$change->name}"),
                'from' => $change->from,
                'to' => $change->to
            ]);
        });

        $roleChanges = [];
        foreach($event->changes['roles']->changes as $change){
            switch($change['type']){
                case 'create':
                    $roleChanges[] = __('application::user.log.updated.roles.created', ['name' => $change['model']->name]);
                    break;
                case 'delete':
                    $roleChanges[] = __('application::user.log.updated.roles.deleted', ['name' => $change['model']->name]);
                    break;
            }
        }

        $permissionChanges = [];
        foreach($event->changes['permissions']->changes as $change){
            switch($change['type']){
                case 'create':
                    $permissionChanges[] = __('application::user.log.updated.permissions.created', ['name' => $change['model']->name]);
                    break;
                case 'delete':
                    $permissionChanges[] = __('application::user.log.updated.permissions.deleted', ['name' => $change['model']->name]);
                    break;
            }
        }

        $changes = collect([])
            ->merge($userChanges)
            ->merge($roleChanges)
            ->merge($permissionChanges)
            ->toArray();

        if(count($changes)){
            $event->model->logActivity(
                ActivityLogType::SUCCESS,
                __('application::user.log.updated.title'),
                __('application::user.log.updated.text', ['name' => $event->model->name]),
                implode('<br>', $changes)
            );
        }
    }
}
