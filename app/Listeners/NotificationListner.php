<?php

namespace App\Listeners;

use App\Event\NotificationEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\User;
use Log;

class NotificationListner
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Event\NotificationEvent  $event
     * @return void
     */
    public function handle(NotificationEvent $event)
    {
        try
        {
            // $firebaseToken = User::all();
            $firebaseToken = User::whereNotNull('device_token')->pluck('device_token')->all();
            \Log::info($firebaseToken);
            \Log::info("yyedara");
            \Log::info($event->notification_message);
            
            $SERVER_API_KEY = config('constants.firebaseConstant.server_api_key');

            // $SERVER_API_KEY = 'AAAAp-9SQfU:APA91bGRXmW6Zr8wzqT-U3XwPT3Vh75mzp7ojEyCKwMGLaB7ohZpMFn73x8XRvP7vOYk1y6KB1zNuJ1Tao5_zwjmKpttN4fF_QX-iDuYXVxB0t2gW0UP5xFmhuXfWxFCQ6Bvd9ee1EfY';
    
            $data = [
                "registration_ids" => $firebaseToken,
                "notification" => [
                    "title" => "Migration.",
                    "body" => $event->notification_message,  
                ]
            ];
            $dataString = json_encode($data);
    
            $headers = [
                'Authorization: key=' . $SERVER_API_KEY,
                'Content-Type: application/json',
            ];
            Log::info($headers);
            
    
            $ch = curl_init();
    
            curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
    
            $response = curl_exec($ch);
    
            Log::info('$response');
            Log::info($response);
        }
        catch (\Exception $e) {
            Log::info($e->getCode());
            Log::info($e->getMessage());
           \Log::info("Internal server error please try again later.4");

           
        } 
    }
}
