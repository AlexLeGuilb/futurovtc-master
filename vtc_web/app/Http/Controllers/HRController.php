<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Affecte;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use \App\Models\User;
use App\Models\Possede;
use App\Models\Role;
use App\Models\Transaction;
use Illuminate\Support\Facades\Hash;
use PDF;

class HRController extends Controller
{
    public function verifAuth()
    {
        if(auth()->user()->typeRole == "RH" || auth()->user()->typeRole == "ADM") {
            return true;
        } else {
            return false;
        }
    }

    public function initHRView()
    {
        if (!$this->verifAuth()) {
            return redirect()->route('accessDenied');
        }
        $employees = User::all();
        $roles = Role::all();
        return view('vueHR', ['employees' => $employees, 'roles' => $roles]);
    }

    public function initFormEmploye($idEmploye)
    {
        if (!$this->verifAuth()) {
            return redirect()->route('accessDenied');
        }

        $employe = User::find($idEmploye);
        $roles = Role::all();
        $driverInfos = $this->getDriversInfo($idEmploye);

        if ($employe != null) {
            return view('formEmploye', ['employe' => $employe, 'roles' => $roles, 'driverInfos' => $driverInfos]);
        } else {
            abort(403, 'Id employe incorrect');
        }
    }

    public function initCreateFormEmploye()
    {
        if (!$this->verifAuth()) {
            return redirect()->route('accessDenied');
        }

        return view('formEmploye', ['roles' => Role::all()]);
    }

    public function deleteEmploye($idEmploye)
    {
        if (!$this->verifAuth()) {
            return redirect()->route('accessDenied');
        }

        $employe = User::find($idEmploye);
        $poss = Possede::where('id', $idEmploye)->get();
        foreach ($poss as $po) {
            $po->delete();
        }
        if ($employe != null) {
            $employe->delete();
            return redirect()->route('indexHR');
        } else {
            abort(403, 'Id employe incorrect');
        }
    }

    public function updateEmploye()
    {
        if (!$this->verifAuth()) {
            return redirect()->route('accessDenied');
        }

        $employe = User::find(request()->idEmploye);

        if ($employe == null) {
            $employe = new User();
            $employe->email = $this->generateUniqueMail(request()->name, request()->prename);
        }

        request()->validate([
            'name' => ['required', 'regex:/^[a-z A-Z]*\-?[a-zA-Z]*$$/'],
            'prename' => ['required', 'regex:/^[a-z A-Z]*\-?[a-zA-Z]*$/'],
            'matricule' => ['required', 'regex:/^([A-Z0-9]{10})$/'],
        ]);
        $employe->name = request()->name;
        $employe->prename = request()->prename;
        $employe->matricule = request()->matricule;
        $employe->typeRole = request()->role;
        $employe->password = Hash::make('caribou');

        $employe->save();

        if ($employe->typeRole == "CHF") {
            $permis = array(
                'A' => request()->A,
                'A1' => request()->A1,
                'A2' => request()->A2,
                'AM' => request()->AM,
                'B' => request()->B,
                'B1' => request()->B1,
                'BE' => request()->BE,
                'C' => request()->C,
                'C1' => request()->C1,
                'CE1' => request()->CE1,
                'CE' => request()->CE,
                'D' => request()->D,
                'D1' => request()->D1,
                'DE' => request()->DE,
                'DE1' => request()->DE1
            );

            foreach ($permis as $keyPermi => $permi) {
                $dbPermi = DB::table('Possede')
                ->where("id",  $employe->id)
                ->where("typePermis", $keyPermi);
                if ($dbPermi->first() != null && $permi == null) {
                    $dbPermi->delete();
                } else if ($dbPermi->first() == null && $permi != null) {

                    DB::insert('insert into Possede (typePermis, id) values (?, ?)', [$keyPermi,  $employe->id]);
                }
            }
        }
        return redirect()->route('indexHR');
    }

    public function initFormPayslip()
    {
        $employe = User::find(request()->idEmploye);

        if ($employe == null) {
            abort(403, 'Id employe incorrect');
        }

        $role = Role::where('typeRole', $employe['typeRole'])->first();
        $driverInfos = $this->getDriversInfo(request()->idEmploye);

        return view('payslip', ['employe' => $employe, 'role' => $role, 'driverInfos' => $driverInfos]);
    }

    public function generatePDF()
    {
        $data = array(
            'nom' => request()->nomEmploye,
            'prenom' => request()->prenomEmploye,
            'matricule' => request()->matricule,
            'typeRole' => request()->role,
            'hours' => request()->hours,
            'euro' => request()->euro,
            'total' => ((int)request()->hours * (int)request()->euro),
            'date' => date('Y-m-d', time()),
        );

        return PDF::loadView('pdf\pdfView', $data)->download();
    }

    private function getDriversInfo($idEmploye)
    {
        $transactions = Affecte::where('id', $idEmploye)->get();

        $hours = 0;
        $kilometers = 0;

        if ($transactions->first() != null) {
            foreach ($transactions as $transaction) {
               $dataArray []= HRController::calculateHoursAndKm($transaction, $hours, $kilometers);
               $hours += $dataArray[0][0];
               $kilometers += $dataArray[0][1];
            }
        }
        return array(
            'hours' => $hours,
            'kilometers' => $kilometers,
        );
    }

    public static function calculateHoursAndKm($transaction, $hours, $kilometers){
        $dbTrans = Transaction::where('idTransaction', $transaction->idTransaction)->first();

        if ($dbTrans != null) {
            // $interval = date_diff(date_create($dbTrans->heureDepart), date_create($dbTrans->heureArrivee));
            $date1 = (string)$dbTrans->dateDepart . " " . (string)$dbTrans->heureDepart;
            $date2 = (string)$dbTrans->dateArrivee . " " . (string)$dbTrans->heureArrivee;
            $timestamp1 = strtotime($date1);
            $timestamp2 = strtotime($date2);

            // dd($hours);
            $hours += abs($timestamp2 - $timestamp1)/(60*60);

            $gps1 = explode(', ', $dbTrans->gpsDepart);
            $gps2 = explode(', ', $dbTrans->gpsArrive);

            $lat1 = $gps1[0];
            $lng1 = $gps1[1];
            $lat2 = $gps2[0];
            $lng2 = $gps2[1];

            $kilometers += ceil(HRController::distance($lat1, $lng1, $lat2, $lng2));
        }

        return array(
            ceil($hours),
            $kilometers,
        );
    }

    /**
    * Titre : Calcul la distance entre 2 points en km
    * URL   : https://phpsources.net/code_s.php?id=1091
    * Auteur           : sheppy1
    * Website auteur   : https://lejournalabrasif.fr/qwanturank-concours-seo-qwant/
    * Date édition     : 05 Aout 2019
    * Date mise à jour : 16 Aout 2019
    * Rapport de la maj:
    * - fonctionnement du code vérifié
    */
    public static function distance($lat1, $lng1, $lat2, $lng2, $miles = false)
    {
        $pi80 = M_PI / 180;
        $lat1 *= $pi80;
        $lng1 *= $pi80;
        $lat2 *= $pi80;
        $lng2 *= $pi80;

        $r = 6372.797; // rayon moyen de la Terre en km
        $dlat = $lat2 - $lat1;
        $dlng = $lng2 - $lng1;
        $a = sin($dlat / 2) * sin($dlat / 2) + cos($lat1) * cos($lat2) * sin($dlng / 2) * sin($dlng / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $km = $r * $c;

        return ($miles ? ($km * 0.621371192) : $km);
    }

    public function generateUniqueMail($name, $prename)
    {
        $name = strtolower(str_replace('ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ', 'AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy', $name));
        $prename = strtolower(str_replace('ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ', 'AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy', $prename));

        $mail = $name . '.' . $prename .'@futurovtc.fr';

        $users = User::where('email', $mail)->get();

        if (count($users) === 0) {
            return $mail;
        } else {
            return $name . '.' . $prename . '-' . (string)count($users) .'@futurovtc.fr';
        }
    }
}
