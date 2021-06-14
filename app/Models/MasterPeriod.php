<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterPeriod extends Model
{
    //
    protected $fillable = [
        'institution',
        'career',
        'term',
        'description',
        'shortDesc',
        'beginDate',
        'endDate',
    ];
}
