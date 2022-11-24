<?php

namespace App\Repositories\Superadmin\MigrationUtility;

use Log;
use App\Models\MigrationLog;
use Carbon\Carbon;
use DB;
use Session;
use App\Events\MigrationEvent;
use App\Jobs\CreateMigrationJob;
use App\Jobs\MatchSendEmail;

class MigrationUtilityRepository
{
    public function create()
    {
        
    }

    public function store($request)
    {
        // dd(auth()->user());
        try
        {
            DB::beginTransaction();
            $migrationData = new MigrationLog;
            $migrationData->user_id = auth()->user()->id;
            $migrationData->partnerId = $request['partnerId'];
            $migrationData->vin = $request['vin'];
            $migrationData->migrated_on = Carbon::now();
            $migrationData->created_on = Carbon::now();
            $migrationData->created_by = auth()->user()->id;
            $migrationData->updated_by = auth()->user()->id;
            $migrationData->pull_status = 0;
            $migrationData->push_status = 0;
            $executeQuery = $migrationData->save();
            if(!$executeQuery)
            {
                DB::rollback();
                return false;

            }
            // $user = User::where('id',auth()->user()->id)->first();
            // $user->device_token = $request->token;
            // $executeQuery = $user->update();
            
            $details = [
                "id" =>$migrationData->id,
                "vin" =>$migrationData->vin,
                "partnerId" =>$migrationData->partnerId,
            ];
            $job = new  MatchSendEmail($details);
            
            app('Illuminate\Contracts\Bus\Dispatcher')->dispatch($job);
            // $job =  MatchSendEmail::dispatch($details);
            $job= json_decode( json_encode($job), true);
            // MatchSendEmail::dispatch($details)->delay(now()->addMinutes(1));
            // MatchSendEmail::dispatch($migrationData->id,$request['vin'],$request['partnerId']);
            // dispatch(new CreateMigrationJob);
            // event(new MigrationEvent());
            return true;

           
        }
        catch (\Exception $e) {
            Log::info($e->getCode());
            Log::info($e->getMessage());
            return false;
        }   
    }
}


?>