<?php

namespace App\Exports;

use App\MasterFacility;
use Maatwebsite\Excel\Concerns\FromCollection;

class MasterEquipmentExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return MasterFacility::all();
    }
}
