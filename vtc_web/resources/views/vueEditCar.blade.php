@extends('layouts.master')
@section('css')
@endsection

@section('bodyContent')

<div class="container">
    <form action="{{route('editCarPost', $car['idVehicule'])}}" method="post">
        @csrf
        <div class="card w-75 mx-auto my-5 etape1">
            <h5 class="card-header primary-color white-text text-center py-4">
                <strong>Véhicule</strong>
            </h5>
            <div class="card-body px-lg-5 pt-0">
                <div class="md-form md-bg">
                    <label for="idVehicule">Immatriculation</label>
                    <input type="text" id="idVehicule" name="idVehicule" class="form-control" value="{{$car['idVehicule']}}" placeholder="Immatriculation" readonly/>
                </div>
                <div class="md-form md-bg">
                    <label for="etatVh">Etat véhicule</label>
                    <select name="etatVh" id="etatVh" class="form-control mdb-select md-form colorful-select dropdown-primary">
                        <option value="Fonction" @if($car['etatVh'] == "Fonction") selected @endif>Reviser</option>
                        <option value="A réviser" @if($car['etatVh'] == "A réviser") selected @endif>A réviser</option>
                        <option value="Révision urgente" @if($car['etatVh'] == "Révision urgente") selected @endif>Révision urgente</option>
                        <option value="HS" @if($car['etatVh'] == "HS") selected @endif>HS</option>
                    </select>
                </div>
                <div class="md-form md-bg">
                    <label for="kmVh">Km véhicule</label>
                    <input type="text" id="kmVh" name="kmVh" class="form-control" value="{{$car['kmVh']}}" placeholder="Km véhicule"/>
                </div>
                <div class="md-form md-bg">
                    <label for="matricule">Km depuis révision</label>
                    <input type="text" id="kmMomentRevision" name="kmMomentRevision" class="form-control" value="{{$car['kmMomentRevision']}}" placeholder="Km depuis révision"/>
                </div>
                <div class="md-form md-bg">
                    <button class="btn btn-outline-primary btn-rounded btn-block z-depth-0 my-4 waves-effect">Mettre à jour</button>
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
