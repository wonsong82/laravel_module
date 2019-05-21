<?php
namespace Module\Application\Controllers\Logic;


class ModuleController {

    public $modules = [];


    public function getModules()
    {
        return $this->modules;
    }


    public function isModuleLoaded($moduleName)
    {
        return !!(collect($this->modules)->where('name', $moduleName)->count());
    }


}