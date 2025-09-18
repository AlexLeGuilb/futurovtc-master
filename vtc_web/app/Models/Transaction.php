<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $table = "Transaction";
    protected $primaryKey = "idTransaction";
    protected $guarded = [];

    public $timestamps = false;
}
