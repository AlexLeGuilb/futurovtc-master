@extends('layouts.master')
@section('css')
    <link href="{{ asset('css/addons/datatables.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/addons/datatables-select.min.css') }}" rel="stylesheet">
@endsection
@section('bodyContent')
    <div class="container">
        @if (session('error'))
            <div class="alert alert-success">
                {{ session('error') }}
            </div>
        @endif
        <a class="btn btn-md btn-info" href="{{route("createCourse")}}" >Créer course</a>
        <table id="HRtable" class="table table-striped" cellspacing="0" width="100%">
            <thead>
            <tr>
                <th class="th-sm">N°client</th>
                <th class="th-sm">Numéro</th>
                <th class="th-sm">Depart</th>
                <th class="th-sm">Arrivée</th>
                <th class="th-sm">Heure</th>
                <th class="th-sm">Date</th>
                <th class="th-sm">Nombre passager(s)</th>
                <th class="th-sm">Modifier</th>
                <th class="th-sm">Supprimer</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($trans as $tra)
                <tr>
                    <td>{{$tra['idClient']}}</td>
                    <td>{{$tra['numTransaction']}}</td>
                    <td>{{$tra['gpsDepart']}}</td>
                    <td>{{$tra['gpsArrive'] }}</td>
                    <td>{{$tra['heureDepart'] }}</td>
                    <td>{{$tra['dateDepart'] }}</td>
                    <td>{{$tra['nbPassager'] }}</td>
                    <td>
                        <form method="post" action="{{route('editCourse')}}" >
                            @csrf
                            <input name="id" type="hidden" value="{{$tra['idTransaction']}}">
                            <button class="btn btn-sm btn-info"><i class="fas fa-edit fa-2x"></i></button>
                        </form>
                    </td>
                    <td>
                        <form method="post" action="{{route('delCourse')}}" >
                            @csrf
                            <input name="id" type="hidden" value="{{$tra['idTransaction']}}">
                            <button class="btn btn-sm btn-danger"><i class="fas fa-trash fa-2x"></i></button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>

        </table>
    </div>
@endsection
@section('js')
    <script src="{{ asset('js/addons/datatables.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/addons/datatables-select.min.js') }}" type="text/javascript"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('#HRtable').DataTable();
            $('#HRtable_wrapper').find('label').each(function () {
                $(this).parent().append($(this).children());
            });
            $('#HRtable_wrapper .dataTables_filter').find('input').each(function () {
                const $this = $(this);
                $this.attr("placeholder", "Rechercher");
                $this.removeClass('form-control-sm');
            });
            $('#HRtable_wrapper .dataTables_length').addClass('d-flex flex-row');
            $('#HRtable_wrapper .dataTables_filter').addClass('md-form');
            $('#HRtable_wrapper select').removeClass('custom-select custom-select-sm form-control form-control-sm');
            $('#HRtable_wrapper select').addClass('mdb-select');
            $('#HRtable_wrapper .mdb-select').materialSelect();
            $('#HRtable_wrapper .dataTables_filter').find('label').remove();
        });
    </script>
@endsection
