<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Session;
use App\Models\User; 
use Validator;
use Log;
use Str;
use Carbon\Carbon;
use Exception;
use App\Services\AuthServices;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use App\Models\Oauth;
use Laravel\Passport\Client as OClient; 

class AuthController extends Controller
{
    protected $authservices;

    public function __construct(AuthServices $authservices)
    {
        $this->authservices = $authservices;
    }

    //open login page
    public function index()
    {
        try
        {
            $loginpage = $this->authservices->index();
            if ($loginpage == "dashboard")
            {
                return redirect()->route('Dashboard');
            }
            else
            {
                return view('login');
            }
        }
        catch (Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());
        }    
    }
    //

    //check auth
    public function authenticate(Request $request)
    {
        try
        {
            $checkauth = $this->authservices->authenticate($request);
            if ($checkauth == "dashboard")
            {
                return redirect()->route('Dashboard');
            }
            else
            {
                return redirect()->route('login');
            }
        }
        catch (Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());
        }   
    }
    //

    //check logout
    public function logout()
    {
        try
        {
            $logoutpage = $this->authservices->logout();
            
            if ($logoutpage == "logout")
            {
                return redirect()->route('login');
            }
        }
        catch (Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());
            Log::info("yes");
        }  
    }
    // public function retrieveByCredentials()
    public function retrieveByCredentials($credentials)
    {
        $user = Oauth::where('username',$credentials['username'])->first();
        $trimmed = $user->expire_time;
        $timestamp = strtotime($trimmed);
        $trimmed[0] = explode('.',$trimmed);
        Log::info(gettype($trimmed[0]));
     
        
        $now = Carbon::now()->toTimeString();
        Log::info($now);
        
        $diff = $ts1  - $ts2 ;

        Log::info( 'diff' );
        Log::info( $diff );
        
        exit;
        if ( $diff > 60*6 ) {
            return 'true';
        }
        else {
            return $user;
        }

        
        // if ($user->expire_time !== 0 ) {
        //     Log::info( 'YES');
        // }

        // if (!$user){
        //     return;
        // } 

        // $created = $user->api_token_created_at;
        // $now     = Carbon::now();
        // $diff    = $now->diffInMinutes($created);

       
    }
    //
    public function login(Request $request) { 
        Log::info('inside Login IF Condi...>>>>>>>');

        $credentials = [
            'username' => $request['username'],
            'password' => $request['password'],
        ];
      
        if ($credentials) { 
            $authuser = Oauth::where('username',$request->username)->first();
            $authuser->refresh_token = Str::uuid(50);
            $authuser->expire_time = Carbon::now()->addSeconds(10)->toTimeString();
            $execute =  $authuser->save();
            if($execute)
            {
                $usersCreadtials = $this->retrieveByCredentials($credentials);
                return $usersCreadtials;
            }
            
        } 

        return response()->json(['message'=>'Invalid Login']);
      
    }

}
