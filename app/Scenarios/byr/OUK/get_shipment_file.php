<?php

namespace App\Scenarios\byr\OUK;

use App\Scenarios\ScenarioBase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class get_shipment_file extends ScenarioBase
{
    private $message;
    private $status_code;
    public function __construct()
    {
        parent::__construct();
        $this->message='';
        $this->status_code='';
    }

    //
    public function exec($request, $sc)
    {
        Log::debug(__METHOD__.':start---');
        // $url_path = Config::get('app.url') . 'storage/app/shipment_csv/moved/';
        $url_path = Config::get('app.url').'storage/app'.config('const.SHIPMENT_MOVED_CSV_PATH');
        $path = \storage_path('app/'.config('const.SHIPMENT_SEND_CSV_PATH').'/');
        $files = array_values(array_diff(scandir($path), array('.', '..')));
        $file_name='';
        $file_path='';
        $checked_files=array();
        if (!empty($files)) {
            for ($i=0; $i < count($files); $i++) {
                if (is_file($path . $files[$i])) {
                    $checked_files[] = $files[$i];
                }
            }
        } else {
            $this->message = "フォルダが空です";
            $this->status_code = $this->error;
            Log::debug(__METHOD__.':end---');
            return ['message' => $this->message, 'status' => $this->status_code,'data' => ['file_name' => $file_name,'file_path'=>$file_path]];
        }
        if (!empty($checked_files)) {
            $file_name = $checked_files[0];
            $file_path = $url_path . $checked_files[0];
            // rename($path . $checked_files[0], $path . 'moved/' . $checked_files[0]);
            rename($path . $checked_files[0], \storage_path('/app'.config('const.SHIPMENT_MOVED_CSV_PATH')) . $checked_files[0]);
            $this->message = "ファイルが見つかりました。";
            $this->status_code = $this->success;
        } else {
            $this->message = "ファイルが見つかりませんでした。";
            $this->status_code = $this->error;
        }
        Log::debug(__METHOD__.':end---');
        return ['message' => $this->message, 'status' => $this->status_code,'data' => ['file_name' => $file_name,'file_path'=>$file_path]];
    }
}
