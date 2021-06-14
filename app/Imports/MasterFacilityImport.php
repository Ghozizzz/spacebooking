<?php

namespace App\Imports;

use App\Models\MasterFacility;
use App\Models\MasterFacilityGroupType;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\TokenStore\TokenCache;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Stream\Stream;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\Http;

class MasterFacilityImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $masterFacility = MasterFacility::firstOrNew([
            'setId' => $row['setid'],
            'facilId' => $row['facil_id']
        ]);
        // $masterFacility->setId = $row['setid'];
        $masterFacility->status = $row['status'];
        $masterFacility->building = $row['building'];
        $masterFacility->description = $row['descr'];
        $masterFacility->type = $row['type'];
        $masterFacility->location = $row['location'];
        $masterFacility->capacity = $row['capacity'];
        $masterFacility->qrcode = $masterFacility->id.rand(1000, 9999);
        if(isset($row['owner'])){
            $masterFacility->owner = $row['owner'];
	    }
        $masterFacility->save();

        return $masterFacility;
    }

    public function headingRow(): int
    {
        return 2;
    }
}
