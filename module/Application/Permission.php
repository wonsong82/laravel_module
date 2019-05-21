<?php
namespace Module\Application;

use Backpack\CRUD\CrudTrait;
use Module\Application\Traits\HasActivityLogs;
use Module\Application\Traits\HasModelChanges;

class Permission extends \Spatie\Permission\Models\Permission
{
    use HasModelChanges, HasActivityLogs,
        CrudTrait;


    protected $table = 'permissions';
    protected $fillable = [
        'name',
        'guard_name'
    ];

    protected $casts = [
    ];



    // ATTRIBUTES




    // STATIC METHODS




    // METHODS




    // SCOPES

    public function scopeOrder($query, $name, $direction)
    {
        switch($name){
            case 'default':
                $query
                    ->orderBy('id');
                break;

            default:
                $query
                    ->orderBy($name, $direction);
        }
    }


    public function scopeSearch($query, $name, $term)
    {
        switch($name){
            case 'name':
                $query
                    ->orWhere('name', 'like', "%$term%");
                break;

            default:
                $query
                    ->orWhere($name, 'like', "%{$term}%");
        }
    }




    // RELATIONS




}