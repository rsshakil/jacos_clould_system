<?php

namespace App\Models\SLR;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class slr_seller extends Model
{
    use SoftDeletes;
    public function slr_companies()
    {
        return $this->hasMany('App\Models\CMN\cmn_company', 'cmn_company_id', 'slr_seller_id');
    }
}
