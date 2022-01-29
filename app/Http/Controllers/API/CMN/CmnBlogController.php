<?php

namespace App\Http\Controllers\API\CMN;

use App\Http\Controllers\Controller;
use App\Http\Controllers\API\AllUsedFunction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\CMN\cmn_blog;
use App\Models\ADM\User;

class CmnBlogController extends Controller
{
    private $all_used_fun;

    public function __construct()
    {
        $this->all_used_fun = new AllUsedFunction();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function return_blog_cmn_company_id(){
        $authUser = Auth::user();
        if ($authUser->hasRole('Slr')) {
            $blog_cmn_company_id = Auth::user()->CompanyId;
        }else if($authUser->hasRole('Byr')){
            $blog_cmn_company_id = Auth::user()->CompanyId;
        }else{
            $blog_cmn_company_id = 0;
        }
        return $blog_cmn_company_id;
    }
    public function index()
    {

        $cmn_company_id = $this->return_blog_cmn_company_id();
        $blog_by = Auth::user()->id;

        $result = cmn_blog::where('is_delete', '0')->where('cmn_company_id',$cmn_company_id)->orderBy('is_top_blog', 'ASC')->orderBy('cmn_blog_id', 'DESC')->get();
        return response()->json(['blog_list' => $result]);
    }
    public function get_all_published_blog_list()
    {
        $cmn_company_id = $this->return_blog_cmn_company_id();
        $result = cmn_blog::where('is_delete', '0')->where('blog_status', 'published')->where('cmn_company_id',$cmn_company_id)->where('is_top_blog', '0')->orderBy('is_top_blog', 'DESC')->orderBy('cmn_blog_id', 'DESC')->get();
        return response()->json(['blog_list' => $result]);
    }
    public function get_signle_top_blog()
    {
        //admin top blog only
        $result = cmn_blog::where('is_delete', '0')->where('blog_status', 'published')->where('cmn_company_id', 0)->where('is_top_blog', '1')->first();
        if ($result) {
            return response()->json(['blog_list' => $result]);
        } else {
            $result = cmn_blog::where('is_delete', '0')->where('blog_status', 'published')->where('cmn_company_id', 0)->orderBy('cmn_blog_id', 'DESC')->first();
            if ($result) {
                return response()->json(['blog_list' => $result]);
            } else {
                $result = array();
                return response()->json(['blog_list' => $result]);
            }
        }
    }
    public function get_user_top_blog()
    {
        $authUser = Auth::user();
        if ($authUser->hasRole('Slr')) {
            $byr_info = $this->all_used_fun->get_slrs_byr_id();
            $result = cmn_blog::where('is_delete', '0')->where('blog_status', 'published')->where('cmn_company_id', $byr_info->cmn_company_id)->where('is_top_blog', '1')->orderBy('cmn_blog_id', 'DESC')->first();
            if ($result) {
                return response()->json(['blog_list' => $result]);
            } else {
                $result =cmn_blog::where('is_delete', '0')->where('blog_status', 'published')->where('cmn_company_id', $byr_info->cmn_company_id)->orderBy('cmn_blog_id', 'DESC')->first();
                if ($result) {
                    return response()->json(['blog_list' => $result]);
                } else {
                    $result = array();
                    return response()->json(['blog_list' => $result]);
                }
            }
        } else {
            $result = array();
            return response()->json(['blog_list' => $result]);
        }
    }
    public function get_user_top_blog_by_byr_id($byr_buyer_id)
    {
        \log::debug('get_user_top_blog_by_byr_id start');
        $authUser = Auth::user();

        if ($authUser->hasRole('Slr')) {
            $byr_info = $this->all_used_fun->get_byr_info_by_byr_buyer_id($byr_buyer_id);
            $result = cmn_blog::where('is_delete', '0')->where('blog_status', 'published')->where('blog_by', $byr_info->adm_user_id)->where('is_top_blog', '1')->orderBy('cmn_blog_id', 'DESC')->first();
            if ($result) {
                return response()->json(['blog_list' => $result]);
            } else {
                $result =cmn_blog::where('is_delete', '0')->where('blog_status', 'published')->where('blog_by', $byr_info->adm_user_id)->orderBy('cmn_blog_id', 'DESC')->first();
                if ($result) {
                    return response()->json(['blog_list' => $result]);
                } else {
                    $result = array();
                    return response()->json(['blog_list' => $result]);
                }
            }
        } else {
            $result = array();
            return response()->json(['blog_list' => $result]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function ckeditor_file_up(Request $request)
    {
        $file_name = $request->file('upload')->getClientOriginalName();
        $path = $request->file('upload')->storeAs(config('const.ORDER_DATA_PATH').date('Y-m'), $file_name);
        $received_path = url('/storage/app//'.config('const.ORDER_DATA_PATH').date('Y-m').'/'.$file_name);
        return response()->json(['url' => $received_path]);
    }
    public function store(Request $request)
    {
        $this->validate($request, [
            'blog_title' => 'required|min:4',
            'blog_content'=>'required'
        ]);
        $cmn_company_id = ($request->cmn_company_id==null?0:$request->cmn_company_id);
        $arr =array(
            'blog_title'=>$request->blog_title,
            'blog_content'=>$request->blog_content,
            'blog_by'=>$request->blog_by,
            'cmn_company_id'=>$cmn_company_id
        );
        $feature_img = $request->feature_img;
        $img = '';
        $path = storage_path().'/app/public/backend/images/blog_images';
       if(!is_dir($path)) {
        mkdir($path, 0755, true);
    }
        if (!empty($feature_img)) {
            if ($request->cmn_blog_id!='') {
                $info=cmn_blog::where('cmn_blog_id', $request->cmn_blog_id)->first();
                $img = $info->feature_img;
            }
            if ($img!=$feature_img) {
                $imgs = $this->all_used_fun->save_base64_image($feature_img, 'blog_image_'. time(), $path_with_end_slash = "storage/app/public/backend/images/blog_images/");
                $arr['feature_img'] = $imgs;
            }
        }
        if ($request->cmn_blog_id!='') {
            cmn_blog::where('cmn_blog_id', $request->cmn_blog_id)->update($arr);
        } else {
            cmn_blog::insert($arr);
        }
        return response()->json(['success' => 1]);
    }

    public function update_blog_infos(Request $request)
    {
        $act_type = $request->action_type;
        $blog_info = $request->blog;
        $cmn_blog_id = $blog_info['cmn_blog_id'];
        if ($act_type==0 || $act_type==1) {
            $pub_type = ($act_type==0?'unpublished':'published');
            cmn_blog::where('cmn_blog_id', $cmn_blog_id)->update(['blog_status'=>$pub_type]);
        } elseif ($act_type==2) {
            cmn_blog::where('blog_by', Auth::user()->id)->update(['is_top_blog'=>'0']);
            cmn_blog::where('cmn_blog_id', $cmn_blog_id)->update(['is_top_blog'=>'1']);
        } else {
            cmn_blog::where('cmn_blog_id', $cmn_blog_id)->update(['is_delete'=>'1']);
        }
        return response()->json(['success' => 1]);
    }
}
