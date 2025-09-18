<style type="text/css">
  .tg  {border-collapse:collapse;border-color:#9ABAD9;border-spacing:0;border-style:solid;border-width:1px;}
  .tg td{background-color:#EBF5FF;border-color:#9ABAD9;border-style:solid;border-width:0px;color:#444;
    font-family:Arial, sans-serif;font-size:14px;overflow:hidden;padding:10px 5px;word-break:normal;}
  .tg th{border-color:#9ABAD9;border-style:solid;border-width:0px;color:#fff;
    font-family:Arial, sans-serif;font-size:14px;font-weight:normal;overflow:hidden;padding:10px 5px;word-break:normal;}
  .tg .tg-1wig{font-weight:bold;text-align:left;vertical-align:top}
  .tg .tg-hmp3{background-color:#D2E4FC;text-align:left;vertical-align:top}
  .tg .tg-7dnc{background-color:#D2E4FC;font-weight:bold;text-align:left;vertical-align:top}
  .tg .tg-0lax{text-align:left;vertical-align:top}
  .tg .tg-wwc1{background-color:#D2E4FC;font-family:Verdana, Geneva, sans-serif !important;;text-align:left;vertical-align:top}
  </style>
  <table class="tg">
  <thead>
    <tr>
      <th class="tg-0lax" colspan="4"><h1 style="color: black;">FutoroVTC</h1></th>
    </tr>
  </thead>
  <tbody>
    <tr>
        <td class="tg-1wig">Société</td>
        <td class="tg-0lax">Futuro VTC</td>
        <td class="tg-1wig">Document</td>
        <td class="tg-0lax">Facture</td>
      </tr>
    <tr>
      <td class="tg-7dnc">Date transaction</td>
      <td class="tg-hmp3">{{$Date_transaction}}</td>
      <td class="tg-7dnc">Transaction n°</td>
      <td class="tg-hmp3">{{$N_transaction}}</td>
    </tr>
    <tr>
      <td class="tg-1wig">Nom</td>
      <td class="tg-0lax">{{$Nom}}</td>
      <td class="tg-1wig">Prenom</td>
      <td class="tg-0lax">{{$Prenom}}</td>
    </tr>
    <tr>
      <td class="tg-7dnc">Adresse départ</td>
      <td class="tg-hmp3">{{$Gps_depart}}</td>
      <td class="tg-7dnc">Adresse arrivée</td>
      <td class="tg-hmp3">{{$Gps_arrivée}}</td>
    </tr>
    <tr>
      <td class="tg-1wig">Date départ</td>
      <td class="tg-0lax">{{$Date_depart}}</td>
      <td class="tg-1wig">Nb passager</td>
      <td class="tg-0lax">{{$Nb_passager}}</td>
    </tr>
    <tr>
      <td class="tg-7dnc">Heure depart</td>
      <td class="tg-hmp3">{{$Heure_depart}}</td>
      <td class="tg-7dnc">Heure arrivée</td>
      <td class="tg-hmp3">{{$Heure_arrivee}}</td>
    </tr>
    <tr>
      <td class="tg-1wig">Distance</td>
      <td class="tg-0lax">{{$distance}}km</td>
      <td class="tg-1wig">Durée course</td>
      <td class="tg-0lax">{{$duree}}h</td>
    </tr>
    <tr>
      <td class="tg-7dnc">Prix HT</td>
      <td class="tg-hmp3">{{$prixHT}}€</td>
      <td class="tg-7dnc">Prix TTC (TVA {{$TVA}}%)</td>
      <td class="tg-hmp3">{{$prixTTC}}€</td>
    </tr>
  </tbody>
  </table>
