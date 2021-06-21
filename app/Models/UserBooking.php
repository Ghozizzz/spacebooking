<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class UserBooking extends Model
{
    public $primaryKey  = 'id';
    protected $fillable = [
        'masterFacilityId',
        'bookDate',
        'bookTime',
        'bookDuration',
        'bookReason',
        'bookStart',
        'bookEnd',
        'eventName',
        'eventType',
        'file',
        'requestorId',
        'requestorName',
        'requestorPhone',
        'requestorFacility',
        'equipments',
        'approverId',
        'approvalStatus',
        'approvalReason',
        'approvedOn',
        'eventId',
    ];

    public function getbookDateAttribute($date)
    {
        return Carbon::parse($date);
    }   

    public function facilities()
    {
        return $this->belongsTo(\App\Models\MasterFacility::class, 'masterFacilityId');
    }
}
