<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Queue;
use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Queue\Events\JobProcessing;
use Illuminate\Queue\Events\JobFailed;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
      
        
        Queue::after(function (JobProcessed $event) {
        //     \Log::info("end process ...");
        //     \Log::notice('Job done: ' . $event->job->resolveName());
        // \Log::notice('Job payload: ' . print_r($event->job->payload(), true));
           
            // $event->connectionName
            // $event->job
            // $event->job->payload()
        });
        Queue::failing(function ( JobFailed $event ) {
            \Log::error('Job failed: ' . 
                       $event->job->resolveName() . 
                      '(' . $event->exception->getMessage() . ')'
                      );
        });
    }
}
