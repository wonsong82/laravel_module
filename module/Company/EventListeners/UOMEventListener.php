<?php

namespace  Module\Company\EventListeners;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Module\Application\Constants\ActivityLogType;

class UOMEventListener
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


    public function handleUOMCreated($event)
    {
        $event->model->logActivity(
            ActivityLogType::INFO,
            __('company::uom.log.created.title'),
            __('company::uom.log.created.text', ['name' => $event->model->code])
        );
    }


    public function handleUOMDeleted($event)
    {
        $event->model->logActivity(
            ActivityLogType::DANGER,
            __('company::uom.log.deleted.title'),
            __('company::uom.log.deleted.text', ['name' => $event->model->code])
        );
    }



    public function handleUOMUpdated($event)
    {
        $uomChanges = collect($event->changes['uom']->changes)->map(function($change){
            return __('company::uom.log.updated.detail', [
                'field' => __("company::uom.field.{$change->name}"),
                'from' => $change->from,
                'to' => $change->to
            ]);
        });

        $changes = collect([])
            ->merge($uomChanges)
            ->toArray();

        if(count($changes)){
            $event->model->logActivity(
                ActivityLogType::SUCCESS,
                __('company::uom.log.updated.title'),
                __('company::uom.log.updated.text', ['name' => $event->model->code]),
                implode('<br>', $changes)
            );
        }
    }
}
