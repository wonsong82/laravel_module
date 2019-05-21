<?php

namespace Module\Application;

use Module\Account\User;
use Illuminate\Database\Eloquent\Model;

class Revision extends Model
{
    protected $table = 'revisions';
    protected $fillable = [
        'revisionable_type',
        'revisionable_id',
        'user_id',
        'old_value',
        'new_value',
        'activity_log_id'
    ];



    // RELATIONS

    public function revisionable()
    {
        return $this->morphTo();
    }

    public function fields()
    {
        return $this->hasMany(RevisionField::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function activity_log()
    {
        return $this->belongsTo(ActivityLog::class);
    }
}
