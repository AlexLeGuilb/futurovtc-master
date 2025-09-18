<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Affecte extends Model
{
    protected $table = "affecte";
    protected $guarded = [];
    //protected $primaryKey = ['idEmploye', 'idTransaction', 'idVehicule'];

    public $timestamps = false;
}
