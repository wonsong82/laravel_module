<?php

namespace Module\Application\Commands\Module;

use Illuminate\Console\Command;
use ReflectionClass;

class ModuleMake extends Command
{
    protected $signature = 'module:make
        {module : Module Name}
        {type : All, Command, Constant, CrudController, CrudRequest Event, LogicController, Model, Request, CrudTest, Locale} 
        {class : Class Name}
        {--override}';
    protected $description = 'Make classes => module:make {module} {type} {class}.  types: All, Command, Constant, CrudController, CrudRequest Event, LogicController, Model, Request, CrudTest, Locale';


    protected $order = null;
    protected $constantOrder = null;


    public function handle()
    {
        $name = $this->argument('module');
        $type = $this->argument('type');

        switch($type){
            case 'All':
                $this->createClass($name, 'CrudController', ['/CrudController' => '/Controllers/Admin']);
                $this->createClass($name, 'Event', ['/Event' => '/Events', '/EventListener' => '/EventListeners']);
                $this->createClass($name, 'LogicController', ['/LogicController' => '/Controllers/Logic']);
                $this->createClass($name, 'Model', ['/Model' => '/']);
                $this->createClass($name, 'Request', ['/Request' => '/Requests']);
                $this->createClass($name, 'CrudTest', ['/CrudTest' => '/Tests/Feature']);
                $this->createClass($name, 'Locale', ['/Locale' => '/Translations/en']);
                break;

            case 'Command':
                $this->createClass($name, 'Command', ['/Command' => '/Commands']);
                break;

            case 'Constant':
                $this->createClass($name, 'Constant', ['/Constant' => '/Constants']);
                break;

            case 'CrudController':
                $this->createClass($name, 'CrudController', ['/CrudController' => '/Controllers/Admin']);
                break;

            case 'CrudRequest':
                $this->createClass($name, 'CrudRequest', ['/CrudRequest' => '/Requests/Admin']);
                break;

            case 'Event':
                $this->createClass($name, 'Event', ['/Event' => '/Events', '/EventListener' => '/EventListeners']);
                break;

            case 'LogicController':
                $this->createClass($name, 'LogicController', ['/LogicController' => '/Controllers/Logic']);
                break;

            case 'Model':
                $this->createClass($name, 'Model', ['/Model' => '/']);
                break;

            case 'Request':
                $this->createClass($name, 'Request', ['/Request' => '/Requests']);
                break;

            case 'CrudTest':
                $this->createClass($name, 'CrudTest', ['/CrudTest' => '/Tests/Feature']);
                break;

            case 'Locale':
                $this->createClass($name, 'Locale', ['/Locale' => '/Translations/en']);
                break;

            default:
                $this->comment('');
                $this->error("\t" . 'Could not find the class type.');
                $this->comment('');
        }



    }




    public function createClass($name, $classType, $paths)
    {
        $moduleName = str_replace(':' . $classType, '', $name);
        $modulePath = base_path('module/' . $moduleName);

        if(!is_dir($modulePath)){
            $this->comment('');
            $this->error("\t" . $moduleName . ' module does not exist. create a module first.');
            $this->comment('');
            exit;
        }

        $info = require_once($modulePath . '/__moduleInfo.php');
        $this->order = $info['moduleOrder'];

        if($classType == 'Constant'){
            $this->findConstantOrder($modulePath);
        }


        foreach($paths as $from => $to){
            $from = realpath(__DIR__ . "/module_classes{$from}");
            $to = $modulePath . $to;

            \File::copyDirectory($from, $to);

            foreach(glob($to . '/*.stub') as $path){
                $this->replace($path, $moduleName);
            }

            foreach(rglob($to . '/**/*.stub') as $path){
                $this->replace($path, $moduleName);
            }
        }


        $className = $this->argument('class');

        $this->comment('');
        $this->info("\t" . $name . ' ' . $className . ' created. Please run composer dump.');
        $this->comment('');
    }


    public function findConstantOrder($modulePath)
    {
        $constantOrder = $this->order * 1000 + 1;

        foreach(glob($modulePath . '/Constants/*.php') as $path){
            $basePath = base_path('module');
            $classPath = pathinfo($path, PATHINFO_DIRNAME);
            $camelCaseName = pathinfo($path, PATHINFO_FILENAME);

            $className = str_replace('/', '\\', 'Module' . str_replace($basePath, '', $classPath). '\\' . $camelCaseName);

            $class = new $className;
            $reflection = new ReflectionClass($className);
            $constants = $reflection->getConstants();

            if($reflection->hasProperty('exclude') && $class->exclude === true){
                continue;
            }

            foreach($constants as $constant){
                if(($cur = (int)floor($constant/100)+1) > $constantOrder){
                    $constantOrder = $cur;
                }
            }
        }

        $this->constantOrder = $constantOrder;
    }



    public function replace($path, $name)
    {
        $moduleOrder = $this->order;
        $class = $this->argument('class');

        $lowerName = trim(strtolower(preg_replace('/([A-Z])/', '_$1', $name)), '_');
        $lowerClass = trim(strtolower(preg_replace('/([A-Z])/', '_$1', $class)), '_');
        $camelClass = strtolower($class[0]) . substr($class, 1);
        $hyphenClass = str_replace('_', '-', $lowerClass);

        $readableClass = trim(preg_replace('/([A-Z])/', ' $1', $class));

        $migrationOrder = sprintf('%04d', $moduleOrder) . '00';

        $constantOrder = $this->constantOrder ? $this->constantOrder : '';


        // change content
        $content = file_get_contents($path);
        $content = str_replace('__LOWER_NAME__', $lowerName, $content);             // module_name  __LOWER_NAME__
        $content = str_replace('__NAME__', $name, $content);                        // ModuleName   __NAME__
        $content = str_replace('__ORDER__', $moduleOrder, $content);                //
        $content = str_replace('__MIGRATION_ORDER__', $migrationOrder, $content);   //
        $content = str_replace('__CLASS__', $class, $content);                      // ClassName    __CLASS__
        $content = str_replace('__LOWER_CLASS__', $lowerClass, $content);           // class_name   __LOWER_CLASS__
        $content = str_replace('__CAMEL_CLASS__', $camelClass, $content);           // className    __CAMEL_CLASS__
        $content = str_replace('__HYPHEN_CLASS__', $hyphenClass, $content);         // class-name   __HYPHEN_CLASS__
        $content = str_replace('__CONSTANT_ORDER__', $constantOrder, $content);
        $content = str_replace('__READABLE_CLASS__', $readableClass, $content);

        file_put_contents($path, $content);


        // change filename
        $p = str_replace('__LOWER_NAME__', $lowerName, $path);
        $p = str_replace('__NAME__', $name, $p);
        $p = str_replace('__ORDER__', $moduleOrder, $p);
        $p = str_replace('__MIGRATION_ORDER__', $migrationOrder, $p);
        $p = str_replace('__CLASS__', $class, $p);
        $p = str_replace('__LOWER_CLASS__', $lowerClass, $p);
        $p = str_replace('__CAMEL_CLASS__', $camelClass, $p);
        $p = str_replace('.stub', '', $p);


        if(!file_exists($p) || $this->option('override')) {
            rename($path, $p);
        }
        else {
            unlink($path);
        }


    }



}
