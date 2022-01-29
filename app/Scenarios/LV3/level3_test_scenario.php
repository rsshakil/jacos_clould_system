<?php

namespace App\Scenarios\LV3;
use App\Http\Controllers\API\AllUsedFunction;

class level3_test_scenario
{
    private $all_functions;
    public function __construct()
    {
        $this->all_functions = new AllUsedFunction();
    }

    public function exec($request,$sc)
    {
        return response()->json(['status'=>0,'message' => 'Success']);
    }
    

    
}
