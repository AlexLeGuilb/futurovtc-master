<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Vehicule;
use App\Models\Permis;
use App\Models\Affecte;
use App\Models\Transaction;
use Illuminate\Http\Request;

class MechanicController extends Controller
{
    public function verifAuth()
    {
        if(auth()->user()->typeRole == "GAR" || auth()->user()->typeRole == "ADM") {
            return true;
        } else {
            return false;
        }
    }

    public function getCars(){
        if (!$this->verifAuth()) {
            return redirect()->route('accessDenied');
        }

        $cars = Vehicule::all();
        return view('vueMechanicList', ['cars' => $cars]);
    }

    public function addCar(){
        if (!$this->verifAuth()) {
            return redirect()->route('accessDenied');
        }

        $permis = Permis::all();
      return view('vueMechanicAddCar', ['permis' => $permis]);
    }

    public function storeCar(){
        if (!$this->verifAuth()) {
            return redirect()->route('accessDenied');
        }

        request()->validate([
            'idVehicule'=>['required', 'unique:\App\Models\Vehicule,idVehicule,except,id'],
            'marque'=>['required'],
            'model'=>['required'],
            'couleur'=>['required'],
            'nbPlace'=>['required', 'min:0', 'max:62'],
            'etatVh'=>['required'],
            'kmVh'=>['required', 'min:0', 'max:999999'],
            'kmConstucteurRevision'=>['required', 'min:0', 'max:100000'],
            'kmMomentRevision'=>['required', 'min:0', 'max:100000'],
            'typePermis'=>['required']
        ]);

        $currrentCar = new Vehicule();
        $currrentCar->idVehicule = request('idVehicule');
        $currrentCar->marque = request('marque');
        $currrentCar->model = request('model');
        $currrentCar->couleur = request('couleur');
        $currrentCar->nbPlace = request('nbPlace');
        $currrentCar->etatVh = request('etatVh');
        $currrentCar->kmVh = request('kmVh');
        $currrentCar->kmConstucteurRevision = request('kmConstucteurRevision');
        $currrentCar->kmMomentRevision = request('kmMomentRevision');
        $currrentCar->typePermis = request('typePermis');
        $currrentCar->save();
        return redirect()->route('listCar');
    }


    public function editCar($idCar){
        if (!$this->verifAuth()) {
            return redirect()->route('accessDenied');
        }

        //return 'formulaire voiture '. $idCar.' a editer';
        $car = Vehicule::find($idCar);
        return view('vueEditCar', ['car' => $car]);
    }

    public function storeUpdateCar($idCar){
        if (!$this->verifAuth()) {
            return redirect()->route('accessDenied');
        }

        $currrentCar = Vehicule::find($idCar);
        $currrentCar->etatVh = request('etatVh');
        $currrentCar->kmVh = request('kmVh');
        $currrentCar->kmMomentRevision = request('kmMomentRevision');
        $currrentCar->update();
        return redirect()->route('listCar');
    }

    public function deleteCar($idCar){
        if (!$this->verifAuth()) {
            return redirect()->route('accessDenied');
        }
        $affects = Affecte::where('idVehicule', $idCar)->get();
        if($affects != null) {
            $vh = Vehicule::where('idVehicule', $idCar)->first();
            if ($vh->etatVh == 'indispo') {
                $vhs = Vehicule::where('nbPlace', '>=', $vh->nbPlace)->get();
                $newVh = $vhs[rand(0, count($vhs)-1)];
            }
            foreach ($affects as $aff) {
                $tra = Transaction::where('idTransaction', $aff->idTransaction)->first();
                if ($tra->courseEffectuee == 0) {
                    $aff->update([
                        'idVehicule' => $newVh->idVehicule
                    ]);
                    Vehicule::where('idVehicule', $newVh->idVehicule)->update([
                        'etatVh' => 'indispo',
                    ]);
                }
            }
            $vh->update([
                'etatVh' => 'HS',
            ]);
        } else {
            Vehicule::where('idVehicule', $idCar)->update([
                'etatVh' => 'HS',
            ]);
        }
        return redirect()->route('listCar');
    }
}
