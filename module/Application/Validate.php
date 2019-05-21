<?php
namespace Module\Application;



use Module\Application\Traits\HasPhones;

class Validate {

    use HasPhones;


    public static function exist($input, $name=null)
    {
        return is_null($name)
            ?!empty($input)
            :isset($input[$name]) && !empty($input[$name]);
    }

    public static function require($input, $name=null)
    {
        return self::exist($input, $name);
    }


    public static function unique($input, $name=null, $class, $fieldName)
    {
        if(!self::exist($input, $name)) return true;
        $input = is_null($name)? $input : $input[$name];
        return !$class::where($fieldName, $input)->count();
    }


    public static function numeric($input, $name)
    {
        if(!self::exist($input, $name)) return true;
        $input = is_null($name)? $input : $input[$name];
        return preg_match('#[\d,.]+$#', $input);
    }


    public static function phone($input, $name)
    {
        if(!self::exist($input, $name)) return true;
        $input = is_null($name)? $input : $input[$name];
        $phone = self::parsePhoneNumber($input);
        return $phone->area && $phone->prefix && $phone->number;
    }


    public static function email($input, $name)
    {
        if(!self::exist($input, $name)) return true;
        $input = is_null($name)? $input : $input[$name];
        return filter_var($input, FILTER_VALIDATE_EMAIL);
    }


    public static function url($input, $name)
    {
        if(!self::exist($input, $name)) return true;
        $input = is_null($name)? $input : $input[$name];
        return preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $input);
    }

    public static function regex($input, $regex, $name)
    {
        if(!self::exist($input, $name)) return true;
        $input = is_null($name)? $input : $input[$name];
        return preg_match($regex, $input);
    }



    public static function validate($validations, $data=null)
    {
        if(!$data) $data = request()->all();

        // Validate Require
        $errors = [];
        $requires = array_filter($validations, function($validation){
            return $validation['type'] == 'exist' || $validation['type'] == 'require';
        });

        foreach($requires as $validation){
            $method = $validation['type'];
            if(!self::$method($data, $validation['name']))
                $errors[$validation['name']] = $validation['message'];
        }

        if(!empty($errors))
            return $errors;


        // Validate Others
        $errors = [];
        $others = array_filter($validations, function($validation){
            return $validation['type'] != 'exist' && $validation['type'] != 'require';
        });

        foreach($others as $validation){
            $method = $validation['type'];
            if($method == 'regex'){
                if(!self::$method($data, $validation['regex'], $validation['name']))
                    $errors[$validation['name']] = $validation['message'];
            }
            else {
                if(!self::$method($data, $validation['name']))
                    $errors[$validation['name']] = $validation['message'];
            }
        }

        return $errors;
    }






}