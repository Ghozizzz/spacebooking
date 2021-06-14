<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MasterFacilityEquipment;
use App\Exports\MasterFacilityEquipmentExport;
use App\Imports\MasterFacilityEquipmentImport;
use Maatwebsite\Excel\Facades\Excel;

class MasterFacilityEquipmentController extends Controller
{
    public function index()
    {
        $dataMasterFacilityEquipment = MasterFacilityEquipment::all();
        $viewData = $this->loadViewData();
        if (session('userName')){
            $data=[
                'userName' => $viewData['userName'],
                'userEmail' => $viewData['userEmail'],
                'dataMasterFacilityEquipments' => $dataMasterFacilityEquipment,
            ];
        }

        return view('masterFacilityEquipment.index', $data);
    }

     /**
    * @return \Illuminate\Support\Collection
    */
    public function export() 
    {
        return Excel::download(new MasterFacilityEquipmentExport, 'MasterFacilityEquipment.xlsx');
    }
    
    /**
     * @return \Illuminate\Support\Collection
     */
    public function import() 
    {
        Excel::import(new MasterFacilityEquipmentImport,request()->file('file'));
            
        return back();
    }
}
