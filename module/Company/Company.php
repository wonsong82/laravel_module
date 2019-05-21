<?php

namespace Module\Company;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Illuminate\Support\Facades\DB;
use Module\Application\Constants\AddressType;
use Module\Application\Events\SerializedModelCreated;
use Module\Application\Events\SerializedModelCreating;
use Module\Application\Locale;
use Module\Application\SerialCode;
use Module\Application\Traits\AddressCrudTrait;
use Module\Application\Traits\HasAddresses;
use Module\Application\Traits\HasConstants;
use Module\Application\Traits\HasPhones;
use Module\Application\Traits\HasActivityLogs;
use Module\Application\Traits\HasModelChanges;
use Module\Company\Traits\Admin\CompanyCrudTrait;
use Module\Quotation\OrderRequest;
use Module\Quotation\Quotation;


/**
 * @property null|object|\Module\Application\Constant timezone
 * @property null|object|\Module\Application\Constant locale
 * @property null|object|\Module\Application\Constant code
 * @property null|object|\Module\Application\Constant billing_address
 * @property null|object|\Module\Application\Constant shipping_address
 * @property null|object|\Module\Application\Constant address
 */
class Company extends Model
{
    use HasConstants, HasAddresses, HasPhones, HasModelChanges, HasActivityLogs,
        CrudTrait, CompanyCrudTrait, AddressCrudTrait;


    protected $table = 'companies';
    protected $fillable = [
        'code',
        'code_serial',
        'status_code',
        //status
        'name',
        'legal_name',
        'desc',
        'phone',
        'fax',
        'email',
        'website',
        'currency_id',
        //currency
        'note',
        'timezone',
        'locale_id',
        //address (physical address)
        //billing_address
        //shipping_address
    ];

    protected $casts = [
    ];



    // ATTRIBUTES




    // STATIC METHODS




    // METHODS

    public function hasCustomization($type)
    {
        return !!DB::table('customizations')->where('company_id', $this->id)->where('type_code', $type)->count();
    }


    /**
     * @return mixed
     */
    public function getCurrencyOptions()
    {
        return $this
            ->currencies()
            ->active()
            ->orderBy('lft')
            ->get()
            ->pluck('text', 'id');
    }

    /**
     * @return mixed
     */
    public function getPaytermOptions()
    {
        return $this
            ->payterms()
            ->active()
            ->orderBy('lft')
            ->get()
            ->pluck('text', 'id');
    }


    /**
     * @param array ...$types
     * @return mixed
     */
    public function getUnits(...$types)
    {
        return $this
            ->units()
            ->types(...$types)
            ->active()
            ->orderBy('lft')
            ->get();
    }

    /**
     * @param array ...$types
     * @return mixed
     */
    public function getUnitOptions(...$types)
    {
        return $this
            ->getUnits(...$types)
            ->pluck('text', 'id');
    }






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

            case 'status':
                $query
                    ->orderBy('status_code', $direction);
                break;

            case 'physical_address_text':
                $query
                    ->leftJoin('company_addresses', function($join){
                        $join->on('company_addresses.company_id', '=', 'companies.id');
                    })
                    ->where('company_addresses.type_code', AddressType::PHYSICAL)
                    ->orderBy('company_addresses.line1', $direction)
                    ->select('companies.*');
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

            case 'physical_address_text':
                $query
                    ->orWhere(function($q) use($term){
                        $q->whereHas('addresses', function($q) use($term){
                            $q->where('type_code', AddressType::PHYSICAL)
                                ->where('line1', 'like', "%{$term}%");
                        });
                    });
                break;

            default:
                $query
                    ->orWhere($name, 'like', "%{$term}%");
        }
    }



    // SERIAL CODE IF ANY

    protected $dispatchesEvents = [
        'creating' => SerializedModelCreating::class,
        'created' => SerializedModelCreated::class
    ];

    public $serialKey = 'code_serial';


    public function assignCode()
    {
        $key = 'COMPANY';
        $prefix = 'C';
        $length = 6;

        return SerialCode::generateCode($key, $prefix, $length)->code;
    }


    /***
     * Use this function to generate serial code within this company
     *
     * @param $prefix
     * @param $length
     * @return object
     */

    public function generateSerialCode($prefix, $length)
    {
        return SerialCode::generateCode('COMPANY_' . $this->code, $prefix, $length);
    }




    // RELATIONS

    public function belongsTos()
    {
        return [
            'locale_id' => [Locale::class, 'language_name'],
            'currency_id' => [Currency::class, 'name']
        ];
    }

    public function locale()
    {
        return $this->belongsTo(Locale::class, 'locale_id');
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }

    public function currencies()
    {
        return $this->hasMany(Currency::class, 'company_id');
    }

    public function payterms()
    {
        return $this->hasMany(Payterm::class, 'company_id');
    }

    public function units()
    {
        return $this->hasMany(Unit::class, 'company_id');
    }

    public function addresses()
    {
        return $this->hasMany(CompanyAddress::class, 'company_id');
    }

    public function users()
    {
        return $this->hasMany(CompanyUser::class, 'company_id');
    }

    public function roles()
    {
        return $this->hasMany('Module\Company\Role', 'company_id');
    }

    public function marginRate()
    {
        return $this->hasOne(MarginRate::class, 'company_id');
    }

    public function quotations()
    {
        return $this->hasMany(Quotation::class, 'company_id');
    }

    public function orderRequests()
    {
        return $this->hasMany(OrderRequest::class, 'company_id');
    }

}
