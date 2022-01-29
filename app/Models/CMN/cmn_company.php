<?php

namespace App\Models\CMN;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class cmn_company extends Model
{
    use SoftDeletes;
    public function byr_companies()
    {
        return $this->hasMany('App\Models\BYR\byr_buyer', 'cmn_company_id');
    }

}
