<?php

namespace Module\Application\Traits;

use Module\Application\ActivityLog;
use Module\Application\User;

trait HasActivityLogs
{
    public function activityLogs()
    {
        return $this->morphMany(ActivityLog::class, 'loggable');
    }

    // hasactivitiy Log 에서 벤더 카테고리 모델 event를 실행 시킬수 있음

    public function logActivity($type, $title, $text, $detail=null)
    {
        $user = auth()->user();
        if(!$user) $user = User::first();

        return $this->activityLogs()->create([
            'user_id' => $user->id,
            'type_code' => $type,
            'title' => $title,
            'text' => $text,
            'detail' => $detail
        ]);
    }

}
