<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TextResult extends Model
{
   protected $connection = 'dnsdb';
   protected $table = "txtresult";
}
