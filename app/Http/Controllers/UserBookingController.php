<?php

namespace App\Http\Controllers;

use App\Models\UserBooking;
use App\Models\MasterFacility;
use App\Mail\Booking;
use App\Mail\NewBooking;
use App\Mail\DeclineBooking;
use App\Mail\CancelBooking;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\MonitorClass;
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
        } else {

            if ($status == 'decline') {
                $booking = UserBooking::where('approvalStatus', 'decline')
                    ->whereIn('masterFacilityId', session('faculty_value'))
                    ->has('facilities')
                    ->orderBy('id', 'desc')
                    ->get();
            } elseif ($status == 'cancel') {
                $booking = UserBooking::where('approvalStatus', 'cancel')
                    ->has('facilities')
                    ->orderBy('id', 'desc')
                    ->get();
            } elseif ($status == 'pending' || $status == 'revise' || $status == "") {
                // $booking = UserBooking::where(function($query){
                //             $query->where('approvalStatus', 'pending')
                //             ->orWhere('approvalStatus', 'revise');
                //         })
                //     ->leftJoin('u_users', function($join) {
                //       $join->on('user_bookings.requestorId', '=', 'u_users.email');
                //     });
                //     if(session('role') == 2){
                //         $booking->where('u_type', 0);
                //     }else{
                //         $booking->whereIn('requestorFacility', explode(';',session('faculty_value')))
                //         ->where('u_type', 1);  
                //     }
                //     $booking->has('facilities')
                //     ->orderBy('user_bookings.id', 'desc')
                //     ->get();
                    if(session('role') == 2){
                        $booking = UserBooking::where(function($query){
                                $query->where('approvalStatus', 'pending')
                                ->orWhere('approvalStatus', 'revise');
                            })->leftJoin('u_users', function($join) {
                              $join->on('user_bookings.requestorId', '=', 'u_users.email');
                            })->has('facilities')
                            ->where('u_type', 0)
                            ->orderBy('user_bookings.id', 'desc')
                            ->get();
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
                            ->get();
                    }
            } elseif ($status == 'accept') {
                $booking = UserBooking::where('approvalStatus', 'accept')
                    ->has('facilities')
                    ->orderBy('id', 'desc')
                    ->get();
            }
        }

        return view('bookingManagement.index', ['booking' => $booking]);
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
            'bookDate'         => 'required',
            'bookTime'         => 'required',
            'bookDuration'     => 'required',
            // 'bookReason'       => 'required',
            'eventName'        => 'required',
            'eventType'        => 'required',
            'file'             => 'required|mimes:pdf,zip|max:2048',
            'requestorPhone'   => 'required',
            'requestorFacility' => 'required',
        ]);

        $facilId          = MasterFacility::find($request->masterFacilityId)->facilId;
        $bookDate         = Carbon::createFromFormat('yy-m-d',  $request->bookDate)->locale('id');
        $formatedBookDate = $bookDate->format('yy-m-d');
        $dayName          = strtoupper($bookDate->translatedFormat('l'));

        $startBookTime   = Carbon::createFromTimeStamp(strtotime("$request->bookTime"));
        $bookDurationInt = intval($request->bookDuration);
        $endBookTime     = $startBookTime->copy()->addMinutes($bookDurationInt);

        $monitorClasses  = MonitorClass::where('facilId', $facilId)->where('hari', $dayName)->get();

        foreach ($monitorClasses as $monitorClass) {
            $checker         = 0;
            $timeExplode = explode("-", $monitorClass->jam);
            $startTime = Carbon::createFromTimeString("$timeExplode[0]");
            $endTime = Carbon::createFromTimeString("$timeExplode[1]");
            if ($startBookTime >= $startTime && $startBookTime < $endTime) {
                $checker++;
            } else {
                if ($endBookTime > $startTime && $endBookTime <= $endTime) {
                    $checker++;
                }
            }
            if ($checker > 0) {
                return redirect()->back()->with('warning', "The room is booked by another user");
            }
        }

        $userBookings = UserBooking::where('masterFacilityId', $request->masterFacilityId)->where('bookDate', $formatedBookDate)->where('approvalStatus', 'accept')->get();

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
                return redirect()->back()->with('warning', "The room is booked by another user");
            }
        }
        $book             = $request->all();

        unset($book['_token']);

        $user = $this->loadViewData();
        $book['requestorName'] = $user['userName'];
        $book['requestorId'] = $user['userEmail'];

        $book['equipments'] = json_encode($request->equipments);

        $pathName = $request->file('file');
        unset($book['file']);
        $booking = new UserBooking;
        $booking->fill($book);
        $booking->save();

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
                return redirect()->route('login.index');
            } else {
                return redirect()->back();
            }
        }

        $booking->file = $arrayData['id'];
        $booking->save();

        Mail::to("room.host@uph.ac.id")->send(new NewBooking($booking));

        return redirect()->route('home.index');
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
                $userBooking->approvalReason = "[DECLINED] The room is booked by another user";
                $userBooking->approverId = $user['userEmail'];
                $userBooking->approvedOn = Carbon::now();
                $userBooking->save();
                return redirect()->back()->with('warning', "The room is booked by another user");
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

        // Mail::to($userBooking->requestorId)->send(new Booking($userBooking));
        new Booking($userBooking);

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

        Mail::to($userBooking->requestorId)->send(new DeclineBooking($userBooking));

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

        Mail::to($userBooking->requestorId)->send(new CancelBooking($userBooking));

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
            'id'               => 'required',
            'masterFacilityId' => 'required',
            'bookDate'         => 'required',
            'bookTime'         => 'required',
            'bookDuration'     => 'required',
            'eventName'        => 'required',
            'eventType'        => 'required',
            'requestorPhone'   => 'required',
            'requestorFacility' => 'required',
        ]);

        $book = $request->all();

        unset($book['_token']);

        $user = $this->loadViewData();
        $book['requestorName'] = $user['userName'];
        $book['requestorId'] = $user['userEmail'];

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
