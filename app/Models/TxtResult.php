<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TxtResult extends Model
{
    use HasFactory;
    public $connection = "dnsdb";
    public $table = "txtresult";
}
