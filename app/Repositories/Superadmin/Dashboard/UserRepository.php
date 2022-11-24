<?php

namespace App\Repositories\Superadmin\Dashboard;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Str;
use DB;
use Hash;
use Log;
use Illuminate\Http\Request;
use App\Models\User;

class UserRepository
{
    //create user
    public function create()
    {
        return true;
    }
    //

    //all data
    public function index($request)
    {
        try
        {
            $users = User::whereIn('role_id',[2,3])->get();
            // dd($users);
            if ($users)
            {
                $data = [
                    "users" => $users,
                ];
                return $data;
            }
        }
        catch (\Illuminate\Database\QueryException $e) {
            Log::info($e->getCode());
            Log::info($e->getMessage());
            DB::rollback();
            Session::flash('danger', 'Internal Server Error. Please try again later.');
            return redirect()->route('usermanagement.create')->withInput();
        }
        catch (\Exception $e) {
            Log::info($e->getCode());
            Log::info($e->getMessage());
            DB::rollback();
            Session::flash('danger', 'Internal Server Error. Please try again later.');
            return redirect()->route('usermanagement.create')->withInput();
        }
    }
    //

    //user store
    public function store($request)
    {
        try
        {
            DB::beginTransaction();
            
            $users = new User;
            $users->uuid = Str::uuid();
            $users->first_name = $request->firstname;
            $users->last_name = $request->lastname;
            $users->email = $request->email;
            $users->password = Hash::make($request->password);
            $users->role_id = $request->role;
            $execute = $users->save();
            if ($execute)
            {
                DB::commit();
                Session::flash('success','Congratulation , Your data created successfully!');
                return true;
                // return redirect()->route('usermanagement.create');
            }
            else
            {
                Session::flash('danger','Sorry , Internal server error');
                DB::rollback();
                return false;
                // return redirect()->route('usermanagement.create');
            }
        }
        catch (\Illuminate\Database\QueryException $e) {
            Log::info($e->getCode());
            Log::info($e->getMessage());
            DB::rollback();
            Session::flash('danger', 'Internal Server Error. Please try again later.');
            return redirect()->route('usermanagement.create')->withInput();
        }
        catch (\Exception $e) {
            Log::info($e->getCode());
            Log::info($e->getMessage());
            DB::rollback();
            Session::flash('danger', 'Internal Server Error. Please try again later.');
            return redirect()->route('usermanagement.create')->withInput();
        }
    }
    // 

    //edit user
    public function edit($id)
    {
        try
        {
            $user = User::where('uuid',$id)->first();
            if ($user)
            {
                $data = [
                    "user" => $user
                ];
                return $data;
            }
        }
        catch (\Illuminate\Database\QueryException $e) {
            Log::info($e->getCode());
            Log::info($e->getMessage());
            DB::rollback();
            Session::flash('danger', 'Internal Server Error. Please try again later.');
            return redirect()->route('usermanagement.create')->withInput();
        }
        catch (\Exception $e) {
            Log::info($e->getCode());
            Log::info($e->getMessage());
            DB::rollback();
            Session::flash('danger', 'Internal Server Error. Please try again later.');
            return redirect()->route('usermanagement.create')->withInput();
        }
        // return view('backend.superadmin.edit' , compact('user'));
    }
    //

    //update user
    public function update($request , $id)
    {
        try
        {
            $rules = [
                'firstname' => 'required',
                'lastname' => 'required',
                'email'=>'required',
            ];
            $messages = [
                'firstname.required' => "FirstName is Required.",
                'lastname.required' => 'LastName is Required.',
                'email.required' => 'Email is Required.',
            ]; 
            $validator = Validator::make($request->all(),$rules, $messages);
            if ($validator->fails())
            {
                return redirect()->back()->withErrors($validator->errors())->withInput();
            }
            else
            {
                $user = User::where('uuid',$id)->first();
                if ($user)
                {
                    DB::beginTransaction();

                    $user->first_name = $request->firstname;
                    $user->last_name = $request->lastname;
                    $user->email = $request->email;
                    $execute = $user->update();
                    if ($execute)
                    {
                        DB::commit();
                        Session::flash('success' , 'Congratulation , Your data Updated successfully!');
                        return redirect()->route('usermanagement.index');
                    }
                    else
                    {
                        Session::flash('danger' , 'Sorry , Internal server error');
                        DB::rollback();
                        return redirect()->route('usermanagement.edit',$id);
                    }
                }
            }
        }
        catch (\Illuminate\Database\QueryException $e) {
            Log::info($e->getCode());
            Log::info($e->getMessage());
            DB::rollback();
            Session::flash('danger', 'Internal Server Error. Please try again later.');
            return redirect()->route('usermanagement.create')->withInput();
        }
        catch (\Exception $e) {
            Log::info($e->getCode());
            Log::info($e->getMessage());
            DB::rollback();
            Session::flash('danger', 'Internal Server Error. Please try again later.');
            return redirect()->route('usermanagement.create')->withInput();
        }
    }
    //

    //deactivate user
    public function deactivate($request , $id)
    {
        try
        {
            $user = User::where('uuid',$id)->where('is_active',1)->first();
            if ($user)
            {
                $user->is_active = 0;
                $execute = $user->update();
                if($execute)
                {
                    return true;
                    // return redirect()->route('usermanagement.index');
                }
                else
                {
                    Session::flash('success','Sorry , Internal server error');
                    return true;
                    // return redirect()->route('usermanagement.index');
                }
            }
            else
            {
                $user = User::where('uuid',$id)->where('is_active',0)->first();
                if ($user)
                {
                    $user->is_active = 1;
                    $execute = $user->update();
                    if($execute)
                    {
                        return true;
                        // return redirect()->route('usermanagement.index');
                    }
                    else
                    {
                        Session::flash('danger' , 'Sorry , Internal server error');
                        return true;
                        // return redirect()->route('usermanagement.index');
                    }
                }
            }
        }
        catch (\Illuminate\Database\QueryException $e) {
            Log::info($e->getCode());
            Log::info($e->getMessage());
            DB::rollback();
            Session::flash('danger', 'Internal Server Error. Please try again later.');
            return redirect()->route('usermanagement.create')->withInput();
        }
        catch (\Exception $e) {
            Log::info($e->getCode());
            Log::info($e->getMessage());
            DB::rollback();
            Session::flash('danger', 'Internal Server Error. Please try again later.');
            return redirect()->route('usermanagement.create')->withInput();
        }
    }
    //
}