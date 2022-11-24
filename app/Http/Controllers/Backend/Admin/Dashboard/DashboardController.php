<?php

namespace App\Http\Controllers\Backend\Admin\Dashboard;

use App\Exceptions\CustomException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use App\Services\Superadmin\Dashboard\DashboardService;
use Log;
use Str;
use App\Models\Oauth;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Exports\UsersExport;
use App\Imports\UsersImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\TextResult;
use App\Models\Setting;
use App\Models\User;

class DashboardController extends Controller
{
    protected $dashboardservice;

    public function __construct(DashboardService $dashboardservice)
    {
        $this->dashboardservice = $dashboardservice;
    }

    //dashboard data
    public function dashboard(Request $request)
    {
        Log::info('My API CALLED in Request Data >>>>>>>');
        Log::info( $request );

        try
        {
            Log::info( 'Try Inside .....' );
            $dashboard =  $this->dashboardservice->dashboard($request);
            if ($dashboard == "notpartnerid")
            {
                return view('error.404');
            }
            else
            {    
                Log::info(' DashBoard >>>  Called .....');
                Log::info( $dashboard );
    
                $partner_datas = $dashboard['partner_datas'];
    
                $date = $dashboard['date'];
    
                return view('backend.admin.dashboard.dashboard',compact('partner_datas','date')); 
            }
            // throw new CustomException("Error Processing Request", 1);
            
        }
        catch (Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());
            return "asdf";
        }
    }
    //
    
    //charts data
    public function chart(Request $request ,$id)
    { 
        try
        {
            $chartsdata =  $this->dashboardservice->chart($request , $id);
            if ($chartsdata == "notpartnerid")
            {
                return view('error.404');
            }
            else if ($chartsdata == "notresult")
            {
                return redirect()->back();
            }
            else if ($chartsdata == "fromisnull")
            {
                return view('error.404');
            }
            else if ($chartsdata == "fromdata")
            {
                return view('error.404');
            }
            else if ($chartsdata == "toisnull")
            {
                return view('error.404');
            }
            else if ($chartsdata == "todata")
            {
                return view('error.404');
            }
            else if ($chartsdata == "datanotfound")
            {
                return view('error.404');
            }
            else
            { 
                // $yearofcharts = $chartsdata['yearofcharts'];
                $maximumvalue = $chartsdata['maximumvalue'];
                $monthofscan = $chartsdata['monthofscan'];
                $partner_id = $chartsdata['partner_id'];
                // $total_months = $chartsdata['total_months'];
                return view('backend.admin.dashboard.chart' , compact('maximumvalue','monthofscan','partner_id'));
            }
        }
        catch (Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());
        }
    }
    //

    //statatics data
    public function dynamictable(Request $request ,$id)
    {
        try
        {
            $stataticsdata =  $this->dashboardservice->dynamictable($request , $id);

            if ($stataticsdata == "notUserFound")
            {
                return view('error.404');
            }
            else if ($stataticsdata == "fromisnull")
            {
                return view('error.404');
            }
            else if ($stataticsdata == "fromdata")
            {
                return view('error.404');
            }
            else if ($stataticsdata == "toisnull")
            {
                return view('error.404');
            }
            else if ($stataticsdata == "todata")
            {
                return view('error.404');
            }
            else if ($stataticsdata == "notresultfound")
            {
                return view('error.404');
            }
            else
            { 
                if (isset($stataticsdata[0]))
                {
                    $id = $stataticsdata['id'];
                    $txtresults = $stataticsdata['txtresults'];
                    return view('backend.admin.dashboard.table',compact('id','txtresults'));
                }
                else
                {
                    $id = $stataticsdata['id'];
                    $txtresults = $stataticsdata['txtresults'];
                    $from_date = $stataticsdata['from_date'];
                    $to_date = $stataticsdata['to_date'];
                    return view('backend.admin.dashboard.table',compact('id', 'txtresults' ,'from_date','to_date'));
                }
            }
            
        }
        catch (Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());
        }
    }
    //

    //export data
    public function export(Request $request,$id) 
    {
        try
        {
            $exportdata =  $this->dashboardservice->export($request , $id);
            return $exportdata;    
        }
        catch (Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());
        }   
    }
    //

    //listview data
    public function listview(Request $request) 
    {
        try
        {
            $listviews =  $this->dashboardservice->listview($request);
            if($listviews == "datanotfound")
            {
                return view('error.404');
            }
            elseif($listviews == "notflagavailable")
            {
                return view('error.404');
            }
            else if ($listviews == "fromisnull")
            {
                return view('error.404');
            }
            else if ($listviews == "fromdata")
            {
                return view('error.404');
            }
            else if ($listviews == "toisnull")
            {
                return view('error.404');
            }
            else if ($listviews == "todata")
            {
                return view('error.404');
            }
            else if ($listviews == "orderisnull")
            {
                return view('error.404');
            }else if ($listviews == "orderdata")
            {
                return view('error.404');
            }
            else
            {
                $partner_datas = $listviews['partner_datas'];
                $date = $listviews['date'];
                return view('backend.admin.dashboard.gridviewtable',compact('partner_datas','date')); 
            }
            // return $listviews;
        } 
        catch (Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());
        }    
    }

    //Get Token
    public function getToken(Request $request)
    {
        Log::info('Request GET TOKEN ');
        Log::info($request->users);
        Log::info("end");



        if ($request->has('licno')) {
            $user = Oauth::where('licno', $request->licno)->first();
            $user->device_token = $request->token;
            $execute  = $user->update();
            if ($execute)
            {
                $user = User::where('id',$request->users)->first();
                $user->device_token = $request->token;
                $executeQuery = $user->update();
                if (! $executeQuery)
                {
                    return response()->json(['success' => false,'message' => "internal server error."],500);
                }
                return response()->json(['success' => true,'token saved successfully.'],200);
            }
        }

        return response()->json(['token saved successfully.']);
        // return view('backend.admin.dashboard.dashboard',compact('partner_datas','date')); 
    }

    public function sendNotification(Request $request)
    {
        Log::info('Notification Called & Dashboard Controller');
        Log::info('Request >>');
        Log::info($request);

        // $firebaseToken = Oauth::whereNotNull('device_token')->pluck('device_token')->all();
       
        // Dynamic Data Request To Get

        $firebaseToken = Oauth::where('licno',$request->licno )->pluck('device_token')->all();

        $SERVER_API_KEY = 'AAAApUvL8WE:APA91bG8ADcqPvxohfovdGglkepkk0R-fQlH2UOSar9L1oZsP97IvApUwpERZUo9LUPTQUwMBKmvBvmQNFt8KkVCQBnf6eUXE9RRxbvkpiIpm6W1pScnTJDfwJg4eNtcGY449r_O7fSP';
  
        $data = [
            "registration_ids" => $firebaseToken,
            "notification" => [
                "title" => $request->licno,
            ],
        ];

        Log::info('CONTROLLER DATA >>>');
        Log::info($data);

        $dataString = json_encode($data);

        $headers = [
            'Authorization: key=' . $SERVER_API_KEY,
            'Content-Type: application/json',
        ];
    
        $ch = curl_init();
      
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
               
        $response = curl_exec($ch);
        
        return response()->json(['Response'=>$response]);
    }

    public function updateData(Request $request)
    {

        try
        {
            if ($request->has('partnerId'))
            {
                if ($request->partnerId == "")
                {
                    //    $this->CustomException->errormessage();
                }
                else
                {
                    $partner_datas = [];

                    $users = Setting::where('user_id',auth()->user()->id)->where('is_active',1)->first();

                    if (!$users)
                    {
                        $users = Oauth::where('licno',$request->partnerId)->first(); 

                        if (! empty($users)) 
                        {
                            $result = TextResult::where('partnerId', $users['licno'])->get();
    
                            // Put empty result check
    
                            $total_no_of_scans_this_month = 0;
                            $total_no_of_scans_this_week = 0;
                            $total_no_of_scans_today = 0;
                    
                            $day_today = date_create(date('d-m-Y', strtotime("today")));
                            $first_day_of_this_week = date_create(date('d-m-Y', strtotime("this week")));
                            $first_day_of_this_month = date_create(date('d-m-Y', strtotime("first day of this month")));
                            
                            foreach ($result as $row_array) {
                                $currentIterationDate = date_create($row_array['uploadDate']); // Expected format:d-m-Y 
                                if ($currentIterationDate >= $first_day_of_this_month) {
    
                                    if ($currentIterationDate >= $first_day_of_this_week) { 
    
                                        if ($currentIterationDate->format('d-m-Y') == $day_today->format('d-m-Y')) { // The date is same as today's date
                                            $total_no_of_scans_today += 1;
                                            $total_no_of_scans_this_week += 1;
                                            $total_no_of_scans_this_month += 1;
                                        }
    
                                        else {
                                            $total_no_of_scans_this_week += 1;
                                            $total_no_of_scans_this_month += 1;
                                        }
                                    }
                                    else {
                                        $total_no_of_scans_this_month += 1;
                                    }
                                }
                            };
    
                            $total_records = $result->count();
                            $partner_id = $row_array['partnerId'];
                            $total_scan_limit = 3000;
                            $total_remaining_scans = $total_scan_limit - $total_records;
                            $total_no_of_scans_this_today = $total_no_of_scans_today;
                            $total_no_of_scans_this_month = $total_no_of_scans_this_month;
                            $total_no_of_scans_this_week= $total_no_of_scans_this_week;
                            $total_scan = $total_scan_limit;
                            $total_used_scan = $total_records;
                            $total_remaining_scans = $total_remaining_scans;
                            $items = [
                                'partner_id' => $partner_id,
                                'total_remaining_scans' => $total_remaining_scans,
                                'total_no_of_scans_this_today' => $total_no_of_scans_this_today,
                                'total_no_of_scans_this_month' => $total_no_of_scans_this_month,
                                'total_no_of_scans_this_week' => $total_no_of_scans_this_week,
                                'total_scan' => $total_scan,
                                "is_active" => 1,
                                'total_used_scan' => $total_used_scan
                            ];   
                            array_push($partner_datas, $items);
                        }
                        Log::info($partner_datas);
                        $date = Carbon::now()->setTimezone('Europe/Paris')->format('d-m-Y h:i:sa');
                        
    
                        // $data  = [
                        //     "partner_datas" => $partner_datas,
                        //     "date" => $date,
                        // ];
                        $singledata = []; 
                        $allpartnerid = Oauth::all();
                        foreach($allpartnerid as $singlepartnerid)
                        {
                            $result = TextResult::where('partnerId', $singlepartnerid['licno'])->get();
                            if(!$result->isEmpty())
                            {
                                if($singlepartnerid['licno'] == $request['partnerId'])
                                {
                                    if (!$result->isEmpty())
                                    {
                                        $data  = [
                                            "partner_id" => $partner_datas[0]['partner_id'],
                                            "total_remaining_scans" => $partner_datas[0]['total_remaining_scans'],
                                            "total_no_of_scans_this_today" => $partner_datas[0]['total_no_of_scans_this_today'],
                                            "total_no_of_scans_this_month" => $partner_datas[0]['total_no_of_scans_this_month'],
                                            "total_no_of_scans_this_week" => $partner_datas[0]['total_no_of_scans_this_week'],
                                            "total_used_scan" => $partner_datas[0]['total_used_scan'],
                                            "total_scan" => $partner_datas[0]["total_scan"],
                                            "date" => $date,
                                            "is_active" => 1
                                        ];
                                    }
                                }
                                else
                                {
                                    $results = TextResult::where('partnerId', $singlepartnerid['licno'])->get();
                                    if (!$results->isEmpty())
                                    {
                                        $data  = [
                                            "partner_id" => $singlepartnerid['licno'],
                                            "date" => $date,
                                            "is_active" => 0
                                        ];
                                    }
                                }
                                array_push($singledata , $data);
                            }
                        }
                        $data  = [
                            "partner_datas" => $singledata,
                            "date" => $date,
                        ];
                        return $data;
                    }
                    else
                    {
                            $result = TextResult::where('partnerId',$request->partnerId)->get();
                            // Put empty result check
    
                            $total_no_of_scans_this_month = 0;
                            $total_no_of_scans_this_week = 0;
                            $total_no_of_scans_today = 0;
                    
                            $day_today = date_create(date('d-m-Y', strtotime("today")));
                            $first_day_of_this_week = date_create(date('d-m-Y', strtotime("this week")));
                            $first_day_of_this_month = date_create(date('d-m-Y', strtotime("first day of this month")));
                            
                            foreach ($result as $row_array) {
                                $currentIterationDate = date_create($row_array['uploadDate']); // Expected format:d-m-Y 
                                if ($currentIterationDate >= $first_day_of_this_month) {
    
                                    if ($currentIterationDate >= $first_day_of_this_week) { 
    
                                        if ($currentIterationDate->format('d-m-Y') == $day_today->format('d-m-Y')) { // The date is same as today's date
                                            $total_no_of_scans_today += 1;
                                            $total_no_of_scans_this_week += 1;
                                            $total_no_of_scans_this_month += 1;
                                        }
    
                                        else {
                                            $total_no_of_scans_this_week += 1;
                                            $total_no_of_scans_this_month += 1;
                                        }
                                    }
                                    else {
                                        $total_no_of_scans_this_month += 1;
                                    }
                                }
                            };
    
                            $total_records = $result->count();
                            $partner_id = $row_array['partnerId'];
                            $total_scan_limit = 3000;
                            $total_remaining_scans = $total_scan_limit - $total_records;
                            $total_no_of_scans_this_today = $total_no_of_scans_today;
                            $total_no_of_scans_this_month = $total_no_of_scans_this_month;
                            $total_no_of_scans_this_week= $total_no_of_scans_this_week;
                            $total_scan = $total_scan_limit;
                            $total_used_scan = $total_records;
                            $total_remaining_scans = $total_remaining_scans;
                            $items = [
                                'partner_id' => $partner_id,
                                'total_remaining_scans' => $total_remaining_scans,
                                'total_no_of_scans_this_today' => $total_no_of_scans_this_today,
                                'total_no_of_scans_this_month' => $total_no_of_scans_this_month,
                                'total_no_of_scans_this_week' => $total_no_of_scans_this_week,
                                'total_scan' => $total_scan,
                                "is_active" => 1,
                                'total_used_scan' => $total_used_scan
                            ];   
                            array_push($partner_datas, $items);

                        
                        Log::info($partner_datas);
                        $date = Carbon::now()->setTimezone('Europe/Paris')->format('d-m-Y h:i:sa');
                        
    
                        // $data  = [
                        //     "partner_datas" => $partner_datas,
                        //     "date" => $date,
                        // ];
                        // dd($partner_datas[0]['partner_id']);
                        $singledata = [];
                        $alluser = Setting::where('user_id',auth()->user()->id)->where('is_active',1)->get();
                        foreach($alluser as $singleuser)
                        {
                            if($request->partnerId == $singleuser['partner_id'])
                            {
                                $data  = [
                                    "partner_id" => $partner_datas[0]['partner_id'],
                                    "total_remaining_scans" => $partner_datas[0]['total_remaining_scans'],
                                    "total_no_of_scans_this_today" => $partner_datas[0]['total_no_of_scans_this_today'],
                                    "total_no_of_scans_this_month" => $partner_datas[0]['total_no_of_scans_this_month'],
                                    "total_no_of_scans_this_week" => $partner_datas[0]['total_no_of_scans_this_week'],
                                    "total_used_scan" => $partner_datas[0]['total_used_scan'],
                                    "total_scan" => $partner_datas[0]["total_scan"],
                                    "date" => $date,
                                    "is_active" => 1
                                ];
                            }
                            else
                            {
                                $data  = [
                                    "partner_id" => $singleuser['partner_id'],
                                    "date" => $date,
                                    "is_active" => 0
                                ];
                            }
                            
                            array_push($singledata , $data);
                        }
                        $data  = [
                            "partner_datas" => $singledata,
                            "date" => $date,
                        ];
                        return $data;
                    }
                    // $users = Oauth::where('licno', $request->partnerId)->first();

                   
                    // return view('backend.admin.dashboard.dashboard',compact('partner_datas','date')); 
                }
            }
            else if($request->has('today'))
            {
                if (auth()->user())
                {
                    $users = Setting::where('user_id',auth()->user()->id)->where('is_active',1)->get();
                    if (!$users->isEmpty())
                    {
                        $partner_datas = [];
                        if($users->isEmpty())
                        {
                            return view('error.404');
                        }
                        foreach ($users as $user) {
                            $data = $user['partner_id'];
                            $result = TextResult::where('partnerId',$data)->get();
                            if (! $result->isEmpty()) {
                                    $total_no_of_scans_this_month = 0;
                                    $total_no_of_scans_this_week = 0;
                                    $total_no_of_scans_today = 0;
                            
                                    // dd(gettype($request->today));

                                    $date = date_parse_from_format('d-m-Y', $request->today);
                                    $day_today = mktime($date['hour'], $date['minute'], $date['second'], $date['month'], $date['day'], $date['year']);
                                  
                                    $first_day_of_this_week = date_create(date('d-m-Y', strtotime("this week")));
                                    $first_day_of_this_month = date_create(date('d-m-Y', strtotime("first day of this month")));
                                        foreach ($result as $row_array) {
                                            $currentIterationDate = date_create($row_array['uploadDate']);
                                         
                                            if ($currentIterationDate >= $first_day_of_this_month) {
        
                                                if ($currentIterationDate >= $first_day_of_this_week) { 
                                                    $currentdate = $day_today;
                                                    $uploaddate = strtotime($currentIterationDate->format('d-m-Y'));
                                                    if ($currentdate == $uploaddate) { // The date is same as today's date
                                                        $total_no_of_scans_today += 1;
                                                        $total_no_of_scans_this_week += 1;
                                                        $total_no_of_scans_this_month += 1;
                                                    }
        
                                                    else {
                                                        $total_no_of_scans_this_week += 1;
                                                        $total_no_of_scans_this_month += 1;
                                                    }
                                                }
                                                else {
                                                    $total_no_of_scans_this_month += 1;
                                                }
                                            }
                                        };
                                    $total_records = $result->count();
                                    $partner_id = $row_array['partnerId'];
                                    $total_scan_limit = 3000;
                                    $total_remaining_scans = $total_scan_limit - $total_records;
                                    $total_no_of_scans_this_today = $total_no_of_scans_today;
                                    $total_no_of_scans_this_month = $total_no_of_scans_this_month;
                                    $total_no_of_scans_this_week= $total_no_of_scans_this_week;
                                    $total_scan = $total_scan_limit;
                                    $total_used_scan = $total_records;
                                    $total_remaining_scans = $total_remaining_scans;
                                    $items = [
                                        'partner_id' => $partner_id,
                                        'total_remaining_scans' => $total_remaining_scans,
                                        'total_no_of_scans_this_today' => $total_no_of_scans_this_today,
                                        'total_no_of_scans_this_month' => $total_no_of_scans_this_month,
                                        'total_no_of_scans_this_week' => $total_no_of_scans_this_week,
                                        'total_scan' => $total_scan,
                                        "is_active" => 1,
                                        'total_used_scan' => $total_used_scan
                                    ];   
                                array_push($partner_datas,$items);
                            }
                        }

                        $gameCollection = collect($partner_datas);

                        $sorted = $gameCollection->sortByDesc('total_no_of_scans_this_today');

                        $partner_datas = $sorted->values()->take(10);
                        
                        Log::info($partner_datas);
                        $date = Carbon::now()->setTimezone('Europe/Paris')->format('d-m-Y h:i:sa');
                        $data  = [
                            "partner_datas" => $partner_datas,
                            "date" => $date,
                        ];
                        return $data;   

                        //
                    }
                    else
                    {
                        $partner_datas = [];
                        $users = Oauth::all(); 
                        if($users->isEmpty())
                        {
                            return view('error.404');
                        }
                        foreach ($users as $user) {
                            $data = $user['licno'];
                            $result = TextResult::where('partnerId',$data)->get();
                            if (! $result->isEmpty()) {
                                    $total_no_of_scans_this_month = 0;
                                    $total_no_of_scans_this_week = 0;
                                    $total_no_of_scans_today = 0;
                            
                                    $date = date_parse_from_format('d-m-Y', $request->today);
                                    $day_today = mktime($date['hour'], $date['minute'], $date['second'], $date['month'], $date['day'], $date['year']);

                                    // $day_today = date_create(date('d-m-Y', strtotime("today")));
                                    $first_day_of_this_week = date_create(date('d-m-Y', strtotime("this week")));
                                    $first_day_of_this_month = date_create(date('d-m-Y', strtotime("first day of this month")));
                                        foreach ($result as $row_array) {
                                            $currentIterationDate = date_create($row_array['uploadDate']);// Expected format:d-m-Y 
                                            if ($currentIterationDate >= $first_day_of_this_month) {

                                                if ($currentIterationDate >= $first_day_of_this_week) { 

                                                    $currentdate = $day_today;
                                                    $uploaddate = strtotime($currentIterationDate->format('d-m-Y'));
                                                    if ($currentdate == $uploaddate) { // The date is same as today's date
                                                        $total_no_of_scans_today += 1;
                                                        $total_no_of_scans_this_week += 1;
                                                        $total_no_of_scans_this_month += 1;
                                                    }

                                                    else {
                                                        $total_no_of_scans_this_week += 1;
                                                        $total_no_of_scans_this_month += 1;
                                                    }
                                                }
                                                else {
                                                    $total_no_of_scans_this_month += 1;
                                                }
                                            }
                                        };
                                    $total_records = $result->count();
                                    $partner_id = $row_array['partnerId'];
                                    $total_scan_limit = 3000;
                                    $total_remaining_scans = $total_scan_limit - $total_records;
                                    $total_no_of_scans_this_today = $total_no_of_scans_today;
                                    $total_no_of_scans_this_month = $total_no_of_scans_this_month;
                                    $total_no_of_scans_this_week= $total_no_of_scans_this_week;
                                    $total_scan = $total_scan_limit;
                                    $total_used_scan = $total_records;
                                    $total_remaining_scans = $total_remaining_scans;
                                    $items = [
                                        'partner_id' => $partner_id,
                                        'total_remaining_scans' => $total_remaining_scans,
                                        'total_no_of_scans_this_today' => $total_no_of_scans_this_today,
                                        'total_no_of_scans_this_month' => $total_no_of_scans_this_month,
                                        'total_no_of_scans_this_week' => $total_no_of_scans_this_week,
                                        'total_scan' => $total_scan,
                                        'total_used_scan' => $total_used_scan
                                    ];   
                                array_push($partner_datas,$items);
                            }
                        }

                        $gameCollection = collect($partner_datas);

                        $sorted = $gameCollection->sortByDesc('total_no_of_scans_this_today');

                        $partner_datas = $sorted->values();
                        
                        $date = Carbon::now()->setTimezone('Europe/Paris')->format('d-m-Y h:i:sa');   
                        $data  = [
                            "partner_datas" => $partner_datas,
                            "date" => $date,
                        ];
                        return $data; 
                    }
                }
                // return view('backend.admin.dashboard.dashboard',compact('partner_datas','date')); 
            }
            else
            {
                if(auth()->user())
                {
                    $users = Setting::where('user_id',auth()->user()->id)->where('is_active',1)->get();

                    if ($users->isEmpty())
                    {
                        $partner_datas = [];
                        $users = Oauth::all(); 
                        if($users->isEmpty())
                        {
                            return view('error.404');
                        }
                        foreach ($users as $user) {
                            $data = $user['licno'];
                            $result = TextResult::where('partnerId',$data)->get();
                            if (! $result->isEmpty()) {
                                    $total_no_of_scans_this_month = 0;
                                    $total_no_of_scans_this_week = 0;
                                    $total_no_of_scans_today = 0;
                            
                                    $day_today = date_create(date('d-m-Y', strtotime("today")));
                                    $first_day_of_this_week = date_create(date('d-m-Y', strtotime("this week")));
                                    $first_day_of_this_month = date_create(date('d-m-Y', strtotime("first day of this month")));
                                        foreach ($result as $row_array) {
                                            $currentIterationDate = date_create($row_array['uploadDate']);
                                            if ($currentIterationDate >= $first_day_of_this_month) {
        
                                                if ($currentIterationDate >= $first_day_of_this_week) { 
        
                                                    if ($currentIterationDate->format('d-m-Y') == $day_today->format('d-m-Y')) { // The date is same as today's date
                                                        $total_no_of_scans_today += 1;
                                                        $total_no_of_scans_this_week += 1;
                                                        $total_no_of_scans_this_month += 1;
                                                    }
        
                                                    else {
                                                        $total_no_of_scans_this_week += 1;
                                                        $total_no_of_scans_this_month += 1;
                                                    }
                                                }
                                                else {
                                                    $total_no_of_scans_this_month += 1;
                                                }
                                            }
                                        };
                                    $total_records = $result->count();
                                    $partner_id = $row_array['partnerId'];
                                    $total_scan_limit = 3000;
                                    $total_remaining_scans = $total_scan_limit - $total_records;
                                    $total_no_of_scans_this_today = $total_no_of_scans_today;
                                    $total_no_of_scans_this_month = $total_no_of_scans_this_month;
                                    $total_no_of_scans_this_week= $total_no_of_scans_this_week;
                                    $total_scan = $total_scan_limit;
                                    $total_used_scan = $total_records;
                                    $total_remaining_scans = $total_remaining_scans;
                                    $items = [
                                        'partner_id' => $partner_id,
                                        'total_remaining_scans' => $total_remaining_scans,
                                        'total_no_of_scans_this_today' => $total_no_of_scans_this_today,
                                        'total_no_of_scans_this_month' => $total_no_of_scans_this_month,
                                        'total_no_of_scans_this_week' => $total_no_of_scans_this_week,
                                        'total_scan' => $total_scan,
                                        "is_active" => 1,
                                        'total_used_scan' => $total_used_scan
                                    ];   
                                array_push($partner_datas,$items);
                            }
                        }
                        Log::info($partner_datas);
                        $date = Carbon::now()->setTimezone('Europe/Paris')->format('d-m-Y h:i:sa');
                        $data  = [
                            "partner_datas" => $partner_datas,
                            "date" => $date,
                        ];
                        return $data;   
                    }
                    else
                    {
                        $partner_datas = [];
                        // $users = Oauth::all(); 
                        // dd($users);
                        if($users->isEmpty())
                        {
                            return view('error.404');
                        }
                        foreach ($users as $user) {
                            $data = $user['partner_id'];
                            $result = TextResult::where('partnerId',$data)->get();
                            if (! $result->isEmpty()) {
                                    $total_no_of_scans_this_month = 0;
                                    $total_no_of_scans_this_week = 0;
                                    $total_no_of_scans_today = 0;
                            
                                    $day_today = date_create(date('d-m-Y', strtotime("today")));
                                    $first_day_of_this_week = date_create(date('d-m-Y', strtotime("this week")));
                                    $first_day_of_this_month = date_create(date('d-m-Y', strtotime("first day of this month")));
                                        foreach ($result as $row_array) {
                                            $currentIterationDate = date_create($row_array['uploadDate']);
                                            if ($currentIterationDate >= $first_day_of_this_month) {
        
                                                if ($currentIterationDate >= $first_day_of_this_week) { 
        
                                                    if ($currentIterationDate->format('d-m-Y') == $day_today->format('d-m-Y')) { // The date is same as today's date
                                                        $total_no_of_scans_today += 1;
                                                        $total_no_of_scans_this_week += 1;
                                                        $total_no_of_scans_this_month += 1;
                                                    }
        
                                                    else {
                                                        $total_no_of_scans_this_week += 1;
                                                        $total_no_of_scans_this_month += 1;
                                                    }
                                                }
                                                else {
                                                    $total_no_of_scans_this_month += 1;
                                                }
                                            }
                                        };
                                    $total_records = $result->count();
                                    $partner_id = $row_array['partnerId'];
                                    $total_scan_limit = 3000;
                                    $total_remaining_scans = $total_scan_limit - $total_records;
                                    $total_no_of_scans_this_today = $total_no_of_scans_today;
                                    $total_no_of_scans_this_month = $total_no_of_scans_this_month;
                                    $total_no_of_scans_this_week= $total_no_of_scans_this_week;
                                    $total_scan = $total_scan_limit;
                                    $total_used_scan = $total_records;
                                    $total_remaining_scans = $total_remaining_scans;
                                    $items = [
                                        'partner_id' => $partner_id,
                                        'total_remaining_scans' => $total_remaining_scans,
                                        'total_no_of_scans_this_today' => $total_no_of_scans_this_today,
                                        'total_no_of_scans_this_month' => $total_no_of_scans_this_month,
                                        'total_no_of_scans_this_week' => $total_no_of_scans_this_week,
                                        'total_scan' => $total_scan,
                                        "is_active" => 1,
                                        'total_used_scan' => $total_used_scan
                                    ];   
                                array_push($partner_datas,$items);
                            }
                        }
                        Log::info($partner_datas);
                        $date = Carbon::now()->setTimezone('Europe/Paris')->format('d-m-Y h:i:sa');
                        $data  = [
                            "partner_datas" => $partner_datas,
                            "date" => $date,
                        ];
                        return $data;   
                    }

                }
                else
                {
                    $partner_datas = [];
                    $users = Oauth::all(); 
                    // dd($users);
                    if($users->isEmpty())
                    {
                        return view('error.404');
                    }
                    foreach ($users as $user) {
                        $data = $user['licno'];
                        $result = TextResult::where('partnerId',$data)->get();
                        if (! $result->isEmpty()) {
                                $total_no_of_scans_this_month = 0;
                                $total_no_of_scans_this_week = 0;
                                $total_no_of_scans_today = 0;
                        
                                $day_today = date_create(date('d-m-Y', strtotime("today")));
                                $first_day_of_this_week = date_create(date('d-m-Y', strtotime("this week")));
                                $first_day_of_this_month = date_create(date('d-m-Y', strtotime("first day of this month")));
                                    foreach ($result as $row_array) {
                                        $currentIterationDate = date_create($row_array['uploadDate']);
                                        if ($currentIterationDate >= $first_day_of_this_month) {
    
                                            if ($currentIterationDate >= $first_day_of_this_week) { 
    
                                                if ($currentIterationDate->format('d-m-Y') == $day_today->format('d-m-Y')) { // The date is same as today's date
                                                    $total_no_of_scans_today += 1;
                                                    $total_no_of_scans_this_week += 1;
                                                    $total_no_of_scans_this_month += 1;
                                                }
    
                                                else {
                                                    $total_no_of_scans_this_week += 1;
                                                    $total_no_of_scans_this_month += 1;
                                                }
                                            }
                                            else {
                                                $total_no_of_scans_this_month += 1;
                                            }
                                        }
                                    };
                                $total_records = $result->count();
                                $partner_id = $row_array['partnerId'];
                                $total_scan_limit = 3000;
                                $total_remaining_scans = $total_scan_limit - $total_records;
                                $total_no_of_scans_this_today = $total_no_of_scans_today;
                                $total_no_of_scans_this_month = $total_no_of_scans_this_month;
                                $total_no_of_scans_this_week= $total_no_of_scans_this_week;
                                $total_scan = $total_scan_limit;
                                $total_used_scan = $total_records;
                                $total_remaining_scans = $total_remaining_scans;
                                $items = [
                                    'partner_id' => $partner_id,
                                    'total_remaining_scans' => $total_remaining_scans,
                                    'total_no_of_scans_this_today' => $total_no_of_scans_this_today,
                                    'total_no_of_scans_this_month' => $total_no_of_scans_this_month,
                                    'total_no_of_scans_this_week' => $total_no_of_scans_this_week,
                                    'total_scan' => $total_scan,
                                    "is_active" => 1,
                                    'total_used_scan' => $total_used_scan
                                ];   
                            array_push($partner_datas,$items);
                        }
                    }
                    $date = Carbon::now()->setTimezone('Europe/Paris')->format('d-m-Y h:i:sa');
                    $data  = [
                        "partner_datas" => $partner_datas,
                        "date" => $date,
                    ];
                    // return $data;   
                    return response()->json(['Message'=>'SuccessFully...' ,'Data' => $data]);
                }
                // return view('backend.admin.dashboard.dashboard',compact('partner_datas','date')); 
            }
        }
        catch (\Exception $e) {
            Log::info($e->getCode());
            Log::info($e->getMessage());
            Session::flash('danger', 'Internal Server Error. Please try4 again later.');
            return redirect()->back();
        }

        return response()->json(['Message'=>'SuccessFully...']);

    }
}
