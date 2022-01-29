<?php

namespace App\Scenarios;
use App\Scenarios\Common;
use App\Models\BYR\byr_order;
use App\Http\Controllers\API\AllUsedFunction;
use App\Exports\IndepenCSVExport;

class indepen_csv_generate
{
    private $all_functions;
    private $common_class_obj;
    public function __construct()
    {
        $this->common_class_obj = new Common();
        $this->all_functions = new AllUsedFunction();
    }

    public function exec($request,$sc)
    {
        $byr_order_id=$request->order_id;
        $new_file_name = "indepen_csv_".date('Y-m-d')."_".time().".csv";
        $download_file_url = \Config::get('app.url')."storage/app/".config('const.INDEPEN_CSV_PATH'). $new_file_name;

        (new IndepenCSVExport($byr_order_id))->store(config('const.INDEPEN_CSV_PATH').'/'.$new_file_name);
        return response()->json(['message' => 'Success', 'url' => $download_file_url]);
    }
    

    
}
