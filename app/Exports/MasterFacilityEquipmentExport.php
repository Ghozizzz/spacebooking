<?php

namespace App\Exports;

use App\MasterFacilityEquipment;
use Maatwebsite\Excel\Concerns\FromCollection;

class MasterFacilityEquipmentExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return MasterFacilityEquipment::all();
    }
}
