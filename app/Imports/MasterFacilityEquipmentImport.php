<?php

namespace App\Imports;

use App\Models\MasterFacilityEquipment;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class MasterFacilityEquipmentImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $masterFacilityEquipment = MasterFacilityEquipment::firstOrNew(array('facilId' => $row['facil_id'], 'roomChar' => $row['room_char']));
        $masterFacilityEquipment->setId = $row['setid'];
        $masterFacilityEquipment->facilId = $row['facil_id'];
        $masterFacilityEquipment->effDate = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['eff_date']);
        $masterFacilityEquipment->status = $row['status'];
        $masterFacilityEquipment->building = $row['building'];
        $masterFacilityEquipment->room = $row['room'];
        $masterFacilityEquipment->Descr = $row['descr'];
        $masterFacilityEquipment->quantity = $row['quantity'];
        $masterFacilityEquipment->save();
        return $masterFacilityEquipment;
    }

    public function headingRow(): int
    {
        return 2;
    }
}
