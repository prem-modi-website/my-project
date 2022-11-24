<?php

namespace App\Models;
use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\Model;


class Oauth extends Model
{
    protected $connection = 'dnsdb';
    protected $table = "oauth";
   
}
