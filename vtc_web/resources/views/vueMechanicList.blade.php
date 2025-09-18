@extends('layouts.master')
@section('css')
    <link href="{{ asset('css/addons/datatables.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/addons/datatables-select.min.css') }}" rel="stylesheet">
@endsection
@section('bodyContent')
<div class="container">
    <a class="btn btn-info btn-md" href="/mechanicAddCar">Ajouter un véhicule</a>
    <table id="HRtable" class="table table-striped" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th class="th-sm">Immatriculation</th>
                <th class="th-sm">Marque</th>
                <th class="th-sm">Modèle</th>
                <th class="th-sm">Couleur</th>
                <th class="th-sm">Nombre place(s)</th>
                <th class="th-sm">Etat véhicule</th>
                <th class="th-sm">Km véhicule</th>
                <th class="th-sm">Km révision</th>
                <th class="th-sm">Km depuis révision</th>
                <th class="th-sm">Type permis</th>
                <th class="th-sm">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($cars as $car)
            <tr>
                <td>{{$car['idVehicule']}}</td>
                <td>{{$car['marque']}}</td>
                <td>{{$car['model']}}</td>
                <td>{{$car['couleur']}}</td>
                <td>{{$car['nbPlace']}}</td>
                <td>{{$car['etatVh']}}</td>
                <td>{{$car['kmVh']}}</td>
                <td>{{$car['kmConstucteurRevision']}}</td>
                <td>{{$car['kmMomentRevision']}}</td>
                <td>{{$car['typePermis']}}</td>
                <td>
                    <a class="btn btn-sm btn-info" href='{{route('editCarGet', $car['idVehicule'])}}' >
                        <i class="fas fa-edit"></i>
                    </a>
                    <a class="btn btn-sm btn-danger" href='{{route('deleteCar', $car['idVehicule'])}}' >
                        <i class="fas fa-trash"></i>
                    </a>
                </td>
            </tr>
        @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th>Immatriculation</th>
                <th>Marque</th>
                <th>Model</th>
                <th>Couleur</th>
                <th>Nombre place(s)</th>
                <th>Etat véhicule</th>
                <th>Km véhicule</th>
                <th>Km révision</th>
                <th>Km depuis révision</th>
                <th>Type permis</th>
                <th>Action</td>
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
