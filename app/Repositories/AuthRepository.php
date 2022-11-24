<?php

namespace App\Repositories;
use Validator;
use Auth;
use Log;
use App\Services\AuthServices;
use Illuminate\Support\Facades\Session;
use App\User;

class AuthRepository
{
    //openloginpage
    public function index()
    {
        if (auth()->user()) 
        {
            $name = "dashboard";
            return $name;
            // return redirect()->route('Dashboard');
        }
        else 
        {
            $name = "login";
            return $name;
            // return view('login');
        }
    }
    //

    //check auth
    public function authenticate($request)
    {
        try
        {
            $rules = [
                'email' => 'required',
                'password' => 'required',
            ];
            $messages = [
                'email.required' => "Email is Required.",
                'password.required' => 'Password is Required.',
            ]; 
            $validator = Validator::make($request->all(),$rules, $messages);
            if ($validator->fails())
            {
                return redirect()->back()->withErrors($validator->errors())->withInput();
            }
            else
            {
                $credentials = $request->only('email', 'password');
                if (Auth::attempt($credentials))
                {
                    if (auth()->user()->role->name == "SuperAdmin")
                    {
                        $name = "dashboard";
                        return $name;
                        // return redirect()->route('Dashboard');
                    }
                    else if(auth()->user()->role->name == "Admin")
                    {

                        $name = "dashboard";
                        return $name;
                        // return redirect()->route('Dashboard');
                    }
                    else
                    {

                        $name = "dashboard";
                        return $name;
                        // return redirect()->route('Dashboard');
                    }
                }
                else
                {
                    Session::flash('danger', 'Email and Password does not match.');
                    $name = "login";
                    return $name;
                    // return redirect()->route('login');
                }
            }
        }
        catch (\Exception\Database\QueryException $e)
        {
            Log::info('There was an error while authenticating user with partner_id: '.$request->partnerid.', email: '.$request->email.'. See logs below.');
            Log::info('Query: '.$e->getSql());
            Log::info('Query: Bindings: '.$e->getBindings());
            Log::info('Error: Code: '.$e->getCode());
            Log::info('Error: Message: '.$e->getMessage());

            Session::flash('danger', 'Internal Server Error. Please try again later.');
            return redirect()->back();
        }
        catch (\Exception $e)
        {
            Log::info('There was an error while authenticating user with partner_id: '.$request->partnerid.', email: '.$request->email.'. See logs below.');
            Log::info('Error: Code: '.$e->getCode());
            Log::info('Error: Message: '.$e->getMessage());

            Session::flash('danger', 'Internal Server Error. Please try again later.');
            return redirect()->back();
        }
    }
    //

    //check logout
    public function logout()
    {
        Auth::logout();
        Session::flush();
        $name = "logout";
        return $name;
    }
    //
}

?>