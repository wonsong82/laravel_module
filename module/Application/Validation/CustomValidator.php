<?php
namespace Module\Application\Validation;

use Illuminate\Support\Facades\Validator;

class CustomValidator
{

    protected $name = '';
    protected $message = 'The :attribute is invalid.';


    public static function register()
    {
        $validation = new static();

        Validator::extend($validation->name, function($attribute, $value, $parameters, $validator) use ($validation){
            return $validation->rule($attribute, $value, $parameters, $validator);
        }, $validation->message);
    }


    protected function rule($attribute, $value, $parameters, $validator){
        /***
         * implement validation rule, return true or false
         */
        return true;
    }



}