<?php

namespace Module\Application\Commands\Module;

use Illuminate\Console\Command;
use Module\Application\Lib\DirectoryIterator;


class ModuleUpdate extends Command
{
    protected $signature = 'module:update {moduleName}';
    protected $description = 'Update existing module with latest structure. Skip files already exist.';


    protected $moduleName = null;
    protected $modulePath = null;
    protected $tempName = null;
    protected $tempPath = null;
    protected $order = null;
    protected $constantOrder = null;


    public function handle()
    {
        $moduleName = $this->moduleName = $this->argument('moduleName');
        $modulePath = $this->modulePath = base_path('module/' . $moduleName);

        $tempName = $this->tempName = '__TEMP__';
        $tempPath = $this->tempPath = base_path('module/' . $tempName);


        if(!is_dir($modulePath)){
            $this->comment('');
            $this->error("\t" . $moduleName . ' module does not exist.');
            $this->comment('');
            exit;
        }

        $info = require_once($modulePath . '/__moduleInfo.php');
        $this->order = $info['moduleOrder'];


        $this->createTempModule();
        $this->updateModule();
        \File::deleteDirectory($tempPath);


        $this->comment('');
        $this->info("\t" . $moduleName . ' Module updated. Please run composer dump.');
        $this->comment('');
    }




    public function createTempModule()
    {
        $name = $this->moduleName;
        $tempPath = $this->tempPath;

        $from =  realpath(__DIR__ . '/module_stub');
        \File::copyDirectory($from, $tempPath);


        foreach(glob($tempPath . '/*.stub') as $path){
            $this->replace($path, $name);
        }

        foreach(rglob($tempPath . '/**/*.stub') as $path){
            $this->replace($path, $name);
        }
    }



    public function updateModule()
    {
        $tempName = $this->tempName;
        $tempPath = $this->tempPath;
        $moduleName = $this->moduleName;


        $files = DirectoryIterator::scan($tempPath, true, DirectoryIterator::SCAN_FILES_ONLY);

        foreach($files as $from){
            $to = str_replace($tempName, $moduleName, $from);
            if(! \File::isFile($to)){
                if(! \File::isDirectory(dirname($to))){
                    \File::makeDirectory(dirname($to), 0777, true);
                }
                \File::copy($from, $to);
            }
        }

    }



    public function replace($path, $name)
    {
        $lowerName = trim(strtolower(preg_replace('/([A-Z])/', '_$1', $name)), '_');
        $moduleOrder = $this->order;


        $migrationOrder = sprintf('%04d', $moduleOrder) . '00';

        // change content
        $content = file_get_contents($path);
        $content = str_replace('__LOWER_NAME__', $lowerName, $content);
        $content = str_replace('__NAME__', $name, $content);
        $content = str_replace('__ORDER__', $moduleOrder, $content);
        $content = str_replace('__MIGRATION_ORDER__', $migrationOrder, $content);

        file_put_contents($path, $content);


        // change filename
        $p = str_replace('__LOWER_NAME__', $lowerName, $path);
        $p = str_replace('__NAME__', $name, $p);
        $p = str_replace('__ORDER__', $moduleOrder, $p);
        $p = str_replace('__MIGRATION_ORDER__', $migrationOrder, $p);
        $p = str_replace('.stub', '', $p);

        rename($path, $p);
    }


}
