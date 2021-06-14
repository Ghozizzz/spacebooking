<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterEquipment extends Model
{
    protected $table = 'u_master_equipment';
    
    protected $fillable = [
        'roomChar',
        'effDate',
        'status',
        'descr',
    ];

    use SoftDeletes;
}
