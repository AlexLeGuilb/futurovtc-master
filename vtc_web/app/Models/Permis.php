<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permis extends Model
{
    protected $table = "Permis";
    protected $primaryKey = "typePermis";
    protected $keyType = "string";

    public $timestamps = false;
}
