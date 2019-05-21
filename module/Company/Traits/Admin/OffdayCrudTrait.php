<?php

namespace Module\Company\Traits\Admin;



use Carbon\Carbon;

trait OffdayCrudTrait
{


    public function btnDays($entry)
    {
        $route = route('company::crud.offday/{month}/days.index', ['month' => $entry->year . sprintf('%02d', $this->month)]);

        return '<a class="btn btn-xs btn-primary" href="'.$route.'"><span class="fa fa-calendar"></span>&nbsp;Off-Days</a>';
    }


    public static function getDayOption($year, $month)
    {
        $lastDay = Carbon::parse("$year-$month-01")->addMonth()->addDays(-1);

        $days = [];
        for($i=1; $i<=(int)$lastDay->format('d'); $i++){
            $days[] = $i;
        }

        $offs = static::where('year', $year)->where('month', $month)->get()->pluck('day');
        $diffs = collect($days)->diff($offs);

        $options = [];
        foreach($diffs as $diff)
            $options[$diff] = $diff;

        return $options;
    }






}
