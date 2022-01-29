<?php

namespace App\Http\Controllers\API\CMN;

use App\Http\Controllers\Controller;
use App\Http\Controllers\API\CMN\CmnScenarioHistoryController;
use App\Exceptions\JcsException;
use Illuminate\Http\Request;
use App\Models\CMN\cmn_scenario;
use App\Models\CMN\cmn_scenario_history;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class CmnScenarioController extends Controller
{
    private $sc_his;

    public function __construct()
    {
        parent::__construct();
        $this->sc_his = new CmnScenarioHistoryController;
    }

    /**
     * exec
     *
     * @param  mixed $request
     * @return void
     */
    public function exec(Request $request)
    {
        Log::debug(__METHOD__.':start---');

        // ユーザチェック
        // if ($request->scenario_id!=6 || $request->scenario_id!=15 || $request->scenario_id!=17) {
        if ($request->scenario_id!=15) { //scenario_id=15 is for invoice command
        // if (!Auth::user()) {
            if($request->has('email')|| $request->has('password')){
            $this->validate($request, ['email' => 'required|email', 'password' => 'required']);
            $user = ['email' => $request->get('email'),'password'=>$request->get('password')];
            if (!Auth::attempt($user)) {
                // ログインエラー
                return $this->sc_his->history_create($this->error, "Authentication faild");
            }
        } else {

        }
    }


        // Permissionチェック
        // TODO

        // 実行ユーザー
        $this->sc_his->sc_history_array['adm_user_id']=Auth::id();

        // シナリオ情報取得
        $cmn_scenario_id=$request->get('scenario_id');
        $cmn_scenario_name=$request->get('scenario_name');

        try {
            $sc = $this->getScenarioInfo($cmn_scenario_id, $cmn_scenario_name);

            // ファイル読み込み
            $customClassPath = "\\App\\";
            $nw_f_pth = explode('/', $sc->file_path);
            foreach ($nw_f_pth as $p) {
                $customClassPath .= $p.'\\';
            }
            $customClassPath = rtrim($customClassPath, "\\");
            $sc_obj = new $customClassPath;

            // シナリオ実行function存在チェック
            if (!method_exists($sc_obj, 'exec')) {
                // exec functionが存在しない場合
                throw new JcsException('Scenario exec function is not exist!');
            }

            $this->sc_his->sc_history_array['cmn_scenario_id']=$sc->cmn_scenario_id;
            $this->sc_his->sc_history_array['data']=$sc->class;

            // シナリオ実行
            $ret = $sc_obj->exec($request, $sc);
            // シナリオ結果
            if (($ret)&&(isset($ret['status']))&&($ret['status'] !== $this->success)) {
                // error
                // return ['status'=>$this->error, 'message'=>$ret['message']];
                throw new JcsException($ret['message']);
            }
            // 正常終了
            if (isset($ret['cmn_connect_id'])) {
                // cmn_connect_idセット
                $this->sc_his->sc_history_array['cmn_connect_id']=$ret['cmn_connect_id'];
            }
            if (isset($ret['data_id'])) {
                // data_idセット
                $this->sc_his->sc_history_array['data_id']=$ret['data_id'];
            }

            // hisotry
            $this->sc_his->history_create($this->success, $ret['message']);
        } catch (\Exception $th) {
            // JCS_EXCEPTION判定
            if ($th->getCode() != config('const.JCS_EXCEPTION')) {
                // JCS_EXCEPTION以外のPHP Exceptionはトレースログ出力
                Log::error($th);
            }

            // 異常終了
            $this->sc_his->history_create($this->error, $th->getMessage());
            return ['status'=>$this->error, 'message'=>$th->getMessage()];
        } finally {
            Log::debug(__METHOD__.':end---');
        }

        $ret_data = '';
        if (isset($ret['data'])) {
            $ret_data = $ret['data'];
        }
        return ['status'=>$this->success, 'message'=>$ret['message'],'data'=>$ret_data];
    }

    private function getScenarioInfo($cmn_scenario_id, $cmn_scenario_name)
    {
        Log::debug(__METHOD__.':start---');
        if ($cmn_scenario_id) {

            // scenario info check
            $sc = cmn_scenario::where('cmn_scenario_id', $cmn_scenario_id)->first();
        } elseif ($cmn_scenario_name) {
            // シナリオ名指定
            // $cmn_scenario_name よりシナリオ取得
            Log::debug('cmn_scenario_name:'.$cmn_scenario_name);

            // scenario info check
            $sc = cmn_scenario::where('name', $cmn_scenario_name)->first();
        } else {
            throw new JcsException("Not scenario select".' $cmn_scenario_id:'.$cmn_scenario_id.' $cmn_scenario_name:'.$cmn_scenario_name);
        }

        // シナリオチェック
        if (empty($sc)) {
            throw new JcsException('No scenario found'.' $cmn_scenario_id:'.$cmn_scenario_id.' $cmn_scenario_name:'.$cmn_scenario_name);
        }
        // シナリオファイル存在チェック
        if (!file_exists(app_path().'/'.$sc->file_path.'.php')) {
            throw new JcsException('Scenario file is not exist!'.$sc->file_path);
        }

        Log::debug(__METHOD__.':end---');
        return $sc;
    }

    public function get_scenario_list()
    {
        $result = cmn_scenario::select('cmn_scenarios.*', 'byr_buyers.super_code')
        ->leftJoin('byr_buyers', 'byr_buyers.byr_buyer_id', '=', 'cmn_scenarios.byr_buyer_id')
        ->leftJoin('slr_sellers', 'slr_sellers.slr_seller_id', '=', 'cmn_scenarios.slr_seller_id')
        ->get();
        return response()->json(['scenario_list'=>$result]);
    }

    public function getScenarioHistoryList(Request $request)
    {
        $per_page = $request->per_page == null ? 10 : $request->per_page;
        $search_where = array();
        $sort_by = $request->sort_by;
        $sort_type = $request->sort_type;
        $table_name = 'cmn_scenario_histories.';
        if($sort_by=='super_code'){
            $table_name ='byr_buyers.';
        }else if($sort_by=='partner_code'){
            $table_name ='cmn_connects.';
        }else if($sort_by=='company_name'){
            $table_name ='cmn_companies.';
        }else if($sort_by=='name'){
            $table_name ='cmn_scenarios.';
        }
        $result = cmn_scenario_history::select('cmn_scenarios.*','cmn_scenario_histories.*', 'byr_buyers.super_code','cmn_connects.partner_code','cmn_companies.company_name')
        ->join('cmn_scenarios', 'cmn_scenario_histories.cmn_scenario_id', '=', 'cmn_scenarios.cmn_scenario_id')
        ->join('cmn_connects', 'cmn_scenario_histories.cmn_connect_id', '=', 'cmn_connects.cmn_connect_id')
        ->join('byr_buyers', 'byr_buyers.byr_buyer_id', '=', 'cmn_connects.byr_buyer_id')
        ->join('slr_sellers', 'slr_sellers.slr_seller_id', '=', 'cmn_connects.slr_seller_id')
        ->join('cmn_companies', 'cmn_companies.cmn_company_id', '=', 'slr_sellers.cmn_company_id');
        if($request->status!='*'){
            $result =$result->where('cmn_scenario_histories.status', $request->status);
        }
        if($request->data!='*'){
            $result =$result->where('cmn_scenario_histories.data', $request->data);
        }
        if($request->super_code!=null){
            $result =$result->where('byr_buyers.super_code', $request->super_code);
        }
        if($request->partner_code!=null){
            $result =$result->where('cmn_connects.partner_code', $request->partner_code);
        }
        if($request->scenario_date_from!=null && $request->scenario_date_to!=null){
            $result= $result->whereBetween('cmn_scenario_histories.exec_datetime', [$request->scenario_date_from, $request->scenario_date_to]);
        }
        if($request->name!=null){
            $result =$result->where('cmn_scenarios.name', $request->name);
        }
        $result = $result->orderBy($table_name.$sort_by, $sort_type)->paginate($per_page);
        return response()->json(['scenario_list'=>$result]);
    }
}
