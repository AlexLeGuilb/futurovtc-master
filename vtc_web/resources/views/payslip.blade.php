@extends('layouts.master')
@section('css')
    <style>
        .grid:after {
            content: '';
            display: block;
            clear: both;
        }

        .grid-item {
            width: auto;
            height: auto;
            float: left;
            margin-bottom: 20px;
        }
    </style>
@endsection

@section('bodyContent')

<div class="container">
    <form action="/generatePDF" method="post" id="formEmploye">
        @csrf
        <div class="card w-75 mx-auto my-5 etape1">
            <h5 class="card-header primary-color white-text text-center py-4">
                <strong>Client</strong>
            </h5>
            <div class="card-body px-lg-5 pt-0">
                <input type="hidden" id="idEmploye" name="idEmploye" value="@if (isset($employe)){{$employe['id']}}@endif" />
                <div class="md-form md-bg">
                    <label for="nomEmploye">Nom</label>
                    <input type="text" id="nomEmploye" name="nomEmploye" class="form-control" value="@if (isset($employe)){{$employe['name']}}@endif" readonly/>
                </div>
                <div class="md-form md-bg">
                    <label for="prenomEmploye">Prénom</label>
                    <input type="text" id="prenomEmploye" name="prenomEmploye" class="form-control" value="@if (isset($employe)) {{$employe['prename']}} @endif" readonly/>
                </div>
                <div class="md-form md-bg">
                    <label for="matricule">Matricule</label>
                    <input type="text" id="matricule" name="matricule" class="form-control" value="@if (isset($employe)){{$employe['matricule']}}@endif" readonly/>
                </div>
                <div class="md-form md-bg">
                    <label for="role">Rôle</label>
                    <input type="text" id="role" name="role" class="form-control" value="@if (isset($employe)) {{$role['libelleRole']}} @endif" readonly/>
                </div>
                @if ($role['libelleRole'] == 'Chauffeur')
                    <div class="md-form md-bg">
                        <label for="hours">Heures totales</label>
                        <input type="text" id="hours" name="hours" class="form-control" value="{{$driverInfos['hours']}}" readonly/>
                    </div>
                @else
                    <div class="md-form md-bg">
                        <label for="hours">Heures totales</label>
                        <input type="text" id="hours" name="hours" class="form-control" value="140" placeholder="140"/>
                    </div>
                @endif
                <div class="md-form md-bg">
                    <label for="euro">€ de l'heure</label>
                    <input type="text" id="euro" name="euro" class="form-control" value="15" placeholder="15"/>
                </div>
                <div class="md-form md-bg">
                    <button class="btn btn-outline-primary btn-rounded btn-block z-depth-0 my-4 waves-effect">Générer PDF <i class="far fa-file-pdf"></i></button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
@section('js')
    <script type="text/javascript" src="{{ asset('js/addons/masonry.pkgd.min.js') }}"></script>
    <script>
        $(document).ready(function(){
            $('.mdb-select').materialSelect();
        });

        $('.grid').masonry({
            itemSelector: '.grid-item',
            columnWidth: 100,
            gutter: 10
        });
    </script>
@endsection

