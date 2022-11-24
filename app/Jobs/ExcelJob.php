<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\TxtResult;
use App\Models\Oauth;
use App\Models\CustomerInformation;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\VinExport;

class ExcelJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $data;
    public $startdate;
    public $endate;
    public function __construct($data)
    {
        $this->data  = $data['partnerId'];
        $this->startdate  = $data['startdate'];
        $this->endate  = $data['enddate'];
    }
    
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $firstdate = date_create($this->startdate);
        $lastdate = date_create($this->endate);
        // \Log::info("ssss");
        \Log::info('txtresult');
        $txtresult  =  TxtResult::where('partnerId',$this->data)->get();

        $emptydata = [];
        foreach ($txtresult as $key => $singletxtresult) {
            $uploaddate  = date_create($singletxtresult['uploadDate']);

            if ($uploaddate >=$firstdate  && $lastdate>=$uploaddate)
            {
                $cutomerInformation = CustomerInformation::where('partnerId',$this->data)->where('vin',$singletxtresult['vin'])->first();
                $dataimage = "https://drivenscan.com/api_v2/testDemo.php?partnerId=9-200406&vin=".$singletxtresult['vin'];
                $curlmaindata = curl_init($dataimage);
                curl_setopt($curlmaindata, CURLOPT_URL, $dataimage);
                curl_setopt($curlmaindata, CURLOPT_RETURNTRANSFER, true);
                
                curl_setopt($curlmaindata, CURLOPT_SSL_VERIFYHOST, false);
                curl_setopt($curlmaindata, CURLOPT_SSL_VERIFYPEER, false);
            
                $mainimagedata = curl_exec($curlmaindata);
                
                $mainimagedata = json_decode($mainimagedata,true);
                $allImage = [];

                if($mainimagedata)
                {
                    foreach ($mainimagedata as $singledata)
                    {
                        $explode = explode('/',$singledata);
                        $explode = explode('.',$explode[4]);
                        if(isset($explode[1]))
                        {
                            if($explode[1] == "jpg" ||$explode[1] == "png")
                            {
                                $explode = explode('_',$explode[0]);
                                if ($explode[0] == "Dashboard")
                                {
                                    array_push($allImage,$explode[0]);
                                }
                                else 
                                {
                                    array_push($allImage,$explode[0].'_'.$explode[1]);
                                }
                            }
                        }
                    }

                    $mainImageArray = [
                        'Dashboard',
                        'Report_FrontBumper',
                        'Report_LeftFrontFender',
                        'Report_LeftFrontTyre',
                        'Report_LeftFrontDoor',
                        'Report_LeftBackDoor',
                        'Report_LeftRoofFrame',
                        'Report_LeftBackTyre',
                        'Report_LeftBackFender',
                        'Report_BackBumper',
                        'Report_Trunk',
                        'Report_TrunkCover',
                        'Report_BackBumper',
                        'Report_RightBackFender',
                        'Report_RightBackTyre',
                        'Report_RightBackDoor',
                        'Report_RightFrontDoor',
                        'Report_RightRoofFrame',
                        'Report_Roof',
                        'Report_RightFrontTyre',
                        'Report_RightFrontFender',
                        'Report_FrontBumper',
                        'Report_Hood',
                        'Report_Underbody'
                    ];

                    $count = 0;
                    foreach (array_unique($allImage) as $key => $singleimage) {
                        if (in_array($singleimage, $mainImageArray))
                        {
                            $count = $count+1;
                        }
                    }

                }

                    $data = [
                        "vin" => $singletxtresult['vin'],
                        "actualVIN" => $singletxtresult['actualVIN'],
                        "Location" => $singletxtresult['location'],
                        "OrderNo" => $cutomerInformation['orderid'],
                        "uploadDate" => $singletxtresult['uploadDate'],
                        "CustomerName" => $cutomerInformation['name'],
                        "make" => $singletxtresult['make'],
                        "model" => $singletxtresult['model'],
                        "color" => $singletxtresult['color'],
                        "totalPrice" => $singletxtresult['totalPrice'],
                        "TotalDents" => $singletxtresult['dents'],
                        "imageCount" => $count
                    ];
                array_push($emptydata , $data);
            }
        }
        return Excel::download(new VinExport($emptydata), 'getData'.$this->data.'.xlsx');
        return Excel::store(new VinExport($emptydata),'getData'.$this->data.'.xlsx');
    }
}
