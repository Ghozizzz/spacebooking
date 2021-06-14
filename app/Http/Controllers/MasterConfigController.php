<?php

namespace App\Http\Controllers;

use App\Models\MasterConfig;
use App\Models\MasterPeriod;
use Illuminate\Http\Request;

class MasterConfigController extends Controller
{
    //
    public function index(){
        $configs = MasterConfig::all();
        $terms = MasterPeriod::select('term', 'description', 'beginDate', 'endDate')->distinct()->orderBy('term', 'desc')->get();
        $activeTerm = MasterConfig::where('configName','activeTerm')->first();
        $activeTerm = is_null($activeTerm) ? null : $activeTerm->configValue;
        return view('masterConfig.index', 
            [
                'configs' => $configs,
                'terms' => $terms,
                'activeTerm' => $activeTerm,
            ]
        );
    }

    public function store(Request $request){
        $request->validate([
            'timeSlotDuration' => 'required|gte:15',
            'bookStart' => 'required|date_format:H:i',
            'bookEnd' => 'required|date_format:H:i',
        ]);
        
        unset($request['_token']);

        foreach($request->all() as $key => $value){
            if($key !== 'days'){
                $value = trim($value);
            }

            $config = MasterConfig::updateOrCreate(
                ['configName' => $key],
                ['configValue' => $value]
            );

            $config->save();
        }

        $days = json_encode($request->days);

        $config = MasterConfig::where('configName', 'days')->first();
        $config->configValue = $days;

        return redirect()->route('home.config');
    }
}
