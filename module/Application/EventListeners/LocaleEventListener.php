<?php

namespace  Module\Application\EventListeners;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Module\Application\Constants\ActivityLogType;

class LocaleEventListener
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


    public function handleLocaleCreated($event)
    {
        $event->model->logActivity(
            ActivityLogType::INFO,
            __('application::locale.log.created.title'),
            __('application::locale.log.created.text', ['name' => $event->model->name])
        );
    }


    public function handleLocaleDeleted($event)
    {
        $event->model->logActivity(
            ActivityLogType::DANGER,
            __('application::locale.log.deleted.title'),
            __('application::locale.log.deleted.text', ['name' => $event->model->name])
        );
    }



    public function handleLocaleUpdated($event)
    {
        $localeChanges = collect($event->changes['locale']->changes)->map(function($change){
            return __('application::locale.log.updated.detail', [
                'field' => __("application::locale.field.{$change->name}"),
                'from' => $change->from,
                'to' => $change->to
            ]);
        });

        $changes = collect([])
            ->merge($localeChanges)
            ->toArray();

        if(count($changes)){
            $event->model->logActivity(
                ActivityLogType::SUCCESS,
                __('application::locale.log.updated.title'),
                __('application::locale.log.updated.text', ['name' => $event->model->name]),
                implode('<br>', $changes)
            );
        }
    }
}
