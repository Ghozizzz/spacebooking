<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterFacility extends Model
{
    protected $table = 'u_master_facility';
    public $primaryKey  = 'id';

    protected $fillable = [
        'setId',
        'facilId',
        'status',
        'building',
        'description',
        'type',
        'location',
        'capacity',
        'owner'
    ];

    use SoftDeletes;

    public function monitorClasses()
    {
        return $this->hasMany(\App\Models\MonitorClass::class, 'facilId' , 'facilId');
    }
}
