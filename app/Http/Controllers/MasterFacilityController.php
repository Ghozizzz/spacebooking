<?php

namespace App\Http\Controllers;

ini_set('max_execution_time', 0);

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

use App\Models\MasterFacility;
use App\Models\MasterEquipment;
use App\Models\MasterConfig;
use App\Models\MasterFacilityImage;
use App\Models\UserBooking;
use App\Models\MasterFacilityGroupType;
use App\Models\MasterPeriod;
use App\Models\MonitorClass;

use App\Exports\TablesExport;
use App\Imports\MasterFacilityImport;
use App\TokenStore\TokenCache;

use Maatwebsite\Excel\Facades\Excel;

use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Stream\Stream;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;

use Microsoft\Graph\Graph;
use Microsoft\Graph\Model;

use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;

class MasterFacilityController extends Controller
{
    public function index()
    {
        $viewData = $this->loadViewData();

        if(session('facilities') && session('role') == 1){
            $dataMasterFacility = MasterFacility::find(session('facilities'));
        }else{
            $dataMasterFacility = MasterFacility::all();
        }

        $dataMasterFacilityWithoutCalenderId = MasterFacility::whereNull('calenderId')->get();
        $countDataMasterFacilityWithoutCalenderId = $dataMasterFacilityWithoutCalenderId->count();

        if (session('userName')) {
            $data=[
                'userName' => $viewData['userName'],
                'userEmail' => $viewData['userEmail'],
                'dataMasterFacilities' => $dataMasterFacility,
                'countMsterFacilities' => $countDataMasterFacilityWithoutCalenderId
            ];
        }

        return view('masterFacility.index', $data);
    }

    public function catalogue(Request $request)
    {
        $dataUserBooking    = UserBooking::all();
        $dataMasterFacility = MasterFacility::find($request->id);
        $dataMasterEquipment = MasterEquipment::all();
        $dataMasterConfig = MasterConfig::all()->keyBy('configName');
        $dataFacilityImage = MasterFacilityImage::where('master_facility_id', $request->id)->get();

        $activeTerm = $dataMasterConfig->get('activeTerm');
        if(!is_null($activeTerm)){
            if(!empty($activeTerm)){
                $beginDate = '';
                $endDate = '';
            }else{
                $activeTerm = $activeTerm->configValue;
                $dataMasterPeriod = MasterPeriod::select('term', \DB::raw('min(beginDate) as beginDate'), \DB::raw('max(endDate) as endDate'))
                            ->where('term', $activeTerm)
                            ->groupBy('term')
                            ->first();
                $beginDate = $dataMasterPeriod->beginDate;
                $endDate = $dataMasterPeriod->endDate;
            }
        }else{
            $beginDate = '';
            $endDate = '';
        }

        $viewData = $this->loadViewData();
        if (session('userName')) {
            $data=[
                'userName' => $viewData['userName'],
                'userEmail' => $viewData['userEmail'],
                'dataMasterFacilities' => $dataMasterFacility,
                'dataMasterEquipments' => $dataMasterEquipment,
                'dataMasterConfigs' => $dataMasterConfig,
                'dataFacilityImages' => $dataFacilityImage,
                'beginDate' => $beginDate,
                'endDate' => $endDate,
            ];
        }

        return view('masterFacility.catalogue', $data);
    }

    public function revise(Request $request)
    {
        $dataUserBooking    = UserBooking::find($request->id);
        $dataMasterFacility = MasterFacility::find($dataUserBooking->masterFacilityId);
        $dataMasterEquipment = MasterEquipment::all();
        $dataMasterConfig = MasterConfig::all()->keyBy('configName');
        $dataFacilityImage = MasterFacilityImage::where('master_facility_id', $dataUserBooking->masterFacilityId)->get();

        $activeTerm = $dataMasterConfig->get('activeTerm');
        if(!is_null($activeTerm)){
            if(!empty($activeTerm)){
                $beginDate = '';
                $endDate = '';
            }else{
                $activeTerm = $activeTerm->configValue;
                $dataMasterPeriod = MasterPeriod::select('term', \DB::raw('min(beginDate) as beginDate'), \DB::raw('max(endDate) as endDate'))
                            ->where('term', $activeTerm)
                            ->groupBy('term')
                            ->first();
                $beginDate = $dataMasterPeriod->beginDate;
                $endDate = $dataMasterPeriod->endDate;
            }
        }else{
            $beginDate = '';
            $endDate = '';
        }

        $viewData = $this->loadViewData();
        if (session('userName')) {
            $data=[
                'userName' => $viewData['userName'],
                'userEmail' => $viewData['userEmail'],
                'dataMasterFacilities' => $dataMasterFacility,
                'dataMasterEquipments' => $dataMasterEquipment,
                'dataMasterConfigs' => $dataMasterConfig,
                'dataFacilityImages' => $dataFacilityImage,
                'dataBooking' => $dataUserBooking,
                'beginDate' => $beginDate,
                'endDate' => $endDate,
            ];
        }

        return view('masterFacility.edit-catalogue', $data);
    }

    public function edit(Request $request)
    {
        $dataMasterFacility = MasterFacility::find($request->id);
        $dataMasterConfig = MasterConfig::all()->keyBy('configName');
        $dataFacilityImage = MasterFacilityImage::where('master_facility_id', $request->id)->get();

        $viewData = $this->loadViewData();
        if (session('userName')) {
            $data=[
                'userName' => $viewData['userName'],
                'userEmail' => $viewData['userEmail'],
                'dataMasterFacilities' => $dataMasterFacility,
                'dataMasterConfigs' => $dataMasterConfig,
                'dataFacilityImages' => $dataFacilityImage,
            ];
        }

        return view('masterFacility.edit', $data);
    }

    public function search_query(Request $request)
    {
        $bookDate = $request->bookDate;
        $bookTime = $request->bookTime;
        $bookDuration = $request->bookDuration;
        $d    = new \DateTime($bookDate);
        $dayName = strtolower($d->format('l'));
        $dataMasterConfig = MasterConfig::all()->keyBy('configName');

        if (!is_null($bookDate) && !is_null($bookTime) && !is_null($bookDuration)) {
            $dataBooking = UserBooking::when($bookDate, function ($query, $bookDate) {
                return $query->where('bookDate', $bookDate);
            }, function ($query) {
                return $query;
            })
            ->when($bookTime, function ($query, $bookTime) {
                return $query->where('bookTime', $bookTime);
            }, function ($query) {
                return $query;
            })
            ->when($bookDuration, function ($query, $bookDuration) {
                return $query->where('bookDuration', '>=', $bookDuration);
            }, function ($query) {
                return $query;
            })
            ->where('approvalStatus', 'accept')
            ->select('masterFacilityId')
            ->distinct()
            ->get();
            $exclude = $dataBooking->pluck('masterFacilityId')->all();
        }else{
            $exclude = array();
        }

        $availability = $request->availability;
        $building = $request->building;
        $capacity = (int)$request->capacity;
        $cap['capacity'] = $capacity;
        $cap['facilityCapacity'] = $dataMasterConfig->get('facilityCapacity');

        $dataMasterFacility = MasterFacility::when($availability, function ($query, $availability) {
            return $query->where('status', $availability);
        }, function ($query) {
            return $query;
        })
        ->when($building, function ($query, $building) {
            return $query->where('type', $building);
        }, function ($query) {
            return $query;
        })
        ->when($cap, function ($query, $cap) {
            $capacity = $cap['capacity'];
            return $query->whereRaw('capacity*'.$cap['facilityCapacity']->configValue.'/100 >= '.$capacity);
        }, function ($query) {
            return $query;
        })
        ->whereNotIn('id', $exclude)
        ->whereNotIn('days' , ['null', 'NULL'])
        ->where('days', 'LIKE', '%'.$dayName.'%')
        ->get();

        $building = MasterFacility::select('type as building')
        ->distinct()
        ->orderBy('type', 'asc')
        ->get();

        $activeTerm = $dataMasterConfig->get('activeTerm');
        if(!is_null($activeTerm)){
            if(!empty($activeTerm)){
                $beginDate = '';
                $endDate = '';
            }else{
                $activeTerm = $activeTerm->configValue;
                $dataMasterPeriod = MasterPeriod::select('term', \DB::raw('min(beginDate) as beginDate'), \DB::raw('max(endDate) as endDate'))
                            ->where('term', $activeTerm)
                            ->groupBy('term')
                            ->first();
                $beginDate = $dataMasterPeriod->beginDate;
                $endDate = $dataMasterPeriod->endDate;
            }
        }else{
            $beginDate = '';
            $endDate = '';
        }
        $viewData = $this->loadViewData();
        if (session('userName')) {
            $data=[
                'userName' => $viewData['userName'],
                'userEmail' => $viewData['userEmail'],
                'dataMasterFacilities' => $dataMasterFacility,
                'building' => $building,
                'dataMasterConfigs' => $dataMasterConfig,
                'beginDate' => $beginDate,
                'endDate' => $endDate,
            ];
        }

        // session()->flashInput($request->input());
        $request->flash();
        return view('masterFacility.search', $data);
    }

    public function search()
    {

        $building = MasterFacility::select('type as building')
        ->distinct()
        ->orderBy('type', 'asc')
        ->get();

        $dataMasterConfig = MasterConfig::all()->keyBy('configName');

        $activeTerm = $dataMasterConfig->get('activeTerm');
        if(!is_null($activeTerm)){
            if(!empty($activeTerm)){
                $beginDate = '';
                $endDate = '';
            }else{
                $activeTerm = $activeTerm->configValue;
                $dataMasterPeriod = MasterPeriod::select('term', \DB::raw('min(beginDate) as beginDate'), \DB::raw('max(endDate) as endDate'))
                            ->where('term', $activeTerm)
                            ->groupBy('term')
                            ->first();
                $beginDate = $dataMasterPeriod->beginDate;
                $endDate = $dataMasterPeriod->endDate;
            }
        }else{
            $beginDate = '';
            $endDate = '';
        }


        $viewData = $this->loadViewData();

        $dataMasterFacility = array();
        if (session('userName')) {
            $data=[
                'userName' => $viewData['userName'],
                'userEmail' => $viewData['userEmail'],
                'dataMasterFacilities' => $dataMasterFacility,
                'building' => $building,
                'dataMasterConfigs' => $dataMasterConfig,
                'beginDate' => $beginDate,
                'endDate' => $endDate,
            ];
        }

        return view('masterFacility.search', $data);
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function timetable(Request $request)
    {
        $facility = MasterFacility::find($request->id);
        $code = $facility->qrcode;
        $contents = QrCode::format('png')->size(100)->generate(route('booking.confirm', ['code' => $code]));
        Storage::put("public/uploads/$code.png", $contents);
        $url = asset("storage/uploads/$code.png");

        $en = Carbon::now()->locale('en_ID');
        $now = $en->format('d/m/Y');
        $startOfWeek = $en->startOfWeek()->format('Y-m-d');
        $endOfWeek = $en->endOfWeek()->format('Y-m-d');

        $periods = MasterPeriod::select('term')
        ->where('beginDate', '<', $startOfWeek)
        ->where('endDate', '>', $endOfWeek)
        ->groupBy('term')
        ->first();

        $classes = MonitorClass::where('term', $periods->term)->get();
        foreach($classes as $class){
            $classTime = explode('-', $class->jam);
            $startTime = explode(':', $classTime[0]);
            $startHour = trim($startTime[0]);
            $endTime = explode(':', $classTime[1]);
            $endHour = trim($endTime[0]);

            $schedule[$class->hari][$startHour] = $class->description;
            $schedule[$class->hari][$endHour] = $class->description;
        }


        $bookings = UserBooking::where('bookDate', '>', $startOfWeek)
        ->where('bookDate', '<', $endOfWeek)
        ->where('masterFacilityId', $facility->id)
	->where('approvalStatus', 'accept')
	->get();

        setlocale(LC_TIME, 'id_ID');
        Carbon::setLocale('id');
	$schedule = array();
        foreach($bookings as $booking){
            $startTime = explode(':', $booking->bookTime);
            $startHour = trim($startTime[0]);

            $day = strtoupper($booking->bookDate->translatedFormat('l'));

            $endTime = Carbon::createFromFormat('H:i', $booking->bookTime)->addMinutes($booking->bookDuration);
            $endHour = $endTime->format('H');

            $loopTime = Carbon::createFromFormat('H', $startHour);
            while($loopTime->lte($endTime)){
                $hour = $loopTime->format('H');

                $schedule[$day][$hour] = $booking->eventName;
                $loopTime = $loopTime->addMinutes(60);
            }
        }

        $startOfWeek = $en->startOfWeek()->format('d/m/Y');
        $endOfWeek = $en->endOfWeek()->format('d/m/Y');

        return view('masterFacility.timetable', [
            'facility' => $facility,
            'url' => $url,
            'startOfWeek' => $startOfWeek,
            'endOfWeek' => $endOfWeek,
            'now' => $now,
            'schedule' => $schedule,
        ]);
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function export(Request $request)
    {
        return Excel::download(new TablesExport($request->code), 'TimeTables.xlsx');
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function import()
    {
        Excel::import(new MasterFacilityImport, request()->file('file'));

        $masterFacilities = MasterFacility::groupBy('type')->pluck('type');
        $RegisteredCalendarGroups = [];

        $tokenCache = new TokenCache();
        $client = new Client;
        // $token = $tokenCache->getAccessToken();
        $token = $this->call_graph();
        $tokenWithBearer = "Bearer $token";
        $GRAPH_ROOT_URL = env('GRAPH_ROOT_URL');
        $USER_ID = env('USER_ID');
        $calenderGroupUrl = $GRAPH_ROOT_URL."/users/".$USER_ID."/calendarGroups";
        $url = $GRAPH_ROOT_URL."/users/".$USER_ID."/calendarGroups";

        foreach ($masterFacilities as $masterFacility) {
            $nextLinkAvailability = true;
            $continue = true;
            $masterFacilityGroupType = MasterFacilityGroupType::firstOrCreate(array('type' => $masterFacility));
            while ($nextLinkAvailability) {
                try {
                    $res = $client->request(
                        'GET',
                        $url,
                        [
                            'headers'        => [
                                'Content-type' => 'application/json',
                                'Authorization' => $tokenWithBearer,
                            ],
                        ],
                        [
                            'http_errors' => false
                        ]
                    );
                    $data = json_decode($res->getBody()->getContents(), true);

                    foreach ($data['value'] as $i) {
                        if ($i['name'] == $masterFacility) {
                            $masterFacilityGroupType->calendarGroupId = $i['id'];
                            $masterFacilityGroupType->save();
                            $continue = false;
                            array_push($RegisteredCalendarGroups, $masterFacility);
                        }
                    }

                    if (isset($data['@odata.nextLink']) && $continue) {
                        $url=$data['@odata.nextLink'];
                    } else {
                        $nextLinkAvailability = false;
                    }
                } catch (ClientException $th) {
                    $th = $th->getResponse()->getBody(true)->getContents();
                    $th = json_decode($th);
                    if ($th->error->code === 'InvalidAuthenticationToken') {
                        $tokenCache = new TokenCache();
                        $tokenCache->clearTokens();
                        return redirect()->route('login.index');
                    }
                } catch (ServerException $th) {
                    $thError = $th->getResponse()->getBody(true)->getContents();
                    $thError = json_decode($thError);
                    if ($thError->error->code === 'UnknownError') {
                        sleep(60);
                        continue;
                    }
                }
            }
        }

        $masterFacilities = MasterFacility::whereNotIn('type', $RegisteredCalendarGroups)->groupBy('type')->pluck('type');
        foreach ($masterFacilities as $masterFacility) {
            try {
                $res = $client->request(
                    'POST',
                    $calenderGroupUrl,
                    [
                        'headers'        => [
                            'Content-type' => 'application/json',
                            'Authorization' => $tokenWithBearer,
                        ],
                        'json' =>[
                            "name" => $masterFacility
                            ]
                        ],
                    [
                        'http_errors' => false
                    ]
                );
                $dataCreateCalendarGroup = json_decode($res->getBody()->getContents());
                $masterFacilityGroupType = MasterFacilityGroupType::firstOrCreate(array('type' => $masterFacility));
                $masterFacilityGroupType->calendarGroupId = $dataCreateCalendarGroup->id;
                $masterFacilityGroupType->save();
            } catch (ClientException $th) {
                $th = $th->getResponse()->getBody(true)->getContents();
                $th = json_decode($th);
                if ($th->error->code === 'InvalidAuthenticationToken') {
                    $tokenCache = new TokenCache();
                    $tokenCache->clearTokens();
                    return redirect()->route('login.index');
                }
            }
        }
        return back();
    }

    public function synchronize()
    {
        $tokenCache = new TokenCache();
        $client = new Client;
        // $token = $tokenCache->getAccessToken();
        $token = $this->call_graph();
        $tokenWithBearer = "Bearer $token";
        $USER_ID = env('USER_ID');
        $nextLinkAvailability = true;
        $GRAPH_ROOT_URL = env('GRAPH_ROOT_URL');
        $url = $GRAPH_ROOT_URL."/users/".$USER_ID."/calendars";
        while ($nextLinkAvailability) {
            try {
                $res = $client->request(
                    'GET',
                    $url,
                    [
                        'headers'        => [
                            'Content-type' => 'application/json',
                            'Authorization' => $tokenWithBearer,
                        ],
                    ],
                    [
                        'http_errors' => false
                    ]
                );
                $data = json_decode($res->getBody()->getContents(), true);
                foreach ($data['value'] as $i) {
                    $masterFacility =  MasterFacility::where('facilId', $i['name'])->first();
                    if ($masterFacility) {
                        if ($masterFacility->calenderId != $i['id']) {
                            $masterFacility->calenderId = $i['id'];
                            $masterFacility->save();
                        }
                    }
                }

                if (isset($data['@odata.nextLink'])) {
                    $url=$data['@odata.nextLink'];
                } else {
                    $nextLinkAvailability = false;
                }
            } catch (ClientException $th) {
                $thError = $th->getResponse()->getBody(true)->getContents();
                $thError = json_decode($thError);
                if ($thError->error->code === 'InvalidAuthenticationToken') {
                    $tokenCache = new TokenCache();
                    $tokenCache->clearTokens();
                    return redirect()->route('login.index');
                }
            } catch (ServerException $th) {
                $thError = $th->getResponse()->getBody(true)->getContents();
                $thError = json_decode($thError);
                if ($thError->error->code === 'UnknownError') {
                    sleep(60);
                    continue;
                }
            }
        }

        $masterFacilities     = MasterFacility::whereNull('calenderId')->get();
        $GRAPH_ROOT_URL       = env('GRAPH_ROOT_URL');
        $baseCalenderGroupUrl = $GRAPH_ROOT_URL."/users/".$USER_ID."/calendarGroups";
        foreach ($masterFacilities as $masterFacility) {
            $masterFacilityGroupType = MasterFacilityGroupType::where('type', $masterFacility->type)->first();
            $calendarGroupId = $masterFacilityGroupType->calendarGroupId;
            $calenderGroupUrl = $baseCalenderGroupUrl.'/'.$calendarGroupId.'/calendars';
            try {
                $res = $client->request(
                    'POST',
                    $calenderGroupUrl,
                    [
                        'headers'        => [
                            'Content-type' => 'application/json',
                            'Authorization' => $tokenWithBearer,
                        ],
                        'json' =>[
                            "name" => $masterFacility->facilId
                            ]
                        ],
                    [
                        'http_errors' => false
                    ]
                );

                $data = json_decode($res->getBody()->getContents());
                $masterFacility->calenderId = $data->id;
                $masterFacility->save();
            } catch (ClientException $th) {
                $th = $th->getResponse()->getBody(true)->getContents();
                $th = json_decode($th);
                if ($th->error->code === 'InvalidAuthenticationToken') {
                    $tokenCache = new TokenCache();
                    $tokenCache->clearTokens();
                    return redirect()->route('login.index');
                }
            }

            sleep(1);
        }
        return redirect()->route('home.master.facility')->with('sync','selesai');
    }

    public function uploadImage(Request $request)
    {
        $validator = $request->validate([
            'file' => 'image|max:5120'
        ]);
        $pathName = $request->file('file')->store('public/uploads');

        $facilityImage = new MasterFacilityImage;
        $facilityImage->master_facility_id = $request->id;
        $pathName = str_replace('public/uploads', 'storage/uploads', $pathName);
        $facilityImage->image = $pathName;
        $facilityImage->save();

        return redirect()->route('home.master.facility.edit', ['id'=>$request->id]);
    }

    public function deleteImage(Request $request)
    {
        $facilityImage = MasterFacilityImage::find($request->img);
        Storage::delete($facilityImage->image);
        $facilityImage->delete();

        return redirect()->route('home.master.facility.edit', ['id'=>$request->id]);
    }

    public function generateQr()
    {
        $dataMasterFacility = MasterFacility::all();
        foreach ($dataMasterFacility as $facility) {
            $facility->qrcode = $facility->id.rand(1000, 9999);
            $facility->save();
        }

        return redirect()->route('home.master.facility');
    }

    public function update(Request $request)
    {
        if (!is_null($request->start_time)) {
            $request->validate([
                'start_time' => 'date_format:H:i',
            ]);
        }

        if (!is_null($request->end_time)) {
            $request->validate([
                'end_time' => 'date_format:H:i',
            ]);
        }

        $days = json_encode($request->days);

        $facility = MasterFacility::find($request->id);
        $facility->room_desc = $request->room_desc;
        $facility->start_time = $request->start_time;
        $facility->end_time = $request->end_time;
        $facility->days = $days;
        $facility->save();

        return redirect()->route('home.master.facility.edit', ['id'=>$request->id]);
    }

    public function call_graph()
    {
        // Initialize the OAuth client
        $oauthClient = new \League\OAuth2\Client\Provider\GenericProvider([
        'clientId'                => env('OAUTH_APP_ID'),
        'clientSecret'            => env('OAUTH_APP_PASSWORD'),
        'redirectUri'             => env('OAUTH_REDIRECT_URI'),
        'urlAuthorize'            => env('OAUTH_AUTHORITY').env('OAUTH_AUTHORIZE_ENDPOINT'),
        'urlAccessToken'          => env('OAUTH_AUTHORITY').env('OAUTH_TOKEN_ENDPOINT'),
        'urlResourceOwnerDetails' => env('OAUTH_AUTHORITY').env('OAUTH_RESOURCE_ENDPOINT'),
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
