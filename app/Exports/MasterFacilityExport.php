<?php

namespace App\Exports;

use App\Models\MasterFacility;
use Maatwebsite\Excel\Concerns\FromCollection;

class MasterFacilityExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return MasterFacility::all();
    }
}
