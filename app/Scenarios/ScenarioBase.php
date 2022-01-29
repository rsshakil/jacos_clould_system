<?php

namespace App\Scenarios;

use App\Http\Controllers\API\AllUsedFunction;
use App\Exceptions\JcsException;

use App\Models\BYR\byr_buyer;

use App\Scenarios\Common;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use Symfony\Component\HttpFoundation\Response;

class ScenarioBase
{
    public $success;
    public $error;
    private $common_class_obj;

    public function __construct()
    {
        $this->success = config('const.SUCCESS');
        $this->error = config('const.ERROR');
        $this->common_class_obj = new Common();
    }

    public function upfileSave($request, $saveDir)
    {
        Log::debug(__METHOD__.':start---');

        if (!array_key_exists('up_file', $request->all())) {
            throw new JcsException('File not found or file path not valid');
        }

        // ファイルアップロード
        $file_name = time() . '-' . $request->file('up_file')->getClientOriginalName();
        $path = $request->file('up_file')->storeAs($saveDir, $file_name);
        $cmn_connect_id = $this->get_connect_id_from_file_name($file_name);

        $save_path = storage_path() . '/app//' . $saveDir . '/' . $file_name;
        Log::info('save file path:' . $save_path);

        Log::debug(__METHOD__.':end---');
        return ['file_name'=>$file_name,'save_path'=>$save_path,'cmn_connect_id'=>$cmn_connect_id];
    }

    /**
     * get_connect_id_from_file_name
     *
     * @param  mixed $file_name
     * @return void
     */
    private function get_connect_id_from_file_name($file_name)
    {
        Log::debug(__METHOD__.':start---');
        $name_array = explode('-', $file_name);
        // \Log::info($file_name);
        // \Log::info($name_array);
        $super_code = $name_array[1];
        $partner_code = $name_array[2];

        $cmn_connect_id = '';
        $connect_info = byr_buyer::select('cmn_connects.cmn_connect_id')
            ->join('cmn_connects', 'cmn_connects.byr_buyer_id', '=', 'byr_buyers.byr_buyer_id')
            ->where(['cmn_connects.partner_code' => $partner_code, 'byr_buyers.super_code' => $super_code])
            ->first();
        if (empty($connect_info)) {
            throw new JcsException('Can not get connect_info: super_code:'.$super_code.' partner_code:'. $partner_code);
        }

        Log::debug(__METHOD__.':end---');
        return $connect_info->cmn_connect_id;
    }

    /**
     * checkCsvData
     *
     * @param  mixed $dataLen
     * @return void
     */
    public function checkCsvData($dataArr, $dataLen)
    {
        // data check
        if (count($dataArr) === 0) {
            // 空データ
            throw new JcsException('Csv data is empty');
        } elseif (count($dataArr[0]) !==$dataLen) {
            throw new JcsException('not match column count');
        }
    }
}
