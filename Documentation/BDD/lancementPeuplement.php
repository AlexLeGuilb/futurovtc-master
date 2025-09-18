<?php

$peup = new Peuplement();

class Peuplement
{
    private $conn;
    function __construct()
    {
        try {
            $this->conn = new PDO("mysql:host=localhost;dbname=vtc_web", "vtc_user", "caribou");
            // set the PDO error mode to exception
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            echo "Connected successfully";
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }

        //$this->conn->exec("SET CHARACTER SET utf8");
        $this->lancement();
    }

    public function lancement()
    {
        $nbClient = $this->client();
        $nbPai = $this->paiement();
        $this->permis();
        $nbVeh = $this->vehicules();
        $this->role();
        $nbEmp = $this->employes();
        $this->transaction($nbClient, $nbPai);
        $this->possede($nbEmp);
        $this->affecte($nbVeh, $nbEmp);
    }

    public function affecte($nbV, $nbE)
    {
        $test = true;
        $error = "";
        $tr = $this->randTra();

        for ($i=0; $i < count($tr); $i++) { 
            $t = $tr[$i];

            $vh = $this->randVehi($t['nbPassager']);
            $v = $vh[rand(0, max(count($vh)-1, 0))]; //2025 éviter les -1

            $em = $this->randEmp($v['typePermis']);
            $e = $em[rand(0, max(count($em)-1,0))]; //2025 éviter les -1

            $req = "INSERT INTO affecte VALUES (".$e['id'].", ".$t['idTransaction'].", '".$v['idVehicule']."'); UPDATE vehicule SET etatVh='indispo' WHERE idVehicule='".$v['idVehicule']."';";
            try {
                $this->conn->exec($req);
            } catch (PDOException $e) {
                $test = false;
                $error .= $e->getMessage();
            }
        }
        if ($test == true) {
            echo "<br>Table : affecte, success";
        } else {
            echo "<br>Table : affecte, error : " . $error;
        }
    }

    public function randTra()
    {
        $req = "SELECT idTransaction, nbPassager FROM transaction WHERE dateDepart>='".date('y-m-d', time())."' AND courseEffectuee=0";
        $sth = $this->conn->query($req);
        $res = $sth->fetchAll();
        return $res;
    }

    public function randVehi($nb)
    {
        $req = "SELECT idVehicule, typePermis FROM vehicule WHERE nbPlace >= ".$nb." ORDER BY nbPlace DESC";
        $sth = $this->conn->query($req);
        $res = $sth->fetchAll();
        return $res;
    }

    public function randEmp($p)
    {
        $req = "SELECT u.id FROM users u, possede p WHERE u.typeRole='CHF' AND u.id = p.id AND p.typePermis LIKE '$p%'";
        $sth = $this->conn->query($req);
        $res = $sth->fetchAll();
        return $res;
    }

    public function randEmpCHF()
    {
        $req = "SELECT * FROM users u WHERE u.typeRole='CHF'";
        $sth = $this->conn->query($req);
        $res = $sth->fetchAll();
        return $res;
    }

    public function possede($nbEmp)
    {
        $test = true;
        $error ="";
        $perm = $this->randPerm();
        $em = $this->randEmpCHF();
        $rn = rand(count($em), count($em)+10);
        for ($i=0; $i < $rn; $i++) {
            $nbE = $em[rand(0, count($em)-1)]['id'];
            $p = $perm[rand(0, count($perm)-1)]['typePermis'];

            $req = "INSERT INTO possede VALUES ('$p', $nbE), ('B', $nbE)";
            try {
                $this->conn->exec($req);
            } catch (PDOException $e) {
            }
        }
        if ($test == true) {
            echo "<br>Table : possede, success";
        } else {
            echo "<br>Table : possede, error : " . $error;
        }
    }

    public function randPerm()
    {
        $req = "SELECT * FROM permis";
        $sth = $this->conn->query($req);
        $res = $sth->fetchAll();
        return $res;
    }

    public function transaction($nbClient, $nbPai)
    {
        $test = true;
        $error = "";
        $compteur = 0;
        for($i=0; $i< rand(20, 50); $i++) {

            if (rand(0, 100) >= 25) {
                $nbPl = rand(1,4);
            } else {
                $nbPl = rand(4,55);
            }
            if (rand(0,10)<=2) {
                $numTra = $this->randomNumTransaction();
                $gpsArr = $this->randomGPS();
                $gpsDep = $this->randomGPS();
                $heureDep = $this->randomHeure();
                $dateDep = $this->randomDate();
                $req = "INSERT INTO transaction VALUES (null, '$numTra', DATE(NOW()), '$gpsDep', '$gpsArr', '$dateDep', '$heureDep', null, null , false, $nbPl , ".rand(1,$nbClient).", ".rand(1,$nbPai).")";
            } else {
                $numTra = $this->randomNumTransaction();
                $gpsArr = $this->randomGPS();
                $gpsDep = $this->randomGPS();

                $arr = $this->randomArriverArr();
                $req = "INSERT INTO transaction VALUES (null, '$numTra', DATE(NOW()), '$gpsDep', '$gpsArr', '$arr[0]', '$arr[1]', '$arr[2]', '$arr[3]', true, $nbPl , ".rand(1,$nbClient).", ".rand(1,$nbPai).")";
            }
            try {
                $this->conn->exec($req);
            } catch (PDOException $e) {
                $test = false;
                $error .= $e->getMessage();
            }
        }
        if ($test == true) {
            echo "<br>Table : transactions, success";
        } else {
            echo "<br>Table : transactions, error : " . $error;
        }
    }

    public function randomArriverArr()
    {
        $h = rand(0,23);
        $m = rand(0,59);
        $heureDep = date('h:i:s', mktime($h,$m,0,0,0,0));
        $heureArr = date('h:i:s', strtotime($heureDep) + (rand(1, 20) * 60));
        
        if ($heureDep > $heureArr) {
            $dateDep = date('y-m-d');
            $dep = strtotime($dateDep);
            $dateArr = date('y-m-d', $dep+86400);
        } else {
            $dateDep = date('y-m-d');
            $dateArr = date('y-m-d', strtotime($dateDep));
        }

        $arr = [$dateDep, $heureDep, $dateArr, $heureArr];
        return $arr;
    }

    public function randomDate()
    {
        $datestart = strtotime(date('y-m-d'));
        $dateend = strtotime(date('y-m-d'))+(rand(50,90)*86400);

        $daystep = 86400;

        $datebetween = abs(($dateend - $datestart) / $daystep);

        $randomday = rand(0, $datebetween);

        return date("Y-m-d", $datestart + ($randomday * $daystep));
    }

    public function randomHeure()
    {
        $h = strtotime("" . rand(0,23) . ":" . rand(0,59) . ":0");
        $heure = date('h:i:s', $h);
        return $heure;
    }

    public function randomGPS()
    {
        $gps = "4" . rand(3, 8) . "." . rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9) . ", ";
        
        $bool2 = rand(0, 1);
        if ($bool2 == 1) {
            $gps .= "-" . rand(0, 4) . "." . rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9);
        } else {
            $gps .= rand(0, 7) . "." . rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9);
        }

        return $gps;
    }

    public function randomNumTransaction()
    {
        $num = chr(rand(65, 90)) . chr(rand(65, 90)) . chr(rand(65, 90))
            . rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9)
            . chr(rand(65, 90)) . chr(rand(65, 90))
            . rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9)
            . chr(rand(65, 90)) . chr(rand(65, 90)) . chr(rand(65, 90))
            . rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9);
        return $num;
    }

    public function employes()
    {
        $test = true;
        $error = "";
        $arrRole = ['RH', 'CPT', 'GAR', 'HTL'];
        $arrMat = [];
        for ($i = 0; $i < 50; $i++) {
            $nom = $this->randomNom();
            $prenom = $this->randomPrenom();
            if ($i <= 30) {
                $role = "CHF";
            } elseif ($i < 48) {
                $role = $arrRole[rand(0, count($arrRole) - 1)];
            } else {
                $role = "ADM";
            }

            $mat = $this->randomMatricule();
            while (in_array($mat, $arrMat)) {
                $mat = $this->randomMatricule();
            }
            $nom = $this->randomNom();
            $prenom = $this->randomPrenom();

            $nomMail = mb_convert_case($nom, MB_CASE_LOWER_SIMPLE, "UTF-8");
            $prenomMail = mb_convert_case($prenom, MB_CASE_LOWER_SIMPLE, "UTF-8");

            $nomMail = $this->replacename($nomMail);
            $prenomMail = $this->replacename($prenomMail);

            $rand = rand(0, 20);
            $mdp = password_hash("caribou", PASSWORD_BCRYPT, ['size' => 10]);

            if($mdp != false) {
                $req = "INSERT INTO users VALUES (null, '$mat', '$nom', '$prenom', '$nomMail.$prenomMail-$rand@futurovtc.fr', '$mdp', null, null, null, null, '$role', 1)";
                try {
                    $this->conn->exec($req);
                } catch (PDOException $e) {
                    $test = false;
                    $error .= $e->getMessage();
                }
            }
        }
        $mdp = password_hash("admin", PASSWORD_BCRYPT, ['size' => 10]);
        $req = "INSERT INTO users VALUES (null, '0000000000', 'admin', 'admin', 'admin@futurovtc.fr', '$mdp', null, null, null, null, 'ADM', 1)";
        $this->conn->exec($req);
        if ($test == true) {
            echo "<br>Table : users (employés), success";
        } else {
            echo "<br>Table : users (employés), error : " . $error;
        }

        return $i;
    }

    public function replacename($str)
    {
        $ch0 = array( 
            "œ"=>"oe",
            "æ"=>"ae",
            "à" => "a",
            "á" => "a",
            "â" => "a",
            "à" => "a",
            "ä" => "a",
            "å" => "a",
            "&#257;" => "a",
            "&#259;" => "a",
            "&#462;" => "a",
            "&#7841;" => "a",
            "&#7843;" => "a",
            "&#7845;" => "a",
            "&#7847;" => "a",
            "&#7849;" => "a",
            "&#7851;" => "a",
            "&#7853;" => "a",
            "&#7855;" => "a",
            "&#7857;" => "a",
            "&#7859;" => "a",
            "&#7861;" => "a",
            "&#7863;" => "a",
            "&#507;" => "a",
            "&#261;" => "a",
            "ç" => "c",
            "&#263;" => "c",
            "&#265;" => "c",
            "&#267;" => "c",
            "&#269;" => "c",
            "&#271;" => "d",
            "&#273;" => "d",
            "è" => "e",
            "é" => "e",
            "ê" => "e",
            "ë" => "e",
            "&#275;" => "e",
            "&#277;" => "e",
            "&#279;" => "e",
            "&#281;" => "e",
            "&#283;" => "e",
            "&#7865;" => "e",
            "&#7867;" => "e",
            "&#7869;" => "e",
            "&#7871;" => "e",
            "&#7873;" => "e",
            "&#7875;" => "e",
            "&#7877;" => "e",
            "&#7879;" => "e",
            "&#285;" => "g",
            "&#287;" => "g",
            "&#289;" => "g",
            "&#291;" => "g",
            "&#293;" => "h",
            "&#295;" => "h",
            "&#309;" => "j",
            "&#311;" => "k",
            "&#314;" => "l",
            "&#316;" => "l",
            "&#318;" => "l",
            "&#320;" => "l",
            "&#322;" => "l",
            "ñ" => "n",
            "&#324;" => "n",
            "&#326;" => "n",
            "&#328;" => "n",
            "&#329;" => "n",
            "ò" => "o",
            "ó" => "o",
            "ô" => "o",
            "õ" => "o",
            "ö" => "o",
            "ø" => "o",
            "&#333;" => "o",
            "&#335;" => "o",
            "&#337;" => "o",
            "&#417;" => "o",
            "&#466;" => "o",
            "&#511;" => "o",
            "&#7885;" => "o",
            "&#7887;" => "o",
            "&#7889;" => "o",
            "&#7891;" => "o",
            "&#7893;" => "o",
            "&#7895;" => "o",
            "&#7897;" => "o",
            "&#7899;" => "o",
            "&#7901;" => "o",
            "&#7903;" => "o",
            "&#7905;" => "o",
            "&#7907;" => "o",
            "ð" => "o",
            "&#341;" => "r",
            "&#343;" => "r",
            "&#345;" => "r",
            "&#347;" => "s",
            "&#349;" => "s",
            "&#351;" => "s",
            "&#355;" => "t",
            "&#357;" => "t",
            "&#359;" => "t",
            "ù" => "u",
            "ú" => "u",
            "û" => "u",
            "ü" => "u",
            "&#361;" => "u",
            "&#363;" => "u",
            "&#365;" => "u",
            "&#367;" => "u",
            "&#369;" => "u",
            "&#371;" => "u",
            "&#432;" => "u",
            "&#468;" => "u",
            "&#470;" => "u",
            "&#472;" => "u",
            "&#474;" => "u",
            "&#476;" => "u",
            "&#7909;" => "u",
            "&#7911;" => "u",
            "&#7913;" => "u",
            "&#7915;" => "u",
            "&#7917;" => "u",
            "&#7919;" => "u",
            "&#7921;" => "u",
            "&#373;" => "w",
            "&#7809;" => "w",
            "&#7811;" => "w",
            "&#7813;" => "w",
            "ý" => "y",
            "ÿ" => "y",
            "&#375;" => "y",
            "&#7929;" => "y",
            "&#7925;" => "y",
            "&#7927;" => "y",
            "&#7923;" => "y",
            "&#377;" => "Z",
            "&#379;" => "Z",
            );
        $str = strtr($str,$ch0);
        $str = str_replace(' ', '-', $str);
        return $str;
    }

    public function randomMatricule()
    {
        $mat = chr(rand(65, 90)) . chr(rand(65, 90)) . chr(rand(65, 90)) . rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9);
        return $mat;
    }

    public function role()
    {
        $test = true;
        $error = "";
        $req = "INSERT INTO role VALUES
            ('RH', 'Ressources Humaines'),
            ('CPT', 'Comptable'),
            ('CHF', 'Chauffeur'),
            ('GAR', 'Garagiste'),
            ('HTL', 'Hotliner'),
            ('ADM', 'Administrateur')
        ";

        try {
            $this->conn->exec($req);
        } catch (PDOException $e) {
            $test = false;
            $error .= $e->getMessage();
        }
        if ($test == true) {
            echo "<br>Table : role, success";
        } else {
            echo "<br>Table : role, error : " . $error;
        }
    }

    public function vehicules()
    {
        $test = true;
        $error = "";
        for ($i = 0; $i < rand(20, 40); $i++) {
            $num = $this->randomNumVehicule();
            $veh = $this->randomMarque();

            $arrayCouleur = [
                'orange',
                'bleu',
                'gris',
                'blanc',
                'vert',
                'noir',
                'rouge',
                'marron',
                'jaune'
            ];
            $col = $arrayCouleur[rand(0, count($arrayCouleur) - 1)];

            $req = "INSERT INTO vehicule VALUES ('$num', '$veh[0]', '$veh[1]', '$col', $veh[2], 'Fonction', " . rand(500, 20000) . ", " . rand(500, 10000) . ", " . rand(500, 20000) . ", '$veh[3]')";
            try {
                $this->conn->exec($req);
            } catch (PDOException $e) {
                $test = false;
                $error .= $e->getMessage();
            }
        }
        if ($test == true) {
            echo "<br>Table : vehicules, success";
        } else {
            echo "<br>Table : vehicules, error : " . $error;
        }
    }

    public function permis()
    {
        $test = true;
        $error = "";
        $req = "INSERT INTO permis VALUES
            ('A', 'Toutes moto'),
            ('AM', 'Toutes moto'),
            ('A1', 'Moto légère'),
            ('A2', 'Moto intermédiaire'),
            ('B', 'Voiture'),
            ('B1', 'Voiture légère'),
            ('BE', 'Voiture + remorque'),
            ('C', 'PL > 7,5T'),
            ('C1', 'PL entre 3,5 et 7,5T'),
            ('CE', 'C + remorque'),
            ('CE1', 'C1 + remorque'),
            ('D', 'Véhicule >= 8 place'),
            ('D1', 'D, 16 place max -8m'),
            ('DE', 'D + remorque'),
            ('DE1', 'D1 + remorque')
        ";
        try {
            $this->conn->exec($req);
        } catch (PDOException $e) {
            $test = false;
            $error .= $e->getMessage();
        }
        if ($test == true) {
            echo "<br>Table : permis, success";
        } else {
            echo "<br>Table : permis, error : " . $error;
        }
    }

    public function paiement()
    {
        $test = true;
        $error = "";
        for ($i = 0; $i < rand(150, 400); $i++) {
            $numCB = $this->randomNumCB();
            $date = date("Y-m");
            $date .= "-01";
            $CVV = "" . rand(0, 10 - 1) . rand(0, 10 - 1) . rand(0, 10 - 1);
            $titulaire = $this->randomNom() . " " . $this->randomPrenom();

            $req = "INSERT INTO paiement VALUES(null, '$numCB', '$date', '$CVV', '$titulaire')";
            try {
                $this->conn->exec($req);
            } catch (PDOException $e) {
                $test = false;
                $error .= $e->getMessage();
            }
        }
        if ($test == true) {
            echo "<br>Table : paiement, success";
        } else {
            echo "<br>Table : paiemment, error : " . $error;
        }
        return $i;
    }

    public function client()
    {
        $test = true;
        $error = "";
        for ($i = 0; $i < rand(100, 300); $i++) {
            $nom = $this->randomNom();
            $prenom = $this->randomPrenom();
            $tel = $this->randomTel();

            $req = "INSERT INTO client VALUES(null, '$nom', '$prenom', '$tel')";
            try {
                $this->conn->exec($req);
            } catch (PDOException $e) {
                $test = false;
                $error .= $e->getMessage();
            }
        }
        if ($test == true) {
            echo "<br>Table : client, success";
        } else {
            echo "<br>Table : client, error : " . $error;
        }
        return $i;
    }

    public function randomPermis()
    {
        $array = [
            'A',
            'AM',
            'A1',
            'A2',
            'B',
            'B1',
            'BE',
            'C',
            'C1',
            'CE',
            'CE1',
            'D',
            'D1',
            'DE',
            'DE1',
        ];

        return $array[rand(0, count($array) - 1)];
    }

    public function randomNumCB()
    {
        $tel = "";
        for ($i = 0; $i < 16; $i++) {
            $tel .= rand(0, 10 - 1);
        }
        return $tel;
    }

    public function randomMarque()
    {
        $arrayMarque = [
            'Citroen',
            'Renault',
            'Mercedes',
        ];

        $rand = rand(0,2);
        $marque = $arrayMarque[$rand];
        $vehi = array();
        switch ($marque) {
            case 'Citroen':
                $arrayModel = [
                    'C3',
                    'C4',
                    'DS',
                ];
                $model = $arrayModel[rand(0, 2)];
                $nbPlace = 4;
                $perm = 'B';
                array_push($vehi, $marque, $model, $nbPlace, $perm);
                break;

            case 'Renault':
                $arrayModel = [
                    'Clio',
                    'Twingo',
                    'Laguna',
                ];
                $model = $arrayModel[rand(0, 2)];
                $nbPlace = 4;
                $perm = 'B';
                array_push($vehi, $marque, $model, $nbPlace, $perm);
                break;

            case 'Mercedes':
                $rn = rand(0, 2);
                $arrayModel = [
                    'Classe A',
                    'Classe B',
                    'Bus',
                ];
                $model = $arrayModel[$rn];
                if ($rn == 2) {
                    $nbPlace = 55;
                    $perm = 'D';
                } else if ($rn == 1) {
                    $nbPlace = 8;
                    $perm = 'B';
                } else  {
                    $nbPlace = 6;
                    $perm = 'B';
                }
                array_push($vehi, $marque, $model, $nbPlace, $perm);
                break;
        }
        return $vehi;
    }

    public function randomNumVehicule()
    {
        $numTemp = chr(rand(65, 90)) . chr(rand(65, 90)) . " " . rand(0, 9) . rand(0, 9) . rand(0, 9) . " " . chr(rand(65, 90)) . chr(rand(65, 90));
        return $numTemp;
    }

    public function randomTel()
    {
        $tel = "0";
        for ($i = 0; $i < 9; $i++) {
            if ($i == 0) {
                $tel .= rand(6, 7);
            } else {
                $tel .= rand(0, 10 - 1);
            }
        }
        return $tel;
    }

    public function randomPrenom()
    {

        $firstname = array(
            'Absolon',
            'Adèle',
            'Adrien',
            'Agnès',
            'Alain',
            'Alexandrie',
            'Alphonsine',
            'Ambre',
            'Amédée',
            'Anastasie',
            'Andrée',
            'Angelique',
            'Anselme',
            'Antoinette',
            'Apolline',
            'Aristide',
            'Armel',
            'Arnaude',
            'Auguste',
            'Aurèle',
            'Aurelien',
            'Axelle',
            'Barnabé',
            'Basile',
            'Béatrice',
            'Benjamine',
            'Benoite',
            'Berthe',
            'Brice',
            'Carole',
            'Céleste',
            'Céline',
            'Cesaire',
            'Charles',
            'Charlot',
            'Chloé',
            'Christian',
            'Christianne',
            'Christophe',
            'Claude',
            'Claudine',
            'Clément',
            'Clothilde',
            'Colombain',
            'Constant',
            'Corentin',
            'Corinne',
            'Cunégonde',
            'Damien',
            'Danièle',
            'Delphine',
            'Denise',
            'Désiré',
            'Diane',
            'Didier',
            'Dimitri',
            'Dion',
            'Donat',
            'Donatienne',
            'Dorothée',
            'Edgard',
            'Edmond',
            'Edwige',
            'Éliane',
            'Élise',
            'Eloi',
            'Emeline',
            'Émilien',
            'Emmanuel',
            'Eric',
            'Esmé',
            'Esther',
            'Eugène',
            'Eulalie',
            'Évariste',
            'Fabien',
            'Fabiola',
            'Faustine',
            'Felicien',
            'Félix',
            'Fernand',
            'Fiacre',
            'Firmin',
            'Florence',
            'Florette',
            'Florianne',
            'Françoise',
            'Frédérique',
            'Gabrielle',
            'Gaetane',
            'Gaston',
            'Georges',
            'Georgine',
            'Gérard',
            'Germain',
            'Gervais',
            'Ghislain',
            'Gigi',
            'Gilberte',
            'Gisèle',
            'Gratien',
            'Guillaume',
            'Guy',
            'Hannah',
            'Hélène',
            'Henri',
            'Herbert',
            'Hermine',
            'Hilaire',
            'Honoré',
            'Horace',
            'Humbert',
            'Ignace',
            'Iréné',
            'Irénée',
            'Isabelle',
            'Jacinthe',
            'Jacques',
            'Jean-marie',
            'Jeannette',
            'Jeannot',
            'Jérôme',
            'Joceline',
            'Josée',
            'Josèphe',
            'Josette',
            'Josue',
            'Judith',
            'Juliane',
            'Julien',
            'Juliette',
            'Justin',
            'Laurentine',
            'Lazare',
            'Léandre',
            'Léonard',
            'Léontine',
            'Liane',
            'Lisette',
            'Lothaire',
            'Louise',
            'Luce',
            'Lucien',
            'Lucile',
            'Lucinde',
            'Lunete',
            'Madeleine',
            'Manon',
            'Marcel',
            'Marcelle',
            'Marcellin',
            'Margot',
            'Marielle',
            'Marin',
            'Marise',
            'Marthe',
            'Martine',
            'Mathilde',
            'Matthieu',
            'Maxime',
            'Maximilienne',
            'Mélissa',
            'Michèle',
            'Michelle',
            'Mirabelle',
            'Modeste',
            'Monique',
            'Morgane',
            'Myriam',
            'Nadine',
            'Natalie',
            'Nazaire',
            'Nicodème',
            'Nicole',
            'Ninon',
            'Noel',
            'Noelle',
            'Océane',
            'Odile',
            'Olivie',
            'Olympe',
            'Oriane',
            'Osanne',
            'Ozanne',
            'Pascaline',
            'Patrice',
            'Paul',
            'Paulette',
            'Pénélope',
            'Perrine',
            'Philibert',
            'Philippine',
            'Pierrick',
            'Quentin',
            'Rainier',
            'Raphaël',
            'Raymond',
            'Rébecca',
            'Régis',
            'Rémi',
            'Renard',
            'René',
            'Reynaud',
            'Robert',
            'Rodolphe',
            'Roger',
            'Rolande',
            'Romaine',
            'Rose',
            'Rosemonde',
            'Roxanne',
            'Sacha',
            'Samuel',
            'Sarah',
            'Sébastienne',
            'Serge',
            'Sévérine',
            'Sidonie',
            'Sophie',
            'Stéphanie',
            'Suzette',
            'Sylvestre',
            'Sylvianne',
            'Tatienne',
            'Theirn',
            'Théodore',
            'Thierry',
            'Timothée',
            'Toussaint',
            'Ulrich',
            'Valentin',
            'Valère',
            'Valéry',
            'Victor',
            'Violette',
            'Vivien',
            'Xavier',
            'Yann',
            'Yannick',
            'Yseult',
            'Yvette',
            'Zacharie',
            'Zoé',
        );

        return $firstname[rand(0, count($firstname) - 1)];
    }

    public function randomNom()
    {
        $lastname = array(
            'Martin',
            'Bernard',
            'Thomas',
            'Petit',
            'Robert',
            'Richard',
            'Durand',
            'Dubois',
            'Moreau',
            'Laurent',
            'Simon',
            'Michel',
            'Lefèvre',
            'Leroy',
            'Roux',
            'David',
            'Bertrand',
            'Morel',
            'Fournier',
            'Girard',
            'Bonnet',
            'Dupont',
            'Lambert',
            'Fontaine',
            'Rousseau',
            'Vincent',
            'Muller',
            'Lefevre',
            'Faure',
            'Andre',
            'Mercier',
            'Blanc',
            'Guerin',
            'Boyer',
            'Garnier',
            'Chevalier',
            'Francois',
            'Legrand',
            'Gauthier',
            'Garcia',
            'Perrin',
            'Robin',
            'Clement',
            'Morin',
            'Nicolas',
            'Henry',
            'Roussel',
            'Mathieu',
            'Gautier',
            'Masson',
            'Marchand',
            'Duval',
            'Denis',
            'Dumont',
            'Marie',
            'Lemaire',
            'Noel',
            'Meyer',
            'Dufour',
            'Meunier',
            'Brun',
            'Blanchard',
            'Giraud',
            'Joly',
            'Riviere',
            'Lucas',
            'Brunet',
            'Gaillard',
            'Barbier',
            'Arnaud',
            'Martinez',
            'Gerard',
            'Roche',
            'Renard',
            'Schmitt',
            'Roy',
            'Leroux',
            'Colin',
            'Vidal',
            'Caron',
            'Picard',
            'Roger',
            'Fabre',
            'Aubert',
            'Lemoine',
            'Renaud',
            'Dumas',
            'Lacroix',
            'Olivier',
            'Philippe',
            'Bourgeois',
            'Pierre',
            'Benoit',
            'Rey',
            'Leclerc',
            'Payet',
            'Rolland',
            'Leclercq',
            'Guillaume',
            'Lecomte',
            'Lopez',
            'Jean',
            'Dupuy',
            'Guillot',
            'Hubert',
            'Berger',
            'Carpentier',
            'Sanchez',
            'Dupuis',
            'Moulin',
            'Louis',
            'Deschamps',
            'Huet',
            'Vasseur',
            'Perez',
            'Boucher',
            'Fleury',
            'Royer',
            'Klein',
            'Jacquet',
            'Adam',
            'Paris',
            'Poirier',
            'Marty',
            'Aubry',
            'Guyot',
            'Carre',
            'Charles',
            'Renault',
            'Charpentier',
            'Menard',
            'Maillard',
            'Baron',
            'Bertin',
            'Bailly',
            'Herve',
            'Schneider',
            'Fernandez',
            'Le Gall',
            'Collet',
            'Leger',
            'Bouvier',
            'Julien',
            'Prevost',
            'Millet',
            'Perrot',
            'Daniel',
            'Le Roux',
            'Cousin',
            'Germain',
            'Breton',
            'Besson',
            'Langlois',
            'Remy',
            'Le Goff',
            'Pelletier',
            'Leveque',
            'Perrier',
            'Leblanc',
            'Barre',
            'Lebrun',
            'Marchal',
            'Weber',
            'Mallet',
            'Hamon',
            'Boulanger',
            'Jacob',
            'Monnier',
            'Michaud',
            'Rodriguez',
            'Guichard',
            'Gillet',
            'Etienne',
            'Grondin',
            'Poulain',
            'Tessier',
            'Chevallier',
            'Collin',
            'Chauvin',
            'Da Silva',
            'Bouchet',
            'Gay',
            'Lemaitre',
            'Benard',
            'Marechal',
            'Humbert',
            'Reynaud',
            'Antoine',
            'Hoarau',
            'Perret',
            'Barthelemy',
            'Cordier',
            'Pichon',
            'Lejeune',
            'Gilbert',
            'Lamy',
            'Delaunay',
            'Pasquier',
            'Carlier',
            'Laporte',
            'Whitman',
        );

        return $lastname[rand(0, count($lastname) - 1)];
    }
}
