@extends('layouts.master')
@section('bodyContent')

<div class="container">
    <form action="{{ route('confirmation') }}" method="POST">
        @csrf
        <div class="card w-75 mx-auto my-5">
            <h5 class="card-header primary-color white-text text-center py-4">
                <strong>Informations de paiement</strong>
            </h5>
            <div class="card-body px-lg-5 pt-0">
                <div class="md-form md-bg">
                    <label for="cardNumber">N° de carte</label>
                    <input type="text" name="cardNumber" id="cardNumber" class="form-control" value="{{ old('cardNumber') }}" maxlength="16" required>
                    @if($errors->has('cardNumber'))
                        <p>{{ $errors->first('cardNumber')}} </p>
                    @endif
                </div>
                <div class="md-form md-bg">
                    <label for="expirationDate">Date d'expiration</label>
                    <input type="date" name="expirationDate" id="expirationDate" class="form-control datepicker" value="{{ old('expirationDate') }}" required>
                    @if($errors->has('expirationDate'))
                        <p>{{ $errors->first('expirationDate')}} </p>
                    @endif
                </div>
                <div class="md-form md-bg">
                    <label for="tel">Titulaire</label>
                    <input type="text" name="owner" id="owner" class="form-control" value="{{ old('owner') }}" required>
                    @if($errors->has('owner'))
                        <p>{{ $errors->first('owner')}} </p>
                    @endif
                </div>
                <div class="md-form md-bg">
                    <label for="tel">CVV</label>
                    <input type="number" name="cvv" id="cvv" class="form-control" value="{{ old('cvv') }}" maxlength="3" required>
                    @if($errors->has('cvv'))
                        <p>{{ $errors->first('cvv')}} </p>
                    @endif
                </div>
                <div class="md-form md-bg">
                    <button class="btn btn-outline-primary btn-rounded btn-block z-depth-0 my-4 waves-effect" type="submit">Save</button>
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
</script>
@endsection
