<?php

namespace Module\Application\Commands\Module;

use Illuminate\Console\Command;
use ReflectionClass;

class ModuleUpdateOrder extends Command
{
    protected $signature = 'module:update-order {moduleName} {newOrder}';
    protected $description = 'Update module order to new given order. (moduleName: moduleName, newOrder: newOrder)';


    protected $moduleName;
    protected $modulePath;
    protected $newOrder;
    protected $prevOrder;


    public function handle()
    {
        $this->moduleName = $this->argument('moduleName');
        $this->newOrder = (int)$this->argument('newOrder');

        $this->modulePath = base_path('module/' . $this->moduleName);

        if(!is_dir($this->modulePath)){
            $this->comment('');
            $this->error("\t" . $this->moduleName . ' module does not exist.');
            $this->comment('');
            exit;
        }


        $this->updateModuleInfo();
        $this->updateConstants();
        $this->updateMigrations();

        $this->comment('');
        $this->info("\t{$this->moduleName} order changed from {$this->prevOrder} to {$this->newOrder}.");
        $this->comment('');
    }


    public function updateModuleInfo()
    {
        $moduleInfoPath = $this->modulePath . '/__moduleInfo.php';
        $moduleInfo = require($moduleInfoPath);

        $this->prevOrder = $moduleInfo['moduleOrder'];


        $code = file_get_contents($moduleInfoPath);
        $pattern = "#(\'moduleOrder\'\s*\=\>\s*){$this->prevOrder}#";
        $replace = '${1}' . $this->newOrder;
        $updatedCode = preg_replace($pattern, $replace, $code);

        file_put_contents($moduleInfoPath, $updatedCode);
    }


    public function updateConstants()
    {
        foreach(glob($this->modulePath . '/Constants/*.php') as $constantPath){
            $code = file_get_contents($constantPath);
            $pattern = "#(const [a-z_\d]+\s*=\s*){$this->prevOrder}([\d]{5})#i";
            $replace = '${1}' . $this->newOrder . '${2}';
            $updatedCode = preg_replace($pattern, $replace, $code);

            file_put_contents($constantPath, $updatedCode);
        }
    }


    public function updateMigrations()
    {
        foreach(glob($this->modulePath . '/Database/migrations/*.php') as $migrationPath){
            $fileName = basename($migrationPath);
            $dir = str_replace($fileName, '', $migrationPath);

            $prevOrder = sprintf('%04d', $this->prevOrder);
            $newOrder = sprintf('%04d', $this->newOrder);

            $pattern = "#^([\d]{4}_[\d]{2}_[\d]{2}_){$prevOrder}([\d]{2}.+\.php)$#";
            $replace = '${1}' . $newOrder . '${2}';
            $updatedPath = $dir . preg_replace($pattern, $replace, $fileName);

            rename($migrationPath, $updatedPath);
        }
    }


}
