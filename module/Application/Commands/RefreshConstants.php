<?php

namespace Module\Application\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Module\Application\Constant;
use Module\Application\ConstantHeader;

class RefreshConstants extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:constant:refresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh Constants';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        DB::statement("SET foreign_key_checks=0");
        ConstantHeader::truncate();
        Constant::truncate();
        DB::statement("SET foreign_key_checks=1");
        Artisan::call('db:seed', ['--class' => 'Module\Application\Database\Seeds\App\ConstantsSeeder']);
    }


}
