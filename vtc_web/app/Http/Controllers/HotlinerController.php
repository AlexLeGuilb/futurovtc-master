<?php

namespace App\Http\Controllers;

use App\Models\Affecte;
use App\Models\Possede;
use App\Models\Vehicule;
use App\Models\Client;
use App\Models\Paiement;
use App\Models\Transaction;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class hotlinerController extends Controller
{
    public function verifAuth()
    {
        if(auth()->user()->typeRole == "HTL" || auth()->user()->typeRole == "ADM") {
            return true;
        } else {
            return false;
        }
    }

    public function homeHotliner()
    {
        if($this->verifAuth()) {
            $transactions = Transaction::where('courseEffectuee', 0)->get();
            return view('homeHotliner', ['trans' => $transactions]);
        } else {
            return redirect()->route('accessDenied');
        }

    }

    public function createCourse() {
        if (!$this->verifAuth()) {
            return redirect()->route('accessDenied');
        }
        return view('vueHotliner');
    }

    public function courseEdit(){
        if (!$this->verifAuth()) {
            return redirect()->route('accessDenied');
        }

        $id = request()->id;
        $trans = Transaction::where('idTransaction', $id)->first();
        $client = Client::where('idClient', $trans->idClient)->first();
        return view('editCourse', ['trans'=>$trans,'idClient'=>$client]);
    }

    public function saveEdit(){
        if (!$this->verifAuth()) {
            return redirect()->route('accessDenied');
        }

        $id = request()->idTransaction;
        $transaction = Transaction::find($id);

        if(request()->nbPers != $transaction->nbPassager){
            $transaction->nbPassager = request()->nbPers;
            if (!$this->assignateEdit($transaction)) {

                $error = "Pas de chauffeur ou de voiture disponible";
                return redirect('hotliner')->with('error',$error);
            }
        }

        Transaction::where('idTransaction', $id)->update([
            'gpsDepart' => request()->startLocation,
            'gpsArrive' => request()->endLocation,
            'heureDepart' => request()->hour,
            'dateDepart' => request()->date,
            'nbPassager' => request()->nbPers,
        ]);
        $idClient = request()->idClient;
        Client::where('idClient', $idClient)->update([
            'nomClient' => request()->clientName,
            'prenomClient' => request()->firstName,
            'tel' => request()->phoneNumber,
        ]);

        $error = "Course modifiée avec succés";
        return redirect('hotliner')->with('error',$error);
    }

    public function delCourse(){
        if (!$this->verifAuth()) {
            return redirect()->route('accessDenied');
        }

        $id = request()->id;
        $trans = Transaction::where('idTransaction', $id)->first();
        $affecte = Affecte::where('idTransaction',$trans->idTransaction);
        $idVehicule = $affecte->first()->idVehicule;
        Vehicule::where('idVehicule',$idVehicule)->update([
            'etatVh'=>'Fonction',
        ]);

        $affecte->delete();
        $trans->delete();


        return redirect()->route('hotliner');
    }

    public function remboursement($id){
        if (!$this->verifAuth()) {
            return redirect()->route('accessDenied');
        }

        $trans = Transaction::where('idTransaction', $id)->first();
        $affecte = Affecte::where('idTransaction',$trans->idTransaction);
        $idVehicule = $affecte->first()->idVehicule;
        Vehicule::where('idVehicule',$idVehicule)->update([
            'etatVh'=>'Fonction',
        ]);

        $affecte->delete();
        $trans->delete();
        $error = "Demande de remboursement effectué";

        return redirect('hotliner')->with('error',$error);;
    }

    public function clientPaiement()
    {
        if (!$this->verifAuth()) {
            return redirect()->route('accessDenied');
        }

        request()->validate([
            'firstName' => ['required', 'regex:/^([A-Z]{1})([a-z .\'-]{1,49})$/'],
            'clientName' => ['required', 'regex:/^([A-Z]{1})([a-z .\'-]{1,49})$/'],
            'phoneNumber' => ['required', 'regex:/^(\+)?([0-9]{10,14})$/'],
            'date' => ['required'],
            'hour' => ['required'],
            'nbPers' => ['required', 'numeric'],
            'startLocation' => ['required', 'regex:/^([4][2-8]|[5][0-1])(\.)([0-9]{6})(, )((\+)?[0-7]|(-)[0-4])(\.[0-9]{6})$/'],
            'endLocation' => ['required', 'regex:/^([4][2-8]|[5][0-1])(\.)([0-9]{6})(, )((\+)?[0-7]|(-)[0-4])(\.[0-9]{6})$/']
        ]);

        $info = [
            'firstName' => request()->firstName,
            'clientName' => request()->clientName,
            'phoneNumber' => request()->phoneNumber,
            'date' => request()->date,
            'hour' => request()->hour,
            'nbPers' => request()->nbPers,
            'startLocation' => request()->startLocation,
            'endLocation' => request()->endLocation
        ];
        session()->put('info', $info);
        return view('vuePaiement');
    }

    public function formValidation()
    {
        if (!$this->verifAuth()) {
            return redirect()->route('accessDenied');
        }

        $info = session()->get('info');

        request()->validate([
            'cardNumber' => ['required', 'regex:/^([0-9]{16})$/'],
            'expirationDate' => ['required'],
            'owner' => ['required', 'regex:/^([A-Z]{1})([a-z .\'-]{1,49})( {1})([A-z .\'-]{1,49})$/'],
            'cvv' => ['required', 'regex:/^([0-9]{3})$/']
        ]);

        $paiement = [
            'cardNumber' => request()->cardNumber,
            'expirationDate' => request()->expirationDate,
            'owner' => request()->owner,
            'cvv' => request()->cvv
        ];


        $phraseOui = "Paiement validé";
        $phraseNon = "Paiement refusé";

        if ($this->savingCourse(array_merge($info, $paiement))) {
            return redirect()->route('hotliner');
        } else {
            return redirect()->route('hotliner');
        }
    }

    public function savingCourse($array)
    {
        if (!$this->verifAuth()) {
            return redirect()->route('accessDenied');
        }

        $idCLient = null;
        $idPaiement = null;

        if ($this->testPaiement($array["cardNumber"], $array["expirationDate"], $array["owner"], $array["cvv"])) {

            $client = Client::where('prenomClient', $array["firstName"])->where('nomClient', $array["clientName"])->where('tel', $array["phoneNumber"])->first();

            if ($client != null) {

                $idClient = $client->idClient;
            } else {

                $idClient = Client::insertGetId([
                    "prenomClient" => $array["firstName"],
                    "nomClient" => $array["clientName"],
                    "tel" => $array["phoneNumber"]

                ]);
            }
            $idPaiement = Paiement::insertGetId([
                "numCB" => $array["cardNumber"],
                "dateExepiCB" => $array["expirationDate"],
                "titulaire" => $array["owner"],
                "CVV" => $array["cvv"]
            ]);

            $num = chr(rand(65, 90)) . chr(rand(65, 90)) . chr(rand(65, 90))
                . rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9)
                . chr(rand(65, 90)) . chr(rand(65, 90))
                . rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9)
                . chr(rand(65, 90)) . chr(rand(65, 90)) . chr(rand(65, 90))
                . rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9);

            Transaction::create([
                'numTransaction' => $num,
                'gpsDepart' => $array["startLocation"],
                'dateTransaction' => date('Y-m-d', time()),
                'gpsArrive' => $array["endLocation"],
                'heureDepart' => $array["hour"],
                'dateDepart' => $array["date"],
                'heureArrivee' => null,
                'dateArrivee' => null,
                'courseEffectuee' => 0,
                'nbPassager' => $array["nbPers"],
                'idClient' => $idClient,
                'idPaiement' => $idPaiement,
            ]);

            $this->assignate($num);
        } else {
            return false;
        }
    }

    public function testPaiement($cardNumber, $expirationDate, $owner, $cvv)
    {
        if (!$this->verifAuth()) {
            return redirect()->route('accessDenied');
        }
        return true;
    }

    public function assignate($numTransa){
        $trans = Transaction::where('numTransaction',$numTransa)->first();
        $idTrans = $trans->idTransaction;
        $nbPlace = $trans->nbPassager;


        $vehicule = Vehicule::where('nbPlace' ,'>=', $nbPlace)->where('etatVh', 'Fonction')->orderBy('nbPlace','asc')->first();
        if( $vehicule == null ){
            $error = "Pas de voiture disponible";
            $trans->delete();

            return redirect('horliner')->with('error',$error);
        }


        $permis = $vehicule->typePermis;
        $drivers = Possede::where('typePermis', 'LIKE', $permis.'%')->get();
        $idDriver = null;
        foreach ($drivers as $driver) {
            $valid = true;

            $user = User::find($driver->id);
            if ($user->actif) {
                $affectes = Affecte::where('id',$driver->id)->get();
                if($affectes != null){
                    foreach ($affectes as $affecte) {
                        $transaction = Transaction::where('idTransaction',$affecte->idTransaction)->where('courseEffectuee','0')->get();
                        if ($transaction != null) {
                            $valid = false;
                            break;
                        }
                    }
                }
            } else {
                $valid = false;
            }

            if($valid) {
                $idDriver = $driver->id;
                break;
            }

        }


        $idVoiture = $vehicule->idVehicule;

        if( $idDriver != null ){
            $affecte = new Affecte();
            $affecte->id = $idDriver;
            $affecte->idVehicule = $idVoiture;
            $affecte->idTransaction = $idTrans;
            $affecte->save();

            Vehicule::where('idVehicule' , $vehicule->idVehicule)->update([
                'etatVh'=>'Indispo',
            ]);
            $valide = "Course créée avec succés";
            return redirect('hotliner')->with('error',$valide);
        }else{
            $error = "Il n'y a pas de chauffeur disponible";

            $trans->delete();
            return redirect('hotliner')->with('error',$error);
        }

    }

    public function assignateEdit($trans){

        $idTrans = $trans->idTransaction;
        $nbPlace = $trans->nbPassager;

        //dd($nbPlace);
        $vehicule = Vehicule::where('nbPlace' ,'>', $nbPlace)->where('etatVh', 'Fonction')->orderBy('nbPlace','asc')->first();
        //dd($vehicule);
        if ($vehicule == null ){
            return false;
        }


        $permis = $vehicule->typePermis;

        $idChauffeurAvantEdit = Affecte::where('idTransaction',$idTrans)->first();
        $idChauffeurAvantEdit = $idChauffeurAvantEdit->id;
        $permisPossede = Possede::where('id',$idChauffeurAvantEdit)->where('typePermis',$permis)->get();
        $voitureAvantChangement = Affecte::where('idTransaction',$idTrans)->first();
        $voitureAvantChangement = $voitureAvantChangement->idVehicule;

        if($permisPossede != null){
            if($vehicule != $voitureAvantChangement){
                Vehicule::where('idVehicule' , $voitureAvantChangement)->update([
                    'etatVh'=>'Fonction',
                ]);
            }
            Affecte::where('idTransaction',$idTrans)->update([
                'idVehicule' => $vehicule->idVehicule,
            ]);
           Vehicule::where('idVehicule' , $vehicule->idVehicule)->update([
                'etatVh'=>'Indispo',
           ]);
            return true;
        }else {
            if($vehicule != $voitureAvantChangement){
                Vehicule::where('idVehicule' , $vehicule->idVehicule)->update([
                    'etatVh'=>'Fonction',
                ]);
            }
            $drivers = Possede::where('typePermis', $permis)->get();
            $idDriver = null;
            foreach ($drivers as $driver) {
                $valid = true;

                $user = User::find($driver->id);
                if ($user->actif) {
                    $affectes = Affecte::where('id',$driver->id)->get();
                    if($affectes != null){
                        foreach ($affectes as $affecte) {
                            $transaction = Transaction::where('idTransaction',$affecte->idTransaction)->where('courseEffectuee','0')->get();
                            if ($transaction != null) {
                                $valid = false;
                                break;
                            }
                        }
                    }
                } else {
                    $valid = false;
                }

                if ($valid) {
                    $idDriver = $driver->id;
                    break;
                }

            }


            $idVoiture = $vehicule->idVehicule;

            if ($idDriver != null) {
                $affecte = new Affecte();
                $affecte->id = $idDriver;
                $affecte->idVehicule = $idVoiture;
                $affecte->idTransaction = $idTrans;
                $affecte->save();

                Vehicule::where('idVehicule' , $vehicule->idVehicule)->update([
                    'etatVh'=>'Indispo',
                ]);
                return true;
            } else {
                return false;
            }
        }

    }
}
