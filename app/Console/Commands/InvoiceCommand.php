<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\API\DATA\INVOICE\InvoiceController;
use App\Models\CMN\cmn_connect;
use App\Http\Controllers\API\SCHEDULE\InvoiceScheduleFunctions;
use App\Models\ADM\User;
use App\Http\Controllers\API\AllUsedFunction;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class InvoiceCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    // protected $signature = 'invoice:scheduler {--start_date=00-00-00} {--end_date=00-00-00}';
    // protected $signature = 'invoice:scheduler {arg=0} {data_order_id=null}';
    protected $signature = 'invoice:scheduler {arg=0} {adm_user_id=0} {byr_buyer_id=0}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Invoice scheduler command';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    private $all_used_fun;
    private $invoice;
    private $global_functions;
    public function __construct()
    {
        parent::__construct();
        $this->all_used_fun = new InvoiceScheduleFunctions();
        $this->invoice=new InvoiceController();
        $this->global_functions=new AllUsedFunction();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $arg = $this->argument('arg');
        $adm_user_id = $this->argument('adm_user_id');
        $byr_buyer_id = $this->argument('byr_buyer_id');

        $cmn_connect_id = null;
        if ($arg!=0 && $adm_user_id!=0 && $byr_buyer_id!=0) {
            $slr_seller_id = Auth::User()->SlrInfo->slr_seller_id;
            $cmn_connects=cmn_connect::select('cmn_connect_id')->where('slr_seller_id',$slr_seller_id)->where('byr_buyer_id',$byr_buyer_id)->get();
            foreach ($cmn_connects as $key => $cmn_connect) {
                $this->invoiceSchedulerCode($arg, $cmn_connect->cmn_connect_id,$byr_buyer_id);
            }
            // $this->invoiceSchedulerCode($arg, $cmn_connect_id,$byr_buyer_id);
        } else {
            $cmn_connects=cmn_connect::select('cmn_connect_id')->get();
            foreach ($cmn_connects as $key => $cmn_connect) {
                $this->invoiceSchedulerCode($arg, $cmn_connect->cmn_connect_id,null);
            }
        }


        // Matched
    }
    public function invoiceSchedulerCode($arg, $cmn_connect_id=null,$byr_buyer_id=null)
    {
        Log::info("----invoiceSchedulerCode Starting----");
        $today=date('y-m-d');
        $todate=date('d');
        $previous_month_today = date('Y-m-'.$todate, strtotime(date('Y-m')." -1 month"));
        $last_date_of_this_month=$this->all_used_fun->closing_date('last');
        $first_day_of_month=date('y-m-01');
        $previous_month_last_day=date('Y-m-d', strtotime('last day of previous month'));
        $cmn_connect_info=cmn_connect::select('optional')->where('cmn_connect_id', $cmn_connect_id)->first();
        $optional=json_decode($cmn_connect_info->optional);
        $closing_date_array=$optional->invoice->closing_date;
        sort($closing_date_array);

        $closing_date_array=$this->all_used_fun->arra_sorting($closing_date_array);
        $closing_date_count=count($closing_date_array);
        $start_date=null;
        $end_date=null;
        // Manual
        if ($arg==1) {
            $closing_date_array_full=array();
            $end_date=$today;
            foreach ($closing_date_array as $key1 => $value) {
                $closing_date_array_full[]=$this->all_used_fun->closing_date($value);
            }
            if (count($closing_date_array_full)==1) {
                if (strtotime($closing_date_array_full[0])<strtotime($today)) {
                    $start_date = date('y-m-d', strtotime("+1 day", strtotime($closing_date_array_full[0])));
                }else{
                    $array_date_day=date('d',strtotime($closing_date_array_full[0]));

                    $array_date_previous_month=date('Y-m-'.$array_date_day, strtotime(date('Y-m')." -1 month"));
                    $start_date = date('y-m-d', strtotime("+1 day", strtotime($array_date_previous_month)));
                    // ===================
                    // if (date('d',strtotime($closing_date_array_full[0]))==date('d',strtotime($last_date_of_this_month))) {
                    //     $start_date= date('y-m-d', strtotime("+1 day", strtotime($previous_month_last_day)));
                    // }
                    // ==================
                    if (date('d',strtotime($previous_month_last_day))==31) {
                        $start_date = $this->dateSubAndCreate($closing_date_array_full[0],30); //For month day count 31: 31+1 days
                   }
                   if (date('d',strtotime($previous_month_last_day))==30) {
                        $start_date = $this->dateSubAndCreate($closing_date_array_full[0],29); //For month day count 30: 30+1 days
                   }
                   if (date('d',strtotime($previous_month_last_day))==29) {
                        $start_date = $this->dateSubAndCreate($closing_date_array_full[0],28); //For month day count 29: 29+1 days
                   }
                   if (date('d',strtotime($previous_month_last_day))==28) {
                        $start_date = $this->dateSubAndCreate($closing_date_array_full[0],27); // For month day count 28: 28+1 days
                   }
                }
            }else{
                foreach ($closing_date_array_full as $key => $closing_day) {
                    $first_date_of_array=$this->all_used_fun->closing_date($closing_date_array[array_key_first($closing_date_array)]);
                    $array_end_date=$this->all_used_fun->closing_date(end($closing_date_array));
                    $startDatedt = strtotime($closing_date_array_full[$key]);
                    $compareDate = strtotime($today);
                    // Test in closing date array
                    if (($key+1)!=count($closing_date_array_full)) {
                    // if (isset($closing_date_array_full[$key+1])) {
                        $endDatedt = strtotime($closing_date_array_full[$key+1]);
                        if ($compareDate > $startDatedt && $compareDate <= $endDatedt) {
                            if ($arg!=1) {
                                $this->comment($start_date);
                            }
                            $start_date = date('y-m-d', strtotime("+1 day", $startDatedt));
                            break;
                        }
                    }
                    // Test in out of closing date array
                    if ($start_date==null) {

                        $first_day_of_monthdt = strtotime($first_day_of_month);
                        $first_date_of_arraydt = strtotime($first_date_of_array);
                        // Test in before closing date array
                        if ($compareDate >= $first_day_of_monthdt && $compareDate <= $first_date_of_arraydt) {
                            $start_date=$first_day_of_monthdt;
                            if (date('d',strtotime($array_end_date))==date('d',strtotime($previous_month_last_day))) {
                                $start_date=$previous_month_last_day;
                            }else{
                            // if (date('d',strtotime($previous_month_last_day))==31 || date('d',strtotime($array_end_date))==31) {
                               if (date('d',strtotime($previous_month_last_day))==31) {
                                $start_date = $this->dateSubAndCreate($array_end_date,30); // For month day count 31: 31+1 days
                               }
                               if (date('d',strtotime($previous_month_last_day))==30) {
                                    $start_date = $this->dateSubAndCreate($array_end_date,29); // For month day count 30: 30+1 days
                               }
                               if (date('d',strtotime($previous_month_last_day))==29) {
                                    $start_date = $this->dateSubAndCreate($array_end_date,28); // For month day count 29: 29+1 days
                               }
                               if (date('d',strtotime($previous_month_last_day))==28) {
                                    $start_date = $this->dateSubAndCreate($array_end_date,27); // For month day count 28: 28+1 days
                               }
                            }
                            if ($start_date!=$end_date) {
                                $bbb=strtotime($start_date);
                                $start_date = date('y-m-d', strtotime("+1 day", $bbb));
                            }
                            break;
                        }
                        // Test in after of closing date array
                        $last_day_of_month= $this->all_used_fun->closing_date('last');
                        $last_day_of_monthdt = strtotime($last_day_of_month);
                        $array_end_datedt = strtotime($array_end_date);
                        if ($compareDate >= $array_end_datedt && $compareDate <= $last_day_of_monthdt) {
                            // $start_date = $array_end_date;
                            $start_date = date('y-m-d', strtotime("+1 day", $array_end_datedt));
                            break;
                        }
                    }
                }
            }
        }else{
            // Auto
            foreach ($closing_date_array as $key => $closing_day) {
                $closing_date= $this->all_used_fun->closing_date($closing_day);
                if ($closing_date==$today) {
                    $end_date=$closing_date;
                    if ($closing_date_count>1) {
                        $array_key=array_search($closing_day, $closing_date_array);
                        if ($array_key==0) {
                            $array_end_date=$this->all_used_fun->closing_date(end($closing_date_array));
                            $start_date=$this->all_used_fun->first_start_date($array_end_date, $closing_date);
                        } else {
                            $start_date=$this->all_used_fun->another_start_date($closing_date_array[$key-1]);
                        }
                    } else {
                        $start_date=$this->all_used_fun->start_date($closing_date, 1);
                    }
                }
            }
        }
// Matched
        if ($arg!=1) {
            $this->comment("Invoice Running.....");
            $this->comment("cmn_connect: ".$cmn_connect_id);
            $this->comment("Start Date: ".$start_date);
            $this->comment("End Date: ".$end_date);
        }
        if ($start_date!=null && $end_date!=null) {
            $request = new \Illuminate\Http\Request();
            $request->setMethod('POST');
            // $request=$this->request;
            $request->request->add(['scenario_id' => 15]);
            $request->request->add(['arg' => $arg]);
            $request->request->add(['byr_buyer_id' => $byr_buyer_id]);
            $request->request->add(['cmn_connect_id' => $cmn_connect_id]);
            $request->request->add(['start_date' => $start_date]);
            $request->request->add(['end_date' => $end_date]);
            $aaa=$this->invoice->invoiceScheduler($request);
            if ($arg!=1) {
                $this->comment("Done");
            }
            return $aaa;
        }
        Log::info("----invoiceSchedulerCode end----");
    }
    public function dateSubAndCreate($array_date,$days){
        $date = date_create($array_date);
        date_sub($date, date_interval_create_from_date_string($days." days"));
        $start_date = date_format($date, "y-m-d");
        return $start_date;
    }
}
