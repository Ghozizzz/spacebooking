@extends('layout')
@section('script')
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.3.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.3.1/js/buttons.bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.3.1/js/buttons.colVis.min.js"></script>

    <script type="text/javascript">
        $( document ).ready( function() {
            $( '#masterMonitorClass-table' ).DataTable({

            });
            $( '#noFacilMonitorClass-table' ).DataTable({

            });
        } );
    </script>
@endsection
@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 mt-4 text-gray-800">Class Data</h1>
    </div>

<?php if(session('role') == 2){ ?>
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
    <!-- Card Header - Dropdown -->
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">Upload</h6>
    </div>
    <!-- Card Body -->
    <div class="card-body">
        <div class="info-area px-1" style="overflow-y:auto">
        <form action="{{ route('MasterMonitorClass.import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="classFile">Class File</label>
                <input type="file" name="file" class="form-control-file" id="classFile" required>
            </div>
            <button class="btn btn-success btn-block">Submit</button>   
            @if ($countmonitorClassesWithNoProblem > 0)
                <a href="{{ route('home.monitor.synchronize') }}" class="btn btn-warning btn-block">Sync Class Schedule to Outlook</a>
            @endif
        </form>
        </div>
    </div>
    </div>
<?php } ?>
    @if (session('sync'))
        <div class="alert alert-success" role="alert">
            Data successfully synced
        </div>
    @endif
    <div class="card shadow mb-4">
        <div class="card-body">
            <h4 class="text-dark">The data below will be synced</h4>
            <div class="table-responsive">
            <table class="table table-bordered" id="masterMonitorClass-table" width="100%" cellspacing="0">
                <thead>
                <tr>
                    <th>Institution</th>
                    <th>Semester</th>
                    <th>Class Number</th>
                    <th>Session</th>
                    <th>Day</th>
                    <th>Time</th>
                    <th>Facility</th>
                </tr>
                </thead>
                <tbody>
                @foreach($monitorClasses as $monitorClass)
                    <tr>
                        <td>{{ $monitorClass->institution }}</td>
                        <td>{{ $monitorClass->term }}</td>
                        <td>{{ $monitorClass->classNbr }}</td>
                        <td>{{ $monitorClass->session }}</td>
                        <td>{{ $monitorClass->hari }}</td>
                        <td>{{ $monitorClass->jam }}</td>
                        <td>{{ $monitorClass->facilId }}</td>
                    </tr>                    
                @endforeach
                </tbody>
            </table>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <h5 class="text-dark">The data below have no facility and will NOT be synced. Please upload facility data from <a href="{{route('home.master.facility')}}">this page</a></h5>
            <div class="table-responsive">
            <table class="table table-bordered" id="noFacilMonitorClass-table" width="100%" cellspacing="0">
                <thead>
                <tr>
                    <th>Institution</th>
                    <th>Semester</th>
                    <th>Class Number</th>
                    <th>Session</th>
                    <th>Day</th>
                    <th>Time</th>
                    <th>Facility</th>
                </tr>
                </thead>
                <tbody>
                @foreach($monitorClassesNoFacility as $monitorClass)
                    <tr>
                        <td>{{ $monitorClass->institution }}</td>
                        <td>{{ $monitorClass->term }}</td>
                        <td>{{ $monitorClass->classNbr }}</td>
                        <td>{{ $monitorClass->session }}</td>
                        <td>{{ $monitorClass->hari }}</td>
                        <td>{{ $monitorClass->jam }}</td>
                        <td class="table-danger"><b>{{ $monitorClass->facilId }}</b></td>
                    </tr>                    
                @endforeach
                </tbody>
            </table>
            </div>
        </div>
    </div>


</div>
<!-- /.container-fluid -->
@endsection
