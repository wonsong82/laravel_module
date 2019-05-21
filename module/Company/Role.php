<?php
namespace Module\Company;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Module\Application\Traits\HasActivityLogs;
use Module\Application\Traits\HasModelChanges;

class Role extends \Spatie\Permission\Models\Role
{
    use HasModelChanges, HasActivityLogs,
        CrudTrait;


    protected $table = 'roles';
    protected $fillable = [
        'company_id',
        'name',
        'guard_name',
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

            case 'example':
                $query
                    ->leftJoin('users', function($join){
                        $join->on('company_members.user_id', '=', 'users.id');
                    })
                    ->orderBy('users.email', $direction)
                    ->select('company_members.*');
                break;

            default:
                $query
                    ->orderBy($name, $direction);
        }
    }


    public function scopeSearch($query, $name, $term)
    {
        switch($name){
            case 'example':
                $query
                    ->orWhere(function($q) use($term){
                        $q->whereHas('user', function($q) use($term){
                            $q->where('email', 'like', '%'.$term.'%');
                        });
                    });
                break;

            default:
                $query
                    ->orWhere($name, 'like', "%{$term}%");
        }
    }



    // SERIAL CODE IF ANY

    /*
    protected $dispatchesEvents = [
        'creating' => SerializedModelCreating::class,
        'created' => SerializedModelCreated::class
    ];

    public $serialKey = 'code';


    public function assignCode()
    {
        $key = '';
        $length = ;

        $this->fill([
            $this->serialKey => $this->company->generateSerialCode($prefix, $length)
        ])->save();
    }
    */




    // RELATIONS




}