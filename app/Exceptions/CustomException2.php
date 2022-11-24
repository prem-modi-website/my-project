<?php
namespace App\Exceptions;
use Exception;
use Illuminate\Http\Request;

trait CustomException
{
    public function errormessage()
    {
      return view('welcome');
    }
}


?>