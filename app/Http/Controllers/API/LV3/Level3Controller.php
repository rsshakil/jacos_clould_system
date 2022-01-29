<?php

namespace App\Http\Controllers\API\LV3;

use App\Http\Controllers\API\AllUsedFunction;
use App\Http\Controllers\API\CMN\CmnScenarioController;
use App\Http\Controllers\Controller;
use App\Models\ADM\User;
use App\Models\BYR\byr_buyer;
use App\Models\CMN\cmn_scenario;
use App\Models\CMN\cmn_companies_user;
use App\Models\LV3\lv3_history;
use App\Models\LV3\lv3_job;
use App\Models\LV3\lv3_service;
use App\Models\LV3\lv3_trigger_file_path;
use App\Models\LV3\lv3_trigger_schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;

use function GuzzleHttp\json_decode;

class Level3Controller extends Controller
{
    private $message;
    private $status_code;
    private $class_name;
    private $lst_customer_id;
    private $lst_service_id;
    private $flag;
    private $all_functions;
    public function __construct()
    {
        $this->message = '';
        $this->status_code = '';
        $this->class_name = '';
        $this->lst_customer_id = '';
        $this->lst_service_id = '';
        $this->flag = '';
        $this->all_functions = new AllUsedFunction();
    }
    public function userLogin(Request $request)
    {
        Log::debug(__METHOD__.':start---');
        $email = $request->user_name;
        $password = $request->password;
        if (User::where('email', '=', $email)->exists()) {
            $user = User::where('email', '=', $email)->first();
            if (Hash::check($password, $user->password)) {
                Log::debug(__METHOD__.':end---');
                return response()->json(['message' => 'success', 'class_name' => 'alert-success', 'user_id' => $user->id, 'user_name' => $user->name]);
            } else {
                Log::debug(__METHOD__.':end---');
                return response()->json(['message' => "passwordがありません。properties.fileを確認してください。", 'class_name' => 'alert-danger']);
            }
        } else {
            Log::debug(__METHOD__.':end---');
            return response()->json(['message' => "emailがありません。properties.fileを確認してください。", 'class_name' => 'alert-danger']);
        }
    }

    public function historyData(Request $request)
    {
        Log::debug(__METHOD__.':start---');
        $user_id = $request->user_id;
        $all_history = lv3_history::select(
            'lv3_histories.lv3_history_id',
            'lv3_histories.execute_type',
            'lv3_histories.execute_date',
            'lv3_histories.status',
            'lv3_histories.message',
            'lv3_histories.created_at',
            'lv3_histories.updated_at',
            'lv3_services.lv3_service_id',
            'lv3_services.service_name',
            'cmn_companies.company_name',
            'cmn_connects.partner_code'
        )
            ->join('lv3_services', 'lv3_histories.lv3_service_id', '=', 'lv3_services.lv3_service_id')
            ->join('cmn_connects', 'lv3_services.cmn_connect_id', '=', 'cmn_connects.cmn_connect_id')
            ->join('byr_buyers', 'byr_buyers.byr_buyer_id', '=', 'cmn_connects.byr_buyer_id')
            ->join('cmn_companies', 'cmn_companies.cmn_company_id', '=', 'byr_buyers.cmn_company_id')
            ->where('lv3_services.adm_user_id', $user_id)
            ->orderBy('lv3_histories.lv3_history_id', 'DESC')
            ->take(100)->get();
        Log::debug(__METHOD__.':end---');
        return \response()->json(['histories' => $all_history]);
    }

    public function getCustomer(Request $request)
    {
        Log::debug(__METHOD__.':start---');

        $customer_array=array();

        $user_id = $request->user_id;
        $customers_data = array();
        $authUser = User::find($user_id);
        //Buyer work
        $customers_data = cmn_companies_user::select(
            'cmn_companies_users.adm_user_id',
            'cmn_connects.cmn_connect_id',
            'cmn_connects.partner_code',
            'cmn_companies.company_name'
        )
            ->join('slr_sellers', 'slr_sellers.cmn_company_id', '=', 'cmn_companies_users.cmn_company_id')
            ->join('cmn_connects', 'cmn_connects.slr_seller_id', '=', 'slr_sellers.slr_seller_id')
            ->join('byr_buyers', 'byr_buyers.byr_buyer_id', '=', 'cmn_connects.byr_buyer_id')
            ->join('cmn_companies', 'cmn_companies.cmn_company_id', '=', 'byr_buyers.cmn_company_id')
            ->where('cmn_companies_users.adm_user_id', $user_id)->get();

        foreach ($customers_data as $key1 => $value) {
            $tmp['cmn_connect_id']=$value->cmn_connect_id;
            $tmp['partner_code']=$value->partner_code;
            $tmp['company_name']=$value->company_name;
            $request->request->add(['cmn_connect_id' => $value->cmn_connect_id]);
            $tmp['service_info']=array();
            // get service
            // $tmp['service_info']=$this->getService($user_id, $value->cmn_connect_id);
            $service_info =$this->getService($user_id, $value->cmn_connect_id);
            // Log::info("==================");
            // Log::info($service_info);
            // Log::info("==================");

            $tmp_array=array();
            foreach ($service_info as $key => $value) {
                $lv3_service_id=$value->lv3_service_id;
                $service_name=$value->service_name;

                $ret=$this->getServiceData($request, $lv3_service_id);
                $ret = json_decode($ret->getContent(), true);
                $service_data=$ret['service'];
                // Log::info("==================");
                // Log::info($service_data);
                // Log::info("==================");

                $tmp_array['lv3_service_id']=$lv3_service_id;
                $tmp_array['service_name']=$service_name;
                $tmp_array['service_data']=$service_data;
                $tmp['service_info'][]=$tmp_array;
            }
            $customer_array[]=$tmp;
        }
        Log::debug(__METHOD__.':end---');
        return response()->json(['customers_data' => $customer_array]);
    }

    private function getService($adm_user_id, $cmn_connect_id)
    {
        return lv3_service::select('lv3_service_id', 'service_name')
        ->where(['cmn_connect_id' => $cmn_connect_id, 'adm_user_id' => $adm_user_id])
        ->get();
    }

    public function addService(Request $request)
    {
        Log::debug(__METHOD__.':start---');

        $user_id = $request->user_id;
        $service_id = $request->service_id;
        $cmn_connect_id = $request->cmn_connect_id;
        $service_name = $request->service_name;
        $service_array = array(
            'cmn_connect_id' => $cmn_connect_id,
            'adm_user_id' => $user_id,
            'service_name' => $service_name,
        );
        if ($service_id != null) {
            $service_info = lv3_service::where('lv3_service_id', $service_id)->first();
            if ($service_info['service_name'] != $service_name) {
                if (lv3_service::where(['cmn_connect_id' => $cmn_connect_id, 'adm_user_id' => $user_id, 'service_name' => $service_name])->exists()) {
                    $this->message = 'このサービス名はすでに使われています。';
                    $this->status_code = 403;
                    $this->class_name = 'alert-danger';
                    return \response()->json(['message' => $this->message, 'status_code' => $this->status_code, 'class_name' => $this->class_name, 'lst_service_id' => $this->lst_service_id]);
                }
            }
            $this->message = '更新完了';
            $this->status_code = 200;
            $this->class_name = 'alert-success';
            $this->flag = 1;
            $this->lst_service_id = $service_id;
            lv3_service::where('lv3_service_id', $service_id)->update($service_array);
        } else {
            if (lv3_service::where(['cmn_connect_id' => $cmn_connect_id, 'adm_user_id' => $user_id, 'service_name' => $service_name])->exists()) {
                $this->message = 'Service name already exists';
                $this->status_code = 403;
                $this->class_name = 'alert-danger';
            } else {
                // 新規追加
                $this->lst_service_id = lv3_service::insertGetId($service_array);

                // lv3_trigger_file_paths
                $service_data = array(
                    'lv3_service_id' => $this->lst_service_id,
                );
                lv3_trigger_file_path::insert($service_data);

                // lv3_jobs
                lv3_job::insert($service_data);

                // lv3_trigger_schedules
                // 不要


                $this->message = '登録完了';
                $this->status_code = 200;
                $this->class_name = 'alert-success';
                $this->flag = 0;
            }
        }
        Log::debug(__METHOD__.':end---');

        return \response()->json(['message' => $this->message, 'status_code' => $this->status_code, 'class_name' => $this->class_name, 'flag' => $this->flag, 'lst_service_id' => $this->lst_service_id]);
    }

    public function scheduleData(Request $request)
    {
        Log::debug(__METHOD__.':start---');

        $user_id = $request->user_id;
        $service_id = $request->service_id;
        $schedule_data = lv3_trigger_schedule::where('lv3_service_id', $service_id)->get();

        $schedule_array = array();
        if (!empty($schedule_data)) {
            foreach ($schedule_data as $key => $value) {
                $test['schedule_id'] = $value->lv3_trigger_schedule_id;
                $test['service_id'] = $value->lv3_service_id;
                $test['day'] = $value->day;
                $test['weekday'] = str_split($this->all_functions->binary_format($this->all_functions->decimal_to_binary($value->weekday)));
                $test['time'] = $value->time;
                $test['last_day'] = $value->last_day;
                $test['disabled'] = $value->disabled;
                $test['original_weekday'] = $value->weekday;
                $schedule_array[] = $test;
            }
        }
        $file_path_info = $this->getFilePath($user_id, $service_id);
        $job_info = lv3_job::select(
            'lv3_job_id',
            'lv3_service_id',
            'job_execution_flag',
            'cmn_scenario_id',
            'execution',
            'batch_file_path',
            'next_service_id',
            'append'
        )
        ->where('lv3_service_id', $service_id)->first();
        // ===Scenario===
        $job_api_scenario_list=cmn_scenario::select(
            'cmn_scenarios.cmn_scenario_id',
            'cmn_scenarios.class',
            'cmn_scenarios.name',
            'cmn_scenarios.description'
        )
        ->join('adm_model_has_roles as amhr', 'cmn_scenarios.adm_role_id', '=', 'amhr.role_id')
        ->leftJoin('cmn_connects as cc', 'cc.byr_buyer_id', '=', 'cmn_scenarios.byr_buyer_id')
        ->join('lv3_services as lsb', 'lsb.cmn_connect_id', '=', 'cc.cmn_connect_id')
        ->where('amhr.model_id', $user_id)
        ->where('lsb.lv3_service_id', $service_id)
        ->union(cmn_scenario::select(
            'cmn_scenarios.cmn_scenario_id',
            'cmn_scenarios.class',
            'cmn_scenarios.name',
            'cmn_scenarios.description'
        )
        ->join('adm_model_has_roles as amhr', 'cmn_scenarios.adm_role_id', '=', 'amhr.role_id')
        ->leftJoin('cmn_connects as ccs', 'ccs.slr_seller_id', '=', 'cmn_scenarios.slr_seller_id')
        ->join('lv3_services as lss', 'lss.cmn_connect_id', '=', 'ccs.cmn_connect_id')
        ->where('amhr.model_id', $user_id)
        ->where('lss.lv3_service_id', $service_id))
        ->get();
        // ===Scenario===

        $all_service_data = lv3_service::select('lv3_service_id', 'service_name', 'cmn_connect_id')
            ->where('adm_user_id', $user_id)->get();

        $final_arr = array(
            'schedule_array' => $schedule_array,
            'file_path_info' => $file_path_info,
            'job_info' => $job_info,
            'job_api_scenario_list' => $job_api_scenario_list,
            'all_service_data' => $all_service_data,
        );
        Log::debug(__METHOD__.':end---');
        return response()->json($final_arr);
    }

    public function getFilePath($user_id, $service_id)
    {
        return lv3_trigger_file_path::select(
            'lv3_trigger_file_path_id',
            'lv3_service_id',
            'check_folder_path',
            'moved_folder_path',
            'api_scenario',
            'api_folder_path',
            'path_execution_flag',
            'api_execution_flag'
        )
        ->where('lv3_service_id', $service_id)->first();
    }

    public function setScheduleData(Request $request)
    {
        Log::debug(__METHOD__.':start---');
        $user_id = $request->user_id;
        $customer_id = $request->cmn_connect_id;
        $service_id = $request->service_id;
        $data_array = $request->data_array;
        $time_array = $request->time_array;
        $time_sp_array = $request->time_sp_array;
        $last_day_array = $request->last_day_array;
        $day_array = $request->day_array;

        $insert_first_array = array();
        $insert_second_array = array();
        for ($i = 0; $i < count($data_array); $i++) {
            $test_first['lv3_service_id'] = $service_id;
            $test_first['weekday'] = $this->all_functions->binary_to_decimal($data_array[$i]);
            $test_first['time'] = $time_array[$i];
            $test_first['disabled'] = 0;
            $insert_first_array[] = $test_first;
        }
        for ($j = 0; $j < count($last_day_array); $j++) {
            $test_second['lv3_service_id'] = $service_id;
            $test_second['day'] = $day_array[$j];
            $test_second['time'] = $time_sp_array[$j];
            $test_second['last_day'] = $last_day_array[$j];
            $test_second['disabled'] = 0;
            $insert_second_array[] = $test_second;
        }
        lv3_trigger_schedule::where('lv3_service_id', $service_id)->delete();
        lv3_trigger_schedule::insert($insert_first_array);
        lv3_trigger_schedule::insert($insert_second_array);
        Log::debug(__METHOD__.':end---');
        return response()->json(['message' => '更新完了', 'class_name' => 'alert-success']);
    }
    public function setFilePath(Request $request)
    {
        Log::debug(__METHOD__.':start---');
        // return $request->all();
        $user_id = $request->user_id;
        $service_id = $request->service_id;
        $path_execution_flag = $request->path_execution_flag;
        $api_execution_flag = $request->api_execution_flag;
        $file_source_path = $request->file_source_path;
        $file_move_path = $request->file_move_path;
        $api_scenario = $request->api_scenario;
        $api_folder_path = $request->api_folder_path;
        $file_path_array = array(
            'lv3_service_id' => $service_id,
            'check_folder_path' => $file_source_path,
            'moved_folder_path' => $file_move_path,
            'api_scenario' => $api_scenario==="null"?null:$api_scenario,
            'api_folder_path' => $api_folder_path,
            'path_execution_flag' => $path_execution_flag,
            'api_execution_flag' => $api_execution_flag,
        );
        if (lv3_trigger_file_path::where('lv3_service_id', $service_id)->exists()) {
            lv3_trigger_file_path::where('lv3_service_id', $service_id)->update($file_path_array);
        } else {
            lv3_trigger_file_path::insert($file_path_array);
        }
        Log::debug(__METHOD__.':end---');
        return \response()->json(['message' => 'ファイルパスを保存しました。', 'class_name' => 'alert-success']);
    }

    public function setJobData(Request $request)
    {
        Log::debug(__METHOD__.':start---');
        $job_id = $request->job_update_id;
        $service_id = $request->service_id;
        $cmn_scenario_id = $request->cmn_scenario_id;
        $batch_file_path = $request->batch_file_path;
        $job_execution_flag = $request->job_execution_flag;
        $execution = $request->execution;
        $next_service_id = $request->next_service_id;
        $job_array = array(
            'lv3_service_id' => $service_id,
            'cmn_scenario_id' => $cmn_scenario_id,
            'batch_file_path' => $batch_file_path,
            'job_execution_flag' => $job_execution_flag,
            'execution' => $execution,
            'next_service_id' => $next_service_id,
        );

        if ($job_id != null) {
            lv3_job::where('lv3_job_id', $job_id)->update($job_array);
            $this->message = 'jobを作成しました。';
            $this->status_code = 200;
            $this->class_name = 'alert-success';
        } else {
            lv3_job::insert($job_array);
            $this->message = 'jobを更新しました。';
            $this->status_code = 200;
            $this->class_name = 'alert-success';
        }
        Log::debug(__METHOD__.':end---');
        return response()->json(['message' => $this->message, 'status_code' => $this->status_code, 'class_name' => $this->class_name]);
    }
    public function lv3ScheduleData(Request $request)
    {
        $type = $request->type;
        $schedule_array = array();
        if ($type == 1) {
            $schedule_array = $this->scheduleTimeData($request);
        } elseif ($type == 2) {
            $schedule_array = $this->scheduleDateData($request);
        }
        return response()->json(['schedule_date_time_data' => $schedule_array]);
    }

    public function scheduleTimeData($request)
    {
        $service_id = $request->service_id;
        $trigger_execution_time = ($request->trigger_execution_time) / 1000;
        $today = date('w');
        $ret_arr = array();
        $schedule_time_data = lv3_trigger_schedule::select('lv3_trigger_schedule_id', 'weekday', 'time')->where('lv3_service_id', $service_id)
            ->where('weekday', '!=', 0)->get();

        $schedule_array = array();
        $active_weekday_array = array();
        foreach ($schedule_time_data as $key => $value) {
            $weekday = str_split($this->all_functions->binary_format($this->all_functions->decimal_to_binary($value->weekday)));
            $key_day = array_search('1', $weekday);

            foreach ($weekday as $key1 => $value1) {
                if ($value1 == 1) {
                    $active_weekday_array[] = $key1;
                }
            }

            $test['lv3_trigger_schedule_id'] = $value->lv3_trigger_schedule_id;
            $test['weekday'] = $active_weekday_array;
            $test['time'] = $value->time;
            $test['original_weekday'] = $value->weekday;
            $schedule_array[] = $test;
            if (in_array($today, $test['weekday'])) {
                array_push($ret_arr, $this->dateProcess($value, $trigger_execution_time));
            }
            $active_weekday_array = [];
        }
        return $ret_arr;
    }
    public function scheduleDateData($request)
    {
        $service_id = $request->service_id;
        $trigger_execution_time = ($request->trigger_execution_time) / 1000;
        $schedule_date_data = lv3_trigger_schedule::select('lv3_trigger_schedule_id', 'day', 'time', 'last_day')->where('lv3_service_id', $service_id)
            ->where('weekday', '=', 0)->get();
        $ret_arr = array();
        foreach ($schedule_date_data as $key => $data) {
            if ($data->last_day == 1) {
                array_push($ret_arr, $this->dateProcess($data, $trigger_execution_time));
            } elseif ($data->day != null) {
                $full_day = strtotime(date('y-m-' . $data->day));
                if (strtotime(date('y-m-d')) == $full_day) {
                    array_push($ret_arr, $this->dateProcess($data, $trigger_execution_time));
                }
            }
        }
        return $ret_arr;
    }
    public function dateProcess($data, $trigger_execution_time)
    {
        $cur_date_time = date('2013-11-13 H:i:s');
        $advance_time = date("Y-m-d H:i:s", (strtotime($cur_date_time) - $trigger_execution_time));
        $arr_time = date('2013-11-13 H:i:s', strtotime($data['time']));
        if (strtotime($arr_time) > strtotime($advance_time) && strtotime($arr_time) <= strtotime($cur_date_time)) {
            return true;
        } else {
            return false;
        }
    }
    public function getServiceData(Request $request, $lv3_service_id=null)
    {
        $service_id = $lv3_service_id==null?$request->service_id:$lv3_service_id;
        $service = lv3_job::select(
            'lv3_trigger_file_paths.lv3_trigger_file_path_id',
            'lv3_trigger_file_paths.lv3_service_id',
            'lv3_trigger_file_paths.check_folder_path',
            'lv3_trigger_file_paths.moved_folder_path',
            'lv3_trigger_file_paths.api_scenario',
            'cs.name as scenario_name',
            'lv3_trigger_file_paths.api_folder_path',
            'lv3_trigger_file_paths.path_execution_flag',
            'lv3_trigger_file_paths.api_execution_flag',
            'lv3_jobs.lv3_job_id',
            'lv3_jobs.lv3_service_id',
            'lv3_jobs.job_execution_flag',
            'lv3_jobs.execution',
            'lv3_jobs.cmn_scenario_id',
            'lv3_jobs.batch_file_path',
            'lv3_jobs.next_service_id',
            'lv3_jobs.append'
        )
            ->leftJoin('lv3_trigger_file_paths', 'lv3_trigger_file_paths.lv3_service_id', '=', 'lv3_jobs.lv3_service_id')
            ->join('cmn_scenarios as cs','cs.cmn_scenario_id','=','lv3_trigger_file_paths.api_scenario')
            ->where('lv3_jobs.lv3_service_id', $service_id)->first();
        return response()->json(['service' => $service]);
    }

    public function historyCreate(Request $request)
    {
        $service_id = $request->service_id;
        $status = $request->status;
        $history_message = $request->history_message;

        $execute_type = $request->process_type;
        if ($execute_type == "Auto") {
            $execute_type = "自動";
        } elseif ($execute_type == "Manual") {
            $execute_type = "手動";
        } else {
            $execute_type = "自動";
        }

        lv3_history::insert([
            'lv3_service_id' => $service_id,
            'execute_type' => $execute_type,
            'status' => $status,
            'message' => $history_message,
        ]);
        return \response()->json(['message' => 'success', 'status_code' => 200, 'class_name' => 'alert-success']);
    }

    public function jobScenario(Request $request)
    {
        Log::debug(__METHOD__.':start---');
        // return $request->all();
        $cs = new CmnScenarioController();
        $ret = $cs->exec($request);
        if ($this->error === $ret['status']) {
            // sceanario exec error
            Log::error($ret['message']);
        }
        Log::debug(__METHOD__.':end---');
        return response()->json($ret);
    }
    // public function getShipmentFile(Request $request)
    // {
    //     Log::debug(__METHOD__.':start---');

    //     // $url_path = Config::get('app.url') . 'storage/app/shipment_csv/moved/';
    //     $url_path = Config::get('app.url').'storage/app'.config('const.SHIPMENT_MOVED_CSV_PATH');
    //     // $path = \storage_path('/app/shipment_csv/');
    //     $path = \storage_path('app/'.config('const.SHIPMENT_SEND_CSV_PATH').'/');
    //     $files = array_values(array_diff(scandir($path), array('.', '..')));
    //     $file_name='';
    //     $file_path='';
    //     $checked_files=array();
    //     if (!empty($files)) {
    //         for ($i=0; $i < count($files); $i++) {
    //             if (is_file($path . $files[$i])) {
    //                 $checked_files[] = $files[$i];
    //             }
    //         }
    //     } else {
    //         $this->message = "フォルダが空です";
    //         $this->status_code = 400;
    //         Log::debug(__METHOD__.':end---');
    //         return \response()->json(['message' => $this->message, 'status_code' => $this->status_code, 'file_name' => $file_name,'file_path'=>$file_path]);
    //     }
    //     if (!empty($checked_files)) {
    //         $file_name = $checked_files[0];
    //         $file_path = $url_path . $checked_files[0];
    //         // rename($path . $checked_files[0], $path . 'moved/' . $checked_files[0]);
    //         rename($path . $checked_files[0], \storage_path('/app'.config('const.SHIPMENT_MOVED_CSV_PATH')) . $checked_files[0]);
    //         $this->message = "ファイルが見つかりました。";
    //         $this->status_code = 200;
    //     } else {
    //         $this->message = "ファイルが見つかりませんでした。";
    //         $this->status_code = 401;
    //     }
    //     Log::debug(__METHOD__.':end---');
    //     return \response()->json(['message' => $this->message, 'status_code' => $this->status_code, 'file_name' => $file_name,'file_path'=>$file_path]);
    // }
    // public function getInvoiceFile(Request $request)
    // {
    //     Log::debug(__METHOD__.':start---');

    //     // $url_path = Config::get('app.url') . 'storage/app/invoice_csv/moved/';
    //     $url_path = Config::get('app.url').'storage/app'.config('const.INVOICE_MOVED_CSV_PATH');
    //     $path = \storage_path('app/'.config('const.INVOICE_SEND_CSV_PATH').'/');
    //     // $path = \storage_path('/app/invoice_csv/');
    //     $files = array_values(array_diff(scandir($path), array('.', '..')));
    //     $file_name='';
    //     $file_path='';
    //     $checked_files=array();
    //     if (!empty($files)) {
    //         for ($i=0; $i < count($files); $i++) {
    //             if (is_file($path . $files[$i])) {
    //                 $checked_files[] = $files[$i];
    //             }
    //         }
    //     } else {
    //         $this->message = "フォルダが空です";
    //         $this->status_code = 400;
    //         Log::debug(__METHOD__.':end---');
    //         return \response()->json(['message' => $this->message, 'status_code' => $this->status_code, 'file_name' => $file_name,'file_path'=>$file_path]);
    //     }
    //     if (!empty($checked_files)) {
    //         $file_name = $checked_files[0];
    //         $file_path = $url_path . $checked_files[0];
    //         rename($path . $checked_files[0], \storage_path('/app'.config('const.INVOICE_MOVED_CSV_PATH')) . $checked_files[0]);
    //         $this->message = "ファイルが見つかりました。";
    //         $this->status_code = 200;
    //     } else {
    //         $this->message = "ファイルが見つかりませんでした。";
    //         $this->status_code = 401;
    //     }
    //     Log::debug(__METHOD__.':end---');
    //     return \response()->json(['message' => $this->message, 'status_code' => $this->status_code, 'file_name' => $file_name,'file_path'=>$file_path]);
    // }
    public function deleteService(Request $request)
    {
        Log::debug(__METHOD__.':start---');
        $service_id = $request->service_id;
        lv3_service::where('lv3_service_id', $service_id)->delete();
        $this->message = '削除が完了しました。';
        $this->status_code = 200;
        $this->class_name = 'alert-success';
        Log::debug(__METHOD__.':end---');
        return \response()->json(['message' => $this->message, 'status_code' => $this->status_code, 'class_name' => $this->class_name]);
    }

    public function job_list(Request $request)
    {
        return $request->all();
    }
}
