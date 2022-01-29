<?php

namespace App\Scenarios;
use App\Http\Controllers\API\AllUsedFunction;
use Illuminate\Support\Facades\Storage;

class file_delete_scenario
{
    private $all_functions;
    public function __construct()
    {
        $this->all_functions = new AllUsedFunction();
    }

    public function exec($request,$sc)
    {
        $directory=$request->directory;
        $files=Storage::files($directory);
        $files_name_date=array();
        $files_names=array();
        foreach ($files as $key => $filename) {
            $files_name_date[]=date('y-m-d',strtotime($this->all_functions->header_part(\explode('/',$filename)[1])));
            $files_names[]=\explode('/',$filename)[1];
        }
        $req_start_date=$request->start_date;
        if ($req_start_date==null) {
            $start_date=(!empty($files_name_date)?min($files_name_date): date('y-m-d'));
        }else{
            $start_date=date('y-m-d',strtotime($req_start_date));
        }

        $end_date=date('y-m-d',strtotime($request->end_date));
        $extension=$request->extension;

        $deletable_files=array();
        for ($i=$start_date; $i <= $end_date; $i++) {
            foreach ($files_names as $name_key => $value) {
                if ($i==$this->all_functions->header_part($value) && $extension==$this->all_functions->ext_check($value)) {
                    $deletable_files[]=$directory.'/'.$value;
                }
            }
        }
        Storage::delete($deletable_files);
        return response()->json(['message'=>"File has been deleted"]);
    }



}
