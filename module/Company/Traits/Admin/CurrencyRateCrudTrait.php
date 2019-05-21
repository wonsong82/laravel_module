<?php
namespace Module\Company\Traits;


trait CurrencyRateCrudTrait {

    public function getBaseCurrencyCodeAttribute()
    {
        return $this->baseCurrency->code;
    }

    public function getQuoteCurrencyCodeAttribute()
    {
        return $this->quoteCurrency->code;
    }


    public function btnViewRateHistory($crud = false)
    {
        $route = route('business::crud.currency-rate.index');
        $query = http_build_query([
            'base_currency' => $crud->baseCurrency->code,
            'quote_currency' => $crud->quoteCurrency->code
        ]);

        return '<a class="btn btn-xs btn-default" target="_blank" href="'.$route.'?'.$query.'">View All Rate History</a>';
    }


}