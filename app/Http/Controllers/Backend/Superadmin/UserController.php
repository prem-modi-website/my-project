<?php

namespace App\Http\Controllers\Backend\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\UserRequest;
use App\Services\Superadmin\Dashboard\UserService;
use Str;
use DB;
use Hash;
use Log;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Oauth;
use App\Models\TextResult;
use App\Models\Setting;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    protected $userservice;

    public function __construct(UserService $userservice)
    {
        $this->userservice = $userservice;
    }

    //get all user
    public function index(Request $request)
    {
        $index = $this->userservice->index($request);
        $users = $index['users'];
        // $users = $index['users'];
        // dd($users);
        return view('backend.superadmin.index' ,compact('users'));
    }
    //

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    //create user
    public function create()
    {
        $create = $this->userservice->create();
        if ($create == true)
        {
            return view('backend.superadmin.create');
        }
    }
    //

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    //store user
    public function store(UserRequest $request)
    {
        $create = $this->userservice->store($request);
        
        if ($create == true)
        {
            return redirect()->route('usermanagement.index');
        }
        else if($create == false)
        {
            return redirect()->route('usermanagement.create');
        }
    }
    //

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    //edit user
    public function edit($id)
    {
        $edit = $this->userservice->edit($id);

        $user = $edit['user'];
        return view('backend.superadmin.edit' , compact('user'));

    }
    //

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    //update user
    public function update(Request $request, $id)
    {
        $update = $this->userservice->update($request, $id);
        return $update;
    }
    //

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

     //deactivate user
    public function deactivate(Request $request , $id)
    {
        $deactivate = $this->userservice->deactivate($request, $id);

        if ($deactivate == true)
        {
            return redirect()->route('usermanagement.index');
        }
    }

    public function settingpage(Request $request)
    {
        $data = [];
        $partnerid = Oauth::get(['licno']);
        if($partnerid)
        {
            $users = Setting::where('user_id' , auth()->user()->id)->where('is_active',1)->get(['partner_id']);
            if ($users)
            {
                foreach($users  as  $user)
                {
                    array_push($data,$user['partner_id']);
                }
            }

            $maindata = [];
            foreach ($partnerid as $singlepartnerid)
            {
                if (in_array($singlepartnerid['licno'],$data))
                {
                    $singledata = [
                        "partner_id" => $singlepartnerid['licno'],
                        "is_active" => 1
                    ];
                }
                else
                {
                    $singledata = [
                        "partner_id" => $singlepartnerid['licno'],
                        "is_active" => 0
                    ];
                }
                array_push($maindata , $singledata);
            }

            $partnerid = [];
            foreach($maindata  as $singlemaindata)
            {
                $result = TextResult::where('partnerId',$singlemaindata['partner_id'])->get();
                if (!$result->isEmpty())
                {
                   array_push($partnerid , $singlemaindata);
                }
            }
            return view('backend.superadmin.settingform',compact('partnerid'));
        }
    }

    public function setting(Request $request)
    {
        $users = Setting::where('user_id', $request->id)->where('is_active',1)->get();
        if ($users->isEmpty())
        {
            foreach($request->languageSelect as $partnerid)
            {
                $user = new Setting;
                $user->user_id = $request->id;
                $user->partner_id = $partnerid;
                $execute = $user->save();
            }
            if($execute)
            {
                return redirect()->route('Dashboard');
            }
        }
        else
        {
            foreach ($users as $user)
            {
                $user->is_active = 0;
                $execute = $user->update();
            }

            if ($execute)
            {
                foreach ($request->languageSelect as $partnerid)
                {
                    $user = new Setting;
                    $user->user_id = $request->id;
                    $user->partner_id = $partnerid;
                    $execute = $user->save();
                }
                if($execute)
                {
                    return redirect()->route('Dashboard');
                }
                else
                {
                    return redirect()->route('settingpage');
                }
            }
        }
    }
    //

}
