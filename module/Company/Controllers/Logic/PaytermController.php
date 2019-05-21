<?php
namespace Module\Company\Controllers\Logic;




use Module\Application\Controllers\Logic\LogicController;
use Module\Application\Exceptions\NotChangedException;
use Module\Application\ModelChangeCollection;
use Module\Company\Company;
use Module\Company\Constants\PaytermStatus;
use Module\Company\Events\PaytermCreated;
use Module\Company\Events\PaytermDeleted;
use Module\Company\Events\PaytermUpdated;
use Module\Company\Payterm;

class PaytermController extends LogicController
{
    public function getDefaultList()
    {
        return [
            [
                'status_code' => PaytermStatus::ACTIVE,
                'code' => 'PIA',
                'name' => 'PIA',
                'desc' => 'company::payterm.in_advance'
            ],
            [
                'status_code' => PaytermStatus::ACTIVE,
                'code' => 'NET7',
                'name' => 'Net 7',
                'desc' => 'Payment seven days after invoice date'
            ],
            [
                'status_code' => PaytermStatus::ACTIVE,
                'code' => 'NET10',
                'name' => 'Net 10',
                'desc' => 'Payment ten days after invoice date'
            ],
            [
                'status_code' => PaytermStatus::ACTIVE,
                'code' => 'NET30',
                'name' => 'Net 30',
                'desc' => 'Payment 30 days after invoice date'
            ],
            [
                'status_code' => PaytermStatus::ACTIVE,
                'code' => 'NET60',
                'name' => 'Net 60',
                'desc' => 'Payment 60 days after invoice date'
            ],
            [
                'status_code' => PaytermStatus::ACTIVE,
                'code' => 'NET90',
                'name' => 'Net 90',
                'desc' => 'Payment 90 days after invoice date'
            ],
            [
                'status_code' => PaytermStatus::ACTIVE,
                'code' => 'EOM',
                'name' => 'EOM',
                'desc' => 'End of month'
            ],
            [
                'status_code' => PaytermStatus::ACTIVE,
                'code' => '21_MFI',
                'name' => '21 MFI',
                'desc' => '21st of the month following invoice date'
            ],
            [
                'status_code' => PaytermStatus::ACTIVE,
                'code' => '1OF10_NET30',
                'name' => '1% 10 Net 30',
                'desc' => '1% discount if payment received within ten days otherwise payment 30 days after invoice date'
            ],
            [
                'status_code' => PaytermStatus::ACTIVE,
                'code' => 'COD',
                'name' => 'COD',
                'desc' => 'Cash on delivery'
            ],
            [
                'status_code' => PaytermStatus::ACTIVE,
                'code' => 'CASH',
                'name' => 'Cash',
                'desc' => 'Account conducted on a cash basis, no credit'
            ],
            [
                'status_code' => PaytermStatus::ACTIVE,
                'code' => 'LOC',
                'name' => 'Letter of credit',
                'desc' => 'A documentary credit confirmed by a bank, often used for export'
            ],
            [
                'status_code' => PaytermStatus::ACTIVE,
                'code' => 'BOE',
                'name' => 'Bill of exchange',
                'desc' => 'A promise to pay at a later date, usually supported by a bank'
            ],
            [
                'status_code' => PaytermStatus::ACTIVE,
                'code' => 'CND',
                'name' => 'CND',
                'desc' => 'Cash next delivery'
            ],
            [
                'status_code' => PaytermStatus::ACTIVE,
                'code' => 'CBS',
                'name' => 'CBS',
                'desc' => 'Cash before shipment'
            ],
            [
                'status_code' => PaytermStatus::ACTIVE,
                'code' => 'CIA',
                'name' => 'CIA',
                'desc' => 'Cash in advance'
            ],
            [
                'status_code' => PaytermStatus::ACTIVE,
                'code' => 'CWO',
                'name' => 'CWO',
                'desc' => 'Cash with order'
            ],
            [
                'status_code' => PaytermStatus::ACTIVE,
                'code' => '1MD',
                'name' => '1MD',
                'desc' => 'Monthly credit payment of a full month\'s supply'
            ],
            [
                'status_code' => PaytermStatus::ACTIVE,
                'code' => '2MD',
                'name' => '2MD',
                'desc' => 'Monthly credit payment of two full month\'s supply'
            ],
            [
                'status_code' => PaytermStatus::ACTIVE,
                'code' => 'CON',
                'name' => 'Contra',
                'desc' => 'Payment from the customer offset against the value of supplies purchased from the customer'
            ],
            [
                'status_code' => PaytermStatus::ACTIVE,
                'code' => 'SP',
                'name' => 'Stage payment',
                'desc' => 'Payment of agreed amounts at stage'
            ]
        ];
    }


    /**
     * @param Company $company
     * @param $params [*status_code, *code, *name, desc]
     * @return object
     */
    public function create(Company $company, $params)
    {
        // Create
        $order = Payterm::getMaxOrder();

        $params['lft'] = ++$order;
        $params['rgt'] = ++$order;
        $params['depth'] = 1;

        $payterm = $company->payterms()->create($params);

        // Event
        event(new PaytermCreated($payterm));

        return $payterm;
    }


    /**
     * @param Payterm $payterm
     * @param $params [**status_code, code, *name, desc]
     * @return mixed
     * @throws NotChangedException
     */
    public function update(Payterm $payterm, $params)
    {
        // changes
        $changes = app(ModelChangeCollection::class);
        $changes['payterm'] = $payterm->getModelChanges(collect($params)->except('lft', 'rgt', 'depth', 'parent_id')->toArray());
        $changes->checkChanges();


        // update
        $changes->save();


        // Event
        event(new PaytermUpdated($payterm, $changes));


        return $changes['payterm']->model;
    }


    /**
     *
     */
    public function updateOrder()
    {
        // todo
    }


    /**
     * @param Payterm $payterm
     * @return Payterm
     */
    public function delete(Payterm $payterm)
    {
        // Delete
        $payterm->delete();

        // Event
        event(new PaytermDeleted($payterm));

        return $payterm;
    }


    /**
     * @param Payterm $payterm
     * @return mixed
     */
    public function activate(Payterm $payterm)
    {
        return $this->update($payterm, [
            'status_code' => PaytermStatus::ACTIVE
        ]);
    }


    /**
     * @param Payterm $payterm
     * @return mixed
     */
    public function deactivate(Payterm $payterm)
    {
        return $this->update($payterm, [
            'status_code' => PaytermStatus::INACTIVE
        ]);
    }

}
