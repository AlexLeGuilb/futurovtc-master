@extends('layouts.master')
@section('css')
    <link href="{{ asset('css/addons/datatables.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/addons/datatables-select.min.css') }}" rel="stylesheet">
@endsection
@section('bodyContent')
<div class="container">
    @if (count($transactions) != 0)
        <table id="HRtable" class="table table-striped" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th class="th-sm">N°transaction</th>
                    <th class="th-sm">Date transaction</th>
                    <th class="th-sm">Gps départ</th>
                    <th class="th-sm">Gps arrivée</th>
                    <th class="th-sm">Date départ</th>
                    <th class="th-sm">Heure départ</th>
                    <th class="th-sm">Date arrivée</th>
                    <th class="th-sm">Heure arrivée</th>
                    <th class="th-sm">Nombre passager(s)</th>
                    <th class="th-sm">Id client</th>
                    <th class="th-sm">Id paiement</th>
                    <th class="th-sm">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($transactions as $transaction)
                    <tr>
                        <td>{{$transaction['numTransaction']}}</td>
                        <td>{{$transaction['dateTransaction']}}</td>
                        <td>{{$transaction['gpsDepart']}}</td>
                        <td>{{$transaction['gpsArrive']}}</td>
                        <td>{{$transaction['dateDepart']}}</td>
                        <td>{{$transaction['heureDepart']}}</td>
                        <td>{{$transaction['dateArrivee']}}</td>
                        <td>{{$transaction['heureArrivee']}}</td>
                        <td>{{$transaction['nbPassager']}}</td>
                        <td>{{$transaction['idClient']}}</td>
                        <td>{{$transaction['idPaiement']}}</td>
                        <td>
                            <a class="btn btn-sm btn-info" href='{{route('exportTransacToPDF', $transaction['idTransaction'])}}' >
                                <i class="far fa-file-alt"></i>
                            </a>
                        </td>
                    </tr>
            @endforeach
            </tbody>
        </table>
    @else
        <p>Il n'y a pas de transaction effecutée</p>
    @endif
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
