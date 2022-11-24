<?php

namespace App\Repositories\Superadmin\Dashboard;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Exports\UsersExport;
use App\Exceptions\CustomException;
use App\Imports\UsersImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Oauth;
use App\Models\TextResult;
use App\Models\Setting;
use Log;
use Session;

class DashboardRepository
{
    public function dashboard($request)
    {
        try
        {
            if ($request->has('partnerId'))
            {
                if ($request->partnerId == "")
                {
                    $name = "notpartnerid";
                    return $name;
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
                    return $data;   
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
    }
    //

    //charts data
    public function chart($request , $id)
    {
        try
        {
            if ($request->to && $request->from)
            {
                $partner_id = $id;
                if ($partner_id == "")
                {
                    $name = "notpartnerid";
                    return $name;
                    // return view('error.404');
                }

                $from_date = \DateTime::createFromFormat('m-Y', $request->from)->format('Y-m');
                $to_date = Carbon::createFromFormat('m-Y', $request->to)->format('Y-m');

                $datestart  = "STR_TO_DATE(uploadDate, '%d-%m-%Y %h:%i:%s')";
                $results = TextResult::where('partnerId',$id)->select("id","vin","regNo","afterRepair","dents",\DB::raw($datestart))->get()
                ->groupBy(function($date) {
                    return  Carbon::parse($date["STR_TO_DATE(uploadDate, '%d-%m-%Y %h:%i:%s')"])->format('Y-m');
                })->toArray();
                if ($results)
                {
                    $month_and_year = array_keys($results);
                    sort($month_and_year);
                    $month_name = [];
                    
                    foreach ($month_and_year as $value) {
                        if ($value > $from_date && $to_date >$value) {
                            $carbon = Carbon::createFromFormat('Y-m', $value);
                            $monthName = $carbon->format('F-Y');
                            array_push($month_name , $monthName);
                        }
                    }

                    $total_months = [];
                    $currentDateTime = Carbon::createFromFormat('Y-m', $from_date);
                    $monthName = $currentDateTime->format('F-Y');
                    $todate = Carbon::createFromFormat('Y-m', $to_date);
                    $tomonthName = $todate->format('F-Y');

                    $period = CarbonPeriod::create($monthName, '1 month',$tomonthName);
                    foreach ($period as $key=> $month) {
                        $items = Carbon::parse($month)->format('F-Y');
                        array_push($total_months, $items);  
                    }

                    $totalmonthofscan = [];   
                    foreach ($total_months as $month) { 
                        $month_year = date('Y-m', strtotime($month)); 
                        if (array_key_exists($month_year,$results)) {
                            $scanofcountallmonth = [
                                "month" => $month,
                                "scan" =>  count($results[$month_year])
                            ];
                        }                           
                        else {
                            $scanofcountallmonth = [
                                "month" => $month,
                                "scan" =>  0
                            ];
                        }
                        array_push($totalmonthofscan , $scanofcountallmonth);
                    }

                    $years = [];
                    foreach($month_name as $year)
                    {
                        $mainyear = explode('-',$year)[1];
                        array_push($years , $mainyear);
                    }

                    $yearofcharts =  array_unique($years);
                    $maximumvalue = max(array_column($totalmonthofscan,'scan'));
                    $data = [
                        "maximumvalue" => $maximumvalue,
                        "monthofscan" => $totalmonthofscan,
                        "partner_id" => $partner_id,
                    ];
                    return $data;
                }
                else
                {
                    $name = "notresult";
                    return $name;
                }
            }
            else
            {
                if (array_key_exists("from",$request->all()))
                {
                    if ($request->from == null)
                    {
                        $name = "fromisnull";
                        return $name;
                    }
                    else
                    {
                        $name = "fromdata";
                        return $name;
                    }
                }
                else if (array_key_exists("to",$request->all()))
                {
                    if ($request->to == "" || $request->to == null)
                    {
                        $name = "toisnull";
                        return $name;
                    }
                    else
                    {
                        $name = "todata";
                        return $name;
                    }
                }
                else
                {
                    $partner_id = $id;
                    $total_no_of_scans_this_month = 0;
                    $total_no_of_scans_this_week = 0;
                    $total_no_of_scans_today = 0;

                    $datestart  = "STR_TO_DATE(uploadDate, '%d-%m-%Y %h:%i:%s')";
                    
                    $results = TextResult::where('partnerId', $id)->select("id", "vin", "regNo", "afterRepair", "dents", \DB::raw($datestart))->get()->groupBy(function($queryResult) {
                        return  Carbon::parse($queryResult["STR_TO_DATE(uploadDate, '%d-%m-%Y %h:%i:%s')"])->format('Y-m');
                    })->toArray();

                    if ($results)
                    {
                        $month_and_year = array_keys($results);
                        sort($month_and_year);

                        $month_name = [];
                        foreach ($month_and_year as $value) {
                            $monthName = Carbon::createFromFormat('Y-m', $value)->format('F-Y');
                            array_push($month_name , $monthName);
                        }
                        //

                        //find the total month
                        $total_months = [];
                        $currentDateTime = Carbon::createFromFormat('Y-m', $month_and_year[0]);
                        $monthName = $currentDateTime->format('F-Y');
                        $lastmonth = end($month_name);
                        $period = CarbonPeriod::create($monthName, '1 month', $lastmonth);
                        foreach ($period as $key=> $month) {
                            $items = Carbon::parse($month)->format('F-Y');
                            array_push($total_months , $items);  
                        }
                        
                        //

                        //find the every month of scan  
                        $totalmonthofscan = [];   
                        foreach ($total_months as $month) { 
                            $month_year = date('Y-m', strtotime($month)); 
                            if (array_key_exists($month_year,$results)) {
                                $scanofcountallmonth = [
                                    "month" => $month,
                                    "scan" =>  count($results[$month_year])
                                ];
                            }                           
                            else {
                                $scanofcountallmonth = [
                                    "month" => $month,
                                    "scan" =>  0
                                ];
                            }
                            array_push($totalmonthofscan , $scanofcountallmonth);
                        }
                        //
                        
                        $years = [];
                        foreach ($month_name as $year) {
                            $mainyear = explode('-',$year)[1];
                            array_push($years , $mainyear);
                        }
                        
                        $yearofcharts =  array_unique($years);
                        $maximumvalue = max(array_column($totalmonthofscan,'scan'));

                        $data = [
                            "maximumvalue" => $maximumvalue,
                            "monthofscan" => $totalmonthofscan,
                            "partner_id" => $partner_id,
                        ];
                        return $data;
                        // exit;
                    }
                    else
                    {
                        $name = "datanotfound";
                        return $name;
                    }
                }
            }
        }
        catch (\Exception $e) {
            Log::info($e->getCode());
            Log::info($e->getMessage());
            Session::flash('danger', 'Internal Server Error. Please try4 again later.');
            return redirect()->back();
        }         
    }
    //

    //statatics data
    public function dynamictable($request , $id)
    {
        try
        {
            if($id != "")
            {
                if ($request->from && $request->to)
                {
                    $from_date = \DateTime::createFromFormat('d/m/Y', $request->from)->format('Y-m-d H:i:s');
                    $to_date = Carbon::createFromFormat('d/m/Y', $request->to)->format('Y-m-d H:i:s');
                    $datestart  = "STR_TO_DATE(uploadDate, '%d-%m-%Y %H:%i:%s')";
                    $users = TextResult::where('partnerId',$id)->select("id","vin","regNo","modifiedDate","dents",\DB::raw($datestart))
                        ->get();    
                    if ($users)
                    {
                        $results = [];
                        foreach($users as $user)
                        {
                            $uploaddate =  $user["STR_TO_DATE(uploadDate, '%d-%m-%Y %H:%i:%s')"];
                            if($uploaddate != "")
                            {
                                $formatchange = Carbon::createFromFormat('Y-m-d H:i:s', $uploaddate)->format('Y-m-d H:i:s');
                                if (($formatchange > $from_date) && ($to_date > $formatchange))
                                {
                                    $txtresult = [
                                        "vin" => $user['vin'],
                                        "regNo" => $user['regNo'],
                                        "uploadDate" => $formatchange,
                                        "dents" => $user['dents'],
                                        "modifiedDate" => $user['modifiedDate'],
                                        
                                    ];
                                    array_push($results,$txtresult);
                                }
                            }
                        }

                        //duplicate vin remove
                        $txtresults = [];
                        $vindata = [];
                        $count = 0;
                        $emptyarray = [];
                        $resultunderscore = "";
            
                        foreach($results as $key => $result)
                        {
                            $explode = explode('_', $result['vin']);
                            if (count($explode) == 1)
                            {
                                
                                if (! in_array($explode[0], $vindata))
                                {
                                    array_push($vindata , $explode[0]);
                                    $duplicate = [
                                        "vin" => $explode[0],
                                        "originalVIN" => $result['vin'],
                                        "index" => 0,
                                        "dents" => $result['dents'],
                                        "uploadDate" => $result['uploadDate'],
                                        "regNo" => $result['regNo'],
                                        "modifiedDate" => $result['modifiedDate'],
                                        "count" => 1
                                    ];
                                    array_push($txtresults, $duplicate);
                                    // dump($finaldata);
                                }
                            }
                            else
                            {
                                if (! in_array($explode[0], $vindata))
                                {

                                    array_push($vindata , $explode[0]);
                                    $duplicate = [
                                        "vin" => $explode[0],
                                        "originalVIN" => $result['vin'],
                                        "index" => $explode[1],
                                        "dents" => $result['dents'],
                                        "uploadDate" => $result['uploadDate'],
                                        "regNo" => $result['regNo'],
                                        "modifiedDate" => $result['modifiedDate'],
                                        "count" => 1
                                    ];
                                    array_push($txtresults, $duplicate);
                                                                        
                                }
                                else
                                {
                                    $index = array_search($explode[0], array_column($txtresults, 'vin'));
                                    // dump($index);
                                    // exit;
                                    
                                    // dump($index);
                                    
                                    // dump($explode[0]);
                                    
                                    if ($index !== false) {
                                        
                                    
                                        if ($txtresults[$index]["index"] <= $explode[1]) {
                                            // dump("yes");
                                            
                                            $duplicate = [
                                                "vin" => $explode[0],
                                                "originalVIN" => $result['vin'],
                                                "index" => $explode[1],
                                                "dents" => $result['dents'],
                                                "uploadDate" => $result['uploadDate'],
                                                "regNo" => $result['regNo'],
                                                "modifiedDate" => $result['modifiedDate'],
                                                "count" => $txtresults[$index]["count"] + 1
                                            ];
                                        
                                            $txtresults[$index] = $duplicate;                                             
                                        } 
                                        else {
                                            
                                            $txtresults[$index]['count'] = $txtresults[$index]["count"] + 1;
                                        }
                                    }
                                }
                            }
                        }
                        //
                        $data = [
                            "id" => $id,
                            "txtresults" => $txtresults,
                            "from_date" => $from_date,
                            "to_date" => $to_date,
                        ];
                        return $data;
                        //  return view('backend.admin.dashboard.table',compact('id', 'txtresults' ,'from_date','to_date'));
                    }
                    else
                    {
                        $name = "notUserFound";
                        return $name;
                        // return view('error.404');
                    }
                }
                else if($request->has('today'))
                {
                   
                    $today = Carbon::today()->format('Y-m-d');

                    $datestart  = "STR_TO_DATE(uploadDate, '%d-%m-%Y %H:%i:%s')";

                    $unixTimestampOfUploadedDate = "UNIX_TIMESTAMP(STR_TO_DATE(uploadDate, '%d-%m-%Y %H:%i:%s'))";
                    $users = TextResult::where('partnerId',$id)->select("id","vin","regNo","modifiedDate","dents",\DB::raw($datestart), \DB::raw($unixTimestampOfUploadedDate))->orderBy(\DB::raw($unixTimestampOfUploadedDate),'desc')
                        ->get();
                   
                        $txtresults = [];
                    foreach($users as $user)
                    {
                        $uploaddate =  $user["STR_TO_DATE(uploadDate, '%d-%m-%Y %H:%i:%s')"];

                        if($uploaddate)
                        {
                            $formatchange = Carbon::createFromFormat('Y-m-d H:i:s', $uploaddate)->format('Y-m-d');

                            if($formatchange == $today)
                            {
                                $formatchange = Carbon::createFromFormat('Y-m-d H:i:s', $uploaddate)->format('Y-m-d H:i:s');

                                $txtresult = [
                                    "vin" => $user['vin'],
                                    "regNo" => $user['regNo'],
                                    "uploadDate" => $formatchange,
                                    "dents" => $user['dents'],
                                    "modifiedDate" => $user['modifiedDate'],                                
                                ];
                                array_push($txtresults,$txtresult);
                            }
                        }
                    }
                     
                    $data = [
                        "id" => $id,
                        "today",
                        "txtresults" => $txtresults,
                    ];
                    return $data;
                    //  return view('backend.admin.dashboard.table',compact('id','txtresults'));
                }
                else
                {
                    if (array_key_exists("from",$request->all()))
                    {
                        if ($request->from == null)
                        {
                            $name = "fromisnull";
                            return $name;
                            // return view('error.404');
                        }
                        else
                        {
                            $name = "fromdata";
                            return $name;
                            // return view('error.404');
                        }
                    }
                    else if (array_key_exists("to",$request->all()))
                    {
                        if ($request->to == "" || $request->to == null)
                        {
                            $name = "toisnull";
                            return $name;
                            // return view('error.404');
                        }
                        else
                        {
                            $name = "todata";
                            return $name;
                            // return view('error.404');
                        }
                    }
                    else
                    {
                        $results = TextResult::where('partnerId',$id)->get();

                        //duplicate vin remove
                        $txtresults = [];
                        $vindata = [];
                        $count = 0;
                        $emptyarray = [];
                        $resultunderscore = "";
            
                        foreach($results as $key => $result)
                        {
                            $explode = explode('_', $result['vin']);
                            if (count($explode) == 1)
                            {
                                
                                if (! in_array($explode[0], $vindata))
                                {
                                    array_push($vindata , $explode[0]);
                                    $duplicate = [
                                        "vin" => $explode[0],
                                        "originalVIN" => $result['vin'],
                                        "index" => 0,
                                        "dents" => $result['dents'],
                                        "uploadDate" => $result['uploadDate'],
                                        "regNo" => $result['regNo'],
                                        "modifiedDate" => $result['modifiedDate'],
                                        "count" => 1
                                    ];
                                    array_push($txtresults, $duplicate);
                                    // dump($finaldata);
                                }
                            }
                            else
                            {
                                if (! in_array($explode[0], $vindata))
                                {

                                    array_push($vindata , $explode[0]);
                                    $duplicate = [
                                        "vin" => $explode[0],
                                        "originalVIN" => $result['vin'],
                                        "index" => $explode[1],
                                        "dents" => $result['dents'],
                                        "uploadDate" => $result['uploadDate'],
                                        "regNo" => $result['regNo'],
                                        "modifiedDate" => $result['modifiedDate'],
                                        "count" => 1
                                    ];
                                    array_push($txtresults, $duplicate);
                                                                        
                                }
                                else
                                {
                                    $index = array_search($explode[0], array_column($txtresults, 'vin'));
                                    // dump($index);
                                    // exit;
                                    
                                    // dump($index);
                                    
                                    // dump($explode[0]);
                                    
                                    if ($index !== false) {
                                        
                                    
                                        if ($txtresults[$index]["index"] <= $explode[1]) {
                                            // dump("yes");
                                            
                                            $duplicate = [
                                                "vin" => $explode[0],
                                                "originalVIN" => $result['vin'],
                                                "index" => $explode[1],
                                                "dents" => $result['dents'],
                                                "uploadDate" => $result['uploadDate'],
                                                "regNo" => $result['regNo'],
                                                "modifiedDate" => $result['modifiedDate'],
                                                "count" => $txtresults[$index]["count"] + 1
                                            ];
                                        
                                            $txtresults[$index] = $duplicate;                                             
                                        } 
                                        else {
                                            
                                            $txtresults[$index]['count'] = $txtresults[$index]["count"] + 1;
                                        }
                                    }
                                }
                            }
                        }
                        //

                        if (!$txtresults)
                        {
                            $name = "notresultfound";
                            return $name;
                            // return view('error.404');
                        }
                        else
                        {
                            $data = [
                                "id" => $id,
                                "today",
                                "txtresults" => $txtresults,
                            ];
                            return $data;
                            //  return view('backend.admin.dashboard.table',compact('id','txtresults'));
                        }
                    }
                }
            }
            else
            {
                return view('error.404');
            }
        }
        catch (\Exception $e) {
            Log::info($e->getCode());
            Log::info($e->getMessage());
            Session::flash('danger', 'Internal Server Error. Please try4 again later.');
            return redirect()->back();
        }   
    }
    //

    //exports data
    public function export($request , $id)
    {
        try
        {
            if ($request->has('to') && $request->has('from'))
            {
                $from_date = \DateTime::createFromFormat('d/m/Y', $request->from)->format('Y-m-d H:i:s');
                $to_date = Carbon::createFromFormat('d/m/Y', $request->to)->format('Y-m-d H:i:s');
                $datestart  = "STR_TO_DATE(uploadDate, '%d-%m-%Y %H:%i:%s')";
                $users = TextResult::where('partnerId',$id)->select("id","vin","regNo","modifiedDate","dents",\DB::raw($datestart))
                    ->get();
                $results = [];
                $count = 0;
                foreach ($users as $key => $user)
                {
                    // $count = $key+1;
                    $uploaddate =  $user["STR_TO_DATE(uploadDate, '%d-%m-%Y %H:%i:%s')"];
                    if ($uploaddate != "")
                    {
                        $formatchange = Carbon::createFromFormat('Y-m-d H:i:s', $uploaddate)->format('Y-m-d H:i:s');
                        if (($formatchange > $from_date) && ($to_date > $formatchange))
                        {
                            $txtresult = [
                                "srno" => ++$count,
                                "vin" => $user['vin'],
                                "regNo" => $user['regNo'],
                                "uploadDate" => $formatchange,
                                "dents" => $user['dents'],
                                "modifiedDate" => $user['modifiedDate'],
                                
                            ];
                            array_push($results,$txtresult);
                        }
                    }
                }

                //duplicate vin remove
                $finaldata = [];
                $vindata = [];
                $count = 0;
                $emptyarray = [];
                $resultunderscore = "";

                foreach($results as $key => $result)
                {
                    $explode = explode('_', $result['vin']);
                    if (count($explode) == 1)
                    {
                        
                        if (! in_array($explode[0], $vindata))
                        {
                            array_push($vindata , $explode[0]);
                            $duplicate = [
                                "vin" => $explode[0],
                                "originalVIN" => $result['vin'],
                                "index" => 0,
                                "dents" => $result['dents'],
                                "uploadDate" => $result['uploadDate'],
                                "regNo" => $result['regNo'],
                                "modifiedDate" => $result['modifiedDate'],
                                "count" => 1
                            ];
                            array_push($finaldata, $duplicate);
                            // dump($finaldata);
                        }
                    }
                    else
                    {
                        if (! in_array($explode[0], $vindata))
                        {
    
                            array_push($vindata , $explode[0]);
                            $duplicate = [
                                "vin" => $explode[0],
                                "originalVIN" => $result['vin'],
                                "index" => $explode[1],
                                "dents" => $result['dents'],
                                "uploadDate" => $result['uploadDate'],
                                "regNo" => $result['regNo'],
                                "modifiedDate" => $result['modifiedDate'],
                                "count" => 1
                            ];
                            array_push($finaldata, $duplicate);
                                                                  
                        }
                        else
                        {
                            $index = array_search($explode[0], array_column($finaldata, 'vin'));
                            // dump($index);
                            // exit;
                            
                            // dump($index);
                            
                            // dump($explode[0]);
                            
                            if ($index !== false) {
                                
                               
                                if ($finaldata[$index]["index"] <= $explode[1]) {
                                    // dump("yes");
                                    
                                    $duplicate = [
                                        "vin" => $explode[0],
                                        "originalVIN" => $result['vin'],
                                        "index" => $explode[1],
                                        "dents" => $result['dents'],
                                        "uploadDate" => $result['uploadDate'],
                                        "regNo" => $result['regNo'],
                                        "modifiedDate" => $result['modifiedDate'],
                                        "count" => $finaldata[$index]["count"] + 1
                                    ];
                                   
                                    $finaldata[$index] = $duplicate;                                             
                                } 
                                else {
                                    
                                    // dump("else check");
                                    // dump($finaldata[$index]);
                                    $finaldata[$index]['count'] = $finaldata[$index]["count"] + 1;
                                }
                                // dump($finaldata);
                            }
                        }
                    }
                }
                $exceldata = [];
                $counts = 0;
                foreach($finaldata as $final)
                {
                    $data = [
                        "No" => $counts += 1,
                        "originalVIN" => $final['originalVIN'],
                        "regNo" => $final['regNo'],
                        "uploadDate" => $final['uploadDate'],
                        "dents" => $final['dents'],
                        "modifiedDate" => $final['modifiedDate'],
                        "count" => $final['count'],
                    ];
                    unset($final['vin']);
                    unset($final['index']);
                    array_push($exceldata , $data);
                }
                //

                // $txtresults = TextResult::where('partnerId',$id)->get(['vin','regNo','uploadDate','dents','afterRepair']);
                $from_date = \DateTime::createFromFormat('m/d/Y', $request->from)->format('d-m-Y');
                $to_date = Carbon::createFromFormat('m/d/Y', $request->to)->format('d-m-Y');
                return Excel::download(new UsersExport($exceldata), 'Scans_for"'.$id.'_'.$from_date.'_'.$to_date.'".xlsx');
            }
            else
            {
                $datestart  = "STR_TO_DATE(uploadDate, '%d-%m-%Y %H:%i:%s')";
                $unixTimestampOfUploadedDate = "UNIX_TIMESTAMP(STR_TO_DATE(uploadDate, '%d-%m-%Y %H:%i:%s'))";
                $users = TextResult::where('partnerId',$id)->select("id","vin","regNo","modifiedDate","dents",\DB::raw($datestart), \DB::raw($unixTimestampOfUploadedDate))
                    ->get();
                // dd($users);
                $i =[];
                foreach ($users as $user) {
                    if ($user["UNIX_TIMESTAMP(STR_TO_DATE(uploadDate, '%d-%m-%Y %H:%i:%s'))"])
                    {
                         array_push($i, $user["UNIX_TIMESTAMP(STR_TO_DATE(uploadDate, '%d-%m-%Y %H:%i:%s'))"]);
                    }
                }
                $minPrice = min($i);
                $maxPrice = max($i);

                $epocminprice = new \DateTime("@$minPrice");  // convert UNIX timestamp to PHP DateTime
                $fromdata = $epocminprice->format('d-m-Y');

                // echo "<br>";
                $epocmaxPrice = new \DateTime("@$maxPrice");  // convert UNIX timestamp to PHP DateTime
                $todata = $epocmaxPrice->format('d-m-Y');
                // exit;
                
              
                $results = TextResult::where('partnerId',$id)->get();

                //duplicate vin remove
                $finaldata = [];
                $vindata = [];
                $count = 0;
                $emptyarray = [];
                $resultunderscore = "";
                
                foreach($results as $key => $result)
                {
                    $explode = explode('_', $result['vin']);
                    if (count($explode) == 1)
                    {
                        
                        if (! in_array($explode[0], $vindata))
                        {
                            array_push($vindata , $explode[0]);
                            $duplicate = [
                                "vin" => $explode[0],
                                "originalVIN" => $result['vin'],
                                "index" => 0,
                                "dents" => $result['dents'],
                                "uploadDate" => $result['uploadDate'],
                                "regNo" => $result['regNo'],
                                "modifiedDate" => $result['modifiedDate'],
                                "count" => 1
                            ];
                            array_push($finaldata, $duplicate);
                            // dump($finaldata);
                        }
                    }
                    else
                    {
                        if (! in_array($explode[0], $vindata))
                        {
    
                            array_push($vindata , $explode[0]);
                            $duplicate = [
                                "vin" => $explode[0],
                                "originalVIN" => $result['vin'],
                                "index" => $explode[1],
                                "dents" => $result['dents'],
                                "uploadDate" => $result['uploadDate'],
                                "regNo" => $result['regNo'],
                                "modifiedDate" => $result['modifiedDate'],
                                "count" => 1
                            ];
                            array_push($finaldata, $duplicate);
                                                                  
                        }
                        else
                        {
                            $index = array_search($explode[0], array_column($finaldata, 'vin'));
                            // dump($index);
                            // exit;
                            
                            // dump($index);
                            
                            // dump($explode[0]);
                            
                            if ($index !== false) {
                                
                               
                                if ($finaldata[$index]["index"] <= $explode[1]) {
                                    // dump("yes");
                                    
                                    $duplicate = [
                                        "vin" => $explode[0],
                                        "originalVIN" => $result['vin'],
                                        "index" => $explode[1],
                                        "dents" => $result['dents'],
                                        "uploadDate" => $result['uploadDate'],
                                        "regNo" => $result['regNo'],
                                        "modifiedDate" => $result['modifiedDate'],
                                        "count" => $finaldata[$index]["count"] + 1
                                    ];
                                   
                                    $finaldata[$index] = $duplicate;                                             
                                } 
                                else {
                                    
                                    // dump("else check");
                                    // dump($finaldata[$index]);
                                    $finaldata[$index]['count'] = $finaldata[$index]["count"] + 1;
                                }
                                // dump($finaldata);
                            }
                        }
                    }
                }

                $exceldata = [];
                $count = 0;
                foreach($finaldata as $final)
                {
                    $data = [
                        "No" => $count++,
                        "originalVIN" => $final['originalVIN'],
                        "regNo" => $final['regNo'],
                        "uploadDate" => $final['uploadDate'],
                        "dents" => $final['dents'],
                        "modifiedDate" => $final['modifiedDate'],
                        "count" => $final['count'],
                    ];
                    unset($final['vin']);
                    unset($final['index']);
                    array_push($exceldata , $data);
                }

                //
                return Excel::download(new UsersExport($exceldata), 'Scans_for"'.$id.'_'.$fromdata.'_'.$todata.'".xlsx');
            }
        }
        catch (\Exception $e) {
            Log::info($e->getCode());
            Log::info($e->getMessage());
            Session::flash('danger', 'Internal Server Error. Please try4 again later.');
            return redirect()->back();
        } 
    }
    //
    
    //listviews data
    public function listview($request)
    {
        try
        {
            if ($request->has('from') && $request->has('to') && $request->has('flag'))
            {
                if ($request['from'] && $request['to'] && $request['flag'])
                {
                    $day_today =date_create(date('d-m-Y', strtotime("today")));
                    $daytoday = strtotime($day_today->format('d-m-Y'));
                    
                    $from_date = date_create(date('d-m-Y', strtotime($request['from'])));
                    $fromdate = strtotime($from_date->format('d-m-Y'));

                    $to_date = date_create(date('d-m-Y', strtotime($request['to'])));
                    $todate = strtotime($to_date->format('d-m-Y'));

                    $weekdate = strtotime("-7 day", $daytoday);
                    $monthdate = strtotime("-1 month", $daytoday);
                    
                    if ($weekdate == $todate && $fromdate == $daytoday)
                    {
                        if ($request['flag'] == "week")
                        {
                            $users = Oauth::all(); 
                            if ($users->isEmpty())
                            {
                                $name = "datanotfound";
                                return $name;
                                // return view('error.404');
                            }
            
                            // dd($users[4]['licno']);
                            $partner_datas = [];
                            foreach($users as $user)
                            {
                                $data = $user['licno'];
                                $result = TextResult::where('partnerId', $data)->get();
                                if (!$result->isEmpty())
                                {
                                    $total_no_of_scans_today = 0;
                                    $total_no_of_scans_this_month = 0;
                                    $total_no_of_scans_this_week = 0;
                                    //     // array_push($partner_datas,$result);
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
                            if ($request['order'] == "desc")
                            {
                                $gameCollection = collect($partner_datas);
            
                                $sorted = $gameCollection->sortByDesc('total_no_of_scans_this_week');
            
                                $partner_datas = $sorted->values();
            
                                $date = Carbon::now()->setTimezone('Europe/Paris')->format('d-m-Y h:i:sa');   

                                $data = [
                                    "partner_datas" => $partner_datas,
                                    "date" => $date,
                                ];
                                return $data;
                                //  return view('backend.admin.dashboard.gridviewtable',compact('partner_datas','date'));     
                            }
                            else if($request['order'] == "asc")
                            {
                                $gameCollection = collect($partner_datas);
            
                                $sorted = $gameCollection->sortBy('total_no_of_scans_this_week');
            
                                $partner_datas = $sorted->values();
            
                                $date = Carbon::now()->setTimezone('Europe/Paris')->format('d-m-Y h:i:sa');   
                                $data = [
                                    "partner_datas" => $partner_datas,
                                    "date" => $date,
                                ];
                                return $data;
                                //  return view('backend.admin.dashboard.gridviewtable',compact('partner_datas','date'));
                            }
                            else
                            {
                                $gameCollection = collect($partner_datas);
            
                                $sorted = $gameCollection->sortByDesc('total_no_of_scans_this_week');
            
                                $partner_datas = $sorted->values();
            
                                $date = Carbon::now()->setTimezone('Europe/Paris')->format('d-m-Y h:i:sa');   
                                $data = [
                                    "partner_datas" => $partner_datas,
                                    "date" => $date,
                                ];
                                return $data;
                                //  return view('backend.admin.dashboard.gridviewtable',compact('partner_datas','date'));     
                            }
                        }
                        else
                        {
                            $name = "notflagavailable";
                            return $name;
                            // return view('error.404');
                        }
                    }
                    else if ($monthdate == $todate && $fromdate == $daytoday)
                    {
                        if ($request['flag'] == "month")
                        {
                            $users = Oauth::all(); 
                            if ($users->isEmpty())
                            {
                                $name = "datanotfound";
                                return $name;
                                // return view('error.404');
                            }
            
                            // dd($users[4]['licno']);
                            $partner_datas = [];
                            foreach($users as $user)
                            {
                                $data = $user['licno'];
                                $result = TextResult::where('partnerId', $data)->get();
                                if (!$result->isEmpty())
                                {
                                    $total_no_of_scans_today = 0;
                                    $total_no_of_scans_this_month = 0;
                                    $total_no_of_scans_this_week = 0;
                                    //     // array_push($partner_datas,$result);
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
                            if ($request['order'] == "desc")
                            {
                                $gameCollection = collect($partner_datas);
            
                                $sorted = $gameCollection->sortByDesc('total_no_of_scans_this_month');
            
                                $partner_datas = $sorted->values();
            
                                $date = Carbon::now()->setTimezone('Europe/Paris')->format('d-m-Y h:i:sa');   
                                $data = [
                                    "partner_datas" => $partner_datas,
                                    "date" => $date,
                                ];
                                return $data;
                                //  return view('backend.admin.dashboard.gridviewtable',compact('partner_datas','date')); 
                            }
                            else if($request['order'] == "asc")
                            {
                                $gameCollection = collect($partner_datas);
            
                                $sorted = $gameCollection->sortBy('total_no_of_scans_this_month');
            
                                $partner_datas = $sorted->values();
            
                                $date = Carbon::now()->setTimezone('Europe/Paris')->format('d-m-Y h:i:sa');   
                                $data = [
                                    "partner_datas" => $partner_datas,
                                    "date" => $date,
                                ];
                                return $data;
                                //  return view('backend.admin.dashboard.gridviewtable',compact('partner_datas','date')); 
                            }
                            else
                            {
                                $gameCollection = collect($partner_datas);
            
                                $sorted = $gameCollection->sortByDesc('total_no_of_scans_this_month');
            
                                $partner_datas = $sorted->values();
            
                                $date = Carbon::now()->setTimezone('Europe/Paris')->format('d-m-Y h:i:sa');   
                                $data = [
                                    "partner_datas" => $partner_datas,
                                    "date" => $date,
                                ];
                                return $data;
                                //  return view('backend.admin.dashboard.gridviewtable',compact('partner_datas','date')); 
                            }
                        }
                        else
                        {
                            $name = "notflagavailable";
                            return $name;
                            // return view('error.404');
                        }
                    }
                    else if ($daytoday == $fromdate)
                    {
                        if ($request['flag'] == "day")
                        {
                            $users = Oauth::all(); 
                            if ($users->isEmpty())
                            {
                                $name = "datanotfound";
                                return $name;
                                // return view('error.404');
                            }
            
                            // dd($users[4]['licno']);
                            $partner_datas = [];
                            foreach($users as $user)
                            {
                                $data = $user['licno'];
                                $result = TextResult::where('partnerId', $data)->get();
                                if (!$result->isEmpty())
                                {
                                    $total_no_of_scans_today = 0;
                                    $total_no_of_scans_this_month = 0;
                                    $total_no_of_scans_this_week = 0;
                                    //     // array_push($partner_datas,$result);
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
                            if ($request['order'] == "desc")
                            {
                                $gameCollection = collect($partner_datas);
            
                                $sorted = $gameCollection->sortByDesc('total_no_of_scans_this_today');
            
                                $partner_datas = $sorted->values();
            
                                $date = Carbon::now()->setTimezone('Europe/Paris')->format('d-m-Y h:i:sa');   
                                $data = [
                                    "partner_datas" => $partner_datas,
                                    "date" => $date,
                                ];
                                return $data;
                                //  return view('backend.admin.dashboard.gridviewtable',compact('partner_datas','date')); 
                            }
                            else if ($request['order'] == "asc")
                            {
                                $gameCollection = collect($partner_datas);
            
                                $sorted = $gameCollection->sortBy('total_no_of_scans_this_today');
            
                                $partner_datas = $sorted->values();
            
                                $date = Carbon::now()->setTimezone('Europe/Paris')->format('d-m-Y h:i:sa');   
                                $data = [
                                    "partner_datas" => $partner_datas,
                                    "date" => $date,
                                ];
                                return $data;
                                //  return view('backend.admin.dashboard.gridviewtable',compact('partner_datas','date')); 
                            }
                            else
                            {
                                $gameCollection = collect($partner_datas);
                                $sorted = $gameCollection->sortByDesc('total_no_of_scans_this_today');
            
                                $partner_datas = $sorted->values();
            
                                $date = Carbon::now()->setTimezone('Europe/Paris')->format('d-m-Y h:i:sa');  
                                $data = [
                                    "partner_datas" => $partner_datas,
                                    "date" => $date,
                                ];
                                return $data; 
                                //  return view('backend.admin.dashboard.gridviewtable',compact('partner_datas','date')); 
                            }
                        }
                        else
                        {
                            $name = "notflagavailable";
                            return $name;
                            // return view('error.404');
                        }
                    }
                    else
                    {
                        $name = "notflagavailable";
                        return $name;
                        // return view('error.404');
                    }
                }
                else{
                    $name = "notflagavailable";
                    return $name;
                    // return view('error.404');
                }
            }
            else
            {
                if (array_key_exists('from',$request->all()))
                {
                    if ($request->from == "")
                    {
                        $name = "fromisnull";
                        return $name;
                        // return view('error.404');
                    }
                    else
                    {
                        $name = "fromdata";
                        return $name;
                        // return view('error.404');
                    }
                }
                else if(array_key_exists('to',$request->all()))
                {
                    if ($request->to == "")
                    {
                        $name = "toisnull";
                        return $name;
                        // return view('error.404');
                    }
                    else
                    {
                        $name = "todata";
                        return $name;
                        // return view('error.404');
                    }
                }
                else if(array_key_exists('order',$request->all()))
                {
                    if ($request->order == "")
                    {
                        $name = "orderisnull";
                        return $name;
                        // return view('error.404');
                    }
                    else
                    {
                        $name = "orderdata";
                        return $name;
                        // return view('error.404');
                    }
                }
                else
                {
                    $partner_datas = [];
                    $users = Oauth::all(); 
                    // dd($users);
                    if($users->isEmpty())
                    {
                        $name = "datanotfound";
                        return $name;
                        // return view('error.404');
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
                                    'total_used_scan' => $total_used_scan
                                ];   
                            array_push($partner_datas,$items);
                        }
                    }
                    Log::info($partner_datas);
                    $date = Carbon::now()->setTimezone('Europe/Paris')->format('d-m-Y h:i:sa'); 
                    $data = [
                        "partner_datas" => $partner_datas,
                        "date" => $date,
                    ];
                    return $data;   
                    //  return view('backend.admin.dashboard.gridviewtable',compact('partner_datas','date')); 
                }
            }
        }
        catch (\Exception $e) {
            Log::info($e->getCode());
            Log::info($e->getMessage());
            Session::flash('danger', 'Internal Server Error. Please try4 again later.');
            return redirect()->back();
        }
    }
    //
}


?>