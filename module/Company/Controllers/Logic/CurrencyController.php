<?php
namespace Module\Company\Controllers\Logic;



use Module\Application\Controllers\Logic\LogicController;
use Module\Application\Exceptions\NotChangedException;
use Module\Application\ModelChangeCollection;
use Module\Company\Company;
use Module\Company\Constants\CurrencyStatus;
use Module\Company\Constants\CurrencySymbolPosition;
use Module\Company\Currency;
use Module\Company\Events\CurrencyCreated;
use Module\Company\Events\CurrencyDeleted;
use Module\Company\Events\CurrencyUpdated;

class CurrencyController extends LogicController
{
    public function getDefaultList()
    {
        return [
            [
                'status_code' => CurrencyStatus::ACTIVE,
                'code' => 'USD',
                'name' => __('company::currency.default.us_dollar'),
                'code_n' => 840,
                'symbol' => '$',
                'symbol_position' => CurrencySymbolPosition::BEFORE_WITHOUT_SPACE,
                'decimal_count' => 2,
                'decimal_separator' => '.',
                'thousand_separator' => ','
            ],
            [
                'status_code' => CurrencyStatus::ACTIVE,
                'code' => 'KRW',
                'name' => __('company::currency.default.korean_won'),
                'code_n' => 111,
                'symbol' => '원',
                'symbol_position' => CurrencySymbolPosition::AFTER_WITHOUT_SPACE,
                'decimal_count' => 0,
                'decimal_separator' => '.',
                'thousand_separator' => ','
            ],
            [
                'status_code' => CurrencyStatus::ACTIVE,
                'code' => 'EUR',
                'name' => __('company::currency.default.euro'),
                'code_n' => 978,
                'symbol' => '€',
                'symbol_position' => CurrencySymbolPosition::AFTER_WITHOUT_SPACE,
                'decimal_count' => 2,
                'decimal_separator' => '.',
                'thousand_separator' => ','
            ],
            [
                'status_code' => CurrencyStatus::ACTIVE,
                'code' => 'CNY',
                'name' => __('company::currency.default.china_yuan'),
                'code_n' => 200,
                'symbol' => '￥',
                'symbol_position' => CurrencySymbolPosition::AFTER_WITHOUT_SPACE,
                'decimal_count' => 2,
                'decimal_separator' => '.',
                'thousand_separator' => ','
            ],
        ];

    }


    /**
     * @param Company $company
     * @param $params [*status_code, *code, *name, code_n, symbol, *symbol_position_code, decimal_count, decimal_separator, thousand_separator]
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create(Company $company, $params)
    {
        // Create
        $order = Currency::getMaxOrder();

        $params['lft'] = ++$order;
        $params['rgt'] = ++$order;
        $params['depth'] = 1;

        $currency = $company->currencies()->create($params);
        // todo: currency rates

        // Event
        event(new CurrencyCreated($currency));


        return $currency;
    }


    /**
     * @param Currency $currency
     * @param $params [*status_code, *code, *name, code_n, symbol, *symbol_position_code, decimal_count, decimal_separator, thousand_separator]
     * @return mixed
     * @throws NotChangedException
     */
    public function update(Currency $currency, $params)
    {
        // changes
        $changes = app(ModelChangeCollection::class);
        $changes['currency'] = $currency->getModelChanges(collect($params)->except('lft', 'rgt', 'depth', 'parent_id')->toArray());
        $changes->checkChanges();


        // update
        $changes->save();


        // event
        event(new CurrencyUpdated($currency, $changes));

        return $changes['currency']->model;
    }



    public function updateOrder()
    {
        // todo
    }



    /**
     * @param Currency $currency
     * @return Currency
     */
    public function delete(Currency $currency)
    {
        // Delete
        $currency->delete();


        // Event
        event(new CurrencyDeleted($currency));


        return $currency;
    }


    /***
     * @param Currency $currency
     * @return mixed
     */
    public function activate(Currency $currency)
    {
        return $this->update($currency, [
            'status_code' => CurrencyStatus::ACTIVE
        ]);
    }


    /***
     * @param Currency $currency
     * @return mixed
     */
    public function deactivate(Currency $currency)
    {
        return $this->update($currency, [
            'status_code' => CurrencyStatus::INACTIVE
        ]);
    }
}
