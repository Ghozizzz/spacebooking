<?php

namespace App\Http\Controllers;
ini_set('max_execution_time', 0);

use Illuminate\Http\Request;
use App\Models\UserBooking;
use App\Models\MasterPeriod;
use App\Models\MasterFacility;
use App\Models\MonitorClass;
use Carbon\Carbon;
use App\TokenStore\TokenCache;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Client;

class CalenderController extends Controller
{
    public function index(Request $request)
    {
        if(request()->ajax()) 
        {
            $start = (!empty($_GET["start"])) ? ($_GET["start"]) : ('');
            $end = (!empty($_GET["end"])) ? ($_GET["end"]) : ('');
            $data = [];
            $calendarData = [];
            $date = Carbon::now();
            $startOfYear = $date->copy()->startOfYear()->format('yy-m-d');
            $endOfYear   = $date->copy()->endOfYear()->format('yy-m-d');

            $facility = MasterFacility::find($request->id);

            $monitorClasses = MonitorClass::where('facilId', $facility->facilId)
            ->get();

            $UserBookings = UserBooking::where('approvalStatus','accept')
                            ->where('masterFacilityId', $request->id)
                            ->get();

            foreach ($UserBookings as $userBooking) {
                $date            = $userBooking->bookDate->locale('id');
                $timeExplode     = explode(":",$userBooking->bookTime);
                $startTimeString = "$timeExplode[0]:$timeExplode[1]:00";
                $startTime       = Carbon::createFromTimeString("$startTimeString");
                
                $bookDurationInt = intval($userBooking->bookDuration);
                $endTime         = Carbon::createFromTimeString("$startTimeString")->addMinutes($bookDurationInt);
                
                // $startTime        = Carbon::createFromTimeString("$userBooking->bookStart");
                // $endTime          = Carbon::createFromTimeString("$userBooking->bookEnd");

                $startTime        = $startTime->format('H:i:s');
                $endTime          = $endTime->format('H:i:s');
                $bookingDate      = $userBooking->bookDate->format('yy-m-d');
                // $startBookingTime = "$bookingDate".'T'."$startTime";
                // $endBookingTime   = "$bookingDate".'T'."$endTime";
                $startBookingTime = $userBooking->bookStart;
                $endBookingTime   = $userBooking->bookEnd;

                $calendarData['id']             = $userBooking->id;
                $calendarData['approvalStatus'] = $userBooking->approvalStatus;
                if(is_null($userBooking->eventId)){
                    $calendarData['color'] = 'blue';
                }
                $calendarData['title']          = $userBooking->eventName;
                $calendarData['start']          = $startBookingTime;
                $calendarData['end']            = $endBookingTime;
                array_push($data,$calendarData);
            }
            // print_r($data);die();
            // $calendarData = [];
            foreach ($monitorClasses as $monitorClass ) {
        		$period = MasterPeriod::where('term', $monitorClass->term)
        		->where('institution', $monitorClass->institution)
        		->where('career', $monitorClass->career)
        		->first();

        		$beginDate = $period->beginDate;
        		$endDate = $period->endDate;

                $timeExplode = explode("-",$monitorClass->jam);
                $startTime   = Carbon::createFromTimeString("$timeExplode[0]");
                $endTime     = Carbon::createFromTimeString("$timeExplode[1]");
                
                
                $startTime = $startTime->format('H:i:s');
                $endTime   = $endTime->format('H:i:s');
                $numberDay = $this->getDayNumber($monitorClass->hari);

                $calendarData['dow']   = [ $numberDay ];
                $calendarData['id']    = $monitorClass->id;
                $calendarData['title'] = $monitorClass->description;
                $calendarData['start'] = $startTime;
                $calendarData['end']   = $endTime;
                $calendarData['beginDate'] = $beginDate;
                $calendarData['endDate'] = $endDate;
                $calendarData['color'] = 'red';
                array_push($data,$calendarData);
            }
            return response()->json($data);
        }
    }

    public function getDayNumber($dayName)
    {
        switch ($dayName) {
            case 'SENIN':
                return 1;
                break;
            
            case 'SELASA':
                return 2;
                break;

            case 'RABU':
                return 3;
                break;
            
            case 'KAMIS':
                return 4;
                break;

            case 'JUMAT':
                return 5;
                break;
            
            case 'SABTU':
                return 6;
                break;

            case 'MINGGU':
                return 0;
                break;
        }
    }

    public function deleteAllCalendar()
    {
        $test = 0;
        $tokenCache = new TokenCache();
        $client = new Client;
        $token = $tokenCache->getAccessToken();
        $tokenWithBearer = "Bearer $token";
        $USER_ID = env('USER_ID');
        $GRAPH_ROOT_URL = env('GRAPH_ROOT_URL');
        $nextLinkAvailability = True;
        $retrieveAllCalendarUrl = $GRAPH_ROOT_URL."/users/".$USER_ID.'/calendars?$skip=1';
        try {
            while ($nextLinkAvailability) {
                $res = $client->request('GET',  $retrieveAllCalendarUrl, [
                        'headers'        => [
                            'Content-type' => 'application/json',
                            'Authorization' => $tokenWithBearer,
                        ],
                    ], 
                    [
                        'http_errors' => false
                    ]
                );

                $data = json_decode($res->getBody()->getContents(),true);
                foreach( $data['value'] as $i ){
                    $deleteCalendarUrl = $GRAPH_ROOT_URL."/users/".$USER_ID."/calendars/".$i['id'];
                    try{
                        $res = $client->request('DELETE',  $deleteCalendarUrl, [
                                'headers'        => [
                                    'Authorization' => $tokenWithBearer,
                                ],
                            ], 
                            [
                                'http_errors' => false
                            ]
                        );
                        if ($res->getStatusCode() == 204 ){
                            $test = $test+1;
                        }
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

                if(isset($data['@odata.nextLink'])){
                    $retrieveAllCalendarUrl = $data['@odata.nextLink'];
                }else{
                    $nextLinkAvailability = False;
                }
            }
        } catch (ClientException $th) {
            $th = $th->getResponse()->getBody(true)->getContents();
            $th = json_decode($th);
            if($th->error->code === 'InvalidAuthenticationToken'){
                $tokenCache = new TokenCache();
                $tokenCache->clearTokens();
                return redirect()->route('login.index');
            }
        }

        return $test;
    }

    public function DeleteAllCalendarGroup()
    {
        $tokenCache = new TokenCache();
        $client = new Client;
        $test = 0;
        $token = $tokenCache->getAccessToken();
        $tokenWithBearer = "Bearer $token";
        $USER_ID = env('USER_ID');
        $GRAPH_ROOT_URL = env('GRAPH_ROOT_URL');
        try {
            $nextLinkAvailability = True;
            $retrieveAllCalendarGroupsUrl = $GRAPH_ROOT_URL."/users/".$USER_ID.'/calendarGroups?$skip=3';
            while ($nextLinkAvailability) {
                $res = $client->request('GET',  $retrieveAllCalendarGroupsUrl, [
                        'headers'        => [
                            'Content-type' => 'application/json',
                            'Authorization' => $tokenWithBearer,
                        ],
                    ], 
                    [
                        'http_errors' => false
                    ]
                );
               

                $data = json_decode($res->getBody()->getContents(),true);
                foreach( $data['value'] as $i ){
                    $deleteCalendarGroupUrl = $GRAPH_ROOT_URL."/users/".$USER_ID."/calendarGroups/".$i['id'];
                    try{
                        $res = $client->request('DELETE',  $deleteCalendarGroupUrl, [
                                'headers'        => [
                                    'Authorization' => $tokenWithBearer,
                                ],
                            ], 
                            [
                                'http_errors' => false
                            ]
                        );

                        if ($res->getStatusCode() == 204 ){
                            $test = $test+1;
                        }
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

                if(isset($data['@odata.nextLink'])){
                    $retrieveAllCalendarGroupsUrl=$data['@odata.nextLink'];
                }else{
                    $nextLinkAvailability = False;
                }
            }
        } catch (ClientException $th) {
            $th = $th->getResponse()->getBody(true)->getContents();
            $th = json_decode($th);
            if($th->error->code === 'InvalidAuthenticationToken'){
                $tokenCache = new TokenCache();
                $tokenCache->clearTokens();
                return redirect()->route('login.index');
            }
        }
        return $test;
    }
}
