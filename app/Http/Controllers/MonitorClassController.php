<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MonitorClass;
use App\Models\MasterPeriod;
use App\Exports\MonitorClassExport;
use App\Imports\MonitorClassImport;
use Maatwebsite\Excel\Facades\Excel;
use App\TokenStore\TokenCache;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Stream\Stream;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\Http;

class MonitorClassController extends Controller
{
    public function index()
    {
        $date = Carbon::now();   
        $monitorClasses = MonitorClass::whereNull('eventId')
                        ->whereNull('lastDateOfYear')
                        ->orWhere('lastDateOfYear','<=',$date)
                        ->get(); 
        $monitorClassesWithNoProblem = MonitorClass::has('facilities')
                        ->whereNotNull('facilId')
                        ->whereNull('eventId')
                        ->whereNull('lastDateOfYear')
                        ->orWhere('lastDateOfYear','<=',$date)
                        ->get(); 
        $monitorClassesNoFacility = MonitorClass::doesntHave('facilities')
                        ->whereNull('eventId')
                        ->whereNull('lastDateOfYear')
                        ->orWhere('lastDateOfYear','<=',$date)
                        ->get();   
        $countmonitorClassesWithNoProblem = $monitorClassesWithNoProblem->count();
        $countMonitorClassesNoFacility = $monitorClassesNoFacility->count();
        $warning = false;
        if($countMonitorClassesNoFacility > $countmonitorClassesWithNoProblem ){
            $warning = true;
        }
        $viewData = $this->loadViewData();
        if (session('userName')){
            $data=[
                'userName'                         => $viewData['userName'],
                'userEmail'                        => $viewData['userEmail'],
                'monitorClasses'                   => $monitorClassesWithNoProblem,
                'monitorClassesNoFacility'         => $monitorClassesNoFacility,
                'countmonitorClassesWithNoProblem' => $countmonitorClassesWithNoProblem,
                'warning'                          => $warning
            ];
        }

        return view('masterMonitorClass.index', $data);
    }

     /**
    * @return \Illuminate\Support\Collection
    */
    public function export() 
    {
        return Excel::download(new MonitorClassExport, 'MonitorClass.xlsx');
    }
    
    /**
     * @return \Illuminate\Support\Collection
     */
    public function import() 
    {
        Excel::import(new MonitorClassImport,request()->file('file'));
            
        return back();
    }

    public function synchronize()
    {
        $tokenCache = new TokenCache();
        $client = new Client;
        $token = $tokenCache->getAccessToken();
        $tokenWithBearer = "Bearer $token";
        $test = [];
        
        $date = Carbon::now();
        
        $monitorClasses = MonitorClass::whereNotNull('facilId')->whereNull('eventId')->whereNull('lastDateOfYear')->get();

        foreach ($monitorClasses as $monitorClass) {
        if(is_null($monitorClass->facilities)){continue;}
            $masterPeriod = MasterPeriod::where('institution',$monitorClass->institution)->where('career',$monitorClass->career)->where('term',$monitorClass->term)->first();
            $startOfYear = $masterPeriod->beginDate; 
            $endOfYear   = $masterPeriod->endDate;
            $calenderUrl = env('GRAPH_CALENDER_URL');
            $timeExplode = explode("-",$monitorClass->jam);
            $startTime = Carbon::createFromTimeString("$timeExplode[0]");
            
            $endTime = Carbon::createFromTimeString("$timeExplode[1]");
            
            $startTime = $startTime->format('H:i:s');
            $endTime = $endTime->format('H:i:s');

            $englishDayName = $this->getEnglishDayName($monitorClass->hari);
            $bookingDate = new Carbon($englishDayName);
            $bookingDate = $bookingDate->format('yy-m-d');
            $startBookingTime = "$bookingDate".'T'."$startTime";
            $endBookingTime = "$bookingDate".'T'."$endTime";

            $calenderId = $monitorClass->facilities->calenderId;
            $calenderUrl = "$calenderUrl/$calenderId/events";
            try {
                $res = $client->request('POST', $calenderUrl , [
                        'headers'        => [
                            'Content-type' => 'application/json',
                            'Authorization' => $tokenWithBearer,
                        ],
                        'json' =>[
                            "subject"=> $monitorClass->jurusan,
                            "body"=> [
                                "contentType"=> "HTML",
                                "content"=> $monitorClass->description
                            ],
                            "start"=> [
                                "dateTime"=> $startBookingTime,
                                "timeZone"=> "Asia/Bangkok"
                            ],
                            "end"=> [
                                "dateTime"=> $endBookingTime,
                                "timeZone"=> "Asia/Bangkok"
                            ],
                            "location"=>[
                                "displayName"=>$monitorClass->facilities->facilId
                            ],
                            "attendees"=> [
                                [
                                "emailAddress"=> [
                                    "address" => $monitorClass->displayName,
                                    "name"    => $monitorClass->displayName
                                ],
                                "type" => "required"
                                ]
                            ],
                            "recurrence" => [
                                "pattern"=> [
                                    "type"       => "weekly",
                                    "interval"   => 1,
                                    "daysOfWeek" => [ "$englishDayName" ]
                                ],
                                
                                "range"=> [
                                    "type"=> "endDate",
                                    "startDate"=> $startOfYear,
                                    "endDate"=> $endOfYear
                                ]
                            ]
                            
                        ]
                    ], 
                    [
                        'http_errors' => false
                    ]
                );
                $arrayData = json_decode($res->getBody()->getContents(),true);
                array_push($test,$arrayData['id']);    
                $monitorClass->eventId = $arrayData['id'];
                $monitorClass->lastDateOfYear = $endOfYear;
                $monitorClass->save();
            } catch (ClientException $th) {
                $th = $th->getResponse()->getBody(true)->getContents();
                $th = json_decode($th);
                if($th->error->code === 'InvalidAuthenticationToken'){
                    $tokenCache = new TokenCache();
                    $tokenCache->clearTokens();
                    return redirect()->route('login.index');
                }
            }
        }
	
        return redirect()->route('home.monitor.class')->with('sync','selesai');
    }

    public function getEnglishDayName($dayName)
    {
        switch ($dayName) {
            case 'SENIN':
                return "monday";
                break;
            
            case 'SELASA':
                return "tuesday";
                break;

            case 'RABU':
                return "wednesday";
                break;
            
            case 'KAMIS':
                return "thursday";
                break;

            case 'JUMAT':
                return "friday";
                break;
            
            case 'SABTU':
                return "saturday";
                break;

            case 'MINGGU':
                return "sunday";
                break;
        }
    }
}
