<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicule extends Model
{
    protected $table = "Vehicule";
    protected $primaryKey = "idVehicule";
    protected $keyType = "string";

    protected $guarded = [];
    public $timestamps = false;
}
