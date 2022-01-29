<?php

namespace App\Models\BYR;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class byr_buyer extends Model
{
    use SoftDeletes;

    public function byr_company()
    {
        return $this->belongsTo('App\Models\BYR\cmn_company');
    }
}
