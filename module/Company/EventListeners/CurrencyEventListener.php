<?php

namespace  Module\Company\EventListeners;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Module\Application\Constants\ActivityLogType;

class CurrencyEventListener
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


    public function handleCurrencyCreated($event)
    {
        $event->model->logActivity(
            ActivityLogType::INFO,
            __('company::currency.log.created.title'),
            __('company::currency.log.created.text', ['name' => $event->model->name])
        );
    }


    public function handleCurrencyDeleted($event)
    {
        $event->model->logActivity(
            ActivityLogType::DANGER,
            __('company::currency.log.deleted.title'),
            __('company::currency.log.deleted.text', ['name' => $event->model->name])
        );
    }


    public function handleCurrencyUpdated($event)
    {
        $currencyChanges = collect($event->changes['currency']->changes)->map(function($change){
            return __('company::currency.log.updated.detail', [
                'field' => __("company::currency.field.{$change->name}"),
                'from' => $change->from,
                'to' => $change->to
            ]);
        });

        $changes = collect([])
            ->merge($currencyChanges)
            ->toArray();

        if(count($changes)){
            $event->model->logActivity(
                ActivityLogType::SUCCESS,
                __('company::currency.log.updated.title'),
                __('company::currency.log.updated.text', ['name' => $event->model->name]),
                implode('<br>', $changes)
            );
        }
    }
}
