<?php
namespace Module\Application\Constants;

use ReflectionClass;

class Locale {
 
    public $exclude = true;


    const EN = 'English';
    const KO = 'Korean';


    public static function getLocaleOptions()
    {
        $options = [];

        $self = new ReflectionClass(__CLASS__);
        foreach($self->getConstants() as $code => $name){
            $key = strtolower($code);
            $value = __('application::locale.' . $key);
            $options[$key] = $value;
        }

        return $options;
    }
    
}