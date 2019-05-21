<?php
namespace Module\Company\Traits\Admin;

use Module\Application\ConstantHeader;

trait CurrencyCrudTrait {

//    public function getUserIdAttribute()
//    {
//        return $this->country->user->id;
//    }


    public function btnViewRates($entry)
    {
        $route = route('business::crud.currency-pair.index');
        $query = http_build_query([
            'base_currency' => $entry->code
        ]);

        return '<a class="btn btn-xs btn-default" target="_blank" href="'.$route.'?'.$query.'">View Rates</a>';
    }




}
