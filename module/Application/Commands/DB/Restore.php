<?php

namespace Module\Application\Commands\DB;

use Illuminate\Console\Command;
use Module\Application\Controllers\Logic\DBController;


class Restore extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:restore';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Restore Database';



    public function handle(DBController $DBController)
    {
        // get files and ask user to select
        $databases = $DBController->getDatabaseFiles();

        foreach($databases as $id => $database){
            $this->comment("[{$id}] {$database['name']} | comment: {$database['comment']} | tables: {$database['tables']}");
        }
        $id = $this->ask('Select database to restore');

        if(!preg_match('#^[\d]+$#', $id)){
            $this->error('Invalid database ID');
            exit;
        }

        // confirm, and ask if migrate is done
        $chosen = $databases[$id];

        $confirm = $this->confirm("You have chosen table(s) to restore from {$chosen['name']}:\n[{$chosen['tables']}]\n\nMake sure you have finished migration, if not, operation cannot be reversed.\nProceed?");

        if(!$confirm){
            $this->error('Operation canceled');
            exit;
        }

        $dbFile = $chosen['path'];

        $DBController->restore($dbFile);

        $this->info('restore operation finished');

    }

}
