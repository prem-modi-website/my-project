<?php

namespace App\Services\Superadmin\Dashboard;

use App\Repositories\Superadmin\Dashboard\DashboardRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;

class DashboardService
{
    protected $dashboardRepository;

    public function __construct(DashboardRepository $dashboardRepository)
    {
        $this->dashboardRepository = $dashboardRepository;
    }

    //dashboard data
    public function dashboard($request)
    {
        try
        {
            $dashboarddata = $this->dashboardRepository->dashboard($request);
            return $dashboarddata;
        }
        catch (Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());
        }
    }
    //

    //chart data
    public function chart($request , $id)
    {
        try
        {
            $chartsdata = $this->dashboardRepository->chart($request , $id);
            return $chartsdata;
        }
        catch (Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());
        }
    }
    //

    //dynamictable datA
    public function dynamictable($request , $id)
    {
        try
        {
            $stataticsdata = $this->dashboardRepository->dynamictable($request , $id);
            return $stataticsdata;
        }
        catch (Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());
        }
    }
    //

    //Exportdata
    public function export($request , $id)
    {
        try
        {
            $exportsdata = $this->dashboardRepository->export($request , $id);
            return $exportsdata;
        }
        catch (Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());
        }
    }
    //

    //listview data
    public function listview($request)
    {
        try
        {
            $listview = $this->dashboardRepository->listview($request);
            return $listview;
        }
        catch (Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());
        }
    }
    //
}


?>