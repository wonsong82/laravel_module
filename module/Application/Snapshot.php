<?php

namespace Module\Application;

use Illuminate\Database\Eloquent\Model;

class Snapshot extends Model
{
    protected $table = 'snapshots';
    protected $fillable = [
        'content',
        'activity_log_id'
    ];



    // RELATIONS

    public function activityLog()
    {
        return $this->belongsTo(ActivityLog::class);
    }

}
