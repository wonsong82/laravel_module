<?php
namespace Module\Application;

use Backpack\CRUD\CrudTrait;
use Module\Application\Traits\HasActivityLogs;
use Module\Application\Traits\HasModelChanges;

/**
 * @property mixed permissions
 */
class Role extends \Spatie\Permission\Models\Role
{
    use HasModelChanges, HasActivityLogs,
        CrudTrait;


    protected $table = 'roles';
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

            default:
                $query
                    ->orWhere($name, 'like', "%{$term}%");
        }
    }






    // RELATIONS




}