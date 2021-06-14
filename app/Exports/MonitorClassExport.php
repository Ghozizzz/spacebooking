<?php

namespace App\Exports;

use App\MonitorClass;
use Maatwebsite\Excel\Concerns\FromCollection;

class MonitorClassExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return MonitorClass::all();
    }
}
