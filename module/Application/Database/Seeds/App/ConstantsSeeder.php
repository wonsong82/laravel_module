<?php
namespace Module\Application\Database\Seeds\App;

use Module\Application\ConstantHeader;
use Illuminate\Database\Seeder;
use ReflectionClass;
use Module\Application\Controllers\Logic\ModuleController;

class ConstantsSeeder extends Seeder
{

    /***
     * Reads constants from app/Constants and create database from them
     */
    public function run()
    {
        foreach(app(ModuleController::class)->getModules() as $module){

            foreach(glob($module['path'] . '/Constants/*.php') as $path){

                $camelCaseName = pathinfo($path, PATHINFO_FILENAME);
                $readableName = trim(preg_replace('/([A-Z])/', ' $1', $camelCaseName));
                $snakeCaseName = strtoupper(str_replace(' ', '_', $readableName));

                $header = ConstantHeader::create([
                    'name' => $snakeCaseName,
                    'display_name' => $readableName
                ]);


                $basePath = base_path('module');
                $classPath = pathinfo($path, PATHINFO_DIRNAME);

                $className = str_replace('/', '\\', 'Module' . str_replace($basePath, '', $classPath). '\\' . $camelCaseName);

                $class = new $className;
                $reflection = new ReflectionClass($className);
                $constants = $reflection->getConstants();


                if($reflection->hasProperty('exclude') && $class->exclude === true){
                    continue;
                }



                $order = 0;
                foreach($constants as $value => $id){
                    $readableValue = ucwords(strtolower(str_replace('_', ' ', $value)));
                    $type = property_exists($class, 'types') && isset($class->types[$id]) ?
                        $class->types[$id] : 'default';

                    $ns = trim(preg_replace('/(Module|Constants)/', '', $reflection->getNamespaceName()), '\\');
                    $ns = trim(strtolower(preg_replace('/([A-Z])/', '_$1', $ns)), '_');

                    $key = $ns . '::constant.' . strtolower($snakeCaseName . '.' . $value);

                    $header->constants()->create([
                        'code' => $id,
                        'name' => $value,
                        'display_name' => $readableValue,
                        'type' => $type,
                        'key' => $key,
                        'order' => ++$order,
                    ]);
                }
            }

        }




    }

}
