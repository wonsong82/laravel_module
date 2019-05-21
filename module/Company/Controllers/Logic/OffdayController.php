<?php
namespace Module\Company\Controllers\Logic;


use Carbon\Carbon;
use Module\Company\Company;
use Module\Company\Offday;
use Module\Company\OffdayMonth;

class OffdayController
{

    public function generateOffdays($months = 3, Company $company = null)
    {
        $year = (int)date('Y');
        $month = (int)date('m');

        $start = new Carbon("$year-$month-1");
        $end = (clone $start)->addMonths($months)->addDays(-1);

        $saturdays = $this->getDaysInRange($start, $end, Carbon::SATURDAY);
        $sundays = $this->getDaysInRange($start, $end, Carbon::SUNDAY);


        $saturdays = $saturdays->map(function($day){
            $day->note = 'Saturday';
            return $day;
        });

        $sundays = $sundays->map(function($day){
            $day->note = 'Sunday';
            return $day;
        });


        $offdays = collect()->merge($saturdays)->merge($sundays)
            ->sortBy('date')
            ->groupBy('year')->map(function($year){
                return $year->groupBy('month');
            });

        if($company){
            $companies = collect([$company]);
        }
        else {
            $companies = Company::all();
        }


        foreach($companies as $company){
            foreach($offdays as $year => $months){
                foreach($months as $month => $days){
                    if(Offday::where('company_id', $company->id)->where('year', $year)->where('month', $month)->count() == 0){
                        foreach($days as $day){
                            $day->company_id = $company->id;
                            Offday::create((array)$day);
                        }
                    }
                }
            }
        }



    }




    public function getDaysInRange($start, $end, $day)
    {
        $start = (clone $start)->next($day);

        $days = [];
        for($date = $start; $date->lte($end); $date->addWeek()){
            $days[] = (object)[
                'date' => $date->format('Y-m-d'),
                'year' => (int)$date->format('Y'),
                'month' => (int)$date->format('m'),
                'day' => (int)$date->format('d')
            ];
        }

        return collect($days);
    }

}