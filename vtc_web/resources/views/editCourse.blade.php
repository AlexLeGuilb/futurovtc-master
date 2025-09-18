@extends('layouts.master')
@section('css')
    <link href="{{ asset('css/addons/datatables.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/addons/datatables-select.min.css') }}" rel="stylesheet">
@endsection
@section('bodyContent')
<div class="container">
    <form action="/saveEdit" method="post">
        @csrf
        <input type="hidden" name="idTransaction" id="idTransaction" value="{{$trans['idTransaction']}}">
        <input type="hidden" name="idClient" id="idClient" value="{{$trans['idClient']}}">
        <div class="card w-75 mx-auto my-5 etape1">
            <h5 class="card-header primary-color white-text text-center py-4">
                <strong>Client</strong>
            </h5>
            <div class="card-body px-lg-5 pt-0">
                <div class="md-form md-bg">
                    <label for="clientName">Nom</label>
                    <input type="text" name="clientName" id="clientName" class="form-control" value="{{$idClient['nomClient']}}">
                    @if($errors->has('clientName'))
                        <p>{{ $errors->first('clientName')}} </p>
                    @endif
                </div>
                <div class="md-form md-bg">
                    <label for="firstName">Prénom</label>
                    <input type="text" name="firstName" id="firstName" class="form-control" value="{{$idClient['prenomClient']}}">
                    @if($errors->has('firstName'))
                        <p>{{ $errors->first('firstName')}} </p>
                    @endif
                </div>
                <div class="md-form md-bg">
                    <label for="tel">Téléphone</label>
                    <input type="text" name="phoneNumber" id="phoneNumber" class="form-control" value="{{$idClient['tel']}}">
                    @if($errors->has('phoneNumber'))
                        <p>{{ $errors->first('phoneNumber')}} </p>
                    @endif
                </div>
            </div>
        </div>

        <div class="card w-75 mx-auto my-5 etape2">
            <h5 class="card-header primary-color white-text text-center py-4">
                <strong>Course</strong>
            </h5>
            <div class="card-body px-lg-5 pt-0">
                <div class="md-form md-bg">
                    <label for="date">Date</label>
                    <input type="date" name="date" id="date" class="form-control datepicker"  value="{{$trans['dateDepart']}}" >
                    @if($errors->has('date'))
                        <p>{{ $errors->first('date')}} </p>
                    @endif
                </div>
                <div class="md-form md-bg">
                    <label for="hour">Heure de départ</label>
                    <input placeholder="Selectionner une heure" type="text" id="hour" name="hour" class="form-control timepicker" value=" {{$trans['heureDepart']}} ">
                    @if($errors->has('hour'))
                        <p>{{ $errors->first('hour')}} </p>
                    @endif
                </div>
                <div class="md-form md-bg">
                    <label for="nbPers">Nombre de personnes</label>
                    <input type="number" name="nbPers" id="nbPers" class="form-control" value="{{$trans['nbPassager']}}">
                    @if($errors->has('nbPers'))
                        <p>{{ $errors->first('nbPers')}} </p>
                    @endif
                </div>
                <div class="md-form md-bg">
                    <label for="startLocation">Lieu de départ</label>
                    <input type="text" name="startLocation" id="startLocation" class="form-control" value="{{$trans['gpsDepart']}}" data-toggle="tooltip" data-placement="right" title="On n'a pas accès a l'API GPS permettant de transformer l'adresse en GPS, il faut donc entrer la Latitude et Longitude à la main.">
                    @if($errors->has('startLocation'))
                        <p>{{$errors->first('startLocation')}}</p>
                    @endif
                </div>
                <div class="md-form md-bg">
                    <label for="endLocation">Lieu d'arrivée</label>
                    <input type="text" name="endLocation" id="endLocation" class="form-control" value="{{$trans['gpsArrive']}}"  data-toggle="tooltip" data-placement="right" title="On n'a pas accès a l'API GPS permettant de transformer l'adresse en GPS, il faut donc entrer la Latitude et Longitude à la main.">
                    @if($errors->has('endLocation'))
                        <p>{{$errors->first('endLocation')}}</p>
                    @endif
                </div>
                <div class="md-form md-bg">
                    <button class="btn btn-outline-primary btn-rounded btn-block z-depth-0 my-4 waves-effect" type="submit">Save</button>
                </div>
                <div class="md-form md-bg">
                    <a href="/remboursement/{{$trans['idTransaction']}}" class="btn btn-outline-primary btn-rounded btn-block z-depth-0 my-4 waves-effect" >Remboursement</a>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
@section("js")
    <script>
        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        })

        $('#date').pickadate({
            monthsFull: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
            weekdaysShort: ['Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam'],
            today: 'aujourd',
            clear: 'effacer',
            closer: 'fermer',
            format: 'dd/mm/yyyy'
        })

        $('#hour').pickatime({
            twelvehour: false,
            donetext: "Choisir",
            cleartext: "Effacer",
            autoclose: true
        })
    </script>
@endsection
