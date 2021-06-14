<?php

namespace App\Imports;

use App\Models\MasterPeriod;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class MasterPeriodImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $masterPeriod = MasterPeriod::firstOrNew(
            [
                'institution' => $row['institution'],
                'career'        => $row['career'],
                'term'   => $row['term'],
            ],
            [
                'description'     => $row['description'],
                'shortDesc'    => $row['short_desc'],
                'beginDate'    => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['begin_date']),
                'endDate'     => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['end_date']),
            ]
        );
        $masterPeriod->save();
        return $masterPeriod;
    }

    public function headingRow(): int
    {
        return 2;
    }
}
