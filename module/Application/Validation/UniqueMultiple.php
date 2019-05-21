<?php
namespace Module\Application\Validation;

use Illuminate\Support\Facades\DB;

class UniqueMultiple extends CustomValidator
{

    protected $name = 'unique_multiple';
    protected $message = 'The :attribute has already been taken.';


    /***
     * Validate multiple unique with given params
     *
     * @param $attribute
     * @param $value
     * @param $parameters: table, columns deliminated by &, id(optional), idKey(optional)
     * @param $validator
     * @return bool
     */
    protected function rule($attribute, $value, $parameters, $validator)
    {
        $table = $parameters[0]; // required

        $fields = isset($parameters[1])? $parameters[1] : $attribute;
        if(strstr($fields, '&')){
            $fields = explode('&', $fields);
        }
        else {
            $fields = [$fields];
        }

        $id = isset($parameters[2]) ? $parameters[2] : null;
        $idKey = isset($parameters[3])? $parameters[3] : 'id';


        $query = DB::table($table);
        foreach($fields as $field){
            $query->where($field, request()->get($field));
        }
        $count = $query->count();


        if($count > 0){
            if($id){
                $ids = $query->get([$idKey])->pluck($idKey);
                return $ids->search($id) !== false;
            }

            return false;
        }

        return true;
    }
}