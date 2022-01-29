<?php

namespace App\Models\LV3;

use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class lv3_history extends Model
{
    /**
     * Prepare a date for array / JSON serialization.
     *
     * @param  \DateTimeInterface  $date
     * @return string
     */
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
