<?php

namespace Module\Application\Controllers\Logic;


use Module\Application\ActivityLog;




class ActivityLogController
{
    public function write($type, $title, $text, $detail=null)
    {
        $user = auth()->user();

        return ActivityLog::create([
            'user_id' => $user->id,
            'type_code' => $type,
            'title' => $title,
            'text' => $text,
            'detail' => $detail
        ]);
    }



    public function getLogs($model, $id=null, $limit=10)
    {
        $query = ActivityLog::query();

        $user = auth()->user();
        if($user && $user->company){
            $query->whereHas('user', function($q) use($user){
                $q->whereHas('companyUser', function($q) use($user){
                    $q->where('company_id', $user->company->id);
                });
            });
        }

        $query->where('loggable_type', $model);


        if($id){
            $query->where('loggable_id', $id);
        }

        $query->orderBy('id', 'desc');

        if($limit){
            $query->limit($limit);
        }

        $logs = $query->get();

        $logs->model = $model;
        $logs->name = class_basename($model);
        $logs->id = $id;

        return $logs;
    }







}
