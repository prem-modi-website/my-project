<?php

namespace App\Services\Superadmin\MigrationUtility;

use App\Repositories\Superadmin\MigrationUtility\MigrationUtilityRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;

class MigrationUtilityService
{
    protected $migrationUtilityRepository;

    public function __construct(MigrationUtilityRepository $migrationUtilityRepository)
    {
        $this->migrationUtilityRepository = $migrationUtilityRepository;
    }
    //dashboard data
    public function create()
    {
       $create =  $this->migrationUtilityRepository->create();
       return $create;
    }
    public function store($request)
    {
       $storeData =  $this->migrationUtilityRepository->store($request);
       return $storeData;
    }
}


?>