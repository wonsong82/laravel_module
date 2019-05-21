<?php

namespace Module\Application\Commands\Module;

use Illuminate\Console\Command;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
use Module\Application\Controllers\Logic\ModuleController;

class ModuleTest extends Command
{
    protected $signature = 'module:test {module*}';
    protected $description = 'Update phpunit.xml with loaded modules. then run test.';

    protected $all = false;
    protected $names = [];



    public function handle()
    {
        $this->names = $this->argument('module');
        if(in_array('all', $this->names))
            $this->all = true;

        $this->renewConfiguration();
        //$this->executeProcess(['vendor/bin/phpunit']);
    }



    protected function renewConfiguration()
    {
        $controller = app(ModuleController::class);

        $dom = new \DomDocument();
        $dom->load(base_path('phpunit.xml'));

        $xpath = new \DOMXPath($dom);

        $units = $xpath->query('//testsuites/testsuite[@name="Unit"]')[0];
        while($units->hasChildNodes()){
            $units->removeChild($units->firstChild);
        }

        $features = $xpath->query('//testsuites/testsuite[@name="Feature"]')[0];
        while($features->hasChildNodes()){
            $features->removeChild($features->firstChild);
        }


        $names = $this->names;
        $all = $this->all;

        foreach($controller->modules as $module){
            if($all || in_array($module['name'], $names)){
                $unit = $dom->createElement(
                    'directory',
                    str_replace(base_path(), '.' , $module['path'] . '/Tests/Unit')
                );

                $feature = $dom->createElement(
                    'directory',
                    str_replace(base_path(), '.', $module['path'] . '/Tests/Feature')
                );

                $units->appendChild($unit);
                $features->appendChild($feature);
            }


        }


        $data = $dom->saveXML();


        $xml = new \DOMDocument();
        $xml->preserveWhiteSpace = false;
        $xml->formatOutput = true;
        $xml->loadXML($data);
        $xml->save('phpunit.xml');

        $this->info('Test Config "phpunit.xml" generated. Run vendor/bin/phpunit for colorized test output.' . "\n");
    }



    protected function executeProcess($command)
    {
        $process = new Process($command);
        $process->run(function ($type, $buffer) {
            if (Process::ERR === $type) {
                $this->echo('comment', $buffer);
            } else {
                $this->echo('line', $buffer);
            }
        });

        if(!$process->isSuccessful()){
            throw new ProcessFailedException($process);
        }

        $this->info('Test completed. Run vendor/bin/phpunit for colorized test output.' . "\n");
    }


    public function echo($type, $content)
    {
        // skip empty lines
        if (trim($content)) {
            $this->{$type}($content);
        }
    }

}
