<?php
namespace Module\Application\Controllers\Logic;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class DBController {


    // Service Provider Seeder

    protected $seeders = [];

    public function addSeeder($seederClass)
    {
        $this->seeders[] = $seederClass;
    }

    public function getSeeders()
    {
        return $this->seeders;
    }










    // Real truncate table so id goes back to 0

    public function truncateAllTables()
    {
        $dir = dirname(__DIR__) . '/migrations';

        if (env('DB_CONNECTION') == 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        }

        foreach (glob($dir . '/' . '*_create_*_table.php') as $file) {
            $match = null;
            if (preg_match('#_create_(.+?)_table.php$#', basename($file), $match)) {
                $table = $match[1];

                if (env('DB_CONNECTION') == 'mysql') {
                    DB::table($table)->truncate();
                } elseif (env('DB_CONNECTION') == 'sqlsrv') {
                    try {
                        DB::statement("DBCC CHECKIDENT ('$table', RESEED, 1)");
                    } catch (\Exception $e) {
                    }
                }
            }

        }


        if (env('DB_CONNECTION') == 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS = 1');
        }

        return $this;
    }




    // DB Backup and Restore

    public function backup($tables, $comment=null)
    {
        // backup
        $location = base_path('database/backups');
        if(!is_dir($location)) mkdir($location);

        $host = env('DB_HOST');
        $port = env('DB_PORT');
        $db = env('DB_DATABASE');
        $user = env('DB_USERNAME');
        $pass = env('DB_PASSWORD');
        $sqlPath = $location .'/'. date('Y_m_d_His_') . $db . '.sql';

        $routines = !count($tables)? ' --routines' : '';
        $tables = !count($tables)? '': ' ' . implode(' ', $tables);

        $command = "mysqldump --host={$host} --port={$port} --user={$user} --password={$pass}{$routines} {$db}{$tables} >  {$sqlPath}";

        shell_exec($command);

        if($comment){
            file_put_contents($sqlPath, $comment, FILE_APPEND);
        }
    }




    public function restore($dbFile)
    {
        // restore
        $host = env('DB_HOST');
        $port = env('DB_PORT');
        $db = env('DB_DATABASE');
        $user = env('DB_USERNAME');
        $pass = env('DB_PASSWORD');

        $command = "mysql --host={$host} --port={$port} --user={$user} --password={$pass} {$db} < {$dbFile}";

        shell_exec($command);
    }





    public function getDatabaseFiles()
    {
        $folder = base_path('database/backups');
        $files = glob($folder . '/*.sql');

        return array_map(function($file){
            $line = $this->readLastLine($file);

            if(preg_match('#^-- {#', $line)){
                $line = json_decode(str_replace('-- ', '', $line));
                $tables = $line->tables;
                $comment = $line->comment;
            }
            else {
                $tables = 'unknown';
                $comment = 'unkonwn';
            }

            return [
                'path' => $file,
                'name' => str_replace('.sql', '', basename($file)),
                'tables' => $tables,
                'comment' => $comment
            ];
        }, $files);
    }




    public function getTableNames()
    {

        $database = Config::get('database.connections.mysql.database');
        $tables = DB::select('SHOW TABLES');
        $combine = "Tables_in_".$database;

        $collection = ['* ALL Tables'];

        foreach($tables as $table){
            $collection[] = $table->$combine;
        }

        return $collection;
    }



    protected function readLastLine($file)
    {
        $fp = @fopen($file, "r");
        $pos = -1;
        $t = " ";
        while ($t != "\n") {
            fseek($fp, $pos, SEEK_END);
            $t = fgetc($fp);
            $pos = $pos - 1;
        }
        $t = fgets($fp);
        fclose($fp);
        return $t;
    }



}