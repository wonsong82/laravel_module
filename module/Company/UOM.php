<?php

namespace Module\Company;

use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Module\Application\Traits\HasOrder;
use Module\Application\Traits\HasActivityLogs;
use Module\Application\Traits\HasModelChanges;
use Module\Company\Traits\HasCompany;


class UOM extends Model
{
    use HasOrder, HasActivityLogs, HasModelChanges, HasCompany,
        CrudTrait;


    protected $table = 'uoms';
    protected $fillable = [
        'company_id',
        'code',
        'isc', // international standard code
        'desc',
        'lft',
        'rgt',
        'depth',
        'parent_id'
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
                    ->orderBy('lft');
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
