<?php

namespace App\Http\Controllers;

use App\Models\UserBooking;
use App\Models\MasterFacility;
use App\Models\MasterConfig;
use App\Models\MonitorClass;
use App\Models\User;
use App\Mail\Booking;
use App\Mail\NewBooking;
use App\Mail\DeclineBooking;
use App\Mail\CancelBooking;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\TokenStore\TokenCache;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\Http;
use Mail;
use DB;

class UserBookingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $status = strtolower($request->status);
        // print_r(session('faculty_value'));die();
        if (session('facilities') && session('role') == 1) {
            if ($status == 'decline') {
                $booking = UserBooking::where('approvalStatus', 'decline')
                    ->whereIn('masterFacilityId', session('facilities'))
                    ->has('facilities')
                    ->orderBy('id', 'desc')
                    ->get();
            } elseif ($status == 'cancel') {
                $booking = UserBooking::where('approvalStatus', 'cancel')
                    ->whereIn('masterFacilityId', session('facilities'))
                    ->has('facilities')
                    ->orderBy('id', 'desc')
                    ->get();
            } elseif ($status == 'pending' || $status == 'revise' || $status == "") {
                $booking = UserBooking::whereIn('approvalStatus', ['pending', 'revise'])
                    ->whereIn('masterFacilityId', session('facilities'))
                    ->has('facilities')
                    ->orderBy('id', 'desc')
                    ->get();
            } elseif ($status == 'accept') {
                $booking = UserBooking::where('approvalStatus', 'accept')
                    ->whereIn('masterFacilityId', session('facilities'))
                    ->has('facilities')
                    ->orderBy('id', 'desc')
                    ->get();
            }

            //COUNT PENDING
            $pending_count = UserBooking::whereIn('approvalStatus', ['pending', 'revise'])
                ->whereIn('masterFacilityId', session('facilities'))
                ->has('facilities')
                ->orderBy('id', 'desc')
                ->count();

            $decline_count = UserBooking::where('approvalStatus', 'decline')
                ->where('updated_at', '>=', Carbon::now()->subDays(1))
                ->whereIn('masterFacilityId', session('facilities'))
                ->has('facilities')
                ->orderBy('id', 'desc')
                ->count();

            $accept_count = UserBooking::where('approvalStatus', 'accept')
                ->where('updated_at', '>=', Carbon::now()->subDays(1))
                ->whereIn('masterFacilityId', session('facilities'))
                ->has('facilities')
                ->orderBy('id', 'desc')
                ->count();

            $cancel_count = UserBooking::where('approvalStatus', 'cancel')
                ->where('updated_at', '>=', Carbon::now()->subDays(1))
                ->whereIn('masterFacilityId', session('facilities'))
                ->has('facilities')
                ->orderBy('id', 'desc')
                ->count();
        } else {
            //COUNT PENDING
            if(session('role') == 2){
                $pending_count = UserBooking::where(function($query){
                        $query->where('approvalStatus', 'pending')
                        ->orWhere('approvalStatus', 'revise');
                    })->leftJoin('u_users', function($join) {
                      $join->on('user_bookings.requestorId', '=', 'u_users.email');
                    })->has('facilities')
                    ->orderBy('user_bookings.id', 'desc')
                    ->count();

                $decline_count = UserBooking::where('approvalStatus','decline')
                    ->leftJoin('u_users', function($join) {
                      $join->on('user_bookings.requestorId', '=', 'u_users.email');
                    })->has('facilities')
                    ->where('user_bookings.updated_at', '>=', Carbon::now()->subDays(1))
                    ->orderBy('user_bookings.id', 'desc')
                    ->count();

                $accept_count = UserBooking::where('approvalStatus','accept')
                    ->leftJoin('u_users', function($join) {
                      $join->on('user_bookings.requestorId', '=', 'u_users.email');
                    })->has('facilities')
                    ->where('user_bookings.updated_at', '>=', Carbon::now()->subDays(1))
                    ->orderBy('user_bookings.id', 'desc')
                    ->count();

                $cancel_count = UserBooking::where('approvalStatus','cancel')
                    ->leftJoin('u_users', function($join) {
                      $join->on('user_bookings.requestorId', '=', 'u_users.email');
                    })->has('facilities')
                    ->where('user_bookings.updated_at', '>=', Carbon::now()->subDays(1))
                    ->orderBy('user_bookings.id', 'desc')
                    ->count();
            }elseif(session('role') == 3){
                $pending_count = UserBooking::where(function($query){
                        $query->where('approvalStatus', 'pending')
                        ->orWhere('approvalStatus', 'revise');
                    })->leftJoin('u_users', function($join) {
                      $join->on('user_bookings.requestorId', '=', 'u_users.email');
                    })->has('facilities')
                    ->whereIn('requestorFacility', explode(';',session('faculty_value')))
                    ->where('u_type', 1)
                    ->orderBy('user_bookings.id', 'desc')
                    ->count();

                $decline_count = UserBooking::where('approvalStatus','decline')
                    ->leftJoin('u_users', function($join) {
                      $join->on('user_bookings.requestorId', '=', 'u_users.email');
                    })->has('facilities')
                    ->where('user_bookings.updated_at', '>=', Carbon::now()->subDays(1))
                    ->whereIn('requestorFacility', explode(';',session('faculty_value')))
                    ->where('u_type', 1)
                    ->orderBy('user_bookings.id', 'desc')
                    ->count();

                $accept_count = UserBooking::where('approvalStatus','accept')
                    ->leftJoin('u_users', function($join) {
                      $join->on('user_bookings.requestorId', '=', 'u_users.email');
                    })->has('facilities')
                    ->where('user_bookings.updated_at', '>=', Carbon::now()->subDays(1))
                    ->whereIn('requestorFacility', explode(';',session('faculty_value')))
                    ->where('u_type', 1)
                    ->orderBy('user_bookings.id', 'desc')
                    ->count();

                $cancel_count = UserBooking::where('approvalStatus','cancel')
                    ->leftJoin('u_users', function($join) {
                      $join->on('user_bookings.requestorId', '=', 'u_users.email');
                    })->has('facilities')
                    ->where('user_bookings.updated_at', '>=', Carbon::now()->subDays(1))
                    ->whereIn('requestorFacility', explode(';',session('faculty_value')))
                    ->where('u_type', 1)
                    ->orderBy('user_bookings.id', 'desc')
                    ->count();
            }

            if ($status == 'decline') {
                // $booking = UserBooking::where('approvalStatus', 'decline')
                //     ->has('facilities')
                //     ->orderBy('id', 'desc')
                //     ->get();
                if(session('role') == 2){
                    $booking = UserBooking::where('approvalStatus','decline')
                        ->leftJoin('u_users', function($join) {
                          $join->on('user_bookings.requestorId', '=', 'u_users.email');
                        })->has('facilities')
                        // ->where('u_type', 0)
                        ->orderBy('user_bookings.id', 'desc')
                        ->get(['user_bookings.*']);
                }elseif(session('role') == 3){
                    $booking = UserBooking::where('approvalStatus','decline')
                        ->leftJoin('u_users', function($join) {
                          $join->on('user_bookings.requestorId', '=', 'u_users.email');
                        })->has('facilities')
                        ->whereIn('requestorFacility', explode(';',session('faculty_value')))
                        ->where('u_type', 1)
                        ->orderBy('user_bookings.id', 'desc')
                        ->get(['user_bookings.*']);
                }
            } elseif ($status == 'cancel') {
                // $booking = UserBooking::where('approvalStatus', 'cancel')
                //     ->has('facilities')
                //     ->orderBy('id', 'desc')
                //     ->get();
                if(session('role') == 2){
                    $booking = UserBooking::where('approvalStatus','cancel')
                        ->leftJoin('u_users', function($join) {
                          $join->on('user_bookings.requestorId', '=', 'u_users.email');
                        })->has('facilities')
                        // ->where('u_type', 0)
                        ->orderBy('user_bookings.id', 'desc')
                        ->get(['user_bookings.*']);
                }elseif(session('role') == 3){
                    $booking = UserBooking::where('approvalStatus','cancel')
                        ->leftJoin('u_users', function($join) {
                          $join->on('user_bookings.requestorId', '=', 'u_users.email');
                        })->has('facilities')
                        ->whereIn('requestorFacility', explode(';',session('faculty_value')))
                        ->where('u_type', 1)
                        ->orderBy('user_bookings.id', 'desc')
                        ->get(['user_bookings.*']);
                }
            } elseif ($status == 'pending' || $status == 'revise' || $status == "") {
                if(session('role') == 2){
                    $booking = UserBooking::where(function($query){
                            $query->where('approvalStatus', 'pending')
                            ->orWhere('approvalStatus', 'revise');
                        })->leftJoin('u_users', function($join) {
                          $join->on('user_bookings.requestorId', '=', 'u_users.email');
                        })->has('facilities')
                        // ->where('u_type', 0)
                        ->orderBy('user_bookings.id', 'desc')
                        ->get(['user_bookings.*']);
                }elseif(session('role') == 3){
                    $booking = UserBooking::where(function($query){
                            $query->where('approvalStatus', 'pending')
                            ->orWhere('approvalStatus', 'revise');
                        })->leftJoin('u_users', function($join) {
                          $join->on('user_bookings.requestorId', '=', 'u_users.email');
                        })->has('facilities')
                        ->whereIn('requestorFacility', explode(';',session('faculty_value')))
                        ->where('u_type', 1)
                        ->orderBy('user_bookings.id', 'desc')
                        ->get(['user_bookings.*']);
                }
            } elseif ($status == 'accept') {
                // $booking = UserBooking::where('approvalStatus', 'accept')
                //     ->has('facilities')
                //     ->orderBy('id', 'desc')
                //     ->get();
                if(session('role') == 2){
                    $booking = UserBooking::where('approvalStatus','accept')
                        ->leftJoin('u_users', function($join) {
                          $join->on('user_bookings.requestorId', '=', 'u_users.email');
                        })->has('facilities')
                        // ->where('u_type', 0)
                        ->orderBy('user_bookings.id', 'desc')
                        ->get(['user_bookings.*']);
                }elseif(session('role') == 3){
                    $booking = UserBooking::where('approvalStatus','accept')
                        ->leftJoin('u_users', function($join) {
                          $join->on('user_bookings.requestorId', '=', 'u_users.email');
                        })->has('facilities')
                        ->whereIn('requestorFacility', explode(';',session('faculty_value')))
                        ->where('u_type', 1)
                        ->orderBy('user_bookings.id', 'desc')
                        ->get(['user_bookings.*']);
                }
            }
        }
        $dataMasterConfigs['facilityCapacity'] = MasterConfig::all()->keyBy('configName')->get('facilityCapacity');

        return view('bookingManagement.index', [
            'booking' => $booking, 
            'pending_count' => $pending_count,
            'decline_count' => $decline_count,
            'accept_count' => $accept_count,
            'cancel_count' => $cancel_count,
            'dataMasterConfigs' => $dataMasterConfigs]);
    }

    public function myBooking()
    {
        //
        $user = $this->loadViewData();
        $booking = UserBooking::where('requestorId', $user['userEmail'])
            ->has('facilities')
            ->orderBy('id', 'desc')
            ->get();
        return view('welcome', ['booking' => $booking]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $validatedData = $request->validate([
            'masterFacilityId' => 'required',
            'bookStartDate'    => 'required',
            'bookStartHour'    => 'required',
            'bookStartMinute'  => 'required',
            'bookEndDate'      => 'required',
            'bookEndHour'      => 'required',
            'bookEndMinute'    => 'required',
            // 'bookTime'         => 'required',
            // 'bookDuration'     => 'required',
            // 'bookReason'       => 'required',
            'eventName'        => 'required',
            'eventType'        => 'required',
            'requestorCapacity'=> 'required',
            // 'file'             => 'required|mimes:pdf,zip|max:2048',
            'requestorPhone'   => 'required',
            'requestorFacility' => 'required',
        ]);

        DB::beginTransaction();

        $startDate = date('d/m/Y', strtotime($request->bookStartDate));
        $endDate = date('d/m/Y', strtotime($request->bookEndDate));

        $oriStart = date('Y-m-d', strtotime($request->bookStartDate)).' '.$request->bookStartHour.':'.$request->bookStartMinute;
        $oriEnd = date('Y-m-d', strtotime($request->bookEndDate)).' '.$request->bookEndHour.':'.$request->bookEndMinute;

        // echo $oriStart.' '.$oriEnd;die();
        if($oriEnd <= $oriStart){
            return redirect()->back()->with('warning', "End date can't be before Start date");
        }

        $request->bookDate = $startDate.' . '.$request->bookStartHour.':'.$request->bookStartMinute.' - '.$endDate.' . '.$request->bookEndHour.':'.$request->bookEndMinute;

        $callback = $this->check_booking($request);

        if($callback['checker'] > 0){
            return redirect()->back()->with('warning', $callback['message']);
        }

        // print_r($callback);die();
        
        $bookDate = $request->bookDate;
        $bD = explode('-', $bookDate);

        $bstart = explode('.', $bD[0]);
        $bend = explode('.', $bD[1]);

        $bs = str_replace("/", '-', $bstart[0]);
        $bookStart = date('Y-m-d H:i',strtotime($bs.' '.$bstart[1]));

        $be = str_replace("/", '-', $bend[0]);
        $bookEnd = date('Y-m-d H:i',strtotime($be.' '.$bend[1]));

        $book                 = $request->all();
        $book['bookDate']     = date('Y-m-d', strtotime($bs));
        $book['bookTime']     = str_replace(' ', '', $bstart[1]);
        $book['bookDuration'] = Carbon::parse($bookEnd)->diffInMinutes(Carbon::parse($bookStart));
        $book['bookStart']    = date('Y-m-d H:i:s',strtotime($bookStart));
        $book['bookEnd']      = date('Y-m-d H:i:s',strtotime($bookEnd));

        unset($book['_token']);

        $user = $this->loadViewData();
        $book['requestorName'] = $user['userName'];
        $book['requestorId'] = $user['userEmail'];
        $book['requestorCapacity'] = $request->requestorCapacity;

        // echo $book['requestorCapacity'];die();

        $book['equipments'] = json_encode($request->equipments);

        $pathName = $request->file('file');
        unset($book['file']);
        $booking = new UserBooking;
        $booking->fill($book);
        $booking->save();
        // die();

        $originalFileName = time() . '-' . $booking->id . '-' . $pathName->getClientOriginalName();

        try {
            $rootGraphUrl = env('GRAPH_ROOT_URL');
            $tokenCache = new TokenCache();
            $client = new Client;
            // $token = $tokenCache->getAccessToken();
            $token = $this->call_graph();
            $tokenWithBearer = "Bearer $token";

            $res = $client->request('PUT', $rootGraphUrl . "/sites/6c2e9cd5-b965-4b1a-ad60-e31ff17d6a54/drive/items/root:/$originalFileName:/content", [
                'headers'        => [
                    'Authorization' => $tokenWithBearer,
                ],
                "multipart" => [
                    [
                        'name'     => '$originalFileName',
                        'contents' => fopen($pathName, 'r')
                    ]
                ]

            ]);
            $arrayData = json_decode($res->getBody()->getContents(), true);
        } catch (ClientException $th) {
            $th = $th->getResponse()->getBody(true)->getContents();

            $th = json_decode($th);
            if ($th->error->code === 'RequestBroker--ParseUri') {
                $tokenCache = new TokenCache();
                $tokenCache->clearTokens();
                DB::rollBack();
                return redirect()->route('login.index');
            } else {
                DB::rollBack();
                return redirect()->back()->with('warning', 'Error Graph: '.$th->error->code.' | '.$th->error->message);
            }
        }

        $booking->file = $arrayData['id'];
        $booking->save();

        $loop_faculty = User::where('role',3)->WhereRaw('faculty_value like "%'.$booking->requestorFacility.'%"')->get();
        foreach ($loop_faculty as $value) {
            if($value->email){
                Mail::to($value->email)->send(new NewBooking($booking));
            }
        }
        Mail::to("room.host@uph.edu")->send(new NewBooking($booking));

        DB::commit();

        return redirect()->route('home.index');
    }

    public function check_booking($request){

        $data['checker'] = 0;
        $data['message'] = 'sukses';

        $facilId          = MasterFacility::find($request->masterFacilityId)->facilId;

        $bookDate = $request->bookDate;
        $bD = explode('-', $bookDate);

        $bstart = explode('.', $bD[0]);
        $bend = explode('.', $bD[1]);

        $bs = str_replace("/", '-', $bstart[0]);
        $bookStart = date('Y-m-d H:i:s',strtotime($bs.' '.$bstart[1].''));

        $be = str_replace("/", '-', $bend[0]);
        $bookEnd = date('Y-m-d H:i:s',strtotime($be.' '.$bend[1]));

        $dayName = '';
        $no = 0;
        $startBookTime = new \DateTime($bs);
        $endBookTime = new \DateTime($be);
        // echo $startBookTime.' |'.$endBookTime;die();

        $dayName = [];
        for ($date = $startBookTime; $date <= $endBookTime; $date->modify('+1 day')) {
            $newDate = $date->format('d-m-Y');
            $hari = Carbon::createFromFormat('d-m-Y', $newDate)->locale('id'); 
            $dayName[$no] = strtoupper($hari->translatedFormat('l'));

            $monitorClasses  = DB::table('u_monitor_classes')
                ->selectRaw("*, (CASE WHEN
                    hari = 'SENIN' THEN 1 WHEN
                    hari = 'SELASA' THEN 2 WHEN
                    hari = 'RABU' THEN 3 WHEN
                    hari = 'KAMIS' THEN 4 WHEN
                    hari = 'JUMAT' THEN 5 WHEN
                    hari = 'SABTU' THEN 6 WHEN
                    hari = 'MINGGU' THEN 7
                    ELSE 0 END
                ) as hari_number")
                ->where('facilId', $facilId)
                ->where('hari', $dayName[$no])
                ->orderBy('hari_number')
                ->get();

            $hari = '';
            foreach ($monitorClasses as $monitorClass) {
                $checker         = 0;
                $start_class = $bs.' '.$monitorClass->start.':00';
                $end_class = $bs.' '.$monitorClass->end.':00';
                $startTime = Carbon::createFromFormat('d-m-Y H:i:s', $start_class)->addDays($no);
                $endTime = Carbon::createFromFormat('d-m-Y H:i:s', $end_class)->addDays($no);

                $hari = $monitorClass->hari;
                if ($bookStart >= $startTime && $bookStart < $endTime) {
                    $checker++;
                } else {
                    if ($bookEnd > $startTime && $bookEnd <= $endTime) {
                        $checker++;
                    }else{
                        if($startTime > $bookStart && $startTime < $bookEnd){
                            $checker++;
                        }else{
                            if($endTime > $bookStart && $endTime < $bookEnd){
                                $checker++;
                            }
                        }
                    }
                }
                if ($checker > 0) {
                    $data['checker'] = $checker;
                    $data['message'] = 'The room is booked by another user classes';
                    return $data;
                }
            }
            $no++;
        }

        $userBookings = collect(DB::select("
            select masterFacilityId,
                sum(
                    CASE WHEN bookStart between '".$bookStart."' and '".$bookEnd."' THEN 1
                    WHEN bookEnd between '".$bookStart."' and '".$bookEnd."' THEN 1
                    WHEN bookStart <= '".$bookStart."' and bookEnd >= '".$bookEnd."' THEN 1
                    ELSE 0 END
                ) as jml
            from user_bookings where bookEnd >= '".$bookStart."'
            and masterFacilityId = ".$request->masterFacilityId." and approvalStatus in ('pending','accept')
            group by masterFacilityId
        "))->first();

        // print_r($userBookings);die();
        if(isset($userBookings)){
            if($userBookings->jml > 0){
                $data['checker'] = 1;
                $data['message'] = 'The room is booked by another user';
                return $data;
            }
        }

        return $data;
    }

    public function accept(Request $request)
    {
        $user            = $this->loadViewData();
        $userBooking     = UserBooking::find($request->id);
        $facilId         = $userBooking->facilities->facilId;
        $bookDate        = $userBooking->bookDate->locale('id');
        $formatedBookDate = $userBooking->bookDate->format('yy-m-d');
        $dayName         = strtoupper($bookDate->translatedFormat('l'));

        $startBookTime   = Carbon::createFromTimeStamp(strtotime("$userBooking->bookTime"));
        $bookDurationInt = intval($userBooking->bookDuration);
        $endBookTime     = $startBookTime->copy()->addMinutes($bookDurationInt);

        $monitorClasses  = MonitorClass::where('facilId', $facilId)->where('hari', $dayName)->get();

        foreach ($monitorClasses as $monitorClass) {
            $checker     = 0;
            $timeExplode = explode("-", $monitorClass->jam);
            $startTime   = Carbon::createFromTimeString("$timeExplode[0]");
            $endTime     = Carbon::createFromTimeString("$timeExplode[1]");
            if ($startBookTime >= $startTime && $startBookTime < $endTime) {
                $checker++;
            } else {
                if ($endBookTime > $startTime && $endBookTime <= $endTime) {
                    $checker++;
                }
            }
            if ($checker > 0) {
                $userBooking->approvalStatus = "decline";
                $userBooking->approvalReason = "[DECLINED] The room is booked by daily class schedule";
                $userBooking->approverId = $user['userEmail'];
                $userBooking->approvedOn = Carbon::now();
                $userBooking->save();
                return redirect()->back()->with('warning', "The room is booked by daily class schedule");
            }
        }

        $userBookings = UserBooking::where('masterFacilityId', $userBooking->masterFacilityId)->where('bookDate', $formatedBookDate)->whereNull('approvedOn')->get();

        foreach ($userBookings as $dataUserBooking) {
            $checker      = 0;
            $startTime       = Carbon::createFromTimeStamp(strtotime("$dataUserBooking->bookTime"));
            $bookDurationInt = intval($dataUserBooking->bookDuration);
            $endTime         = $startTime->copy()->addMinutes($bookDurationInt);
            if ($startBookTime >= $startTime && $startBookTime < $endTime) {
                $checker++;
            } else {
                if ($endBookTime > $startTime && $endBookTime <= $endTime) {
                    $checker++;
                }
            }
            if ($checker > 0) {
                $dataUserBooking->approvalStatus = "decline";
                $dataUserBooking->approvalReason = "[DECLINED] The room is booked by another user";
                $dataUserBooking->approverId = $user['userEmail'];
                $dataUserBooking->approvedOn = Carbon::now();
                $dataUserBooking->save();
            }
        }
        $userBookings = UserBooking::where('masterFacilityId', $userBooking->masterFacilityId)->where('bookDate', $formatedBookDate)->where('approvalStatus', 'accept')->get();

        foreach ($userBookings as $dataUserBooking) {
            $checker      = 0;
            $startTime       = Carbon::createFromTimeStamp(strtotime("$dataUserBooking->bookTime"));
            $bookDurationInt = intval($dataUserBooking->bookDuration);
            $endTime         = $startTime->copy()->addMinutes($bookDurationInt);
            if ($startBookTime >= $startTime && $startBookTime < $endTime) {
                $checker++;
            } else {
                if ($endBookTime > $startTime && $endBookTime <= $endTime) {
                    $checker++;
                }
            }
            if ($checker > 0) {
                $userBooking->approvalStatus = "decline";
                $userBooking->approvalReason = "[DECLINED] The room is booked by another user";
                $userBooking->approverId = $user['userEmail'];
                $userBooking->approvedOn = Carbon::now();
                $userBooking->save();
                return redirect()->back()->with('warning', "The room is booked by another user");
            }
        }
        $userBooking->approvalStatus = "accept";
        $userBooking->approvalReason = '[ACCEPTED] ' . $request->reason;
        $userBooking->approverId = $user['userEmail'];
        $userBooking->approvedOn = Carbon::now();
        $userBooking->save();

        Mail::to($userBooking->requestorId)->send(new Booking($userBooking));
        Mail::to("spacebooking@uph.edu")->send(new Booking($userBooking));
        // new Booking($userBooking);

        return redirect()->back();
    }

    public function decline(Request $request)
    {
        $user = $this->loadViewData();

        $userBooking = UserBooking::find($request->id);
        $userBooking->approvalStatus = "decline";
        $userBooking->approvalReason = '[DECLINED] ' . $request->reason;
        $userBooking->approverId = $user['userEmail'];
        $userBooking->approvedOn = Carbon::now();
        $userBooking->save();

        // Mail::to($userBooking->requestorId)->send(new DeclineBooking($userBooking));
        Mail::to("spacebooking@uph.edu")->send(new Booking($userBooking));

        return redirect()->back();
    }

    public function cancel(Request $request)
    {
        $user = $this->loadViewData();

        $userBooking = UserBooking::find($request->id);
        if ($userBooking->approvalStatus == 'pending' || $userBooking->approvalStatus == 'accept') {
            $userBooking->approvalStatus = "cancel";
            $userBooking->approvalReason = '[CANCELLED] ' . $request->reason;
            $userBooking->save();
        }

        // Mail::to($userBooking->requestorId)->send(new CancelBooking($userBooking));
        Mail::to("spacebooking@uph.edu")->send(new Booking($userBooking));

        return redirect()->back();
    }

    public function prompt(Request $request)
    {
        $user = $this->loadViewData();

        $userBooking = UserBooking::find($request->id);
        if ($userBooking->approvalStatus == 'pending') {
            $userBooking->approvalStatus = "revise";
            $userBooking->approvalReason = '[REVISION] ' . $request->reason;
            $userBooking->save();
        }

        return redirect()->back();
    }

    public function revise(Request $request)
    {
        //
        $validatedData = $request->validate([
            'masterFacilityId' => 'required',
            'bookStartDate'    => 'required',
            'bookStartHour'    => 'required',
            'bookStartMinute'  => 'required',
            'bookEndDate'      => 'required',
            'bookEndHour'      => 'required',
            'bookEndMinute'    => 'required',
            // 'bookTime'         => 'required',
            // 'bookDuration'     => 'required',
            // 'bookReason'       => 'required',
            'eventName'        => 'required',
            'eventType'        => 'required',
            'requestorCapacity'=> 'required',
            // 'file'             => 'required|mimes:pdf,zip|max:2048',
            'requestorPhone'   => 'required',
            'requestorFacility' => 'required',
        ]);

        $startDate = date('d/m/Y', strtotime($request->bookStartDate));
        $endDate = date('d/m/Y', strtotime($request->bookEndDate));

        $oriStart = date('Y-m-d', strtotime($request->bookStartDate)).' '.$request->bookStartHour.':'.$request->bookStartMinute;
        $oriEnd = date('Y-m-d', strtotime($request->bookEndDate)).' '.$request->bookEndHour.':'.$request->bookEndMinute;

        // echo $oriStart.' '.$oriEnd;die();
        if($oriEnd <= $oriStart){
            return redirect()->back()->with('warning', "End date can't be before Start date");
        }

        $request->bookDate = $startDate.' . '.$request->bookStartHour.':'.$request->bookStartMinute.' - '.$endDate.' . '.$request->bookEndHour.':'.$request->bookEndMinute;

        $bookDate = $request->bookDate;
        $bD = explode('-', $bookDate);

        $bstart = explode('.', $bD[0]);
        $bend = explode('.', $bD[1]);

        $bs = str_replace("/", '-', $bstart[0]);
        $bookStart = date('Y-m-d H:i',strtotime($bs.' '.$bstart[1]));

        $be = str_replace("/", '-', $bend[0]);
        $bookEnd = date('Y-m-d H:i',strtotime($be.' '.$bend[1]));

        $book                 = $request->all();
        $book['bookDate']     = date('Y-m-d', strtotime($bs));
        $book['bookTime']     = str_replace(' ', '', $bstart[1]);
        $book['bookDuration'] = Carbon::parse($bookEnd)->diffInMinutes(Carbon::parse($bookStart));
        $book['bookStart']    = date('Y-m-d H:i:s',strtotime($bookStart));
        $book['bookEnd']      = date('Y-m-d H:i:s',strtotime($bookEnd));

        $book = $request->all();

        unset($book['_token']);

        $user = $this->loadViewData();
        $book['requestorName'] = $user['userName'];
        $book['requestorId'] = $user['userEmail'];
        $book['requestorCapacity'] = $request->requestorCapacity;

        $book['equipments'] = json_encode($request->equipments);

        $pathName = $request->file('file');
        unset($book['file']);
        $booking = UserBooking::find($request->id);

        // If not supposed to be revised, abort
        if ($booking->approvalStatus !== 'revise') {
            return redirect()->route('home.index');
        }

        $booking->fill($book);
        $booking->approvalStatus = 'pending';
        $booking->save();

        if (!is_null($pathName)) {
            $originalFileName = time() . '-revised-' . $booking->id . '-' . $pathName->getClientOriginalName();

            try {
                $rootGraphUrl = env('GRAPH_ROOT_URL');
                $tokenCache = new TokenCache();
                $client = new Client;
                $token = $this->call_graph();
                $tokenWithBearer = "Bearer $token";

                $res = $client->request('PUT', $rootGraphUrl . "/sites/6c2e9cd5-b965-4b1a-ad60-e31ff17d6a54/drive/items/root:/$originalFileName:/content", [
                    'headers'        => [
                        'Authorization' => $tokenWithBearer,
                    ],
                    "multipart" => [
                        [
                            'name'     => '$originalFileName',
                            'contents' => fopen($pathName, 'r')
                        ]
                    ]

                ]);
                $arrayData = json_decode($res->getBody()->getContents(), true);
            } catch (ClientException $th) {
                $th = $th->getResponse()->getBody(true)->getContents();

                $th = json_decode($th);
                if ($th->error->code === 'RequestBroker--ParseUri') {
                    $tokenCache = new TokenCache();
                    $tokenCache->clearTokens();
                    return redirect()->route('login.index');
                } else {
                    return redirect()->back();
                }
            }

            $booking->file = $arrayData['id'];
            $booking->save();
        }

        return redirect()->route('home.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\UserBooking  $userBooking
     * @return \Illuminate\Http\Response
     */
    public function show(UserBooking $userBooking)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\UserBooking  $userBooking
     * @return \Illuminate\Http\Response
     */
    public function edit(UserBooking $userBooking)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\UserBooking  $userBooking
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UserBooking $userBooking)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\UserBooking  $userBooking
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserBooking $userBooking)
    {
        //
    }

    public function file(Request $request)
    {
        $booking = UserBooking::find($request->id);
        $rootGraphUrl = env('GRAPH_ROOT_URL');
        $tokenCache = new TokenCache();
        $client = new Client;
        // $token = $tokenCache->getAccessToken();
        $token = $this->call_graph();
        $tokenWithBearer = "Bearer $token";
        try {
            $res = $client->request('POST', $rootGraphUrl . "/sites/6c2e9cd5-b965-4b1a-ad60-e31ff17d6a54/drive/items/$booking->file/preview", [
                'headers'        => [
                    'Authorization' => $tokenWithBearer,
                ],
            ]);
        } catch (ClientException $th) {
            $th = $th->getResponse()->getBody(true)->getContents();
            dump($rootGraphUrl . "/sites/6c2e9cd5-b965-4b1a-ad60-e31ff17d6a54/drive/items/$booking->file/preview");
            dd($th);
            $th = json_decode($th);
            if ($th->error->code === 'RequestBroker--ParseUri') {
                $tokenCache = new TokenCache();
                $tokenCache->clearTokens();
                return redirect()->route('login.index');
            }
        }
        $arrayData = json_decode($res->getBody()->getContents(), true);

        $main_url = $arrayData['getUrl'];
        $file = readfile($main_url);
    }

    public function confirm(Request $request)
    {
        $user = $this->loadViewData();
        session(['redirect_url' => url()->current()]);
        if (empty($user)) {
            return redirect()->route('login.index');
        }

        // Check if user has a booking for this room
        $facility = MasterFacility::where('qrcode', $request->code)->first();
        $bookings = UserBooking::where('masterFacilityId', $facility->id)
            ->where('requestorId', $user['userEmail'])
            ->where('approvalStatus', 'accept')
            ->whereDate('bookDate', Carbon::today())
            ->get();

        foreach ($bookings as $booking) {
            $now = Carbon::now();
            $start = Carbon::createFromTimeString($booking->bookTime)->subMinutes(30);
            $end = Carbon::createFromTimeString($booking->bookTime)->addMinutes(30);

            // Users are only allowed to confirm if current time is +- 30 mins of bookingTime
            if ($now->between($start, $end)) {
                $booking->requestorConfirmed = 1;
                $booking->save();
                return view('confirm');
            }
        }

        return redirect()->route('home.index');
    }

    public function call_graph()
    {
        // Initialize the OAuth client
        $oauthClient = new \League\OAuth2\Client\Provider\GenericProvider([
            'clientId'                => env('OAUTH_APP_ID'),
            'clientSecret'            => env('OAUTH_APP_PASSWORD'),
            'redirectUri'             => env('OAUTH_REDIRECT_URI'),
            'urlAuthorize'            => env('OAUTH_AUTHORITY') . env('OAUTH_AUTHORIZE_ENDPOINT'),
            'urlAccessToken'          => env('OAUTH_AUTHORITY') . env('OAUTH_TOKEN_ENDPOINT'),
            'urlResourceOwnerDetails' => env('OAUTH_AUTHORITY') . env('OAUTH_RESOURCE_ENDPOINT'),
        ]);

        $options = [
            'scope' => 'https://graph.microsoft.com/.default'
        ];

        try {
            // Make the token request
            $accessToken = $oauthClient->getAccessToken('client_credentials', $options);
            return $accessToken->getToken();
        } catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
            dd($e);
        }
    }
}
