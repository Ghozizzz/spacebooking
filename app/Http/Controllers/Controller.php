<?php

namespace App\Http\Controllers;

use Microsoft\Graph\Graph;
use Microsoft\Graph\Model;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Models\User;
use App\Models\MasterFacility;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function loadViewData()
    {
        $viewData = [];

        // Check for flash errors
        if (session('error')) {
            $viewData['error'] = session('error');
            $viewData['errorDetail'] = session('errorDetail');
        }

        // Check for logged on user
        if (session('userName'))
        {
            $user = User::where('email', session('userEmail'))->first();
            $viewData['userName'] = session('userName');
            $viewData['userEmail'] = session('userEmail');
            session(['role' => $user->role]);
            session(['faculty_value' => $user->faculty_value]);

	        // will return empty array if nothing is found
            $facilities = MasterFacility::where('owner', session('userEmail'))->pluck('id')->toArray();
            session(['facilities' => $facilities]);
        }

        return $viewData;
    }
}
