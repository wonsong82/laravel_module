<?php

namespace  Module\Company\EventListeners;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Module\Application\Constants\ActivityLogType;

class UnitEventListener
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
            __('company::unit.log.created.title'),
            __('company::unit.log.created.text', ['name' => $event->model->code])
        );
    }


    public function handleUOMDeleted($event)
    {
        $event->model->logActivity(
            ActivityLogType::DANGER,
            __('company::unit.log.deleted.title'),
            __('company::unit.log.deleted.text', ['name' => $event->model->code])
        );
    }



    public function handleUOMUpdated($event)
    {
        $unitChanges = collect($event->changes['unit']->changes)->map(function($change){
            return __('company::unit.log.updated.detail', [
                'field' => __("company::unit.field.{$change->name}"),
                'from' => $change->from,
                'to' => $change->to
            ]);
        });

        $changes = collect([])
            ->merge($unitChanges)
            ->toArray();

        if(count($changes)){
            $event->model->logActivity(
                ActivityLogType::SUCCESS,
                __('company::unit.log.updated.title'),
                __('company::unit.log.updated.text', ['name' => $event->model->code]),
                implode('<br>', $changes)
            );
        }
    }
}
