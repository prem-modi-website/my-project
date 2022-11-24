<?php

namespace App\Jobs;
use Mail;
use App\Mail\MyTestMail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Log;
use App\Event\NotificationEvent;
use App\Models\MigrationLog;
use Carbon\Carbon;
use DB;
use Session;

class MatchSendEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $details;
    public function __construct($details)
    {
        $this->details = $details;
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        \Log::info("yesssdatas");
        \Log::info($this->details['id']);
        \Log::info($this->details['id']);
        \Log::info("yes");
        try
        {
            // Data pull api
            $url = 'http://localhost/DNS/getData.php?vin='.$this->details['vin'].'&partner_id='.$this->details['partnerId'];
            $crl = curl_init();
            
            curl_setopt($crl, CURLOPT_URL, $url);
            curl_setopt($crl, CURLOPT_FRESH_CONNECT, true);
            curl_setopt($crl, CURLOPT_RETURNTRANSFER, true);
            $response_data = curl_exec($crl);
            $json_decode_response = json_decode($response_data,TRUE);
           
          
           
            if($json_decode_response['status'] == 200)
            {
                $migrationLog = MigrationLog::where('id', $this->details['id'])->first();
                $migrationLog->migrated_on = Carbon::now();
                $migrationLog->pull_status = 1;
                $executeQuery = $migrationLog->update();
                if(!$executeQuery)
                {
                    DB::rollback();
                   \Log::info("Internal server error please try again later.1");
                   $notification_message = "Internal server error please try again later.";
                   event(new NotificationEvent($notification_message));
                   return false;



                }
                $pullApiUrl = 'http://localhost/DNS_staging/api_v2/putDataInDatabase.php?vin='.$this->details['vin'].'&partner_id='.$this->details['partnerId'];
              
                $data_array = array(
                    "data" =>  $json_decode_response['data'],
                    "folders" => $json_decode_response['folders'],
                );
                $data = json_encode($data_array);
                
                curl_setopt($crl, CURLOPT_URL, $pullApiUrl);
                curl_setopt($crl, CURLOPT_POST, true);
                curl_setopt($crl, CURLOPT_POSTFIELDS, $data);
                curl_setopt($crl, CURLINFO_HEADER_OUT, true);
                curl_setopt($crl, CURLOPT_RETURNTRANSFER, true);
                // Set HTTP Header for POST request 
                curl_setopt($crl, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json'
                    )
                );
                
                $resp = curl_exec($crl);
                $manage = json_decode($resp, true);
                
                if($manage['status'] == 200)
                {
                    \Log::info("if");
                    $migrationLog = MigrationLog::where('id', $this->details['id'])->first();
                    $migrationLog->push_status = 1;
                    $executeQuery = $migrationLog->update();

                    if(!$executeQuery)
                    {
                        DB::rollback();
                       \Log::info("Internal server error please try again later.2");
                       $notification_message = "Internal server error please try again later.";
                        event(new NotificationEvent($notification_message));
                        return false;


                    }
                    DB::commit();
                    //   Mail::to('mansi.technotery@gmail.com')->send(new MyTestMail($this->details['id']));
                    $notification_message = "Data save succesfully";
                    $event = event(new NotificationEvent($notification_message));
                    //   Session::flash('dataSave', "Dava save succesfully");
                    return true;
                  

                }
                else
                {

                    DB::rollback();
                   \Log::info("Internal server error please try again later.23");
                   $notification_message = "Internal server error please try again later.";
                   event(new NotificationEvent($notification_message));
                   return false;
                    

                }

            }
            else
            {
                DB::rollback();
               \Log::info("Internal server error please try again later.3");
               $notification_message = "Internal server error please try again later.";
                        event (new NotificationEvent($notification_message));
                        return false;

            }
           
        }
        catch (\Exception $e) {
            Log::info($e->getCode());
            Log::info($e->getMessage());
           \Log::info("Internal server error please try again later.467");
           $notification_message = "Internal server error please try again later.";
                        event (new NotificationEvent($notification_message));
                   return false;

        } 
    }
}
