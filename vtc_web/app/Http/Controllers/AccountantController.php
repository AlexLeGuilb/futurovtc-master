<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Client;
use PDF;

class AccountantController extends Controller
{
    public function verifAuth()
    {
        if(auth()->user()->typeRole == "CPT" || auth()->user()->typeRole == "ADM") {
            return true;
        } else {
            return false;
        }
    }

    public function getTransactions(){

        if($this->verifAuth()) {
            $transactions = Transaction::where('courseEffectuee', 1)->get();
            return view('vueAccountantTransac', ['transactions' => $transactions]);
        } else {
            return redirect()->route('accessDenied');
        }


    }

    public function exportTransacToPDF($idTransaction)
    {
        if (!$this->verifAuth()) {
            return redirect()->route('accessDenied');
        }

        $transaction = Transaction::find($idTransaction);
        $client = Client::find($transaction->idClient);

        $TVA = 20;
        $hours = 0;
        $kilometers = 0;
        $dataArray []= HRController::calculateHoursAndKm($transaction, $hours, $kilometers);

               $hours += $dataArray[0][0];
               $kilometers += $dataArray[0][1];
        $data = array(
            'N_transaction' => $transaction->numTransaction,
            'Date_transaction' => $transaction->dateTransaction,
            'Id_client' => $transaction->idClient,
            'Nom' => $client->nomClient,
            'Prenom' => $client->prenomClient,
            'Gps_depart' => $transaction->gpsDepart,
            'Gps_arrivée' => $transaction->gpsArrive,
            'Heure_depart' => $transaction->heureDepart,
            'Heure_arrivee' => $transaction->heureArrivee,
            'Date_depart' => $transaction->dateDepart,
            'Nb_passager' => $transaction->nbPassager,
            'Id_paiement' => $transaction->idPaiement,
            'duree' => $hours,
            'distance' => $kilometers,
            'prixHT' => $transaction->nbPassager * $hours + $kilometers,
            'prixTTC' => ($transaction->nbPassager * $hours + $kilometers) * 1.20,
            'TVA' => $TVA,
            'date' => date('Y-m-d', time()),
        );

        return PDF::loadView('pdf\pdfViewAccountant', $data)->download();
    }
}
