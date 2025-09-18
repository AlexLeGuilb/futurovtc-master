@extends('layouts.master')
@section('css')
@endsection

@section('bodyContent')

<div class="container">
    <form action="/mechanicAddCar" method="post">
        @csrf
        <div class="card w-75 mx-auto my-5 etape1">
            <h5 class="card-header primary-color white-text text-center py-4">
                <strong>Vehicule</strong>
            </h5>
            <div class="card-body px-lg-5 pt-0">
                <div class="md-form md-bg">
                    <label for="idVehicule">Immatriculation</label>
                    <input type="text" id="idVehicule" name="idVehicule" class="form-control" value=""/>
                </div>
                <div class="md-form md-bg">
                    <label for="marque">Marque</label>
                    <input type="text" id="marque" name="marque" class="form-control" value="" />
                </div>
                <div class="md-form md-bg">
                    <label for="model">Modèle</label>
                    <input type="text" id="model" name="model" class="form-control" value="" />
                </div>
                <div class="md-form md-bg">
                    <label for="matricule">Couleur</label>
                    <input type="text" id="couleur" name="couleur" class="form-control" value="" />
                </div>
                <div class="md-form md-bg">
                    <label for="matricule">Nombre place(s)</label>
                    <input type="text" id="nbPlace" name="nbPlace" class="form-control" value="" />
                </div>
                <div class="md-form md-bg">
                    <label for="etatVh">Etat véhicule</label>
                    <select name="etatVh" id="etatVh" class="form-control mdb-select md-form colorful-select dropdown-primary">
                        <option value="Révisé">Révisé</option>
                        <option value="A réviser">A réviser</option>
                        <option value="Révision urgente">Révision urgente</option>
                    </select>
                </div>
                <div class="md-form md-bg">
                    <label for="kmVh">Km véhicule</label>
                    <input type="text" id="kmVh" name="kmVh" class="form-control" value="" />
                </div>
                <div class="md-form md-bg">
                    <label for="kmConstucteurRevision">Km révision</label>
                    <input type="text" id="kmConstucteurRevision" name="kmConstucteurRevision" class="form-control" value="" />
                </div>
                <div class="md-form md-bg">
                    <label for="matricule">Km depuis révision</label>
                    <input type="text" id="kmMomentRevision" name="kmMomentRevision" class="form-control" value="" />
                </div>
                <div class="md-form md-bg">
                    <select name="typePermis" id="typePermis" class="form-control mdb-select md-form colorful-select dropdown-primary">
                        @foreach($permis as $perm)
                            <option value="{{ $perm['typePermis'] }}">{{ $perm['libellePermis'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="md-form md-bg">
                    <button class="btn btn-outline-primary btn-rounded btn-block z-depth-0 my-4 waves-effect">Ajouter</button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
@section('js')
    <script>
        $(document).ready(function(){
            $('.mdb-select').materialSelect();
        });
    </script>
@endsection
