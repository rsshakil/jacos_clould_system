<?php

namespace App\Scenarios;

class taskctrl_test
{
    //
    public function exec($request,$sc)
    {
	    \Log::debug('taskctrl_test exec start  ---------------');
	    $taskctrl = config('const.TASKCTRL');
	    $opt = "-frw";
	    $workflow_name = "bms_jx_shipment_send";
	    $torihiki = "client-test";
	    $gyomu = "shipment";
	    $param = "test";
	    $cmd = "$taskctrl $opt $workflow_name $torihiki $gyomu $param";
	    \Log::debug($cmd);
	    exec($cmd,$out,$ret);
	    if($ret != 0){
		    // error
		    \Log::error($cmd);
		    \Log::error($out);
	    }
	    \Log::debug($ret);
	    \Log::debug($out);
	    \Log::debug('taskctrl_test exec end  ---------------');
            return;
    }


}
