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
    <form action="/updateEmploye" method="post" id="formEmploye">
        @csrf
        <div class="card w-75 mx-auto my-5 etape1">
            <h5 class="card-header primary-color white-text text-center py-4">
                <strong>Modifier employé</strong>
            </h5>
            <div class="card-body px-lg-5 pt-0">
                <input type="hidden" id="idEmploye" name="idEmploye" value="@if (isset($employe)){{$employe['id']}}@endif" />
                <div class="md-form md-bg">
                    <label for="name">Nom</label>
                    <input type="text" id="name" name="name" class="form-control" value="@if (isset($employe)){{$employe['name']}}@endif" placeholder="Nom" required/>
                    @if($errors->has('name'))
                        <p>{{ $errors->first('name')}} </p>
                    @endif
                </div>
                <div class="md-form md-bg">
                    <label for="prename">Prénom</label>
                    <input type="text" id="prename" name="prename" class="form-control" value="@if (isset($employe)){{$employe['prename']}}@endif" placeholder="Prenom" required/>
                    @if($errors->has('prename'))
                        <p>{{ $errors->first('prename')}} </p>
                    @endif
                </div>
                <div class="md-form md-bg">
                    <label for="matricule">Matricule</label>
                    <input type="text" id="matricule" name="matricule" class="form-control" value="@if (isset($employe)){{$employe['matricule']}}@endif" placeholder="Matricule" required/>
                    @if($errors->has('matricule'))
                        <p>{{ $errors->first('matricule')}} </p>
                    @endif
                </div>
                <div class="md-form md-bg">
                    <select name="role" id="role" class="form-control mdb-select md-form colorful-select dropdown-primary">
                        @foreach($roles as $role)
                            <option value="{{ $role['typeRole'] }}" @if (isset($employe) && ($employe['typeRole'] == $role['typeRole'])) selected @endif>{{ $role['libelleRole'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div id="wrapperDriver" class="w-100" data-toggle="buttons">
                    <label>Permis : </label>
                    <div class="grid">
                        @foreach (\App\Models\Permis::all() as $permi)
                            <div class="grid-item">
                                <label class="btn btn-md btn-primary active">
                                    <input type="checkbox" autocomplete="off" id="{{$permi['typePermis']}}" name="{{$permi['typePermis']}}" value="{{$permi['typePermis']}}"
                                    @if (isset($employe) && (\App\Models\Possede::where("id", $employe['id'])->where("typePermis", $permi['typePermis'])->exists())) checked @endif /> {{$permi['typePermis']}}
                                </label>
                            </div>
                        @endforeach
                    </div>
                    @if (isset($employe) &&($employe['typeRole'] == $role['typeRole']))
                        <div class="md-form md-bg">
                            <label for="hours">Heures totales :</label>
                            <input type="text" id="hours" name="hours" class="form-control" value="{{$driverInfos['hours']}}" readonly/>
                        </div>
                        <div class="md-form md-bg">
                            <label for="kilometers">Kilométrage total :</label>
                            <input type="text" id="kilometers" name="kilometers" class="form-control" value="{{$driverInfos['kilometers']}}" readonly/>
                        </div>
                    @else
                        <input type="hidden" id="hours" name="hours" class="form-control" value="0" readonly/>
                        <input type="hidden" id="kilometers" name="kilometers" class="form-control" value="0" readonly/>
                    @endif
                </div>
                <div class="md-form md-bg">
                    <button class="btn btn-outline-primary btn-rounded btn-block z-depth-0 my-4 waves-effect">Valider</button>
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

            if ($('#role').val() == "CHF") {
                $("#wrapperDriver").show();
            } else {
                $("#wrapperDriver").hide();
                $("#wrapperDriver").addClass('d-none');
            }
        });

        $('#role').on('change', function() {
            if (this.value == "CHF") {
                $("#wrapperDriver").show();
                $("#wrapperDriver").removeClass('d-none');
            } else {
                $("#wrapperDriver").hide();
                $("#wrapperDriver").addClass('d-none');
            }
        });

        $('.grid').masonry({
            itemSelector: '.grid-item',
            columnWidth: 100,
            gutter: 10
        });
    </script>
@endsection

