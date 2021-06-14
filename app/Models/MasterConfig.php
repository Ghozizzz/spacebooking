<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterConfig extends Model
{
    //
    protected $fillable = [
        'configName',
        'configValue',
    ];
}
