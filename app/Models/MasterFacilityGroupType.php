<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterFacilityGroupType extends Model
{
    protected $table = 'master_facility_group_type';
    
    protected $fillable = [
        'type',	
        'calendarGroupId'
    ];

}
