<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\MasterConfig;
use Illuminate\Http\Request;

class UserController extends Controller
{
    //
    public function index(){
        $users = User::all();
        return view('user.index', ['users' => $users]);
    }

    //
    public function toggleStatus(Request $request){
        $user = User::find($request->id);

        $user->active = !$user->active;
        $user->save();
        return redirect()->route('home.user');
    }

    //
    public function toggleRole(Request $request){
        $user = User::find($request->id);

        $user->role = $request->role;
        $user->save();
        return redirect()->route('home.user');
    }

    public function relasi_faculty(){
        $users = User::where('role',3)->get();
        return view('user.relasi_faculty', ['users' => $users]);
    }

    public function relasi_faculty_edit(Request $request)
    {
        $dataMasterConfig = MasterConfig::all()->keyBy('configName');
        $user = User::find($request->id);

        $viewData = $this->loadViewData();
        if (session('userName')) {
            $data=[
                'userName' => $viewData['userName'],
                'userEmail' => $viewData['userEmail'],
                'dataUser' => $user,
                'dataMasterConfigs' => $dataMasterConfig,
            ];
        }

        return view('user.relasi_faculty_edit', $data);
    }

    public function relasi_faculty_update(Request $request){
        $user = User::find($request->id);

        $faculty = '';
        if(!empty($request->faculty)){
            foreach ($request->faculty as $key => $v) {
                $faculty .= $v.';';
            }
        }
        // echo $faculty;die();
        $user->faculty_value = $faculty;
        $user->save();

        return redirect()->route('home.user.relasi_faculty');
    }
}