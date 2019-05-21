<?php
namespace Module\Application\Traits;

use Module\Application\Constant;
use Module\Application\ModelChange;

trait HasModelChanges {


    /**
     * Get changes of the model (after fill)
     *
     * @return array  Object{name, from, to)
     */
    public function getAllChanges()
    {
        $changes = [];

        if($this->isDirty()){

            $original = $this->getOriginal();
            foreach($this->getDirty() as $fieldName => $fieldValue){

                $change = new \stdClass();
                $change->name = $fieldName; // for translations
                $change->from = $this->parseValueFromModel($fieldName, $original[$fieldName]); //이전값
                $change->to   = $this->parseValueFromModel($fieldName, $fieldValue);//이후값
                $changes[$fieldName] = $change;
            }
        }

        return $changes;
    }

    /***
     * From given field name, convert camel case format to readable format without _id if exists
     *
     * @param $fieldName
     * @return string
     */
    protected function parseFieldName($fieldName)
    {
        return ucwords(str_replace('_', ' ', str_replace('_id', '', $fieldName)));
    }


    /***
     * From given field name and its value, return the value.
     * If value is related (with _id), return the value from the related model (looking for `code` or `name` field)
     *
     * @param $fieldName
     * @param $value
     * @return mixed
     */
    protected function parseValueFromModel($fieldName, $value)
    {
        // Relation model 내용 가져와서 확인
        $belongsTos = method_exists($this, 'belongsTos')
            ? $this->belongsTos() : null;


        if($belongsTos && isset($belongsTos[$fieldName])){
            $model = $belongsTos[$fieldName][0];
            $field = $belongsTos[$fieldName][1];
            $inst = $model::find((int)$value);
            if($inst){
                return $inst->$field;
            } else {
                return $value;
            }
        }

        elseif(preg_match('#_code$#', $fieldName)){
            $constant = Constant::find($value);
            if($constant){
                return __($constant->key);
            } else {
                return $value;
            }
        }

        else {
            return $value;
        }
    }


    /***
     * use this for single model. [BelongsTo, HasOne]
     *
     * @param $changes
     * @return ModelChange
     */
    public function getModelChanges($changes)
    {
        $model = $this->fill($changes);
        $changes = $model->getAllChanges();
        $changed = !empty($changes);

        return new ModelChange('model', null, $model, $changes, $changed);
    }


    /***
     * For belongsToMany (Pivot), can only track add & delete atm
     *
     * @param $relation
     * @param $items
     * @return ModelChange
     */
    public function getPivotChanges($relation, $items)
    {
        $changes = new ModelChange('pivot', $relation, $this, [], false);

        $oldItems = [];
        foreach($this->$relation as $oldItem){
            $oldItems[$oldItem->id] = $oldItem;
        }

        $newItems = [];
        $class = get_class($this->$relation()->getRelated());
        foreach($items??[] as $id){
            $item = $class::find($id);
            $newItems[$item->id] = $item;
        }

        // find changes
        foreach($newItems as $item){
            if(isset($oldItems[$item->id??null])){
                unset($oldItems[$item->id]);
            }
            else { // attaches
                $changes->changes[] = [
                    'type' => 'create',
                    'model' => $item
                ];
                $changes->changed = true;
            }
        }

        // detaches
        foreach($oldItems as $item){
            $changes->changes[] = [
                'type' => 'delete',
                'model' => $item
            ];
            $changes->changed = true;
        }

        return $changes;
    }


    /***
     * For HasMany
     *
     * @param $relation
     * @param $items
     * @return ModelChange
     */
    public function getRelationChanges($relation, $items)
    {
        $changes = new ModelChange('relation', $relation, $this, [], false);

        $oldItems = [];

        // get old items
        foreach($this->$relation as $oldItem){
            $oldItems[$oldItem->id] = $oldItem;
        }

        foreach($items??[] as $itemData){

            if(isset($oldItems[$itemData->id??null])){ // exist
                $item = $oldItems[$itemData->id];
                $change = $item->getModelChanges((array)$itemData);

                if($change->changed){ // exist and updated
                    $changes->changes[] = [
                        'type' => 'update',
                        'model' => $change->model,
                        'changes' => $change->changes
                    ];

                    $changes->changed = true;
                }

                unset($oldItems[$itemData->id]);
            }

            else { // not exist ( new )
                $class = get_class($this->$relation()->getRelated());
                $model = new $class;
                $model->fill((array)$itemData);

                $changes->changes[] = [
                    'type' => 'create',
                    'model' => $model
                ];

                $changes->changed = true;
            }
        }

        // delete
        foreach($oldItems as $item){
            $changes->changes[] = [
                'type' => 'delete',
                'model' => $item
            ];

            $changes->changed = true;
        }

        return $changes;
    }




}