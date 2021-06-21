<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MonitorClass extends Model
{
    protected $table = 'u_monitor_classes';
    
    protected $fillable = [
        'institution',
        'term',
        'session',
        'courseId',
        'offerNbr',
        'career',
        'acadGroup',
        'fakultas',
        'acadOrg',
        'jurusan',
        'classNbr',
        'section',
        'subject',
        'catalog',
        'description',
        'component',
        'id',
        'displayName',
        'patNbr',
        'hari',
        'jam',
        'start',
        'end',
        'facilId',
        'totEnrl',
        'classStat',
        'classType',
        'combSectsId',
    ];

    use SoftDeletes;

    public function facilities()
    {
        return $this->belongsTo(\App\Models\MasterFacility::class, 'facilId' , 'facilId');
    }
}
