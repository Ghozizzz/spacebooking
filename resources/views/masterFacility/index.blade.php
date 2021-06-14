@extends('layout')
@section('script')
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.3.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.3.1/js/buttons.bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.3.1/js/buttons.colVis.min.js"></script>

    <script type="text/javascript">
        $( document ).ready( function() {
            $( '#masterFacility-table' ).DataTable({

            });
        } );
    </script>
@endsection
@section('content')
<body>
    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 mt-4 text-gray-800">Facility Data</h1>
        </div>

	@if(session('role') !== 1)
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Upload</h6>
            </div>

            <div class="card-body">
                <div class="info-area px-1" style="overflow-y:auto">
                    <form action="{{ route('MasterFacility.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <label for="scheduleFile">Facility File</label>
                        <input type="file" name="file" class="form-control-file" required>
                        <br>
                        <button class="btn btn-success btn-block">Submit</button>
                        @if ($countMsterFacilities > 0)
                            <a href="{{ route('home.master.synchronize') }}" class="btn btn-warning btn-block">Sync Facilities to Outlook</a>
                        @endif
                        {{-- <a class="btn btn-warning" href="{{ route('MasterFacility.export') }}">Export User Data</a> --}}
                    </form>
                </div>
            </div>
        </div>
	@endif

        @if (session('sync'))
            <div class="alert alert-success" role="alert">
                Data Sudah Di Sync
            </div>
        @endif
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="masterFacility-table">
                        <thead>
                            <tr>
                                <th>Set Id</th>
                                <th>Facil Id</th>
                                <th>Status</th>
                                <th>Building</th>
                                <th>Description</th>
                                <th>Type</th>
                                <th>Location</th>
                                <th>Capacity</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($dataMasterFacilities as $dataMasterFacility)
                            <tr>
                                <td>{{ $dataMasterFacility->setId }}</td>
                                <td>{{ $dataMasterFacility->facilId }}</td>
                                <td>{{ $dataMasterFacility->status }}</td>
                                <td>{{ $dataMasterFacility->building }}</td>
                                <td>{{ $dataMasterFacility->description }}</td>
                                <td>{{ $dataMasterFacility->type }}</td>
                                <td>{{ $dataMasterFacility->location }}</td>
                                <td>{{ $dataMasterFacility->capacity }}</td>
                                <td>
                                    <a href="{{ route('home.master.facility.edit' , ['id' => $dataMasterFacility->id]) }}" class="btn btn-primary">Edit</a>
                                    <a href="{{ route('home.master.facility.timetable' , ['id' => $dataMasterFacility->id]) }}" class="btn btn-info">Print</a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer">
                <a class="btn btn-danger" href="{{ route('MasterFacility.qr') }}">Generate QR Code</a>
            </div>
        </div>
    </div>


</body>
@endsection
