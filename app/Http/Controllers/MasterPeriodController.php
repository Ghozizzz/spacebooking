<?php

namespace App\Http\Controllers;

use App\Models\MasterPeriod;
use Illuminate\Http\Request;
use App\Imports\MasterPeriodImport;
use Maatwebsite\Excel\Facades\Excel;

class MasterPeriodController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $dataMasterPeriod = MasterPeriod::all()->sortByDesc('beginDate');
        $viewData = $this->loadViewData();
        if (session('userName')){
            $data=[
                'userName' => $viewData['userName'],
                'userEmail' => $viewData['userEmail'],
                'dataMasterPeriods' => $dataMasterPeriod,
            ];
        }
        // return $data;
        return view('masterPeriod.index', $data);
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function import() 
    {
        Excel::import(new MasterPeriodImport,request()->file('file'));
            
        return back();
    }
}
