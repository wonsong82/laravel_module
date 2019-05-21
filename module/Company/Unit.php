<?php
namespace Module\Company;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Module\Application\Traits\HasActivityLogs;
use Module\Application\Traits\HasConstants;
use Module\Application\Traits\HasModelChanges;
use Module\Application\Traits\HasOrder;
use Module\Company\Constants\UnitStatus;
use Module\Company\Traits\HasCompany;
use Module\Quotation\QuotationLineItem;
use Module\Quotation\QuotationLineItemInbox;

class Unit extends Model
{
    use HasOrder, HasActivityLogs, HasModelChanges, HasConstants, HasCompany,
        CrudTrait;


    protected $table = 'units';
    protected $fillable = [
        'company_id',
        'status_code',
        'type_code',
        'symbol',
        'name',
        'plural_name',
        'desc',
        'lft',
        'rgt',
        'depth',
        'parent_id'
    ];

    protected $casts = [
    ];



    // ATTRIBUTES

    public function getTextAttribute()
    {
        return sprintf('%s (%s)', $this->attributes['name'], $this->attributes['symbol']);
    }




    // STATIC METHODS




    // METHODS




    // SCOPES

    public function scopeActive($query)
    {
        $query->where('status_code', UnitStatus::ACTIVE);
    }

    public function scopeSymbol($query, $symbol)
    {
        $query->where('code', $symbol);
    }


    public function scopeTypes($query, ...$types)
    {
        $query->where(function($q) use($types){
            foreach($types as $type)
                $q->orWhere('type_code', $type);
        })->select(['id', 'symbol', 'name']);
    }


    public function scopeOrder($query, $name, $direction)
    {
        switch($name){

            case 'default':
                $query
                    ->orderBy('status_code')
                    ->orderBy('lft');
                break;

            case 'status':
                $query
                    ->orderBy('status_code', $direction);
                break;

            case 'type':
                $query
                    ->orderBy('type_code', $direction);
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