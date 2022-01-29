<?php

namespace App\Http\Controllers\API\SCHEDULE;

use App\Http\Controllers\Controller;

class InvoiceScheduleFunctions extends Controller
{

    public function arra_sorting($closing_date_array){
        $new_array=array();
        $last_val=0;
        foreach ($closing_date_array as $key => $value) {
            if ($value!='last') {
                $new_array[]=$value;
            }else{
                $last_val=1;
            }
        }
        if ($last_val==1) {
            $new_array[]='last';
        }
        return $new_array;
    }

    public function closing_date($closing_day)
    {
        if ($closing_day == 'last') {
            $month_end = strtotime('last day of this month', time());
            $closing_date = date('y-m-d', $month_end);
        } else {
            $closing_date = date('y-m-' . str_pad($closing_day, 2, '0', STR_PAD_LEFT));
        }
        return $closing_date;
    }
    public function first_start_date($array_end_date, $array_first_date)
    {
        $first_date = date('y-m-01');
        $date_first_part = $this->my_date_diff($first_date, $array_first_date);
        $last_day = date('y-m-d', strtotime('last day of this month', time()));
        $date_last_part = $this->my_date_diff($last_day, $array_end_date);
        $total = $date_first_part + $date_last_part;
        $total = $total > 0 ? $total : 1;
        $date = date_create($array_first_date);
        date_sub($date, date_interval_create_from_date_string($total . " days"));
        $start_date = date_format($date, "y-m-d");
        return $start_date;
    }
    public function another_start_date($date)
    {
        // $date1 = $this->closing_date($date1);
        $date = $this->closing_date($date);
        $start_date = date('y-m-d', strtotime("+1 day", strtotime($date)));
        // $start_date=$this->start_date($date, 1);
        // $day_diff = ($this->my_date_diff($date1, $date2));
        // $start_date = date('y-m-' .str_pad($day_diff, 2, '0', STR_PAD_LEFT));

        return $start_date;
    }
    public function start_date($closing_date, $day)
    {
        $previous_month=date('y-m-d', strtotime($closing_date." -1 month"));
        $start_date = date('y-m-d', strtotime("+" . $day . " day", strtotime($previous_month)));
        return $start_date;
    }
    public function my_date_diff($date1, $date2)
    {
        $diff = strtotime($date1) - strtotime($date2);
        // 1 day = 24 hours
        // 24 * 60 * 60 = 86400 seconds
        $date_diff = abs(round($diff / 86400));
        return $date_diff;
    }
}
