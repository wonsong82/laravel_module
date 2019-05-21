<?php
namespace Module\Company;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Module\Application\Traits\HasActivityLogs;
use Module\Application\Traits\HasConstants;
use Module\Application\Traits\HasModelChanges;
use Module\Application\Traits\HasOrder;
use Module\Company\Constants\PaytermStatus;
use Module\Company\Traits\HasCompany;

class Payterm extends Model
{
    use HasOrder, HasActivityLogs, HasModelChanges, HasConstants, HasCompany,
        CrudTrait;


    protected $table = 'payterms';
    protected $fillable = [
        'company_id',
        'status_code',
        'code',
        'name',
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
        return sprintf('%s (%s)', $this->attributes['name'], $this->attributes['code']);
    }




    // STATIC METHODS




    // METHODS




    // SCOPES

    public function scopeCode($query, $code)
    {
        $query->where('code', $code);
    }


    public function scopeActive($query)
    {
        $query->where('status_code', PaytermStatus::ACTIVE);
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