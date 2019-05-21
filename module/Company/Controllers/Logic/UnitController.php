<?php
namespace Module\Company\Controllers\Logic;




use Module\Application\Controllers\Logic\LogicController;
use Module\Application\Exceptions\NotChangedException;
use Module\Application\ModelChangeCollection;
use Module\Company\Company;
use Module\Company\Constants\UnitStatus;
use Module\Company\Constants\UnitType;
use Module\Company\Events\UnitCreated;
use Module\Company\Events\UnitDeleted;
use Module\Company\Events\UnitUpdated;
use Module\Company\Unit;

class UnitController extends LogicController
{
    public function getDefaultList()
    {
        return [
            [
                'status_code' => UnitStatus::ACTIVE,
                'type_code' => UnitType::COUNT,
                'symbol' => 'ea',
                'name' => 'each',
                'plural_name' => 'each'
            ],
            [
                'status_code' => UnitStatus::ACTIVE,
                'type_code' => UnitType::COUNT,
                'symbol' => 'box',
                'name' => 'box',
                'plural_name' => 'Boxes',
            ],
            [
                'status_code' => UnitStatus::ACTIVE,
                'type_code' => UnitType::COUNT,
                'symbol' => 'pack',
                'name' => 'pack',
                'plural_name' => 'packs',
            ],
            [
                'status_code' => UnitStatus::ACTIVE,
                'type_code' => UnitType::COUNT,
                'symbol' => 'pallet',
                'name' => 'pallet',
                'plural_name' => 'pallets',
            ],
            [
                'status_code' => UnitStatus::ACTIVE,
                'type_code' => UnitType::LENGTH,
                'symbol' => 'mm',
                'name' => 'millimeter',
                'plural_name' => 'millimeters',
            ],
            [
                'status_code' => UnitStatus::ACTIVE,
                'type_code' => UnitType::LENGTH,
                'symbol' => 'cm',
                'name' => 'centimeter',
                'plural_name' => 'centimeters',
            ],
            [
                'status_code' => UnitStatus::ACTIVE,
                'type_code' => UnitType::LENGTH,
                'symbol' => 'm',
                'name' => 'meter',
                'plural_name' => 'meters',
            ],
            [
                'status_code' => UnitStatus::ACTIVE,
                'type_code' => UnitType::LENGTH,
                'symbol' => 'in',
                'name' => 'inch',
                'plural_name' => 'inches',
            ],
            [
                'status_code' => UnitStatus::ACTIVE,
                'type_code' => UnitType::LENGTH,
                'symbol' => 'ft',
                'name' => 'foot',
                'plural_name' => 'feet',
            ],
            [
                'status_code' => UnitStatus::ACTIVE,
                'type_code' => UnitType::MASS,
                'symbol' => 'mg',
                'name' => 'milligram',
                'plural_name' => 'milligrams',
            ],
            [
                'status_code' => UnitStatus::ACTIVE,
                'type_code' => UnitType::MASS,
                'symbol' => 'g',
                'name' => 'gram',
                'plural_name' => 'grams',
            ],
            [
                'status_code' => UnitStatus::ACTIVE,
                'type_code' => UnitType::MASS,
                'symbol' => '㎏',
                'name' => 'kilogram',
                'plural_name' => 'kilograms',
            ],
            [
                'status_code' => UnitStatus::ACTIVE,
                'type_code' => UnitType::MASS,
                'symbol' => 'lb',
                'name' => 'pound',
                'plural_name' => 'pounds',
            ],
            [
                'status_code' => UnitStatus::ACTIVE,
                'type_code' => UnitType::AREA,
                'symbol' => '㎠',
                'name' => 'square centimeter',
                'plural_name' => 'square centimeters',
            ],
            [
                'status_code' => UnitStatus::ACTIVE,
                'type_code' => UnitType::AREA,
                'symbol' => '㎡',
                'name' => 'square meter',
                'plural_name' => 'square meters',
            ],
            [
                'status_code' => UnitStatus::ACTIVE,
                'type_code' => UnitType::VOLUME,
                'symbol' => '㎤',
                'name' => 'cubic centimeter',
                'plural_name' => 'cubic centimeters',
            ],
            [
                'status_code' => UnitStatus::ACTIVE,
                'type_code' => UnitType::VOLUME,
                'symbol' => '㎥',
                'name' => 'cubic meter',
                'plural_name' => 'cubic meters',
            ],

        ];        
    }


    /**
     * @param Company $company
     * @param $params [*status_code, *type_code, *symbol, *name, *plural_name, desc]
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create(Company $company, $params)
    {
        // Create
        $order = Unit::getMaxOrder();

        $params['lft'] = ++$order;
        $params['rgt'] = ++$order;
        $params['depth'] = 1;

        $unit = $company->units()->create($params);

        // Event
        event(new UnitCreated($unit));


        return $unit;
    }


    /**
     * @param Unit $unit
     * @param $params [*status_code, *type_code, *symbol, *name, *plural_name, desc]
     * @return mixed
     * @throws NotChangedException
     */
    public function update(Unit $unit, $params)
    {
        // changes
        $changes = app(ModelChangeCollection::class);
        $changes['unit'] = $unit->getModelChanges(collect($params)->except('lft', 'rgt', 'depth', 'parent_id')->toArray());
        $changes->checkChanges();

        // Update
        $changes->save();

        // Event
        event(new UnitUpdated($unit, $changes));


        return $changes['unit']->model;
    }



    public function updateOrder()
    {
        // todo

    }



    /**
     * @param Unit $unit
     * @return Unit
     */
    public function delete(Unit $unit)
    {
        // Delete
        $unit->delete();

        // Event
        event(new UnitDeleted($unit));


        return $unit;
    }


    /**
     * @param Unit $unit
     * @return mixed
     */
    public function activate(Unit $unit)
    {
        return $this->update($unit, [
            'status_code' => UnitStatus::ACTIVE
        ]);
    }


    /**
     * @param Unit $unit
     * @return mixed
     */
    public function deactivate(Unit $unit)
    {
        return $this->update($unit, [
            'status_code' => UnitStatus::INACTIVE
        ]);
    }

}
