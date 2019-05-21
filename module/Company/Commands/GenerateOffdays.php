<?php
namespace Module\Company\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Module\Company\Controllers\Logic\OffdayController;

class GenerateOffdays extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:company:offday:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Populate 3 months offdays';

    /**
     * Execute the console command.
     *
     * @param OffdayController $offdayController
     * @return mixed
     */
    public function handle(OffdayController $offdayController)
    {
        DB::beginTransaction();
        $offdayController->generateOffdays(3);
        DB::commit();
    }


}
