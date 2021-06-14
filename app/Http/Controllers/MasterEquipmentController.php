<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MasterEquipment;
use App\Exports\MasterEquipmentExport;
use App\Imports\MasterEquipmentImport;
use Maatwebsite\Excel\Facades\Excel;

class MasterEquipmentController extends Controller
{
    public function index()
    {
        $dataMasterEquipment = MasterEquipment::all();
        $viewData = $this->loadViewData();
        if (session('userName')){
            $data=[
                'userName' => $viewData['userName'],
                'userEmail' => $viewData['userEmail'],
                'dataMasterEquipments' => $dataMasterEquipment,
            ];
        }
        // return $data;
        return view('masterEquipment.index', $data);
    }

     /**
    * @return \Illuminate\Support\Collection
    */
    public function export() 
    {
        return Excel::download(new MasterEquipmentExport, 'MasterEquipment.xlsx');
    }
    
    /**
     * @return \Illuminate\Support\Collection
     */
    public function import() 
    {
        Excel::import(new MasterEquipmentImport,request()->file('file'));
            
        return back();
    }
}
