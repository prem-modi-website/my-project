<?php

namespace App\Http\Controllers\Backend\SuperAdmin\Migration;
ini_set('max_execution_time', 300);
use App\Http\Controllers\Controller;
use App\Services\Superadmin\MigrationUtility\MigrationUtilityService;
use Session;
use App\Models\User;
use Illuminate\Http\Request;

class MigrationUtilityController extends Controller
{
    //
    protected $migrationData;

    public function __construct(MigrationUtilityService $migrationData)
    {
        $this->migrationData = $migrationData;
    }
    
    public function create()
    {
        $data = $this->migrationData->create();
        return view('backend.superadmin.migration-utility.create');
       
    }

    public function saveToken(Request $request)
    {

        $user = User::where('id',auth()->user()->id)->first();
        $user->device_token = $request->token;
        $executeQuery = $user->update();
        if(! $executeQuery)
        {
            return response()->json(['success' => false,'message' => "internal server error."],500);

        }
        return response()->json(['success' => true,'token saved successfully.'],200);
    }

    public function store(Request $request)
    {
        $data = $this->migrationData->store($request->all());
        if($data == true)
        {
            \Log::info("in if");
            Session::flash('success', 'Message send via chrome notification.please on chrome notification.');
            return redirect()->route('migration-create');
        }
        else
        {
            Session::flash('danger', "Internal server error please try again later.");
            return redirect()->route('migration-create');
        }
    }

   
}
