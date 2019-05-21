<?php
namespace Module\Company\Controllers\Logic;




use Module\Application\Controllers\Logic\LogicController;
use Module\Application\Exceptions\NotChangedException;
use Module\Application\ModelChangeCollection;
use Module\Company\Company;
use Module\Company\Events\UOMCreated;
use Module\Company\Events\UOMDeleted;
use Module\Company\Events\UOMUpdated;
use Module\Company\UOM;

class UOMController extends LogicController
{
    public function getDefaultList()
    {
        return [
            [
                'code' => 'EA',
                'isc' => 'Each',
                'desc' => 'EA'
            ],
            [
                'code' => 'BOX',
                'name' => 'Box',
                'desc' => 'BX'
            ],
            [
                'code' => 'PACK',
                'name' => 'Pack',
                'desc' => 'PK'
            ],
            [
                'code' => 'PALLET',
                'name' => 'Pallet',
                'desc' => 'PF'
            ],
            [
                'code' => 'ROLL',
                'name' => 'Roll',
                'desc' => 'RO'
            ],
            [
                'code' => 'BOTTLE',
                'name' => 'Bottle',
                'desc' => 'BT'
            ],
            [
                'code' => 'CAN',
                'name' => 'Can',
                'desc' => 'CA'
            ],
            [
                'code' => 'GR',
                'name' => 'Gram',
                'desc' => 'GRM'
            ],
            [
                'code' => 'KG',
                'name' => 'Kilograms',
                'desc' => 'KGM'
            ],
            [
                'code' => 'KM',
                'name' => 'Kilometers',
                'desc' => 'KMT'
            ],
            [
                'code' => 'L',
                'name' => 'Liter',
                'desc' => 'LTR'
            ],
            [
                'code' => 'MILES',
                'name' => 'Miles',
                'desc' => '1A'
            ],
            [
                'code' => 'HOUR',
                'name' => 'Hour',
                'desc' => 'HUR'
            ],
            [
                'code' => 'DAY',
                'name' => 'Day',
                'desc' => 'DAY'
            ],
        ];        
    }


    /**
     * @param Company $company
     * @param $params [*code, *isc, desc]
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create(Company $company, $params)
    {
        // Create
        $order = UOM::getMaxOrder();

        $params['lft'] = ++$order;
        $params['rgt'] = ++$order;
        $params['depth'] = 1;

        $uom = $company->uoms()->create($params);

        // Event
        event(new UOMCreated($uom));


        return $uom;
    }


    /**
     * @param UOM $uom
     * @param $params [*code, *isc, desc]
     * @return mixed
     * @throws NotChangedException
     */
    public function update(UOM $uom, $params)
    {
        // changes
        $changes = app(ModelChangeCollection::class);
        $changes['uom'] = $uom->getModelChanges(collect($params)->only('code', 'isc', 'desc')->toArray());
        $changes->checkChanges();

        // Update
        $changes->save();

        // Event
        event(new UOMUpdated($uom, $changes));


        return $changes['uom']->model;
    }



    public function updateOrder()
    {
        // todo

    }



    /**
     * @param UOM $uom
     * @return UOM
     */
    public function delete(UOM $uom)
    {
        // Delete
        $uom->delete();

        // Event
        event(new UOMDeleted($uom));


        return $uom;
    }

}
