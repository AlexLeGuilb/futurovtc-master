@extends('layouts.master')
@section('css')
    <link href="{{ asset('css/addons/datatables.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/addons/datatables-select.min.css') }}" rel="stylesheet">
    <style>
        .switch.success-switch label input[type=checkbox]:checked + .lever:after {
            background-color: #33B5E5;
        }
    </style>
@endsection
@section('bodyContent')
<div class="container">
    <input id="idDriver" type="hidden" value="{{auth()->user()->id}}"/>
    @if (auth()->user()->typeRole == 'CHF')
        <div class="switch success-switch">
            <label>
                Inactif
            <input name="switch" id="switch" type="checkbox" @if (auth()->user()->actif) checked @endif/>
            <span class="lever"></span> Actif
        </label>
        </div>
    @endif
    <table id="HRtable" class="table table-striped" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th class="th-sm">Numéro</th>
                <th class="th-sm">Depart</th>
                <th class="th-sm">Arrivée</th>
                <th class="th-sm">Heure</th>
                <th class="th-sm">Date</th>
                <th class="th-sm">Nombre passager(s)</th>
                <th class="th-sm">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($trans as $tra)
            <tr>
                <td>{{$tra['numTransaction']}}</td>
                <td>{{$tra['gpsDepart']}}</td>
                <td>{{$tra['gpsArrive'] }}</td>
                <td>{{$tra['heureDepart'] }}</td>
                <td>{{$tra['dateDepart'] }}</td>
                <td>{{$tra['nbPassager'] }}</td>
                <td>
                    <a class="btn btn-md btn-info" href="{{route("courseValide", $tra['idTransaction'])}}" >Valider course</a>
                </td>
            </tr>
        @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th>Numéro</th>
                <th>Départ</th>
                <th>Arrivée</th>
                <th>Heure</th>
                <th>Date</th>
                <th>Nombre passager</th>
                <th>Action</th>
            </tr>
        </tfoot>
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
                $this.attr("placeholder", "Search");
                $this.removeClass('form-control-sm');
            });
            $('#HRtable_wrapper .dataTables_length').addClass('d-flex flex-row');
            $('#HRtable_wrapper .dataTables_filter').addClass('md-form');
            $('#HRtable_wrapper select').removeClass('custom-select custom-select-sm form-control form-control-sm');
            $('#HRtable_wrapper select').addClass('mdb-select');
            $('#HRtable_wrapper .mdb-select').materialSelect();
            $('#HRtable_wrapper .dataTables_filter').find('label').remove();

            $('#switch').on('click', function() {
                $.ajax({
                    type: "GET",
                    url: "{{route('updateActivity')}}",
                    data: {switch: $(this).is(":checked"),
                            idDriver: $('#idDriver').val()},
                    dataType: "text",
                    success: function (result) {
                        // console.log(result);
                    }
                });
            })
        });
    </script>
@endsection
