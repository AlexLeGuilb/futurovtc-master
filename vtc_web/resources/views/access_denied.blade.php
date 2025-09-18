@extends('layouts.master')

@section('bodyContent')
    <div class="text-center my-5 py-5">
        <p class="h1 my-3 py-3">Vous n'avez pas les droits d'accès à cette page !</p>
        <p class="my-3 py-3">Cliquez sur ce bouton pour revenir à votre page<br><a class="btn btn-info btn-md" href="{{ route('index') }}">Go !</a></p>
    </div>
@endsection
