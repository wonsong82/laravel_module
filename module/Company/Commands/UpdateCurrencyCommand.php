<?php
namespace Module\Company\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Module\Company\Controllers\Admin\CurrencyRateCrudController;
use Module\Company\Currency;
use Module\Company\CurrencyRate;

class UpdateCurrencyCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:currency:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Currency values';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // get currencies
        // foreach currency -> set as base -> run API to get rate list->
        // filter list by currencies that are on the currency table.

        $currencies = Currency::all()->pluck('code','code')->toArray();

        foreach ($currencies as $currency){

            $client = new \GuzzleHttp\Client();

            $result = $client->get('https://api.exchangeratesapi.io/latest?base=' . $currency);

            $statusCode = $result->getStatusCode();

            if($statusCode == 200){

                $resultBody = json_decode($result->getBody(),true);

                $rates = $resultBody['rates'];

                $list =array_intersect_key($rates,$currencies);

                foreach ($list as $key => $value){

                    if($key !== $currency){

                        $newCurrencyDataEntry = new CurrencyRate();

                        $newCurrencyDataEntry->fill([
                            'base_currency_id' => Currency::where('code', $currency)->first()->id,
                            'quote_currency_id' => Currency::where('code', $key)->first()->id,
                            'pair' => $currency.'/'.$key,
                            'rate' => $value,
                            'date' => Carbon::now()
                        ])->save();

                    }

                }



            }else{
                //todo add exception handler here
                return false;
            }


        }

//        $ctl = new CurrencyRateCrudController();

    }


}
