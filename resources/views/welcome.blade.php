@extends('layout')
@section('script')
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.3.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.3.1/js/buttons.bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.3.1/js/buttons.colVis.min.js"></script>

    <script type="text/javascript">
        $( document ).ready( function() {
            $( '#mybooking-table' ).DataTable({
            });
            $( '#completed-booking-table' ).DataTable({
            });
        } );
    </script>
@endsection
@section('content')
        <!-- Begin Page Content -->
        <div class="container-fluid">
	  @if(session('role') !== 1)
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
              <h1 class="h3 mb-0 mt-4 text-gray-800">Dashboard</h1>
          </div>

	  <div class="row my-2">
		<iframe width="100%" height="545" src="https://app.powerbi.com/reportEmbed?reportId=316f0a8d-a548-4f8c-a58a-f86e84d39b75&autoAuth=true&ctid=9024fef9-acd7-4a9a-98d1-b7225b67cf24&config=eyJjbHVzdGVyVXJsIjoiaHR0cHM6Ly93YWJpLXNvdXRoLWVhc3QtYXNpYS1yZWRpcmVjdC5hbmFseXNpcy53aW5kb3dzLm5ldC8ifQ%3D%3D" frameborder="0" allowFullScreen="true"></iframe>
	  </div>
	  @endif


          <div class="d-sm-flex align-items-center justify-content-between mb-4">
              <h1 class="h3 mb-0 mt-4 text-gray-800">My Bookings</h1>
          </div>


          <!-- Content Row -->

          <div class="row my-2">

          <div class="table-responsive">
            <table class="table table-hover table-bordered" id="mybooking-table">
              <thead>
                  <tr>
                      <th scope="col">#</th>
                      <th scope="col">Room</th>
                      <th scope="col">Status</th>
                      <th scope="col">Event name</th>
                      <th scope="col">Event type</th>
                      <th scope="col">Actions</th>
                  </tr>
              </thead>
              <tbody>
                  @foreach($booking as $key=>$item)
                          @php
			  $bookDateTime = $item->bookDate->format('d M Y').' '.$item->bookTime;
			  $bookDateTime = \Carbon\Carbon::createFromFormat('d M Y H:i', $bookDateTime);
			  $now = \Carbon\Carbon::now();
			  $diff = $now->diffInMinutes($bookDateTime, true);

                          if($item->approvalStatus == 'pending')
                            $status = '<span class="badge badge-info">Pending</span>';
                          elseif($item->approvalStatus == 'revise')
                            $status = '<span class="badge badge-warning">Need revision</span>';
                          else
                            continue
                          @endphp
                          <tr>
                              <th>{{$key+1}}</th>
                              <th scope="row">{{$item->facilities->facilId}}</th>
                              <td>{!!$status!!}</td>
                              <td>{{ empty($item->eventName) ? '-' : $item->eventName  }} </td>
                              <td>{{ empty($item->eventType) ? '-' : $item->eventType  }} </td>
                              <td>
                                  <a href="#info" class="btn btn-info btn-sm" data-toggle="modal"
                                      data-target="#infoModal-{{$item->id}}"><i class="fas fa-info-circle"></i> Details</a>
                                  @if($item->approvalStatus == 'pending' && $diff >= 60)
                                      <a href="#" class="btn btn-danger btn-sm" data-toggle="modal"
                                      data-target="#cancelModal-{{$item->id}}"><i class="far fa-times-circle"></i> Cancel</a>
                                      <div class="modal fade" id="cancelModal-{{$item->id}}" tabindex="-1" role="dialog"
                                          aria-labelledby="cancelModalLabel" aria-hidden="true">
                                          <div class="modal-dialog modal-lg" role="document">
                                              <div class="modal-content">
                                                  <div class="modal-header">
                                                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                          <span aria-hidden="true">&times;</span>
                                                      </button>
                                                  </div>
                                                  <div class="modal-body">
                                                      <div><strong>Reason:</strong></div>
                                                      <form method="post" action="{{route('home.booking.cancel')}}">
                                                          @csrf
                                                          <div class="form-row my-2">
                                                              <div class="col">
                                                                  <div class="input-group">
                                                                      <input type="hidden" name="id" value="{{$item->id}}">
                                                                      <textarea class="form-control"
                                                                          placeholder="Reason (if necessary...)" name="reason"
                                                                          rows="5" required></textarea>
                                                                  </div>
                                                              </div>
                                                          </div>
                                                  </div>
                                                  <div class="modal-footer">
                                                      <button type="button" class="btn btn-secondary"
                                                          data-dismiss="modal">Close</button>
                                                      <input type="submit" class="btn btn-primary">
                                                  </div>
                                                  </form>

                                              </div>
                                          </div>
                                      </div>

                                  @elseif($item->approvalStatus == 'revise')
                                  <a href="{{route('home.master.revise', ['id' => $item->id])}}" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i> Revise</a>
                                  @endif

                                  <!-- Info Modal -->
                                  <div class="modal fade text-dark" id="infoModal-{{$item->id}}" tabindex="-1" role="dialog"
                                      aria-labelledby="infoModalLabel" aria-hidden="true">
                                      <div class="modal-dialog modal-lg" role="document">
                                          <div class="modal-content">
                                              <div class="modal-header">
                                                  Booking Details
                                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                      <span aria-hidden="true">&times;</span>
                                                  </button>
                                              </div>
                                              <div class="modal-body">
                                                  <p>
                                                  <strong>Requested by:</strong>
                                                  {{$item->requestorName}}
                                                  </p>
                                                  <p>
                                                  <strong>Requestor phone:</strong>
                                                  {{$item->requestorPhone}}
                                                  </p>
                                                  <p>
                                                  <strong>Requestor faculty:</strong>
                                                  {{empty($item->requestorFacility) ? '-' : $item->requestorFacility}}
                                                  </p>
                                                  <hr/>
                                                  <p>
                                                  <strong>Event name:</strong>
                                                  {{ empty($item->eventName) ? '-' : $item->eventName  }}
                                                  </p>
                                                  <p>
                                                  <strong>Event type:</strong>
                                                  {{ empty($item->eventType) ? '-' : $item->eventType  }}
                                                  </p>
                                                  <p>
                                                  <strong>Booked room:</strong>
                                                  {{$item->facilities->facilId}}
                                                  </p>
                                                  <p>
                                                  <strong>Booking time:</strong>
                                                  {{$item->bookDate->format('d M Y')}} from {{$item->bookTime}} for {{$item->bookDuration}} minutes
                                                  </p>
					          <p>
                                                  <strong>Booking time:</strong>

					          </p>

                                                  <p>
                                                  <strong>Equipments:</strong>
                                                  @if(!is_null($item->equipments) && $item->equipments !== 'null')
                                                      @php
                                                      $equipments =  json_decode($item->equipments);
                                                      @endphp
                                                      @foreach($equipments as $equipment)
                                                      {{$equipment}}
                                                      @endforeach
                                                  @else
                                                  -
                                                  @endif
                                                  </p>
                                                  <p>
                                                  <strong>Additional requests:</strong>
                                                  {{$item->bookReason}}
                                                  </p>
                                                  <hr/>
                                                  <p>
                                                  <strong>Approval status:</strong>
                                                  {{ucwords($item->approvalStatus)}}
                                                  </p>
                                                  <p>
                                                  <strong>Approval reason:</strong>
                                                  {{$item->approvalReason}}
                                                  </p>
                                                  <p>
                                                  <strong>Approved by:</strong>
                                                  {{$item->approverId}}
                                                  </p>
                                                  <hr/>
                                                  <p>
                                                  <strong>Requested on:</strong>
                                                  {{$item->created_at}}
                                                  </p>
                                              </div>
                                          </div>
                                      </div>
                                  </div>
                              </td>
                          </tr>
                  @endforeach
              </tbody>
            </table>
          </div>
          </div>

        </div>
        <hr/>
        <div class="container-fluid">
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 mt-4 text-gray-800">Completed Bookings</h1>
            </div>


            <!-- Content Row -->

            <div class="row my-2">

            <div class="table-responsive">
              <table class="table table-hover table-bordered" id="completed-booking-table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Room</th>
                        <th scope="col">Status</th>
                        <th scope="col">Event name</th>
                        <th scope="col">Event type</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($booking as $key=>$item)
                            @php
				$bookDateTime = $item->bookDate->format('d M Y').' '.$item->bookTime;
				$bookDateTime = \Carbon\Carbon::createFromFormat('d M Y H:i', $bookDateTime);
				$now = \Carbon\Carbon::now();
				$diff = $now->diffInMinutes($bookDateTime, true);

	                        if($item->approvalStatus == 'accept')
                            	  $status = '<span class="badge badge-success">Accepted</span>';
                                elseif($item->approvalStatus == 'cancel')
                            	  $status = '<span class="badge badge-danger">Cancelled</span>';
                                elseif($item->approvalStatus == 'decline')
                            	  $status = '<span class="badge badge-danger">Declined</span>';
                                else
                            	  continue;
                            @endphp
                            <tr>
                                <th>{{$key+1}}</th>
                                <th scope="row">{{$item->facilities->facilId}}</th>
                                <td>{!!$status!!}</td>
                                <td>{{ empty($item->eventName) ? '-' : $item->eventName  }} </td>
                                <td>{{ empty($item->eventType) ? '-' : $item->eventType  }} </td>
                                <td>
                                    <a href="#info" class="btn btn-info btn-sm" data-toggle="modal"
                                        data-target="#infoModal-{{$item->id}}"><i class="fas fa-info-circle"></i> Details</a>
                                    @if($item->approvalStatus == 'accept' && $diff >= 60)
                                        <a href="#" class="btn btn-danger btn-sm" data-toggle="modal"
                                        data-target="#cancelModal-{{$item->id}}"><i class="far fa-times-circle"></i> Cancel</a>
                                        <div class="modal fade" id="cancelModal-{{$item->id}}" tabindex="-1" role="dialog"
                                            aria-labelledby="cancelModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-lg" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div><strong>Reason:</strong></div>
                                                        <form method="post" action="{{route('home.booking.cancel')}}">
                                                            @csrf
                                                            <div class="form-row my-2">
                                                                <div class="col">
                                                                    <div class="input-group">
                                                                        <input type="hidden" name="id" value="{{$item->id}}">
                                                                        <textarea class="form-control"
                                                                            placeholder="Reason (if necessary...)" name="reason"
                                                                            rows="5" required></textarea>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">Close</button>
                                                        <input type="submit" class="btn btn-primary">
                                                    </div>
                                                    </form>

                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Info Modal -->
                                    <div class="modal fade text-dark" id="infoModal-{{$item->id}}" tabindex="-1" role="dialog"
                                        aria-labelledby="infoModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-lg" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    Booking Details
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>
                                                    <strong>Requested by:</strong>
                                                    {{$item->requestorName}}
                                                    </p>
                                                    <p>
                                                    <strong>Requestor phone:</strong>
                                                    {{$item->requestorPhone}}
                                                    </p>
                                                    <p>
                                                    <strong>Requestor faculty:</strong>
                                                    {{empty($item->requestorFacility) ? '-' : $item->requestorFacility}}
                                                    </p>
                                                    <hr/>
                                                    <p>
                                                    <strong>Event name:</strong>
                                                    {{ empty($item->eventName) ? '-' : $item->eventName  }}
                                                    </p>
                                                    <p>
                                                    <strong>Event type:</strong>
                                                    {{ empty($item->eventType) ? '-' : $item->eventType  }}
                                                    </p>
                                                    <p>
                                                    <strong>Booked room:</strong>
                                                    {{$item->facilities->facilId}}
                                                    </p>
                                                    <p>
                                                    <strong>Booking time:</strong>
                                                    {{$item->bookDate->format('d M Y')}} from {{$item->bookTime}} for {{$item->bookDuration}} minutes
                                                    </p>
                                                    <p>
                                                    <strong>Equipments:</strong>
                                                    @if(!is_null($item->equipments) && $item->equipments !== 'null')
                                                        @php
                                                        $equipments =  json_decode($item->equipments)
                                                        @endphp
                                                        @foreach($equipments as $equipment)
                                                        {{$equipment}}
                                                        @endforeach
                                                    @else
                                                    -
                                                    @endif
                                                    </p>
                                                    <p>
                                                    <strong>Additional requests:</strong>
                                                    {{$item->bookReason}}
                                                    </p>
                                                    <hr/>
                                                    <p>
                                                    <strong>Approval status:</strong>
                                                    {{ucwords($item->approvalStatus)}}
                                                    </p>
                                                    <p>
                                                    <strong>Approval reason:</strong>
                                                    {{$item->approvalReason}}
                                                    </p>
                                                    <p>
                                                    <strong>Approved by:</strong>
                                                    {{$item->approverId}}
                                                    </p>
                                                    <hr/>
                                                    <p>
                                                    <strong>Requested on:</strong>
                                                    {{$item->created_at}}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                    @endforeach
                </tbody>
              </table>
            </div>
            </div>

          </div>
        <script>
          $("#file").on('change', function() {
           var fullPath = this.value;
           var filename = fullPath.replace(/^.*[\\\/]/, '')
           $("#fileLabel").text(filename);
          });
        </script>
@endsection
