<?php

namespace  Module\Company\EventListeners;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Module\Application\Constants\ActivityLogType;

class MarginRateEventListener
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


    public function handleMarginRateUpdated($event)
    {
        $marginRateChanges = collect($event->changes['rates']->changes)->map(function($change){
            if($change->name == 'rates'){
                $from = collect(json_decode($change->from))->map(function($e){
                    return $e->rate . '%';
                });
                $to = collect(Json_decode($change->to))->map(function($e){
                    return $e->rate . '%';
                });

                $change->from = implode(', ', $from->toArray());
                $change->to = implode(', ', $to->toArray());

            }

            return __('company::margin_rate.log.updated.detail', [
                'field' => __("company::margin_rate.field.{$change->name}"),
                'from' => $change->from,
                'to' => $change->to
            ]);
        });

        $changes = collect([])
            ->merge($marginRateChanges)
            ->toArray();

        if(count($changes)){
            $event->model->logActivity(
                ActivityLogType::SUCCESS,
                __('company::margin_rate.log.updated.title'),
                __('company::margin_rate.log.updated.text'),
                implode('<br>', $changes)
            );
        }
    }
}
