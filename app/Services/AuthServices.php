<?php

namespace App\Services;

use App\Repositories\AuthRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;

class AuthServices
{
    protected $authRepository;

    public function __construct(AuthRepository $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    public function index()
    {
        try
        {
            $loginpage = $this->authRepository->index();
            return $loginpage;
        }
        catch (Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());
        }
    }

    public function logout()
    {
        try
        {
            $logoutpage = $this->authRepository->logout();
            return $logoutpage;
        }
        catch (Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());
        }
    }

    public function authenticate($request)
    {
        try
        {
            $logincode = $this->authRepository->authenticate($request);
            return $logincode;
        }
        catch (Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());
        }
    }

}


?>