<?php

namespace Module\Company;

use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Module\Company\Traits\CurrencyRateCrudTrait;

class CurrencyRate extends Model
{
    use CrudTrait, CurrencyRateCrudTrait;


    protected $table = 'currency_rates';
    protected $fillable = [
        'base_currency_code',
        'quote_currency_code',
        'pair',
        'rate',
        'recorded_at',
        // latest_rate
    ];
    protected $casts = [
        'rate' => 'real',
        'recorded_at' => 'datetime'
    ];


    /**
     * get latest rate of this kind
     */
    public function getLatestRateAttribute()
    {
        return $this->baseCurrency->getRate($this->quoteCurrency)->rate;
    }



    // RELATIONS

    public function baseCurrency()
    {
        return $this->belongsTo(Currency::class, 'base_currency_code');
    }


    public function quoteCurrency()
    {
        return $this->belongsTo(Currency::class, 'quote_currency_code');
    }
}
