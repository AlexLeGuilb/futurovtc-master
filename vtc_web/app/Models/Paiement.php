<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paiement extends Model
{
    protected $table = "Paiement";
    protected $primaryKey = "idPaiement";
    protected $guarded = [];

    public $timestamps = false;
}
