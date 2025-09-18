@extends('layouts.master')

@section('bodyContent')

<div class="container">
    <form action="{{ route('paiement') }}" method="POST">
        @csrf
        <div class="card w-75 mx-auto my-5 etape1">
            <h5 class="card-header primary-color white-text text-center py-4">
                <strong>Client</strong>
            </h5>
            <div class="card-body px-lg-5 pt-0">
                <div class="md-form md-bg">
                    <label for="clientName">Nom</label>
                    <input type="text" name="clientName" id="clientName" class="form-control" value="{{ old('clientName') }}" required autofocus>
                    @if($errors->has('clientName'))
                        <p>{{ $errors->first('clientName')}} </p>
                    @endif
                </div>
                <div class="md-form md-bg">
                    <label for="firstName">Prénom</label>
                    <input type="text" name="firstName" id="firstName" class="form-control" value="{{ old('firstName') }}" required>
                    @if($errors->has('firstName'))
                        <p>{{ $errors->first('firstName')}} </p>
                    @endif
                </div>
                <div class="md-form md-bg">
                    <label for="tel">Téléphone</label>
                    <input type="text" name="phoneNumber" id="phoneNumber" class="form-control" value="{{ old('phoneNumber') }}" required>
                    @if($errors->has('phoneNumber'))
                        <p>{{ $errors->first('phoneNumber')}} </p>
                    @endif
                </div>
                <div class="md-form md-bg">
                    <span class="btn btn-outline-primary btn-rounded btn-block z-depth-0 my-4 waves-effect netxEtape">Suivant</span>
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
                    <input type="date" name="date" id="date" class="form-control datepicker"  value="{{ old('date') }}" required>
                    @if($errors->has('date'))
                        <p>{{ $errors->first('date')}} </p>
                    @endif
                </div>
                <div class="md-form md-bg">
                    <label for="hour">Heure de départ</label>
                    <input type="text" id="hour" name="hour" class="form-control timepicker" value="{{ old('hour') }}" required>
                    @if($errors->has('hour'))
                        <p>{{ $errors->first('hour')}} </p>
                    @endif
                </div>
                <div class="md-form md-bg">
                    <label for="nbPers">Nombre de personne(s)</label>
                    <input type="number" name="nbPers" id="nbPers" class="form-control" value="{{ old('nbPers') }}" required>
                    @if($errors->has('nbPers'))
                        <p>{{ $errors->first('nbPers')}} </p>
                    @endif
                </div>
                <div class="md-form md-bg">
                    <label for="startLocation">Lieu de départ</label>
                    <input type="text" name="startLocation" id="startLocation" class="form-control" value="{{ old('startLocation') }}"  data-toggle="tooltip" data-placement="right" title="On n'a pas accès a l'API GPS permettant de transformer l'adresse en GPS, il faut donc entrer la Latitude et Longitude à la main." required>
                    @if($errors->has('startLocation'))
                        <p>{{ $errors->first('startLocation')}} </p>
                    @endif
                </div>
                <div class="md-form md-bg">
                    <label for="endLocation">Lieu d'arrivé</label>
                    <input type="text" name="endLocation" id="endLocation" class="form-control" value="{{ old('endLocation') }}"  data-toggle="tooltip" data-placement="right" title="On n'a pas accès a l'API GPS permettant de transformer l'adresse en GPS, il faut donc entrer la Latitude et Longitude à la main." required>
                    @if($errors->has('endLocation'))
                        <p>{{ $errors->first('endLocation')}} </p>
                    @endif
                </div>
                <div class="md-form md-bg">
                    <label for="exemple">Exemple de coordonnée GPS</label>
                    <input type="text" id="exemple" class="form-control" value="46.669813, 0.369572"  data-toggle="tooltip" data-placement="right" title="Un exemple de coordonnées GPS qui pointe sur le futuroscope" readonly>
                </div>
                <div class="md-form md-bg">
                    <button class="btn btn-outline-primary btn-rounded btn-block z-depth-0 my-4 waves-effect" type="submit">Save</button>
                </div>
                <div class="md-form md-bg">
                    <span class="btn btn-outline-danger btn-rounded btn-block z-depth-0 my-4 waves-effect previousEtape">Retour</span>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@section('js')
<script type="text/javascript">
    $( document ).ready(function() {
        $('.datepicker').pickadate();
        $('[data-toggle="tooltip"]').tooltip()

        $('#hour').pickatime({
        twelvehour: false,
        });
        $(".etape1").show();
        $(".etape2").hide();
    });

    $(".netxEtape").click(function() {
        if ($('#clientName').val() != "" && $('#firstName').val() != "" && $('#phoneNumber').val() != "") {
            $(".etape1").hide();
            $(".etape2").show();
        }
    });

    $(".previousEtape").click(function() {
        $(".etape2").hide();
        $(".etape1").show();
    });

    $('.datepicker').pickadate({
        monthsFull: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre',
        'Novembre', 'Décembre'],
        weekdaysShort: ['Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam'],
        today: 'aujourd',
        clear: 'effacer',
        close: 'fermer',
        format: 'yyyy-mm-dd',
        formatSubmit: 'yyyy-mm-dd'
    })

    $('#hour').pickatime({
        twelvehour: false,
        autoclose: true,
        donetext: 'Choisir',
        cleartext: 'Effacer',
    });
</script>
@endsection
