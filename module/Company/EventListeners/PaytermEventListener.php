<?php

namespace Module\Company\EventListeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Module\Application\Constants\ActivityLogType;

class PaytermEventListener
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



    public function handlePaytermCreated($event)
    {
        $event->model->logActivity(
            ActivityLogType::INFO,
            __('company::payterm.log.created.title'),
            __('company::payterm.log.created.text', ['name' => $event->model->name])
        );
    }


    public function handlePaytermDeleted($event)
    {
        $event->model->logActivity(
            ActivityLogType::DANGER,
            __('company::payterm.log.deleted.title'),
            __('company::payterm.log.deleted.text', ['name' => $event->model->name])
        );
    }


    public function handlePaytermUpdated($event)
    {
        $customerChanges = collect($event->changes['payterm']->changes)->map(function($change){
            return __('company::payterm.log.updated.detail', [
                'field' => __("company::payterm.field.{$change->name}"),
                'from' => $change->from,
                'to' => $change->to
            ]);
        });

        $changes = collect([])
            ->merge($customerChanges)
            ->toArray();

        if(count($changes)){
            $event->model->logActivity(
                ActivityLogType::SUCCESS,
                __('company::payterm.log.updated.title'),
                __('company::payterm.log.updated.text', ['name' => $event->model->name]),
                implode('<br>', $changes)
            );
        }
    }








}
