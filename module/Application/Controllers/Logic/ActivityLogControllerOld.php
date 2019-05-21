<?php

namespace Module\Application\Controllers\Logic;


use Module\Application\ActivityLog;
use Module\Application\Snapshot;

class ActivityLogControllerOld
{


    public function getLogs($loggableType, $loggableId, $pageName='page', $pageNumber=1, $count=8)
    {
        if($loggableId){
            $loggable = $loggableType::find($loggableId);
            $logs = $loggable->activity_logs();
        }
        else {
            $logs = ActivityLog::where('loggable_type', $loggableType);
        }

        $logs = $logs
            ->with('user', 'snapshot')
            ->orderBy('id', 'desc')
            ->paginate($count, null, $pageName, $pageNumber);

        return $logs;
    }


    public function getLog(ActivityLog $activityLog)
    {
        return ActivityLog::with('user')->find($activityLog->id);
    }


    public function getSnapshot(Snapshot $snapshot)
    {
        return json_decode($snapshot->snapshot);
    }


    public function write()
    {

    }




}
