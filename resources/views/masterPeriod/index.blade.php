@extends('layout')
@section('script')
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.3.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.3.1/js/buttons.bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.3.1/js/buttons.colVis.min.js"></script>

    <script type="text/javascript">
        $( document ).ready( function() {
            $( '#masterPeriod-table' ).DataTable({

            });
        } );
    </script>
@endsection
@section('content')
<body>
    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 mt-4 text-gray-800">Period Data</h1>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Upload</h6>
            </div>

            <div class="card-body">
                <div class="info-area px-1" style="overflow-y:auto">
                    <form action="{{ route('masterPeriod.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <label for="scheduleFile">Period File</label>
                        <input type="file" name="file" class="form-control-file" required>
                        <br>
                        <button class="btn btn-success btn-block">Submit</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="masterPeriod-table">
                        <thead>
                            <tr>
                                <th>Institution</th>
                                <th>Career</th>
                                <th>Term</th>
                                <th>Description</th>
                                <th>Short Description</th>
                                <th>Begin Date</th>
                                <th>End Date</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($dataMasterPeriods as $dataMasterPeriod)
                            <tr>
                                <td>{{ $dataMasterPeriod->institution }}</td>
                                <td>{{ $dataMasterPeriod->career }}</td>
                                <td>{{ $dataMasterPeriod->term }}</td>
                                <td>{{ $dataMasterPeriod->description }}</td>
                                <td>{{ $dataMasterPeriod->shortDesc }}</td>
                                <td>{{ $dataMasterPeriod->beginDate }}</td>
                                <td>{{ $dataMasterPeriod->endDate }}</td>
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
