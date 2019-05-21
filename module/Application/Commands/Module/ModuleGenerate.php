<?php

namespace Module\Application\Commands\Module;

use Illuminate\Console\Command;



class ModuleGenerate extends Command
{
    protected $signature = 'module:generate {moduleName} {order}';
    protected $description = 'Generate a new module {moduleName} {order}';


    protected $moduleName = null;
    protected $modulePath = null;
    protected $order = null;
    protected $constantOrder = null;


    public function handle()
    {
        $name = $this->moduleName = $this->argument('moduleName');
        $this->modulePath = base_path('module/' . $name);

        $this->createModule();

        $this->comment('');
        $this->info("\t" . $name . ' Module created. Please run composer dump.');
        $this->comment('');
    }





    public function createModule()
    {
        $name = $this->moduleName;
        $modulePath = $this->modulePath;

        if(is_dir($modulePath)){
            $this->comment('');
            $this->error("\t" . $name . ' module already exist.');
            $this->comment('');
            exit;
        }

        $from =  realpath(__DIR__ . '/module_stub');
        \File::copyDirectory($from, $modulePath);


        foreach(glob($modulePath . '/*.stub') as $path){
            $this->replace($path, $name);
        }

        foreach(rglob($modulePath . '/**/*.stub') as $path){
            $this->replace($path, $name);
        }
    }




    public function replace($path, $name)
    {
        $lowerName = trim(strtolower(preg_replace('/([A-Z])/', '_$1', $name)), '_');
        $hyphenName = str_replace('_', '-', $lowerName);

        $moduleOrder = $this->argument('order');


        $migrationOrder = sprintf('%04d', $moduleOrder) . '00';

        // change content
        $content = file_get_contents($path);
        $content = str_replace('__LOWER_NAME__', $lowerName, $content);
        $content = str_replace('__HYPHEN_NAME__', $hyphenName, $content);
        $content = str_replace('__NAME__', $name, $content);
        $content = str_replace('__ORDER__', $moduleOrder, $content);
        $content = str_replace('__MIGRATION_ORDER__', $migrationOrder, $content);


        file_put_contents($path, $content);


        // change filename
        $p = str_replace('__LOWER_NAME__', $lowerName, $path);
        $p = str_replace('__HYPHEN_NAME__', $hyphenName, $p);
        $p = str_replace('__NAME__', $name, $p);
        $p = str_replace('__ORDER__', $moduleOrder, $p);
        $p = str_replace('__MIGRATION_ORDER__', $migrationOrder, $p);
        $p = str_replace('.stub', '', $p);

        rename($path, $p);
    }



    public function clearPlaceHolders()
    {
        $modulePath = $this->modulePath;
        foreach(glob($modulePath . '/placeholder') as $path){
            unlink($path);
        }

        foreach(rglob($modulePath . '/**/placeholder') as $path){
            unlink($path);
        }

    }


}
