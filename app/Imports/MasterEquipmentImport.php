<?php

namespace App\Imports;

use Carbon\Carbon;
use App\Models\MasterEquipment;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class MasterEquipmentImport implements ToModel, WithValidation, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $masterEquipment = MasterEquipment::firstOrNew(array('roomChar' => $row['room_char']));
        $masterEquipment->effDate = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['eff_date']);
        $masterEquipment->status = $row['status'];
        $masterEquipment->descr = $row['descr'];
        $masterEquipment->save();
        return $masterEquipment;
    }

    public function rules(): array
    {
        return [
           'room_char' => 'required|string',
           'status'    => 'required|string',
           'descr'     => 'required|string',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'room_char.required' => 'room char harus diisi',
            'room_char.string'   => 'room char harus string',
            'status.required'    => 'status harus diisi',
            'status.string'      => 'statu harus berbentuk string',
            'descr.required'     => 'description harus diisi',
            'descr.string'       => 'description harus berbentuk string',
        ];
    }

    public function headingRow(): int
    {
        return 2;
    }
}
