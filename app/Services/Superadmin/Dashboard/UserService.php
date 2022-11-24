<?php

namespace App\Services\Superadmin\Dashboard;

use App\Repositories\Superadmin\Dashboard\UserRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;

class UserService
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function create()
    {
        $create = $this->userRepository->create();
        return $create;
    }

    public function index($request)
    {
        $index = $this->userRepository->index($request);
        return $index;
    }

    public function store($request)
    {
        $store = $this->userRepository->store($request);
        return $store;
    }

    public function edit($id)
    {
        $edit = $this->userRepository->edit($id);
        return $edit;
    }

    public function update($request , $id)
    {
        $update = $this->userRepository->update($request , $id);
        return $update;
    }
    
    public function deactivate($request , $id)
    {
        $deactivate = $this->userRepository->deactivate($request , $id);
        return $deactivate;
    }
}