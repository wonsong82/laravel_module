<?php
namespace Module\Company\Traits;

trait YearMonthCodeTrait {


    protected static function generateYearCode($year)
    {
        $obj = new static();
        return $obj->getYearCode($year);
    }

    protected static function generateMonthCode($month)
    {
        $obj = new static();
        return $obj->getMonthCode($month);
    }


    protected function getYearCode($year)
    {
        $years = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $startYear = 2015;

        $index = (int)$year - $startYear;

        return $years[$index];
    }

    protected function getYears()
    {
        $year = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $startYear = 2015;
        $years = [];

        for($i=0;$i<strlen($year);$i++){
            $years[$startYear]= $startYear;
            $startYear++;
        }

        return $years;
    }

    protected function getMonthCode($month)
    {
        $months = '123456789XYZ';

        $index = (int)$month - 1;

        return $months[$index];
    }


    protected function getYear($year_code)
    {
        $years = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $startYear = 2015;

        $year = (int)strpos($years, $year_code) + $startYear;

        return $year;
    }

    protected function getMonth($month_code)
    {
        $months = '123456789XYZ';

        $month = (int)strpos($months, $month_code) + 1;

        return $month;
    }


}
