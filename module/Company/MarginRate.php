<?php
namespace Module\Company;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Module\Application\Traits\HasActivityLogs;
use Module\Application\Traits\HasModelChanges;
use Module\Company\Traits\HasCompany;

class MarginRate extends Model
{
    use HasActivityLogs, HasModelChanges, HasCompany,
        CrudTrait;


    protected $table = 'margin_rates';
    protected $fillable = [
        'company_id',
        'rates' // json array
    ];

    protected $casts = [
    ];



    // ATTRIBUTES
    public function getRatesAttribute()
    {
        return json_decode($this->attributes['rates']);
    }

    public function setRatesAttribute($rates)
    {
        $this->attributes['rates'] = json_encode($rates);
    }



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

//            case 'example':
//                $query
//                    ->leftJoin('users', function($join){
//                        $join->on('company_members.user_id', '=', 'users.id');
//                    })
//                    ->orderBy('users.email', $direction)
//                    ->select('company_members.*');
//                break;

            default:
                $query
                    ->orderBy($name, $direction);
        }
    }


    public function scopeSearch($query, $name, $term)
    {
        switch($name){

//            case 'example':
//                $query
//                    ->orWhere(function($q) use($term){
//                        $q->whereHas('user', function($q) use($term){
//                            $q->where('email', 'like', "%{$term}%");
//                        });
//                    });
//                break;

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

    public $serialKey = 'code_serial';


    public function assignCode()
    {
        $prefix = '';
        $length = 0;

        return $this->company->generateSerialCode($prefix, $length)->code;
    }
    */




    // RELATIONS

//    public function belongsTos()
//    {
//        return [
//            '_id' => [modelName:class, 'fieldName']
//        ];
//    }




}