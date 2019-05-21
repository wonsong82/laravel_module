<?php

namespace  Module\Company\EventListeners;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Module\Application\Constants\ActivityLogType;

class CompanyEventListener
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



    public function handleCompanyCreated($event)
    {
        $event->model->logActivity(
            ActivityLogType::INFO,
            __('company::company.log.created.title'),
            __('company::company.log.created.text', ['name' => $event->model->name])
        );
    }


    public function handleCompanyDeleted($event)
    {
        $event->model->logActivity(
            ActivityLogType::DANGER,
            __('company::company.log.deleted.title'),
            __('company::company.log.deleted.text', ['name' => $event->model->name])
        );
    }


    public function handleCompanyUpdated($event)
    {
        $companyChanges = collect($event->changes['company']->changes)->map(function($change){
            return __('company::company.log.updated.detail', [
                'field' => __("company::company.field.{$change->name}"),
                'from' => $change->from,
                'to' => $change->to
            ]);
        });

        $physical = collect($event->changes['physical']->changes)->map(function($change){
            return
                __('company::company.field.physical') . ' ' .
                __('company::company.log.updated.detail', [
                    'field' => __("application::address.{$change->name}"),
                    'from' => $change->from,
                    'to' => $change->to
            ]);
        });

        $billing = collect($event->changes['billing']->changes)->map(function($change){
            return
                __('company::company.field.billing') . ' ' .
                __('company::company.log.updated.detail', [
                    'field' => __("application::address.{$change->name}"),
                    'from' => $change->from,
                    'to' => $change->to
                ]);
        });

        $shipping = collect($event->changes['shipping']->changes)->map(function($change){
            return
                __('company::company.field.shipping') . ' ' .
                __('company::company.log.updated.detail', [
                    'field' => __("application::address.{$change->name}"),
                    'from' => $change->from,
                    'to' => $change->to
                ]);
        });

        $changes = collect([])
            ->merge($companyChanges)
            ->merge($physical)
            ->merge($billing)
            ->merge($shipping)
            ->toArray();


        if(count($changes)){
            $event->model->logActivity(
                ActivityLogType::SUCCESS,
                __('company::company.log.updated.title'),
                __('company::company.log.updated.text', ['name' => $event->model->name]),
                implode('<br>', $changes)
            );
        }

    }
}
