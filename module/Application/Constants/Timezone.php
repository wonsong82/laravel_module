<?php
namespace Module\Application\Constants;

use DateTimeZone;

class Timezone {
 
    public $exclude = true;
    
    


    public static function getTimezoneOptions()
    {
        $timezones = [];
        foreach(DateTimeZone::listIdentifiers(DateTimeZone::ALL) as $timezone){
            $timezones[$timezone] = $timezone;
        }

        return $timezones;
    }



}