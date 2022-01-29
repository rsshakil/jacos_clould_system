<?php

namespace App\Scenarios;
use App\Scenarios\Common;
use Illuminate\Database\Eloquent\Model;
use App\Models\BYR\byr_item;
use App\Models\BYR\byr_item_class;
use App\Models\CMN\cmn_maker;
use App\Models\CMN\cmn_category;
use App\Models\CMN\cmn_category_description;
use App\Models\CMN\cmn_category_path;
use App\Http\Controllers\API\AllUsedFunction;
use DB;
class item_master extends Model
{
    private $all_functions;
    private $common_class_obj;
    public function __construct()
    {
        $this->common_class_obj = new Common();
        $this->all_functions = new AllUsedFunction();
    }
    private $format = [
        [
            "type"=>"header",
            "key"=>["start" => 1,"length" => 2,"value" => "HD"],
            "fmt"=>[
                ["start" => 1,		"length" => 2,"name" => "hd_tag",			"name_jp"=>"タグ"],
                ["start" => 15,		"length" => 8,"name" => "invoice_num",		"name_jp"=>"伝票番号"],
                ["start" => 72,		"length" => 8,"name" => "order_date",	"name_jp"=>"発注日"],
                ["start" => 94,		"length" => 8,"name" => "shipment_date","name_jp"=>"納品指定日"],
                ["start" => 148,	"length" => 4,"name" => "order_class",	"name_jp"=>"発注区分"],
                ["start" => 1197,	"length" => 1,"name" => "auto_order",	"name_jp"=>"自動発注区分"],
                ["start" => 1283,	"length" => 6,"name" => "company_code",	"name_jp"=>"小売企業コード"],
                ["start" => 1296,	"length" => 10,"name" => "company_name_kana","name_jp"=>"小売企業名称(ｶﾅ)"],
                ["start" => 1356,	"length" => 40,"name" => "company_name",	"name_jp"=>"小売企業名称(漢字)"],
                ["start" => 1851,	"length" => 4,"name" => "shop_code",	"name_jp"=>"店舗コード"],
                ["start" => 1864,	"length" => 6,"name" => "purchase_code",	"name_jp"=>"仕入先コード"],
                ["start" => 1877,	"length" => 20,"name" => "purchase_name_kana",	"name_jp"=>"仕入先名称(ｶﾅ)"],
                ["start" => 1897,	"length" => 20,"name" => "purchase_name",	"name_jp"=>"仕入先名称(漢字)"],
                ["start" => 2141,	"length" => 10,"name" => "shop_name_kana",	"name_jp"=>"店舗名称(ｶﾅ)"],
                ["start" => 2171,	"length" => 50,"name" => "shop_name",	"name_jp"=>"店舗名称(漢字)"],
                ["start" => 2405,	"length" => 6,"name" => "transmission_code",	"name_jp"=>"送受信コード"],
                ["start" => 2811,	"length" => 3,"name" => "major_category_code",	"name_jp"=>"大分類コード"],
                ["start" => 2817,	"length" => 20,"name" => "major_category_name_kana",	"name_jp"=>"大分類名称(ｶﾅ)"],
                ["start" => 2847,	"length" => 40,"name" => "major_category_name",	"name_jp"=>"大分類名称(漢字)"],
            ]
        ],
        [
            "type"=>"data",
            "key"=>["start" => 1,"length" => 2,"value" => "DT"],
            "fmt"=>[
                ["start" => 1,		"length" => 2,"name" => "dt_tag",			"name_jp"=>"タグ"],
                ["start" => 3,		"length" => 13,"name" => "jan",		"name_jp"=>"SKU"],
                ["start" => 20,		"length" => 1,"name" => "invoice_line_num",	"name_jp"=>"伝票行番号"],
                ["start" => 67,		"length" => 13,"name" => "own_jan","name_jp"=>"自社品番"],
                ["start" => 80,		"length" => 15,"name" => "maker_jan",	"name_jp"=>"メーカー品番"],
                ["start" => 120,	"length" => 2,"name" => "submajor_category",	"name_jp"=>"中分類"],
                ["start" => 130,	"length" => 2,"name" => "minor_category",	"name_jp"=>"小分類"],
                ["start" => 156,	"length" => 10,"name" => "size_name","name_jp"=>"サイズ名称"],
                ["start" => 360,	"length" => 40,"name" => "item_name",	"name_jp"=>"商品名称(漢字)"],
                ["start" => 430,	"length" => 10,"name" => "brand_name",	"name_jp"=>"ブランド名称"],
                ["start" => 547,	"length" => 10,"name" => "color_name",	"name_jp"=>"カラー名称"],
                ["start" => 669,	"length" => 5,"name" => "order_quentity",	"name_jp"=>"発注数(ﾊﾞﾗ)"],
                ["start" => 697,	"length" => 5,"name" => "order_quentity_original",	"name_jp"=>"発注数(ﾊﾞﾗ)(納品数量元値)"],
                ["start" => 715,	"length" => 9,"name" => "item_price",	"name_jp"=>"原価金額"],
                ["start" => 726,	"length" => 9,"name" => "suggested_retail_price",	"name_jp"=>"標準上代金額"],
                ["start" => 761,	"length" => 7,"name" => "item_unit_price",	"name_jp"=>"原価"],
                ["start" => 772,	"length" => 7,"name" => "suggested_retail_unit_price",	"name_jp"=>"標準上代"],
                ["start" => 786,	"length" => 3,"name" => "lot_quentity",	"name_jp"=>"ロット数"],
            ]
        ],
        [
            "type"=>"footer",
            "key"=>["start" => 1,"length" => 2,"value" => "TR"],
            "fmt"=>[
                ["start" => 1,		"length" => 2,"name" => "tr_tag",			"name_jp"=>"タグ"],
                ["start" => 5,		"length" => 10,"name" => "item_price_total",		"name_jp"=>"原価金額合計"],
                ["start" => 29,		"length" => 10,"name" => "suggested_retail_price_total",	"name_jp"=>"売価金額合計"],
            ]
        ],
    ];

    //
    public function exec($request,$sc)
    {
        $cmn_m_cls = new Common();
        // include(app_path() . '/scenarios/common.php');
        \Log::debug('ouk_order_toj exec start  ---------------');
        // ファイルアップロード
        // echo $request->file('up_file');exit;
       $file_name = $request->file('up_file')->getClientOriginalName();
        $path = $request->file('up_file')->storeAs(config('const.ORDER_DATA_PATH').date('Y-m'), $file_name);
        \Log::debug('save path:'.$path);
        $received_path = storage_path().'/app//'.config('const.ORDER_DATA_PATH').date('Y-m').'/'.$file_name;
        // フォーマット変換
        // byr_orders,byr_order_details格納
        $collection = $this->all_functions->csvReader($received_path);
        $customer_order_array=array();
        $byr_buyer_id = $sc->byr_buyer_id;
        foreach($collection as $vl){
            $jan = $vl[0];
          $maker_code = substr($jan, 0, 7);
          $maker_name = $vl[3];
          $maker_name_kana = $vl[2];
          $category_code = $vl[1];
        if (cmn_maker::where('maker_code', $maker_code)->where('byr_buyer_id', $byr_buyer_id)->exists()) {
            $maker_id=cmn_maker::where('maker_code', $maker_code)->where('byr_buyer_id', $byr_buyer_id)->first()->cmn_maker_id;
        } else {
            $maker_ins_array = array(
                'byr_buyer_id'=>$byr_buyer_id,
                'cmn_shop_id'=>0,
                'maker_name'=>$maker_name,
                'maker_name_kana'=>$maker_name_kana,
                'maker_code'=>$maker_code,
            );
            $maker_id=cmn_maker::insertGetId($maker_ins_array);
        }
          /*get maker id*/
            /*get category_id*/
            if(substr($category_code, 0, 4)=='0000'){
                $last2=substr($category_code, -2);
                $category_code = $last2.'0000';
            }
            $codes = str_split($category_code, 2);
            if($codes[0]=='00' && ($codes[2]!='00' && $codes[1]!='00')){
                $category_code = $codes[1].$codes[2].$codes[0];
            }

            if (cmn_category_description::where('category_code', $category_code)->where('byr_buyer_id', $byr_buyer_id)->exists()) {
                $category_id=cmn_category_description::where('category_code', $category_code)->where('byr_buyer_id', $byr_buyer_id)->first()->cmn_category_id;
            } else {
                $code_split = str_split($category_code, 2);
                $parent_id = 0;
                for($i=0;$i<3;$i++){
                    if($code_split[$i]!='00'){
                        if($i==0){
                            $new_code = $code_split[0].'0000';
                        }else if($i==1){
                            $new_code = $code_split[0].$code_split[1].'00';
                            $cat_code = $code_split[0].'0000';
                            $parent_id = cmn_category_description::where('category_code', $cat_code)->first()->category_id;
                        }else if($i==2){
                            $new_code =$code_split[0].$code_split[1].$code_split[2];
                            $cat_code=$code_split[0].$code_split[1].'00';
                            $parent_id = cmn_category_description::where('category_code', $cat_code)->where('byr_buyer_id', $byr_buyer_id)->first()->category_id;
                        }

                        if (cmn_category_description::where('category_code', $new_code)->where('byr_buyer_id', $byr_buyer_id)->exists()) {
                            $parent_id=cmn_category_description::where('category_code', $new_code)->where('byr_buyer_id', $byr_buyer_id)->first()->category_id;
                        }else{
                            $parent_id = $parent_id;
                            $cat_id = cmn_category::insertGetId(['parent_id'=>$parent_id]);
                            $cat_desc = cmn_category_description::insertGetId(['cmn_category_id'=>$cat_id,'category_name'=>$new_code,'category_code'=>$new_code,'byr_buyer_id'=>$byr_buyer_id]);
                            $result = DB::select("SELECT cmn_category_paths.*,cmn_category_descriptions.category_code FROM cmn_category_paths inner join cmn_category_descriptions on cmn_category_descriptions.cmn_category_id=cmn_category_paths.path_id WHERE cmn_category_paths.cmn_category_id = '". $parent_id . "' ORDER BY `lavel` ASC");
                            $lavel=0;
                            if($result){
                                foreach($result as $val){
                                    cmn_category_path::insert(['cmn_category_id'=>$cat_id,'path_id'=>$val->path_id,'lavel'=>$lavel]);
                                    $lavel++;
                                }
                            }
                            cmn_category_path::insert(['cmn_category_id'=>$cat_id,'path_id'=>$cat_id,'lavel'=>$lavel]);
                            $parent_id = $cat_id;
                        }
                    }
                }
                $category_id = $parent_id;
            }

            $byr_items_data['byr_shop_id']=0;
            $byr_items_data['byr_buyer_id']=$byr_buyer_id;
            $byr_items_data['cmn_category_id']=$category_id;
            $byr_items_data['cmn_maker_id']=$maker_id;
            $byr_items_data['case_inputs']=intval($vl[11]);
            $byr_items_data['ball_inputs']= 1;
            $byr_items_data['name']= $vl[5];
            $byr_items_data['name_kana']= $vl[4];
            $byr_items_data['jan']=$jan;
            $byr_items_data['spec_kana']= $vl[6];
            $byr_items_data['spec']=$vl[7];
            $byr_items_data['color']=$vl[8];
            $byr_items_data['size']=$vl[9];
            $byr_items_data['tax']=$vl[10];
            $customer_order_array[]=$byr_items_data;

            $cost_price = intval($vl[13]);
            $shop_price = intval($vl[14]);
            $vendor_item_class['cost_price']=$cost_price;
            $vendor_item_class['shop_price']=$shop_price;
            $vendor_item_class['start_date']=$vl[15];
            $vendor_item_class['end_date']=$vl[16];
            $vendor_item_class['order_point_inputs']=$vl[17];
            $vendor_item_class['order_point_quantity']=$vl[18];
            $vendor_item_class['order_lot_inputs']=$vl[19];
            $vendor_item_class['order_lot_quantity']=$vl[20];
            if (byr_item::where('jan', $jan)->where('byr_buyer_id',$byr_buyer_id)->exists()) {
                byr_item::where('jan', '=', $jan)->where('byr_buyer_id',$byr_buyer_id)->update($byr_items_data);
                $byr_item_id=byr_item::where('jan', $jan)->where('byr_buyer_id',$byr_buyer_id)->first()->byr_item_id;
                $vendor_item_class['byr_item_id']=$byr_item_id;
                byr_item_class::where('byr_item_id', '=', $byr_item_id)->update($vendor_item_class);
            }else{
                $byr_item_id = byr_item::insertGetId($byr_items_data);
                $vendor_item_class['byr_item_id']=$byr_item_id;
                byr_item_class::insert($vendor_item_class);
            }

        }
        echo '<pre>';
        print_r($customer_order_array);
        exit;
        echo 'save path:'.$path;exit;
        \Log::debug('ouk_order_toj exec end  ---------------');
        return 0;
    }
}
