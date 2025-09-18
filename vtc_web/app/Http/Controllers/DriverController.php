<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use \App\Models\Transaction;
use App\Models\Vehicule;
use App\Models\Affecte;
use App\Models\User;

class DriverController extends Controller
{
    public function verifAuth()
    {
        if(auth()->user()->typeRole == "CHF" || auth()->user()->typeRole == "ADM") {
            return true;
        } else {
            return false;
        }
    }

    public function listDriver()
    {
        if (!$this->verifAuth()) {
            return redirect()->route('accessDenied');
        }

        if (auth()->user()->typeRole == "ADM") {
            $affectes = Affecte::all();
        } else {
            $idChauffeur = auth()->user()->id;
            $affectes = Affecte::where('id',$idChauffeur)->get();
        }

        $trans = array();
        foreach ($affectes as $affecte){
            $trans[] = Transaction::where('idTransaction', $affecte->idTransaction)->first();
        }
        return view('driver', ['trans' => $trans]);
    }

    public function validateRun($idTrans)
    {
        if (!$this->verifAuth()) {
            return redirect()->route('accessDenied');
        }

        $transaction = Transaction::find($idTrans);
        $transaction->courseEffectuee = 1;
        $transaction->heureArrivee = date("Y-m-d H:i:s");
        $transaction->dateArrivee = date("Y-m-d");

        $affecte = Affecte::where('idTransaction',$transaction->idTransaction);
        $vehicule = Vehicule::find($affecte->first()->idVehicule);
        $vehicule->etatVh = 'Fonction';

        $gps1 = explode(', ', $transaction->gpsDepart);
        $gps2 = explode(', ', $transaction->gpsArrive);

        $lat1 = $gps1[0];
        $lng1 = $gps1[1];
        $lat2 = $gps2[0];
        $lng2 = $gps2[1];

        $vehicule->kmVh += ceil(HRController::distance($lat1, $lng1, $lat2, $lng2));

        $vehicule->save();

        $affecte->delete();

        $transaction->save();
        return redirect()->route('listCourse');
    }

    public function updateActivity()
    {
        if (!$this->verifAuth()) {
            return redirect()->route('accessDenied');
        }

        $activity = $_GET['switch'];
        $idDriver = $_GET['idDriver'];

        if ($activity == 'true') {
            $activity = 1;
        } else {
            $activity = 0;
        }

        $driver = User::find($idDriver);
        $driver->actif = $activity;
        $driver->save();
    }
}
