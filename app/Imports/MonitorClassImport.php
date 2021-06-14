<?php

namespace App\Imports;

use App\Models\MonitorClass;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class MonitorClassImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $monitorClass = MonitorClass::firstOrNew(array(
            'institution' => $row['institution'],
            'term'        => $row['term'],
            'acadGroup'   => $row['acad_group'],
            'acadOrg'     => $row['acad_org'],
            'courseId'    => $row['course_id'],
            'classNbr'    => $row['class_nbr'],
            'section'     => $row['section'],
            'id'          => $row['id'],
            'patNbr'     => $row['pat_nbr'],
            'career'     => $row['career'],
        ));
        $monitorClass->session = $row['session'];
        $monitorClass->offerNbr = $row['offer_nbr'];
        $monitorClass->fakultas = $row['fakultas'];
        $monitorClass->jurusan = $row['jurusan'];
        $monitorClass->subject = $row['subject'];
        $monitorClass->catalog = $row['catalog'];
        $monitorClass->description = $row['description'];
        $monitorClass->component = $row['component'];
        $monitorClass->displayName = $row['display_name'];
        $monitorClass->patNbr = $row['pat_nbr'];
        $monitorClass->hari = $row['hari'];
        $monitorClass->jam = $row['jam'];
        $monitorClass->facilId = $row['facil_id'];
        $monitorClass->totEnrl = $row['tot_enrl'];
        $monitorClass->classStat = $row['class_stat'];
        $monitorClass->classType = $row['class_type'];
        $monitorClass->combSectsId = $row['comb_sects_id'];
        $monitorClass->save();
        return $monitorClass;
    }

    public function headingRow(): int
    {
        return 2;
    }
}
