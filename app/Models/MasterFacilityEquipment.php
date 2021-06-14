<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterFacilityEquipment extends Model
{
    protected $table = 'u_master_facility_equipment';
    
    protected $fillable = [
        'setId',
        'facilId',
        'effDate',
        'status',
        'building',
        'room',
        'Descr',
        'roomChar',
        'quantity',

    ];

    use SoftDeletes;
}
