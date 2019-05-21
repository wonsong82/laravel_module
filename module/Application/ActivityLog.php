<?php

namespace Module\Application;

use Backpack\CRUD\CrudTrait;
use Module\Application\Traits\HasConstants;
use Illuminate\Database\Eloquent\Model;

/**
 * @property null|object|Constant loggable_type
 */
class ActivityLog extends Model
{
    use HasConstants, CrudTrait;

    protected $table = 'activity_logs';
    protected $fillable = [
        'user_id',
        'type_code',
        //type,
        'loggable_type',
        'loggable_id',
        'title',
        'text',
        'detail'
    ];


    public function getModelNameAttribute()
    {
        return class_basename($this->loggable_type);
    }




    // SCOPES

    public function scopeOrder($query, $name, $direction)
    {
        switch($name){
            case 'default':
                $query
                    ->orderBy('id', 'desc');
                break;


            case 'by':
                $query
                    ->leftJoin('company_members', function($join){
                        $join->on('company_members.user_id', '=', 'activity_logs.user_id');
                    })
                    ->orderBy('company_members.name', $direction)
                    ->select('activity_logs.*');
                break;

            default:
                $query
                    ->orderBy($name, $direction);
        }
    }


    public function scopeSearch($query, $name, $term)
    {
        switch($name){

            default:
                $query
                    ->orWhere($name, 'like', "%{$term}%");
        }
    }




    // RELATIONS

    public function loggable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function revisions()
    {
        return $this->belongsToMany(Revision::class);
    }

    public function snapshots()
    {
        return $this->belongsToMany(Snapshot::class);
    }
}
