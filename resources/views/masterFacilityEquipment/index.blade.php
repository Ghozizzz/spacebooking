@extends('layout')
@section('script')
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.3.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.3.1/js/buttons.bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.3.1/js/buttons.colVis.min.js"></script>

    <script type="text/javascript">
        $( document ).ready( function() {
            $( '#masterFacilityEquipment-table' ).DataTable({

            });
        } );
    </script>
@endsection
@section('content')
<body>
    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 mt-4 text-gray-800">Facility Equipment Data</h1>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Upload</h6>
            </div>

            <div class="card-body">
                <div class="info-area px-1" style="overflow-y:auto">
                    <form action="{{ route('MasterFacilityEquipment.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <label for="scheduleFile">Facility Equipment File</label>
                        <input type="file" name="file" class="form-control-file" required>
                        <br>
                        <button class="btn btn-success btn-block">Submit</button>
                        {{-- <a class="btn btn-warning" href="{{ route('MasterFacility.export') }}">Export User Data</a> --}}
                    </form>
                </div>
            </div>
        </div>

        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive table-bordered">
                    <table class="table" id="masterFacilityEquipment-table">
                        <thead>
                            <tr>
                                <th>Set Id</th>
                                <th>Facil Id</th>
                                <th>Eff Date</th>
                                <th>Status</th>
                                <th>Building</th>
                                <th>Room</th>
                                <th>Description</th>
                                <th>Room Char</th>
                                <th>Quantity</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($dataMasterFacilityEquipments as $dataMasterFacilityEquipment)
                            <tr>
                                <td>{{ $dataMasterFacilityEquipment->setId }}</td>
                                <td>{{ $dataMasterFacilityEquipment->facilId }}</td>
                                <td>{{ $dataMasterFacilityEquipment->effDate }}</td>
                                <td>{{ $dataMasterFacilityEquipment->status }}</td>
                                <td>{{ $dataMasterFacilityEquipment->building }}</td>
                                <td>{{ $dataMasterFacilityEquipment->room }}</td>
                                <td>{{ $dataMasterFacilityEquipment->Descr }}</td>
                                <td>{{ $dataMasterFacilityEquipment->roomChar }}</td>
                                <td>{{ $dataMasterFacilityEquipment->quantity }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
@endsection
