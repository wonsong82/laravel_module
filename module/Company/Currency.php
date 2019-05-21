<?php

namespace Module\Company;

use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Module\Application\Traits\HasConstants;
use Module\Application\Traits\HasOrder;
use Module\Company\Constants\CurrencyStatus;
use Module\Company\Constants\CurrencySymbolPosition;
use Module\Company\Traits\Admin\CurrencyCrudTrait;
use Module\Application\Traits\HasActivityLogs;
use Module\Application\Traits\HasModelChanges;
use Module\Company\Traits\HasCompany;

/**
 * @property mixed id
 * @property mixed decimal_count
 * @property mixed decimal_point
 * @property mixed thousand_separator
 * @property mixed symbol_position
 * @property mixed symbol
 * @property null|object|\Module\Application\Constant symbol_position_code
 */
class Currency extends Model
{
    use HasOrder, HasActivityLogs, HasModelChanges, HasConstants, HasCompany,
        CrudTrait;


    protected $table = 'currencies';
    protected $fillable = [
        'company_id',
        'status_code',
        'code',
        'name',
        'code_n',
        'symbol',
        'symbol_position_code',
        'decimal_count',
        'decimal_separator',
        'thousand_separator',
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
        return sprintf('%s (%s)', $this->attributes['code'], $this->attributes['symbol']);
    }




    // STATIC METHODS




    // METHODS




    /***
     * Using this currency is Base Currency, get the latest rate for Quote Currency.
     *
     * @param $quoteCurrency
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function getRate(Currency $quoteCurrency)
    {
        // EUR/USD means EUR is base currency and USD is quote currency.
        // So if price is 1000 EUR, and EUR/USD rate is 1.5, price in USD is 1500.
        return $this->rates()
            ->with('base_currency', 'quote_currency')
            ->where('quote_currency_id', $quoteCurrency->id)
            ->orderBy('id', 'desc')
            ->first();
    }



    /***
     * With given amount, return the formatted currency string
     *
     * @param $amount
     * @return string
     */
    public function format($amount)
    {
        $amount = number_format($amount, $this->decimal_count, $this->decimal_point, $this->thousand_separator);

        switch($this->symbol_position_code){
            case CurrencySymbolPosition::BEFORE_WITH_SPACE:
                $amount = $this->symbol . ' ' . $amount;
                break;

            case CurrencySymbolPosition::BEFORE_WITHOUT_SPACE:
                $amount = $this->symbol . $amount;
                break;

            case CurrencySymbolPosition::AFTER_WITH_SPACE:
                $amount = $amount . ' ' . $this->symbol;
                break;

            case CurrencySymbolPosition::AFTER_WITHOUT_SPACE:
                $amount = $amount . $this->symbol;
                break;

            default:
                $amount = $this->symbol . $amount;
        }

        return $amount;
    }




    // SCOPES

    public function scopeCode($query, $code)
    {
        $query->where('code', $code);
    }

    public function scopeActive($query)
    {
        $query->where('status_code', CurrencyStatus::ACTIVE);
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

    public function rates()
    {
        return $this->hasMany(CurrencyRate::class, 'base_currency_id');
    }

}
