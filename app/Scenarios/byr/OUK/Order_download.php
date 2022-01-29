<?php

namespace App\Scenarios\byr\OUK;

use App\Scenarios\Common;
use App\Http\Controllers\API\CMN\CmnScenarioController;
use App\Http\Controllers\API\DATA\Data_Controller;

use Illuminate\Database\Eloquent\Model;
use App\Models\CMN\cmn_connect;
use App\Models\CMN\cmn_category;
use App\Models\DATA\ORD\data_order;
use App\Traits\Csv;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class Order_download extends Model
{
    private $all_functions;
    private $common_class_obj;
    public function __construct()
    {
        $this->common_class_obj = new Common();
    }

    //
    public function exec($request, $sc)
    {
        Log::debug(get_class().' exec start  ---------------');

        // ダウンロード形式取得
        // cmn_connectsより[order_download]のjob_idを取得
        // 取得できない場合、標準BMS基準CSVダウンロード
        // job_id指定の場合はjob_id実行
        // ダウンロードデータ取得

        Log::debug($request->all());
        // セッション確認
        if ($request->session()->has('byr_buyer_id')) {
            // セッションよりbyr_buyer_id
            $byr_buyer_id=$request->session()->get('byr_buyer_id');
        } else {
            // postデータよりbyr_buyer_id
            $byr_buyer_id=$request->get('byr_buyer_id');
        }




        // 追加情報取得　ダウンロード形式
        $cc=cmn_connect::select(
            'optional'
        )
        ->join('slr_sellers', 'slr_sellers.slr_seller_id', '=', 'cmn_connects.slr_seller_id')
        ->join('cmn_companies_users', 'cmn_companies_users.cmn_company_id', '=', 'slr_sellers.cmn_company_id')
        ->where('cmn_companies_users.adm_user_id', Auth::id())
        ->where('cmn_connects.byr_buyer_id', $byr_buyer_id)
        ->first();
        if (!$cc) {
            // 追加情報取得エラー
            Log::error('Can not get [optional]');
            return ['status'=>1, 'message' => 'Can not get [optional]'];
        }
        Log::debug($cc->optional);

        // ダウンロード形式
        if ($cc->optional) {
            // ダウンロード形式指定
            Log::debug(json_decode($cc->optional, true));
            // シナリオID取得
            $cmn_scenario_id = json_decode($cc->optional, false)->order->download;
            Log::debug('download format scenario:'.$cmn_scenario_id);

            // 実行中シナリオチェック(無限ループ回避)
            if ($cmn_scenario_id === $request->scenario_id) {
                Log::error('Can not use same scenario:'.$cmn_scenario_id);
                return ['status'=>1, 'message' => 'Can not use same scenario:'.$cmn_scenario_id];
            }

            $req2 = $request;
            $req2->merge([
                'scenario_id' => $cmn_scenario_id,
            ]);

            // シナリオ実行
            $cs = new CmnScenarioController();
            $ret = $cs->exec($req2);
            Log::debug($ret->getContent());
            // \Log::debug($ret->getContent());
            $ret = json_decode($ret->getContent(), true);
            if (1 === $ret['status']) {
                // sceanario exec error
                Log::error($ret['message']);
                return $ret;
            }
            $fileName = $ret['file_name'];
            $filePath = $ret['file_path'];
            $headers = [
                'Content-Type' => 'text/plain;charset=Shift_JIS',
                'Content-Disposition' => 'attachment; filename="'.$fileName .'"'
            ];

            return ['file_link'=>$ret['url']];
        } else {
            // デフォルトCSV形式
            $od = Data_Controller::get_order_data($request);
            $fileName = '_'.date('YmdHis').'.csv';
            $filePath = 'app/temp/'.$fileName;
            $filePath = Csv::createCsv($filePath);
            Csv::writeAll($filePath, $od);
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="'.$fileName .'"'
            ];
        }

        Log::debug($filePath);
        // \Log::debug(file_get_contents($filePath));
        Log::debug(get_class().' exec end    ---------------');
        // return response()->streamDownload(function () {
        //     file_get_contents('C:\\mylog.log');
        // }, 200, $headers);
        return response()->download($filePath, $fileName, $headers);
        // return response()->download($filePath);
    }
}
