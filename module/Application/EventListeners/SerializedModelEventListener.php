<?php

namespace  Module\Application\EventListeners;



class SerializedModelEventListener
{
    public function handle($event)
    {
        $method = 'handle' . class_basename($event);
        if(method_exists($this, $method)){
            $this->$method($event);
        }
    }


    /**
     * Catch before
     * @param $event
     */
    public function handleSerializedModelCreating($event)
    {
        $serialKey = $event->model->serialKey;
        $codeKey = str_replace('_serial', '', $serialKey);

        $tempSerial = '__TEMP__' . uniqid();

        if($event->model->$serialKey == null){
            $event->model->fill([$serialKey => $tempSerial]);
        }

        if($event->model->$codeKey == null){
            $event->model->fill([$codeKey => $tempSerial]);
        }
    }


    public function handleSerializedModelCreated($event)
    {
        $serialKey = $event->model->serialKey;
        $codeKey = str_replace('_serial', '', $serialKey);

        if(substr($event->model->$serialKey, 0, 8) == '__TEMP__'){
            $serialCode = $event->model->assignCode();

            if(substr($event->model->$codeKey, 0, 8) == '__TEMP__'){
                $event->model->fill([
                    $serialKey => $serialCode,
                    $codeKey => $serialCode
                ]);
            }
            else {
                $event->model->fill([
                    $serialKey => $serialCode
                ]);
            }

            $event->model->save();
        }
    }
}
