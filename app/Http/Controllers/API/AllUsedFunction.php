<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ADM\User;
use App\Models\BYR\byr_buyer;
use App\Models\CMN\cmn_companies_user;
use App\Models\CMN\cmn_company;
use App\Models\CMN\cmn_connect;
use App\Models\SLR\slr_seller;
use App\Models\CMN\cmn_category;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
require_once base_path('vendor/tecnickcom/tcpdf/tcpdf.php');
use setasign\Fpdi\Tcpdf\Fpdi;
use tecnickcom\tcpdf\TCPDF_FONTS;

class AllUsedFunction extends Controller
{
    /**
     * Get all User by customize id and name
     *
     * @return All Users as an array.
     */
    public function allUsers()
    {
        $users = User::select('id as user_id', 'name as user_name')->get();
        return $users;
    }
    /**
     * Get all Users as default
     *
     * @return All Users as an array.
     */
    public function allUsersAll()
    {
        $users = User::withTrashed()->get();
        return $users;
    }

    /**
     * Get all permission for a desired role
     *
     * @param  int $role_id
     * @return All permission as an array.
     */
    public function get_role_permission_by_role_id($role_id = null, $status = null)
    {
        if (!empty($role_id)) {
            $permissions = $this->get_permissions($role_id);
            $permission_array = array();
            foreach ($permissions as $key => $permission) {
                if ($status == null) {
                    $permission_array[] = '<button class="btn btn-info btn-sm" style="margin-top:5px;">' . $permission->name . '</button>';
                } else {
                    $permission_array[] = $permission->name;
                }
            }
            return $permissions = implode(' ', $permission_array);
        } else {
            return $permissions = Permission::select('id', 'name')->get();
        }
    }
    /**
     * Assign permissions to a role.
     *
     * @param  int $role_id
     * @param  array $permissions
     * @return revoke previous permission and set new permission
     */
    public function assignPermissionToRole($role_id, $permissions)
    {
        $permission_id = $permissions;
        $role = Role::find($role_id);
        $permission = $this->get_permissions();
        $role->revokePermissionTo($permission);
        $role->givePermissionTo($permission_id);
    }
    /**
     * Get all permission for a desired role
     *
     * @param  int $role_id
     * @return All permission as an array.
     */
    public function get_permissions($role_id = null)
    {
        if ($role_id != null) {
            $permissions = Permission::select('adm_permissions.*')
                ->join('adm_role_has_permissions as rhp', 'adm_permissions.id', '=', 'rhp.permission_id')
                ->where('rhp.role_id', $role_id)
                ->get();
        } else {
            $permissions = Permission::all();
        }

        return $permissions;
    }
    /**
     * Get all permission for a desired user
     *
     * @param  array $user
     * @return All permissions as an array.
     */
    public function allPermissionForUser($user)
    {
        $all_permissions_for_user = $user->getAllPermissions();
        $all_permissions_for_user_array = array();
        foreach ($all_permissions_for_user as $all_permission_for_user) {
            $all_permissions_for_user_array[] = $all_permission_for_user->name;
        }
        return $all_permissions_for_user_array;
    }
    /**
     * Get all Permissions by customize id and name
     *
     * @return All Permissions as an array.
     */
    public function get_permission_custom_field()
    {
        return $permissions = Permission::select('id as permission_id', 'name as permission_name')->get();
    }
    /**
     * Get all Roles by customize id and name
     *
     * @return All Roles as an array.
     */
    public function get_role_custom_field()
    {
        return $roles = Role::select('id as role_id', 'name as role_name')->get();
    }
    /**
     * Get all Roles as default
     *
     * @return All Roles as an array.
     */
    public function get_roles()
    {
        return $roles = Role::all();
    }

    /**
     * Read a csv file and convert by path
     *
     * @param  string $baseUrl
     * @param  boolean $take_header 1 for no header 0 for with header
     * @return All csv data as an array.
     */
    public function csvReader($baseUrl, $take_header = 1)
    {
        // $data = array_map('str_getcsv', file($baseUrl));
        $temp_data = file_get_contents($baseUrl);
        if (mb_detect_encoding($temp_data, ['UTF-8', 'SJIS-win', 'SJIS', 'eucJP-win', 'ASCII', 'EUC-JP', 'JIS']) != "UTF-8") {
            $temp_data = mb_convert_encoding($temp_data, "UTF-8", 'SJIS-win, SJIS, eucJP-win, ASCII, EUC-JP, JIS');
            $data = array_map('str_getcsv', array_filter(explode("\r\n", $temp_data)));
        } else {
            $data = array_map('str_getcsv', file($baseUrl));
        }
        $csv_data = array_slice($data, $take_header);
        Log::debug('----- CSV file read completed from this url: (' . $baseUrl . ')-----');
        return $csv_data;
    }

    /**
     * File encoding from SJIJ to utf8
     * @param  SJIJ String or array
     * @return utf-8 encoded string
     */
    public static function convert_from_sjis_to_utf8_recursively($dat)
    {
        if (is_string($dat)) {
            if (mb_detect_encoding($dat) != "UTF-8") {
                return mb_convert_encoding($dat, "UTF-8", "sjis-win");
            } else {
                return mb_convert_encoding($dat, "UTF-8", "auto");
            }
        } elseif (is_array($dat)) {
            $ret = [];
            foreach ($dat as $i => $d) {
                $ret[$i] = self::convert_from_sjis_to_utf8_recursively($d);
            }

            return $ret;
        } elseif (is_object($dat)) {
            foreach ($dat as $i => $d) {
                $dat->$i = self::convert_from_sjis_to_utf8_recursively($d);
            }

            return $dat;
        } else {
            return $dat;
        }
    }
    /**
     * File encoding from utf8 to SJIJ
     * @param utf-8 String or array
     * @return  SJIJ encoded string
     */
    public static function convert_from_utf8_to_sjis__recursively($dat)
    {
        if (is_string($dat)) {
            // Original
            return mb_convert_encoding($dat, "sjis-win", "UTF-8");
        } elseif (is_array($dat)) {
            $ret = [];
            foreach ($dat as $i => $d) {
                $ret[$i] = self::convert_from_utf8_to_sjis__recursively($d);
            }

            return $ret;
        } elseif (is_object($dat)) {
            foreach ($dat as $i => $d) {
                $dat->$i = self::convert_from_utf8_to_sjis__recursively($d);
            }

            return $dat;
        } else {
            return $dat;
        }
    }
    /**
     * File encoding from Latin1 utf8
     * @param Latin1 String or array
     * @return  utf-8 encoded string
     */
    public static function convert_from_latin1_to_utf8_recursively($dat)
    {
        if (is_string($dat)) {
            return utf8_encode($dat);
        } elseif (is_array($dat)) {
            $ret = [];
            foreach ($dat as $i => $d) {
                $ret[$i] = self::convert_from_latin1_to_utf8_recursively($d);
            }

            return $ret;
        } elseif (is_object($dat)) {
            foreach ($dat as $i => $d) {
                $dat->$i = self::convert_from_latin1_to_utf8_recursively($d);
            }

            return $dat;
        } else {
            return $dat;
        }
    }
    /**
     * Change a file name from a given file name
     * @param  String File name
     * @return String formated file name
     */
    public function file_name_change($file_name)
    {
        $file_array = explode('.', $file_name);
        $array_size = sizeof($file_array);
        $file_name_without_ext = $file_array[$array_size - 2];
        $today_for_file = date("YmdHis");
        return $file_name = $file_name_without_ext . "_" . $today_for_file . "." . $file_array[$array_size - 1];
    }
    /**
     * Get cmn_companies information by cmn_company_id or not
     * @param  int $cmn_company_id
     * @return Array Company information
     */
    public function get_company_list($cmn_company_id = 0)
    {
        if ($cmn_company_id != 0) {
            return $results = cmn_company::where('cmn_company_id', $cmn_company_id)->first();
        } else {
            return $results = byr_buyer::select('cmn_companies.*','byr_buyers.byr_buyer_id','byr_buyers.super_code')
                ->join('cmn_companies', 'cmn_companies.cmn_company_id', '=', 'byr_buyers.cmn_company_id')->get();
        }
    }
    /**
     * Get cmn_companies_users information by adm_user_id or not
     * @param  int $adm_user_id
     * @return Array Formated Company information
     */
    public function get_user_info($adm_user_id = 0, $selected_byr_buyer_id=0)
    {
        Log::debug(__METHOD__.':start---');
        $arr = array('cmn_company_id' => 0, 'byr_buyer_id' => 0, 'cmn_connect_id' => 0);
        if ($adm_user_id != 0) {
            $company_type_info = cmn_companies_user::select('cmn_companies_users.cmn_company_id', 'cmn_companies.company_type')
            ->join('cmn_companies', 'cmn_companies_users.cmn_company_id', '=', 'cmn_companies.cmn_company_id')
            ->where('cmn_companies_users.adm_user_id', $adm_user_id)
            ->first();
            $cmn_company_info = cmn_companies_user::select('cmn_companies_users.cmn_company_id', 'cmn_connects.cmn_connect_id');
            if ($company_type_info->company_type=='seller') {
                $cmn_company_info = $cmn_company_info->join('slr_sellers', 'slr_sellers.cmn_company_id', '=', 'cmn_companies_users.cmn_company_id')
                ->join('cmn_connects', 'cmn_connects.slr_seller_id', '=', 'slr_sellers.slr_seller_id');
                if ($selected_byr_buyer_id!=0) {
                    $cmn_company_info = $cmn_company_info->where('cmn_connects.byr_buyer_id', $selected_byr_buyer_id);
                }
            } elseif ($company_type_info->company_type=='buyer') {
                $cmn_company_info = $cmn_company_info->join('byr_buyers', 'byr_buyers.cmn_company_id', '=', 'cmn_companies_users.cmn_company_id')
                ->join('cmn_connects', 'cmn_connects.byr_buyer_id', '=', 'byr_buyers.byr_buyer_id');
            }

            // return $cmn_company_info = cmn_companies_user::select('byr_buyers.cmn_company_id', 'byr_buyers.byr_buyer_id', 'cmn_connects.cmn_connect_id')
            //     ->join('byr_buyers', 'byr_buyers.cmn_company_id', '=', 'cmn_companies_users.cmn_company_id')
            // $cmn_company_info->join('cmn_connects', 'cmn_connects.byr_buyer_id', '=', 'byr_buyers.byr_buyer_id')
            $cmn_company_info=$cmn_company_info->where('cmn_companies_users.adm_user_id', $adm_user_id)->first();

            if (!empty($cmn_company_info)) {
                $company_details = cmn_company::where('cmn_company_id', $cmn_company_info->cmn_company_id)->first();
                $arr = array(
                    'cmn_company_id' => $cmn_company_info->cmn_company_id,
                    'company_name' => $company_details->company_name,
                    'company_type' => $company_details->company_type,
                    // 'byr_buyer_id' => $cmn_company_info->byr_buyer_id,
                    'cmn_connect_id' => $cmn_company_info->cmn_connect_id,
                );
            }
        }
        Log::debug(__METHOD__.':end---');
        return $arr;
    }

    public function get_cmn_connect_id_by_seller_id_buyer_id($slr_seller_id, $byr_buyer_id)
    {
        $cmn_connect_info = cmn_connect::where(['slr_seller_id'=>$slr_seller_id,'byr_buyer_id'=>$byr_buyer_id])->first();
        return $cmn_connect_info;
    }

    /**
     * Save Base64 image in Directory
     * @param  Object $base64_image_string Base64 Image Object
     * @param  String $output_file_without_extension File name without extension which will be returned as file name
     * @param  String $path_with_end_slash Directory path with end slash where the file will be saved
     * @return Array Formated Object Array
     */
    public function save_base64_image($base64_image_string, $output_file_without_extension, $path_with_end_slash = "")
    {
        $splited = explode(',', substr($base64_image_string, 5), 2);
        $mime = $splited[0];
        $data = $splited[1];

        $mime_split_without_base64 = explode(';', $mime, 2);
        $mime_split = explode('/', $mime_split_without_base64[0], 2);
        if (count($mime_split) == 2) {
            $extension = $mime_split[1];
            if ($extension == 'jpeg') {
                $extension = 'jpg';
            }
            $output_file_with_extension = $output_file_without_extension . '.' . $extension;
        }
        file_put_contents($path_with_end_slash . $output_file_with_extension, base64_decode($data));
        return $output_file_with_extension;
    }
    public function save_file($requestFile, $fullPath)
    {
        file_put_contents($fullPath, $requestFile);
        return 'success';
    }
    /**
     * Check an Object is an base64 image or not
     * @param  object $base64_obj Base64 Image Object
     * @return boolean If base64 return 1 else 0
     */
    public function itsBase64($base64_obj)
    {
        if (substr($base64_obj, 0, 11) === 'data:image/') {
            return 1;
        } else {
            return 0;
        }
    }
    /**
     * Check string length and add space padding after the string until desired length
     * @param  string $input desired string
     * @param  int $pad_length desired string length
     * @return string formated string with space padding added
     */
    public function mb_str_pad($input, $pad_length)
    {
        $len = $pad_length - mb_strlen($input);
        if ($len < 0) {
            return mb_substr($input, 0, $pad_length);
        }
        return $input . str_repeat(' ', $len);
    }
    /**
     * Get file extension from a file name
     * @param  string $file_name desired file name
     * @return string File extension
     */
    public function ext_check($file_name)
    {
        $ext = \explode('.', $file_name)[1];
        return $ext;
    }
    /**
     * Get first 8 character from a date based file name for date
     * @param  string $file_name desired file name
     * @return string date string from file name
     */
    public function header_part($file_name)
    {
        $header = \substr($file_name, 0, 8);
        return $header;
    }
    /**
     * Split at all position not after the start: ^ and not before the end: $
     * @param  string $string desired string
     * @return string Formated string
     */
    public function mb_str_split($string)
    {
        return preg_split('/(?<!^)(?!$)/u', $string);
    }
    /**
     * Binary number set for date schedule
     * @param  int $binary_number desired binary number Like: 11011
     * @return int Formated binary number as 7 days like: 0011011
     */
    public function binary_format($binary_number)
    {
        $binary_length = strlen($binary_number);

        $formated_binary_number = null;
        if ($binary_length < 7) {
            $addable = 7 - $binary_length;
            $formated_binary_number = str_repeat('0', $addable) . $binary_number;
        } else {
            $formated_binary_number = $binary_number;
        }
        return $formated_binary_number;
    }
    /**
     * Get binary to decimal number
     * @param  int $binary desired binary number Like: 0000011
     * @return int decimal number as like: 3
     */
    public function binary_to_decimal($binary)
    {
        return bindec($binary);
    }
    /**
     * Get decimal to binary number
     * @param  int $decimal desired decimal number Like: 3
     * @return int binary number as like: 11
     */
    public function decimal_to_binary($decimal)
    {
        $bos = null;
        while ($decimal >= 1) {
            $bin = $decimal % 2;
            $decimal = round(
                $decimal / 2,
                0,
                PHP_ROUND_HALF_DOWN
            );
            $bos .= $bin;
        }
        return strrev($bos);
    }

    /**
     * Get buyer info by slr id
     * @param  int Saller id
     * @return array buyer info
     */
    public function get_slrs_byr_id($slr_id = null)
    {
        $byrs_info = array();
        if ($slr_id == null) {
            $slr_id = Auth::user()->id;
        }
        $byr_id_info = cmn_companies_user::select('byr_buyers.byr_buyer_id')
            ->join('slr_sellers', 'slr_sellers.cmn_company_id', '=', 'cmn_companies_users.cmn_company_id')
            ->join('cmn_connects', 'cmn_connects.slr_seller_id', '=', 'slr_sellers.slr_seller_id')
            ->join('byr_buyers', 'byr_buyers.byr_buyer_id', '=', 'cmn_connects.byr_buyer_id')
            ->where('cmn_companies_users.adm_user_id', $slr_id)->first();

        $byrs_info = byr_buyer::select('byr_buyers.byr_buyer_id', 'cmn_companies_users.adm_user_id', 'cmn_companies.*')
            ->join('cmn_companies_users', 'cmn_companies_users.cmn_company_id', '=', 'byr_buyers.cmn_company_id')
            ->join('cmn_companies', 'cmn_companies.cmn_company_id', '=', 'cmn_companies_users.cmn_company_id')
            ->where('byr_buyers.byr_buyer_id', $byr_id_info->byr_buyer_id)->first();
        return $byrs_info;
    }
    public function get_byr_info_by_byr_buyer_id($byr_buyer_id = null)
    {
        $byrs_info = byr_buyer::select('byr_buyers.byr_buyer_id', 'cmn_companies_users.adm_user_id', 'cmn_companies.*')
            ->join('cmn_companies_users', 'cmn_companies_users.cmn_company_id', '=', 'byr_buyers.cmn_company_id')
            ->join('cmn_companies', 'cmn_companies.cmn_company_id', '=', 'cmn_companies_users.cmn_company_id');
        if ($byr_buyer_id == null) {
            $adm_user_id = Auth::user()->id;
            $byrs_info = $byrs_info->where('cmn_companies_users.adm_user_id', $adm_user_id)->first();
        } else {
            $byrs_info = $byrs_info->where('byr_buyers.byr_buyer_id', $byr_buyer_id)->first();
        }

        return $byrs_info;
    }
    public function get_slr_info_by_slr_seller_id($slr_seller_id = null)
    {
        $slrs_info = slr_seller::select('slr_sellers.slr_seller_id', 'cmn_companies_users.adm_user_id', 'cmn_companies.*')
            ->join('cmn_companies_users', 'cmn_companies_users.cmn_company_id', '=', 'slr_sellers.cmn_company_id')
            ->join('cmn_companies', 'cmn_companies.cmn_company_id', '=', 'cmn_companies_users.cmn_company_id');
        if ($slr_seller_id == null) {
            $adm_user_id = Auth::user()->id;
            $slrs_info = $slrs_info->where('cmn_companies_users.adm_user_id', $adm_user_id)->first();
        } else {
            $slrs_info = $slrs_info->where('slr_sellers.slr_seller_id', $slr_seller_id)->first();
        }

        return $slrs_info;
    }



    public function dateDiff($date1, $date2)
    {
        $t1 = strtotime($date1);
        $t2 = strtotime($date2);

        $delta_T = ($t2 - $t1);

        $day = round(($delta_T % 604800) / 86400);
        $hours = round((($delta_T % 604800) % 86400) / 3600);
        $minutes = round(((($delta_T % 604800) % 86400) % 3600) / 60);
        $sec = round((((($delta_T % 604800) % 86400) % 3600) % 60));

        return array(
            'day' => $day,
            'hours' => $hours,
            'minutes' => $minutes,
            'sec' => $sec,
        );
    }

    public function get_allCategoryByByrId($byr_buyer_id)
    {
        $result = cmn_category::select(
            'category_code',
            DB::raw("CONCAT(category_code,' | ',category_name) as category_name")
        )->where('byr_buyer_id', $byr_buyer_id)->where('level', 1)->orderBy('category_code')->get();
        return $result;
    }
    public function validateDate($date, $format = 'Y-m-d')
    {
        $b = \DateTime::createFromFormat($format, $date);
        return $b && $b->format($format) === $date;
    }

    public function folder_create($folder_name)
    {
        if (!file_exists(storage_path() . '/' . $folder_name)) {
            mkdir(storage_path() . '/' . $folder_name, 0777, true);
        }
    }
    public function downloadFileName($request, $file_type="csv", $file_header="受注")
    {
        Log::debug(__METHOD__.':start---');
        $adm_user_id=$request->adm_user_id;
        $byr_buyer_id=$request->byr_buyer_id;
        $byr_buyer_id=$byr_buyer_id==null?Auth::User()->ByrInfo->byr_buyer_id:$byr_buyer_id;
        $file_name_info=byr_buyer::select('cmn_companies.company_name')
            ->join('cmn_companies', 'cmn_companies.cmn_company_id', '=', 'byr_buyers.cmn_company_id')
            ->where('byr_buyers.byr_buyer_id', $byr_buyer_id)
            ->first();
        $file_name = $file_header.'_'.$file_name_info->company_name.'_'.date('YmdHis').'.'.$file_type;
        // \Log::info($file_name);
        // $file_name = $file_name_info->super_code.'-'."shipment_".$file_name_info->super_code.'-'.$file_name_info->partner_code."-".$file_name_info->jcode.'_shipment_'.date('YmdHis').'.'.$file_type;
        Log::debug(__METHOD__.':end---');
        return $file_name;
    }

    public function downloadPdfFileName($order_info, $file_type="pdf", $file_header="発注明細書",$byr_buyer_id=null){
        Log::debug(__METHOD__.':start---');
        $file_name ="No_file.".$file_type;
        if (!empty($order_info)) {
            $file_name = $file_header.'_'.$order_info['mes_lis_buy_name'].'_'.$order_info['mes_lis_shi_tra_goo_major_category'].'_'.$order_info['mes_lis_shi_tra_dat_delivery_date_to_receiver'].'.'.$file_type;
        }else{
            $byr_info=byr_buyer::select('cmn_companies.company_name')
            ->join('cmn_companies','cmn_companies.cmn_company_id','=','byr_buyers.cmn_company_id')
            ->first();
            $file_name = $file_header.'_'.$byr_info->company_name.'_'.date('YmdHis').'.'.$file_type;
        }
        return $file_name;
        Log::debug(__METHOD__.':end---');
    }

    public function sendFileName($request, $file_type="csv", $file_header="shipment")
    {
        Log::debug(__METHOD__.':start---');
        $adm_user_id=$request->adm_user_id;
        $byr_buyer_id=$request->byr_buyer_id;
        $cmn_connect_id = $request->cmn_connect_id;
        $file_name_info=cmn_connect::select(
            'byr_buyers.super_code',
            'cmn_companies.jcode',
            'cmn_companies.company_name',
            'cmn_connects.partner_code'
        )
            ->join('byr_buyers', 'cmn_connects.byr_buyer_id', '=', 'byr_buyers.byr_buyer_id')
            ->join('slr_sellers', 'slr_sellers.slr_seller_id', '=', 'cmn_connects.slr_seller_id')
            ->join('cmn_companies', 'cmn_companies.cmn_company_id', '=', 'slr_sellers.cmn_company_id')
            ->where('cmn_connects.cmn_connect_id', $cmn_connect_id)
            ->first();
        $file_name = $file_name_info->super_code.'-'.$file_header.'_'.$file_name_info->super_code.'-'.$file_name_info->partner_code."-".$file_name_info->jcode.'_'.$file_header.'_'.date('YmdHis').'.'.$file_type;
        Log::debug(__METHOD__.':end---');
        return $file_name;
    }

    public function getCmnConnectId($adm_user_id, $byr_buyer_id)
    {
        $authUser = User::find($adm_user_id);
        $cmn_company_id = '';
        $cmn_connect_id = '';
        if (!$authUser->hasRole(config('const.adm_role_name'))) {
            $cmn_company_info=$this->get_user_info($adm_user_id, $byr_buyer_id);
            $cmn_company_id = $cmn_company_info['cmn_company_id'];
            $cmn_connect_id = $cmn_company_info['cmn_connect_id'];
        }
        return $cmn_connect_id;
    }
    // PDF Call
    public function fpdfRet()
    {
        Log::debug(__METHOD__.':start---');
        $receipt = new Fpdi();
        // Set PDF margins (top left and right)
        $receipt->SetMargins(0, 0, 0);

        // Disable header output
        $receipt->setPrintHeader(false);

        // Disable footer output
        $receipt->setPrintFooter(false);
        // $receipt->UseTemplate($tplIdx, null, null, null, null, true);
        $receipt->setFontSubsetting(true);
        // font declared
        $fontPathRegular = storage_path(config('const.MIGMIX_FONT_PATH'));
        $receipt->SetFont(\TCPDF_FONTS::addTTFfont($fontPathRegular), '', 8, '', true);

        Log::debug(__METHOD__.':end---');

        return $receipt;
    }
    // PDF Save function
    // public function pdfFileSave($receipt, $file_number,$pdf_save_path)
    public function pdfFileSave($receipt, $pdf_file_name=null,$pdf_save_path)
    {
        Log::debug(__METHOD__.':start---');
        // $pdf_file_name=date('YmdHis').'_'.$file_number.'_receipt.pdf';
        // $pdf_file_name=$new_file_name;

        $this->folder_create($pdf_save_path);
        $response = new Response(
            $receipt->Output(storage_path($pdf_save_path.$pdf_file_name), 'F'),
            200,
            array('content-type' => 'application / pdf')
        );
        $pdf_file_url = Config::get('app.url').'storage/'.$pdf_save_path.$pdf_file_name;
        $pdf_file_path = storage_path($pdf_save_path.$pdf_file_name);
        $file_info=array(
            'pdf_file_url'=>$pdf_file_url,
            'pdf_file_name'=>$pdf_file_name,
            'pdf_file_path'=>$pdf_file_path
        );
        Log::debug(__METHOD__.':end---');
        return $file_info;
    }
    public function pdfHeaderData($receipt, $pdf_data, $x, $y)
    {
        Log::debug(__METHOD__.':start---');
        $receipt->setSourceFile(storage_path(config('const.BLANK_ORDER_PDF_PATH')));
        $tplIdx = $receipt->importPage(1);
        $receipt->UseTemplate($tplIdx, null, null, null, null, true);
        $receipt->SetXY($x + 23, $y + 33.5);
        $receipt->Write(0, $pdf_data[0]->fax_number);
        $receipt->SetXY($x + 15, $y + 37.8);
        $receipt->Write(0, $pdf_data[0]->mes_lis_ord_par_sel_name_sbcs);
        $receipt->SetXY($x + 26.5, $y + 41.8);
        $receipt->Write(0, $pdf_data[0]->mes_lis_ord_par_sel_code);
        $receipt->SetXY($x + 122, $y + 33);
        $receipt->Write(0, $pdf_data[0]->mes_lis_ord_par_shi_name);
        Log::debug(__METHOD__.':end---');
        return $receipt;
    }

    public function coordinateText($receipt, $pdf_data, $x = 0, $y = 50.7, $sum_of_y=103.4)
    {
        Log::debug(__METHOD__.':start---');
        $classification_code=$pdf_data[0]->mes_lis_ord_tra_ins_goods_classification_code=='01'?'定番':($pdf_data[0]->mes_lis_ord_tra_ins_goods_classification_code=='03'?'特売':$pdf_data[0]->mes_lis_ord_tra_ins_goods_classification_code);
        //Cell($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=0, $link='', $stretch=0, $ignore_min_height=false, $calign='T', $valign='M')
        $receipt->SetXY($x + 29.6, $y);
        $receipt->Cell(14.8, 0, $pdf_data[0]->mes_lis_ord_par_rec_name_sbcs, 0, 1, 'L', 0, '', 0);
        $receipt->SetXY($x + 62.5, $y);
        $receipt->Cell(20, 0, str_pad($pdf_data[0]->mes_lis_ord_par_rec_code, 4, "0", STR_PAD_LEFT), 0, 1, 'C', 0, '', 0);
        $receipt->SetXY($x + 100.5, $y);
        $receipt->Cell(11.5, 0, '50', 0, 1, 'C', 0, '', 0);
        $receipt->SetXY($x + 121.5, $y);
        $receipt->Cell(11.5, 0, str_pad($pdf_data[0]->mes_lis_ord_tra_goo_major_category, 4, "0", STR_PAD_LEFT), 0, 1, 'C', 0, '', 0);
        $receipt->SetXY($x + 147.5, $y);
        $receipt->Cell(4.5, 0, $pdf_data[0]->mes_lis_ord_log_del_delivery_service_code, 0, 1, 'C', 0, '', 0);
        $receipt->SetXY($x + 170.2, $y);
        $receipt->Cell(22, 0, $pdf_data[0]->mes_lis_ord_tra_trade_number, 0, 1, 'C', 0, '', 0);
        $receipt->SetXY($x + 207, $y);
        $receipt->Cell(21.6, 0, date('y/m/d', strtotime($pdf_data[0]->mes_lis_ord_tra_dat_order_date)), 0, 1, 'C', 0, '', 0);
        $receipt->SetXY($x + 243, $y);
        $receipt->Cell(21.6, 0, date('y/m/d', strtotime($pdf_data[0]->mes_lis_ord_tra_dat_delivery_date_to_receiver)), 0, 1, 'C', 0, '', 0);
        $receipt->SetXY($x + 29.6, $y += 4.5);
        $receipt->Cell(14.8, 0, $classification_code, 0, 1, 'C', 0, '', 0);
        $y += 8.3;
        foreach ($pdf_data as $key1 => $value) {
            $receipt->SetXY($x += 14.7, $y);
            $receipt->Cell(14.8, 4.5, str_pad($value->mes_lis_ord_lin_lin_line_number, 2, "0", STR_PAD_LEFT), 0, 1, 'C', 0, '', 0);
            $receipt->SetXY($x += 15, $y);
            $receipt->Cell(52.5, 4.5, $value->mes_lis_ord_lin_ite_name_sbcs, 0, 1, 'L', 0, '', 0);
            $receipt->SetXY($x += 52.5, $y);
            $receipt->Cell(30, 4.5, $value->mes_lis_ord_lin_ite_order_item_code, 0, 1, 'L', 0, '', 0);
            $receipt->SetXY($x += 30, $y);
            $receipt->Cell(21, 4.5, intVal($value->mes_lis_ord_lin_qua_ord_quantity), 0, 1, 'R', 0, '', 0);
            $receipt->SetXY($x += 21, $y);
            $receipt->Cell(37, 4.5, number_format($value->mes_lis_ord_lin_amo_item_net_price_unit_price, 2), 0, 1, 'R', 0, '', 0);
            $receipt->SetXY($x += 37, $y);
            $receipt->Cell(36.8, 4.5, number_format($value->mes_lis_ord_lin_amo_item_net_price), 0, 1, 'R', 0, '', 0);
            $receipt->SetXY($x += 36.8, $y);
            $receipt->Cell(21.5, 4.5, number_format($value->mes_lis_ord_lin_amo_item_selling_price_unit_price), 0, 1, 'R', 0, '', 0);
            $receipt->SetXY($x += 21.5, $y);
            $receipt->Cell(36, 4.5, number_format($value->mes_lis_ord_lin_amo_item_selling_price), 0, 1, 'R', 0, '', 0);
            $x = 0;
            $y += 4.5;
        }
        $x = 0;
        $receipt->SetXY($x + 170.5, $sum_of_y);
        $receipt->Cell(36.5, 4.5, number_format($value->mes_lis_ord_tot_tot_net_price_total), 0, 1, 'R', 0, '', 0);
        $receipt->SetXY($x + 228.2, $sum_of_y);
        $receipt->Cell(36.5, 4.5, number_format($value->mes_lis_ord_tot_tot_selling_price_total), 0, 1, 'R', 0, '', 0);
        $y=0;
        Log::debug(__METHOD__.':end---');
        return $receipt;
    }

    public function logInformation($byr_buyer_id){
        // $authUser = User::find($adm_user_id);

        $authUser = Auth::User();
        $adm_user_id = Auth::User()->id;
            $cmn_company_id = '';
            $cmn_connect_id = '';
            if (!$authUser->hasRole(config('const.adm_role_name'))) {
                $cmn_company_info=$this->get_user_info($adm_user_id,$byr_buyer_id);
                $cmn_company_id = $cmn_company_info['cmn_company_id'];
                $cmn_connect_id = $cmn_company_info['cmn_connect_id'];
            }
            $cmn_connect_info=cmn_connect::select('cmn_connects.partner_code',DB::raw("(select cmn_companies.company_name from cmn_companies inner join byr_buyers on cmn_companies.cmn_company_id = byr_buyers.cmn_company_id where byr_buyers.byr_buyer_id=cmn_connects.byr_buyer_id) as buyer_name"),
            DB::raw("(select cmn_companies.company_name from cmn_companies inner join slr_sellers on cmn_companies.cmn_company_id = slr_sellers.cmn_company_id where slr_sellers.slr_seller_id=cmn_connects.slr_seller_id) as seller_name"))
            ->where('cmn_connects.cmn_connect_id',$cmn_connect_id)
            ->first();
            return $cmn_connect_info;
    }
    public function getByrJsonData($request){
        $byr_buyer_id =$request->byr_buyer_id;
        $byr_buyer_id =$byr_buyer_id?$byr_buyer_id:Auth::User()->ByrInfo->byr_buyer_id;
        $buyer_settings = byr_buyer::select('setting_information')->where('byr_buyer_id', $byr_buyer_id)->first();
        $result = $buyer_settings->setting_information;
        return $result;
    }
    public function getbyrjsonValueBykeyName($arrKeyName, $arrVal=null, $dataType = "orders", $buyer_settings) {
        $values='';
        if (isset($buyer_settings)) {
            $buyer_settings=(array)$buyer_settings;
            if (isset($buyer_settings[$dataType])) {
                $dataType=(array)$buyer_settings[$dataType];
                if (isset($dataType[$arrKeyName])) {
                    $arrKeyName=(array)$dataType[$arrKeyName];
                    $values=$arrKeyName[$arrVal];
                }
            }
        }
        return $values;
    }
}
