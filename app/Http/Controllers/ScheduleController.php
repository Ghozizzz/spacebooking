<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MonitorClass;
use App\Models\UserBooking;
use App\TokenStore\TokenCache;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Stream\Stream;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\Http;

class ScheduleController extends Controller
{
    public function index()
    {
        $data = [];
        $userBookings = UserBooking::where('approvalStatus','accept')->whereNull('eventId')->get();
        
        foreach($userBookings as $userBooking){
            $date = $userBooking->bookDate->locale('id');
            $timeExplode = explode(":",$userBooking->bookTime);
            $startTimeString ="$timeExplode[0]:$timeExplode[1]:00";
            $startTime = Carbon::createFromTimeString("$startTimeString");
            
            $bookDurationInt = intval($userBooking->bookDuration);
            $endTime = Carbon::createFromTimeString("$startTimeString")->addMinutes($bookDurationInt);
            
            $startTime = $startTime->format('H:i');
            $endTime = $endTime->format('H:i');

            $jam = "$startTime - $endTime";
            $dataMerge = [
                'facilId' => $userBooking->facilities->facilId, 
                'hari' => strtoupper($date->translatedFormat('l')),
                'jam' => $jam
            ];
            array_push($data,$dataMerge); 
        }

        $data = json_decode(json_encode($data), FALSE);
        $viewData    = $this->loadViewData();
        $countUserBookings = $userBookings->count();
        if (session('userName')){
            $data=[
                'userName' => $viewData['userName'],
                'userEmail' => $viewData['userEmail'],
                'schedules' => $data,
                'countUserBookings' => $countUserBookings
            ];
        }

        return view('Schedule.index', $data);
    }

    public function store()
    {
        $userBookings = UserBooking::where('approvalStatus','accept')->whereNull('eventId')->get();
        
        $tokenCache = new TokenCache();
        $client = new Client;
        $token = $tokenCache->getAccessToken();
        $tokenWithBearer = "Bearer $token";
        $rootCalenderUrl = env('GRAPH_CALENDER_URL');
    
        foreach ($userBookings as $userBooking) {
            $date = $userBooking->bookDate->locale('id');
            $timeExplode = explode(":",$userBooking->bookTime);
            $startTimeString ="$timeExplode[0]:$timeExplode[1]:00";
            $startTime = Carbon::createFromTimeString("$startTimeString");
            
            $bookDurationInt = intval($userBooking->bookDuration);
            $endTime = Carbon::createFromTimeString("$startTimeString")->addMinutes($bookDurationInt);
            
            $startTime = $startTime->format('H:i:s');
            $endTime = $endTime->format('H:i:s');
            $bookingDate = $userBooking->bookDate->format('yy-m-d');
            $startBookingTime = "$bookingDate".'T'."$startTime";
            $endBookingTime = "$bookingDate".'T'."$endTime";
            $calenderId =$userBooking->facilities->calenderId;

            $calenderUrl = $rootCalenderUrl.'/'.$calenderId.'/events';
            try {
                $res = $client->request('POST', $calenderUrl , [
                        'headers'        => [
                            'Content-type' => 'application/json',
                            'Authorization' => $tokenWithBearer,
                        ],
                        'json' =>[
                            "subject"=> $userBooking->eventName,
                            "body"=> [
                                "contentType"=> "HTML",
                                "content"=> $userBooking->eventName.'<p>Please confirm your booking by scanning the QR code in the room. You can confirm the booking 30 minutes before the book time.</p>'
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
                                "displayName"=>$userBooking->facilities->facilId
                            ],
                            "attendees"=> [
                                [
                                "emailAddress"=> [
                                    "address" => $userBooking->requestorId,
                                    "name"    => $userBooking->requestorName
                                ],
                                "type"=> "required"
                                ]
                            ]
                        ]
                    ], 
                    [
                        'http_errors' => false
                    ]
                );
                $arrayData = json_decode($res->getBody()->getContents(),true);
                
                $userBooking->eventId = $arrayData['id'];
                $userBooking->save();
            } catch (ClientException $th) {
                $th = $th->getResponse()->getBody(true)->getContents();

                $th = json_decode($th);
                
                if($th->error->code === 'RequestBroker--ParseUri'){
                    $tokenCache = new TokenCache();
                    $tokenCache->clearTokens();
                    return redirect()->route('login.index');
                }
            }
        }
        return redirect()->route('home.schedule');
    }
}
