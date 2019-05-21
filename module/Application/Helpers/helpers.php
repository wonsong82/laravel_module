<?php

if(!function_exists('rglob')) {
    function rglob($pattern, $flags = 0, $traversePostOrder = false)
    {
        // Keep away the hassles of the rest if we don't use the wildcard anyway
        if (strpos($pattern, '/**/') === false) {
            return glob($pattern, $flags);
        }

        $patternParts = explode('/**/', $pattern);

        // Get sub dirs
        $dirs = glob(array_shift($patternParts) . '/*', GLOB_ONLYDIR | GLOB_NOSORT);


        // Get files for current dir
        $files = glob($pattern, $flags);

        foreach ($dirs as $dir) {
            $subDirContent = rglob($dir . '/**/' . implode('/**/', $patternParts), $flags, $traversePostOrder);

            if (!$traversePostOrder) {
                $files = array_merge($files, $subDirContent);
            } else {
                $files = array_merge($subDirContent, $files);
            }
        }

        return $files;
    }
}


if(!function_exists('str_slug_int')){
    function str_slug_int($title, $separator = '-'){
        $slug = str_slug($title, $separator);
        if(!$slug){
            $slug = str_replace(' ', $separator, $title);
            $slug = strtolower(str_replace('%', '', urlencode($slug)));
        }

        return $slug;
    }
}



if(!function_exists('clear_null')){
    function clear_null(&$_data, $fields=null){
        if($fields === null){
            $fields = array_keys($_data);
        }

        foreach($fields as $key){
            if(!isset($_data[$key])){
                unset($_data[$key]);
            }
        }
    }
}



if(!function_exists('module_loaded')){
    function module_loaded($name){
//        $serviceProviderClass = 'Module\\' . $name . '\\' . $name . 'ServiceProvider';
//        return !!app()->getProvider($serviceProviderClass);

        return app(\Module\Application\Controllers\Logic\ModuleController::class)->isModuleLoaded($name);
    }
}



if(!function_exists('readable_days')){
    function readable_days($days){
        $y = $w = $d = 0;

        $y = floor($days/365);
        $w = floor(($days%365)/7);
        $d = floor($days%365)%7;

        $readableDays = [];
        if($y){
            $readableDays[] = __('application::helpers.readable_days.year', ['year' => $y]);
        }
        if($w){
            $readableDays[] = __('application::helpers.readable_days.week', ['week' => $w]);
        }
        if($d){
            $readableDays[] = __('application::helpers.readable_days.day',  ['day'  => $d]);
        }

        return implode(' ', $readableDays);
    }
}


