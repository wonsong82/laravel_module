<?php

namespace  Module\Company\EventListeners;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Module\Application\Constants\ActivityLogType;

class CompanyUserEventListener
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


    public function handleCompanyUserCreated($event)
    {
        $event->model->logActivity(
            ActivityLogType::INFO,
            __('company::company_user.log.created.title'),
            __('company::company_user.log.created.text', ['name' => $event->model->name])
        );
    }


    public function handleCompanyUserDeleted($event)
    {
        $event->model->logActivity(
            ActivityLogType::DANGER,
            __('company::company_user.log.deleted.title'),
            __('company::company_user.log.deleted.text', ['name' => $event->model->name])
        );
    }



    public function handleCompanyUserUpdated($event)
    {
        $userChanges = collect($event->changes['user']->changes)->map(function($change){
            return __('company::company_user.log.updated.detail', [
                'field' => __("company::company_user.field.{$change->name}"),
                'from' => $change->from,
                'to' => $change->to
            ]);
        });

        $authChanges = collect($event->changes['auth']->changes)->map(function($change){
            return __('company::company_user.log.updated.detail', [
                'field' => __("company::company_user.field.{$change->name}"),
                'from' => $change->from,
                'to' => $change->to
            ]);
        });


        $roleChanges = [];
        foreach($event->changes['roles']->changes as $change){
            switch($change['type']){
                case 'create':
                    $roleChanges[] = __('company::company_user.log.updated.roles.created', ['name' => $change['model']->name]);
                    break;
                case 'delete':
                    $roleChanges[] = __('company::company_user.log.updated.roles.deleted', ['name' => $change['model']->name]);
                    break;
            }
        }

        $permissionChanges = [];
        foreach($event->changes['permissions']->changes as $change){
            switch($change['type']){
                case 'create':
                    $permissionChanges[] = __('company::company_user.log.updated.permissions.created', ['name' => $change['model']->name]);
                    break;
                case 'delete':
                    $permissionChanges[] = __('company::company_user.log.updated.permissions.deleted', ['name' => $change['model']->name]);
                    break;
            }
        }


        $changes = collect([])
            ->merge($userChanges)
            ->merge($authChanges)
            ->merge($roleChanges)
            ->merge($permissionChanges)
            ->toArray();


        if(count($changes)) {
            $event->model->logActivity(
                ActivityLogType::SUCCESS,
                __('company::company_user.log.updated.title'),
                __('company::company_user.log.updated.text', ['name' => $event->model->name]),
                implode('<br>', $changes)
            );
        }
    }
}
