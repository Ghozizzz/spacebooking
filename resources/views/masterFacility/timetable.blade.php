<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Class</title>

    <!-- Custom fonts for this template-->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/all.css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('/css/app.css') }}">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css">
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <!-- Custom styles for this template-->
    <link href="{{ asset('css/sb-admin-2.min.css')}}" rel="stylesheet">
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.3.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.3.1/js/buttons.bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.3.1/js/buttons.colVis.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function () {
            $('#mybooking-table').DataTable({});
            $('#completed-booking-table').DataTable({});
        });

    </script>

    <style>
        .hide {
            display: none;
        }

        .show {
            display: block;
        }

    </style>
    @yield('css')
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column bg-white">

            <!-- Main Content -->
            <div id="content">
                <div class="container d-print-none">
                    <div class="row my-1">
                        <a href="#" class="btn btn-block btn-primary" onclick="window.print()">Print timetable</a>
                    </div>
                </div>

                <div class="container printarea">
                    <!-- Content Row -->

                    <div class="row my-1">
                        <div class="table-responsive">
                            <table class="table table-bordered text-center table-sm">
                                <thead class="">
                                    <tr>
                                        <th scope="col" colspan="5" class="align-middle">
                                            <p>
                                                Building {{$facility->building}} - {{$facility->facilId}} - {{$facility->description}}
                                            </p>
                                            <p>
                                                {{$startOfWeek}} - {{$endOfWeek}}
                                            </p>
                                        </th>
                                        <th scope="col" colspan="3">
                                            {{-- <img src="{{$url}}"> --}}
                                            @if(!is_null($facility->qrcode))
                                            {!! QrCode::size(100)->generate(route('booking.confirm', ['code' => $facility->qrcode])); !!}
                                            @endif
                                        </th>
                                    </tr>
                                </thead>

                                <thead class="thead-dark">
                                    <tr>
                                        <th scope="col">Waktu</th>
                                        <th scope="col">Minggu</th>
                                        <th scope="col">Senin</th>
                                        <th scope="col">Selasa</th>
                                        <th scope="col">Rabu</th>
                                        <th scope="col">Kamis</th>
                                        <th scope="col">Jumat</th>
                                        <th scope="col">Sabtu</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th scope="row">00:00-01:00</th>
                                        <td>
                                            @if(isset($schedule['MINGGU']['00']))
                                                <span class="bg-danger text-white">{{ $schedule['MINGGU']['00'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['SENIN']['00']))
                                                <span class="bg-danger text-white">{{ $schedule['SENIN']['00'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['SELASA']['00']))
                                                <span class="bg-danger text-white">{{ $schedule['SELASA']['00'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['RABU']['00']))
                                                <span class="bg-danger text-white">{{ $schedule['RABU']['00'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['KAMIS']['00']))
                                                <span class="bg-danger text-white">{{ $schedule['KAMIS']['00'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['JUMAT']['00']))
                                                <span class="bg-danger text-white">{{ $schedule['JUMAT']['00'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['SABTU']['00']))
                                                <span class="bg-danger text-white">{{ $schedule['SABTU']['00'] }} </span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">01:00-02:00</th>
                                        <td>
                                            @if(isset($schedule['MINGGU']['01']))
                                                <span class="bg-danger text-white">{{ $schedule['MINGGU']['01'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['SENIN']['01']))
                                                <span class="bg-danger text-white">{{ $schedule['SENIN']['01'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['SELASA']['01']))
                                                <span class="bg-danger text-white">{{ $schedule['SELASA']['01'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['RABU']['01']))
                                                <span class="bg-danger text-white">{{ $schedule['RABU']['01'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['KAMIS']['01']))
                                                <span class="bg-danger text-white">{{ $schedule['KAMIS']['01'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['JUMAT']['01']))
                                                <span class="bg-danger text-white">{{ $schedule['JUMAT']['01'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['SABTU']['01']))
                                                <span class="bg-danger text-white">{{ $schedule['SABTU']['01'] }} </span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">02:00-03:00</th>
                                        <td>
                                            @if(isset($schedule['MINGGU']['02']))
                                                <span class="bg-danger text-white">{{ $schedule['MINGGU']['02'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['SENIN']['02']))
                                                <span class="bg-danger text-white">{{ $schedule['SENIN']['02'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['SELASA']['02']))
                                                <span class="bg-danger text-white">{{ $schedule['SELASA']['02'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['RABU']['02']))
                                                <span class="bg-danger text-white">{{ $schedule['RABU']['02'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['KAMIS']['02']))
                                                <span class="bg-danger text-white">{{ $schedule['KAMIS']['02'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['JUMAT']['02']))
                                                <span class="bg-danger text-white">{{ $schedule['JUMAT']['02'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['SABTU']['02']))
                                                <span class="bg-danger text-white">{{ $schedule['SABTU']['02'] }} </span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">03:00-04:00</th>
                                        <td>
                                            @if(isset($schedule['MINGGU']['03']))
                                                <span class="bg-danger text-white">{{ $schedule['MINGGU']['03'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['SENIN']['03']))
                                                <span class="bg-danger text-white">{{ $schedule['SENIN']['03'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['SELASA']['03']))
                                                <span class="bg-danger text-white">{{ $schedule['SELASA']['03'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['RABU']['03']))
                                                <span class="bg-danger text-white">{{ $schedule['RABU']['03'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['KAMIS']['03']))
                                                <span class="bg-danger text-white">{{ $schedule['KAMIS']['03'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['JUMAT']['03']))
                                                <span class="bg-danger text-white">{{ $schedule['JUMAT']['03'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['SABTU']['03']))
                                                <span class="bg-danger text-white">{{ $schedule['SABTU']['03'] }} </span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">04:00-05:00</th>
                                        <td>
                                            @if(isset($schedule['MINGGU']['04']))
                                                <span class="bg-danger text-white">{{ $schedule['MINGGU']['04'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['SENIN']['04']))
                                                <span class="bg-danger text-white">{{ $schedule['SENIN']['04'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['SELASA']['04']))
                                                <span class="bg-danger text-white">{{ $schedule['SELASA']['04'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['RABU']['04']))
                                                <span class="bg-danger text-white">{{ $schedule['RABU']['04'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['KAMIS']['04']))
                                                <span class="bg-danger text-white">{{ $schedule['KAMIS']['04'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['JUMAT']['04']))
                                                <span class="bg-danger text-white">{{ $schedule['JUMAT']['04'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['SABTU']['04']))
                                                <span class="bg-danger text-white">{{ $schedule['SABTU']['04'] }} </span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">05:00-06:00</th>
                                        <td>
                                            @if(isset($schedule['MINGGU']['05']))
                                                <span class="bg-danger text-white">{{ $schedule['MINGGU']['05'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['SENIN']['05']))
                                                <span class="bg-danger text-white">{{ $schedule['SENIN']['05'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['SELASA']['05']))
                                                <span class="bg-danger text-white">{{ $schedule['SELASA']['05'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['RABU']['05']))
                                                <span class="bg-danger text-white">{{ $schedule['RABU']['05'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['KAMIS']['05']))
                                                <span class="bg-danger text-white">{{ $schedule['KAMIS']['05'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['JUMAT']['05']))
                                                <span class="bg-danger text-white">{{ $schedule['JUMAT']['05'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['SABTU']['05']))
                                                <span class="bg-danger text-white">{{ $schedule['SABTU']['05'] }} </span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">06:00-07:00</th>
                                        <td>
                                            @if(isset($schedule['MINGGU']['06']))
                                                <span class="bg-danger text-white">{{ $schedule['MINGGU']['06'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['SENIN']['06']))
                                                <span class="bg-danger text-white">{{ $schedule['SENIN']['06'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['SELASA']['06']))
                                                <span class="bg-danger text-white">{{ $schedule['SELASA']['06'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['RABU']['06']))
                                                <span class="bg-danger text-white">{{ $schedule['RABU']['06'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['KAMIS']['06']))
                                                <span class="bg-danger text-white">{{ $schedule['KAMIS']['06'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['JUMAT']['06']))
                                                <span class="bg-danger text-white">{{ $schedule['JUMAT']['06'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['SABTU']['06']))
                                                <span class="bg-danger text-white">{{ $schedule['SABTU']['06'] }} </span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">07:00-08:00</th>
                                        <td>
                                            @if(isset($schedule['MINGGU']['07']))
                                                <span class="bg-danger text-white">{{ $schedule['MINGGU']['07'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['SENIN']['07']))
                                                <span class="bg-danger text-white">{{ $schedule['SENIN']['07'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['SELASA']['07']))
                                                <span class="bg-danger text-white">{{ $schedule['SELASA']['07'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['RABU']['07']))
                                                <span class="bg-danger text-white">{{ $schedule['RABU']['07'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['KAMIS']['07']))
                                                <span class="bg-danger text-white">{{ $schedule['KAMIS']['07'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['JUMAT']['07']))
                                                <span class="bg-danger text-white">{{ $schedule['JUMAT']['07'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['SABTU']['07']))
                                                <span class="bg-danger text-white">{{ $schedule['SABTU']['07'] }} </span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">08:00-09:00</th>
                                        <td>
                                            @if(isset($schedule['MINGGU']['08']))
                                                <span class="bg-danger text-white">{{ $schedule['MINGGU']['08'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['SENIN']['08']))
                                                <span class="bg-danger text-white">{{ $schedule['SENIN']['08'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['SELASA']['08']))
                                                <span class="bg-danger text-white">{{ $schedule['SELASA']['08'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['RABU']['08']))
                                                <span class="bg-danger text-white">{{ $schedule['RABU']['08'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['KAMIS']['08']))
                                                <span class="bg-danger text-white">{{ $schedule['KAMIS']['08'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['JUMAT']['08']))
                                                <span class="bg-danger text-white">{{ $schedule['JUMAT']['08'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['SABTU']['08']))
                                                <span class="bg-danger text-white">{{ $schedule['SABTU']['08'] }} </span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">09:00-10:00</th>
                                        <td>
                                            @if(isset($schedule['MINGGU']['09']))
                                                <span class="bg-danger text-white">{{ $schedule['MINGGU']['09'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['SENIN']['09']))
                                                <span class="bg-danger text-white">{{ $schedule['SENIN']['09'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['SELASA']['09']))
                                                <span class="bg-danger text-white">{{ $schedule['SELASA']['09'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['RABU']['09']))
                                                <span class="bg-danger text-white">{{ $schedule['RABU']['09'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['KAMIS']['09']))
                                                <span class="bg-danger text-white">{{ $schedule['KAMIS']['09'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['JUMAT']['09']))
                                                <span class="bg-danger text-white">{{ $schedule['JUMAT']['09'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['SABTU']['09']))
                                                <span class="bg-danger text-white">{{ $schedule['SABTU']['09'] }} </span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">10:00-11:00</th>
                                        <td>
                                            @if(isset($schedule['MINGGU']['10']))
                                                <span class="bg-danger text-white">{{ $schedule['MINGGU']['10'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['SENIN']['10']))
                                                <span class="bg-danger text-white">{{ $schedule['SENIN']['10'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['SELASA']['10']))
                                                <span class="bg-danger text-white">{{ $schedule['SELASA']['10'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['RABU']['10']))
                                                <span class="bg-danger text-white">{{ $schedule['RABU']['10'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['KAMIS']['10']))
                                                <span class="bg-danger text-white">{{ $schedule['KAMIS']['10'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['JUMAT']['10']))
                                                <span class="bg-danger text-white">{{ $schedule['JUMAT']['10'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['SABTU']['10']))
                                                <span class="bg-danger text-white">{{ $schedule['SABTU']['10'] }} </span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">11:00-12:00</th>
                                        <td>
                                            @if(isset($schedule['MINGGU']['11']))
                                                <span class="bg-danger text-white">{{ $schedule['MINGGU']['11'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['SENIN']['11']))
                                                <span class="bg-danger text-white">{{ $schedule['SENIN']['11'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['SELASA']['11']))
                                                <span class="bg-danger text-white">{{ $schedule['SELASA']['11'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['RABU']['11']))
                                                <span class="bg-danger text-white">{{ $schedule['RABU']['11'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['KAMIS']['11']))
                                                <span class="bg-danger text-white">{{ $schedule['KAMIS']['11'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['JUMAT']['11']))
                                                <span class="bg-danger text-white">{{ $schedule['JUMAT']['11'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['SABTU']['11']))
                                                <span class="bg-danger text-white">{{ $schedule['SABTU']['11'] }} </span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">12:00-13:00</th>
                                        <td>
                                            @if(isset($schedule['MINGGU']['12']))
                                                <span class="bg-danger text-white">{{ $schedule['MINGGU']['12'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['SENIN']['12']))
                                                <span class="bg-danger text-white">{{ $schedule['SENIN']['12'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['SELASA']['12']))
                                                <span class="bg-danger text-white">{{ $schedule['SELASA']['12'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['RABU']['12']))
                                                <span class="bg-danger text-white">{{ $schedule['RABU']['12'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['KAMIS']['12']))
                                                <span class="bg-danger text-white">{{ $schedule['KAMIS']['12'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['JUMAT']['12']))
                                                <span class="bg-danger text-white">{{ $schedule['JUMAT']['12'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['SABTU']['12']))
                                                <span class="bg-danger text-white">{{ $schedule['SABTU']['12'] }} </span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">13:00-14:00</th>
                                        <td>
                                            @if(isset($schedule['MINGGU']['13']))
                                                <span class="bg-danger text-white">{{ $schedule['MINGGU']['13'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['SENIN']['13']))
                                                <span class="bg-danger text-white">{{ $schedule['SENIN']['13'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['SELASA']['13']))
                                                <span class="bg-danger text-white">{{ $schedule['SELASA']['13'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['RABU']['13']))
                                                <span class="bg-danger text-white">{{ $schedule['RABU']['13'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['KAMIS']['13']))
                                                <span class="bg-danger text-white">{{ $schedule['KAMIS']['13'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['JUMAT']['13']))
                                                <span class="bg-danger text-white">{{ $schedule['JUMAT']['13'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['SABTU']['13']))
                                                <span class="bg-danger text-white">{{ $schedule['SABTU']['13'] }} </span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">14:00-15:00</th>
                                        <td>
                                            @if(isset($schedule['MINGGU']['14']))
                                                <span class="bg-danger text-white">{{ $schedule['MINGGU']['14'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['SENIN']['14']))
                                                <span class="bg-danger text-white">{{ $schedule['SENIN']['14'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['SELASA']['14']))
                                                <span class="bg-danger text-white">{{ $schedule['SELASA']['14'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['RABU']['14']))
                                                <span class="bg-danger text-white">{{ $schedule['RABU']['14'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['KAMIS']['14']))
                                                <span class="bg-danger text-white">{{ $schedule['KAMIS']['14'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['JUMAT']['14']))
                                                <span class="bg-danger text-white">{{ $schedule['JUMAT']['14'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['SABTU']['14']))
                                                <span class="bg-danger text-white">{{ $schedule['SABTU']['14'] }} </span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">15:00-16:00</th>
                                        <td>
                                            @if(isset($schedule['MINGGU']['15']))
                                                <span class="bg-danger text-white">{{ $schedule['MINGGU']['15'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['SENIN']['15']))
                                                <span class="bg-danger text-white">{{ $schedule['SENIN']['15'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['SELASA']['15']))
                                                <span class="bg-danger text-white">{{ $schedule['SELASA']['15'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['RABU']['15']))
                                                <span class="bg-danger text-white">{{ $schedule['RABU']['15'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['KAMIS']['15']))
                                                <span class="bg-danger text-white">{{ $schedule['KAMIS']['15'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['JUMAT']['15']))
                                                <span class="bg-danger text-white">{{ $schedule['JUMAT']['15'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['SABTU']['15']))
                                                <span class="bg-danger text-white">{{ $schedule['SABTU']['15'] }} </span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">16:00-17:00</th>
                                        <td>
                                            @if(isset($schedule['MINGGU']['16']))
                                                <span class="bg-danger text-white">{{ $schedule['MINGGU']['16'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['SENIN']['16']))
                                                <span class="bg-danger text-white">{{ $schedule['SENIN']['16'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['SELASA']['16']))
                                                <span class="bg-danger text-white">{{ $schedule['SELASA']['16'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['RABU']['16']))
                                                <span class="bg-danger text-white">{{ $schedule['RABU']['16'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['KAMIS']['16']))
                                                <span class="bg-danger text-white">{{ $schedule['KAMIS']['16'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['JUMAT']['16']))
                                                <span class="bg-danger text-white">{{ $schedule['JUMAT']['16'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['SABTU']['16']))
                                                <span class="bg-danger text-white">{{ $schedule['SABTU']['16'] }} </span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">17:00-18:00</th>
                                        <td>
                                            @if(isset($schedule['MINGGU']['17']))
                                                <span class="bg-danger text-white">{{ $schedule['MINGGU']['17'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['SENIN']['17']))
                                                <span class="bg-danger text-white">{{ $schedule['SENIN']['17'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['SELASA']['17']))
                                                <span class="bg-danger text-white">{{ $schedule['SELASA']['17'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['RABU']['17']))
                                                <span class="bg-danger text-white">{{ $schedule['RABU']['17'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['KAMIS']['17']))
                                                <span class="bg-danger text-white">{{ $schedule['KAMIS']['17'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['JUMAT']['17']))
                                                <span class="bg-danger text-white">{{ $schedule['JUMAT']['17'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['SABTU']['17']))
                                                <span class="bg-danger text-white">{{ $schedule['SABTU']['17'] }} </span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">18:00-19:00</th>
                                        <td>
                                            @if(isset($schedule['MINGGU']['18']))
                                                <span class="bg-danger text-white">{{ $schedule['MINGGU']['18'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['SENIN']['18']))
                                                <span class="bg-danger text-white">{{ $schedule['SENIN']['18'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['SELASA']['18']))
                                                <span class="bg-danger text-white">{{ $schedule['SELASA']['18'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['RABU']['18']))
                                                <span class="bg-danger text-white">{{ $schedule['RABU']['18'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['KAMIS']['18']))
                                                <span class="bg-danger text-white">{{ $schedule['KAMIS']['18'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['JUMAT']['18']))
                                                <span class="bg-danger text-white">{{ $schedule['JUMAT']['18'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['SABTU']['18']))
                                                <span class="bg-danger text-white">{{ $schedule['SABTU']['18'] }} </span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">19:00-20:00</th>
                                        <td>
                                            @if(isset($schedule['MINGGU']['19']))
                                                <span class="bg-danger text-white">{{ $schedule['MINGGU']['19'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['SENIN']['19']))
                                                <span class="bg-danger text-white">{{ $schedule['SENIN']['19'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['SELASA']['19']))
                                                <span class="bg-danger text-white">{{ $schedule['SELASA']['19'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['RABU']['19']))
                                                <span class="bg-danger text-white">{{ $schedule['RABU']['19'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['KAMIS']['19']))
                                                <span class="bg-danger text-white">{{ $schedule['KAMIS']['19'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['JUMAT']['19']))
                                                <span class="bg-danger text-white">{{ $schedule['JUMAT']['19'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['SABTU']['19']))
                                                <span class="bg-danger text-white">{{ $schedule['SABTU']['19'] }} </span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">20:00-21:00</th>
                                        <td>
                                            @if(isset($schedule['MINGGU']['20']))
                                                <span class="bg-danger text-white">{{ $schedule['MINGGU']['20'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['SENIN']['20']))
                                                <span class="bg-danger text-white">{{ $schedule['SENIN']['20'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['SELASA']['20']))
                                                <span class="bg-danger text-white">{{ $schedule['SELASA']['20'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['RABU']['20']))
                                                <span class="bg-danger text-white">{{ $schedule['RABU']['20'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['KAMIS']['20']))
                                                <span class="bg-danger text-white">{{ $schedule['KAMIS']['20'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['JUMAT']['20']))
                                                <span class="bg-danger text-white">{{ $schedule['JUMAT']['20'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['SABTU']['20']))
                                                <span class="bg-danger text-white">{{ $schedule['SABTU']['20'] }} </span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">21:00-22:00</th>
                                        <td>
                                            @if(isset($schedule['MINGGU']['21']))
                                                <span class="bg-danger text-white">{{ $schedule['MINGGU']['21'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['SENIN']['21']))
                                                <span class="bg-danger text-white">{{ $schedule['SENIN']['21'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['SELASA']['21']))
                                                <span class="bg-danger text-white">{{ $schedule['SELASA']['21'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['RABU']['21']))
                                                <span class="bg-danger text-white">{{ $schedule['RABU']['21'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['KAMIS']['21']))
                                                <span class="bg-danger text-white">{{ $schedule['KAMIS']['21'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['JUMAT']['21']))
                                                <span class="bg-danger text-white">{{ $schedule['JUMAT']['21'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['SABTU']['21']))
                                                <span class="bg-danger text-white">{{ $schedule['SABTU']['21'] }} </span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">22:00-23:00</th>
                                        <td>
                                            @if(isset($schedule['MINGGU']['22']))
                                                <span class="bg-danger text-white">{{ $schedule['MINGGU']['22'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['SENIN']['22']))
                                                <span class="bg-danger text-white">{{ $schedule['SENIN']['22'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['SELASA']['22']))
                                                <span class="bg-danger text-white">{{ $schedule['SELASA']['22'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['RABU']['22']))
                                                <span class="bg-danger text-white">{{ $schedule['RABU']['22'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['KAMIS']['22']))
                                                <span class="bg-danger text-white">{{ $schedule['KAMIS']['22'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['JUMAT']['22']))
                                                <span class="bg-danger text-white">{{ $schedule['JUMAT']['22'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['SABTU']['22']))
                                                <span class="bg-danger text-white">{{ $schedule['SABTU']['22'] }} </span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">23:00-00:00</th>
                                        <td>
                                            @if(isset($schedule['MINGGU']['23']))
                                                <span class="bg-danger text-white">{{ $schedule['MINGGU']['23'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['SENIN']['23']))
                                                <span class="bg-danger text-white">{{ $schedule['SENIN']['23'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['SELASA']['23']))
                                                <span class="bg-danger text-white">{{ $schedule['SELASA']['23'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['RABU']['23']))
                                                <span class="bg-danger text-white">{{ $schedule['RABU']['23'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['KAMIS']['23']))
                                                <span class="bg-danger text-white">{{ $schedule['KAMIS']['23'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['JUMAT']['23']))
                                                <span class="bg-danger text-white">{{ $schedule['JUMAT']['23'] }} </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($schedule['SABTU']['23']))
                                                <span class="bg-danger text-white">{{ $schedule['SABTU']['23'] }} </span>
                                            @endif
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <small class="text-danger">Printed on: {{$now}}</small>
                </div>
            </div>
        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Custom scripts for all pages-->
    <script src="{{asset('js/sb-admin-2.min.js')}}"></script>

</body>

</html>
