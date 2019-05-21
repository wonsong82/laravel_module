<?php

namespace Module\Application\Commands\Module;

use Illuminate\Console\Command;

class ModuleList extends Command
{
    protected $signature = 'module:list';
    protected $description = 'List modules';



    public function handle()
    {
        $modulePath = base_path('module');
        $modules = [];

        foreach(rglob($modulePath . '/**/__moduleInfo.php') as $moduleInfoPath){
            $moduleInfo = require_once($moduleInfoPath);
            $modules[] = $moduleInfo;
        }

        $this->comment("\n");
        collect($modules)->sortBy('moduleOrder')->map(function($module){
            $this->comment("\t[{$module['moduleOrder']}] {$module['moduleName']}");
        });
        $this->comment("\n");
    }







}
