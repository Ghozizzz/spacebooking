@extends('layout')
@section('script')
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.3.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.3.1/js/buttons.bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.3.1/js/buttons.colVis.min.js"></script>

    <script type="text/javascript">
        $( document ).ready( function() {
            $( '#dataTable' ).DataTable({

            });
        } );
    </script>
@endsection
@section('content')
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
      <h1 class="h3 mb-0 mt-4 text-gray-800">Schedule Data</h1>
    </div>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
            <thead>
              <tr>
                <th>Facility</th>
                <th>Day</th>
                <th>Time</th>
                <th>PIC Email</th>
              </tr>
            </thead>
            <tbody>
                @foreach($schedules as $schedule)
                <tr>
                    <td>{{ $schedule->facilId }}</td>
                    <td>{{ $schedule->hari }}</td>
                    <td>{{ $schedule->jam }}</td>
                    <td>uph@uph.ac.id</td>
                </tr>
                @endforeach
            </tbody>
          </table>
        </div>
      </div>
      <div class="card-footer">
        @if ($countUserBookings > 0)
          <a href="{{ route('home.schedule.store') }}" class="btn btn-primary btn-block">Sync Booking Schedule to Outlook</a>  
        @endif
      </div>
    </div>

  </div>
@endsection