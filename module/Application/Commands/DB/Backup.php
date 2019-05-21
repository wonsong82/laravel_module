<?php

namespace Module\Application\Commands\DB;

use Illuminate\Console\Command;
use Module\Application\Controllers\Logic\DBController;

class Backup extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:backup';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backup database';



    public function handle(DBController $DBController)
    {
        $tables = $DBController->getTableNames();
        foreach($tables as $id => $tableName){
            $this->comment("[{$id}] {$tableName}");
        }

        $tableIDs = $this->ask('Select tables by typing their IDs separated by space');

        // invalid input
        if(!preg_match('#^[\d\s]+$#', $tableIDs)){
            $this->error('Invalid table IDs');
            exit;
        }

        $tableIDs = explode(' ', trim(preg_replace('#\s+#', ' ', $tableIDs)));
        sort($tableIDs);
        $tableIDs = array_map(function($value){
            return (int)$value;
        }, $tableIDs);

        // if 0 is included, leave only one
        if($tableIDs[0] == 0){
            $tableIDs = [0];
        }


        // message it out and confirm
        $confirm = $this->confirm("You have chosen following table(s):\n[" . implode(', ', array_map(function($id) use ($tables){
                return $tables[$id];
            }, $tableIDs)) . "]\nProceed to backup?" );


        if(!$confirm){
            $this->error('Operation canceled');
            exit;
        };


        $comment = $this->ask('Add comment');


        $tableNames = $tableIDs[0] == 0
            ? []
            : array_map(function($id) use ($tables){
                return $tables[$id];
            }, $tableIDs);


        // add comment string to the file
        $line = '-- ' . json_encode([
                'tables' => implode(', ', array_map(function($id) use ($tables){
                    return $tables[$id];
                }, $tableIDs)),
                'comment' => $comment
            ]);


        $DBController->backup($tableNames, $line);

        $this->info('backup operation finished');
    }


}
