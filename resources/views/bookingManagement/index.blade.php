@extends('layout')
@section('script')
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
    integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous">
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
    integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous">
</script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
    integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
</script>
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.3.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.3.1/js/buttons.bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.3.1/js/buttons.colVis.min.js"></script>
<script type="text/javascript">
    $( document ).ready( function() {
        $( '#booking-table' ).DataTable({
        });
    } );
</script>
@endsection
@section('content')

<body>
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 mt-4 text-gray-800">Booking Management</h1>
        </div>

        <!-- Content Row -->

        <div class="row my-4">
            <div class="col">
                <div class="list-group list-group-horizontal text-center">
                    <a href="{{ route("home.booking.index")}}"
                        class="list-group-item list-group-item-action {{ Request::is('*booking') ? 'active' : '' }}">
                        Pending requests
                        <span class="badge badge-danger">{{$pending_count}}</span>
                    </a>
                    <a href="{{ route("home.booking.status" , [ 'status' => 'accept' ]) }}"
                        class="list-group-item list-group-item-action {{ Request::is('*booking/status/accept') ? 'active' : '' }}">Accepted
                        requests
                        <span class="badge badge-danger">{{$accept_count}}</span></a>
                    <a href="{{ route("home.booking.status" , [ 'status' => 'decline' ]) }}"
                        class="list-group-item list-group-item-action {{ Request::is('*booking/status/decline') ? 'active' : '' }}">Declined
                        requests
                        <span class="badge badge-danger">{{$decline_count}}</span></a>
                    <a href="{{ route("home.booking.status" , [ 'status' => 'cancel' ]) }}"
                        class="list-group-item list-group-item-action {{ Request::is('*booking/status/cancel') ? 'active' : '' }}">Cancelled
                        requests
                        <span class="badge badge-danger">{{$cancel_count}}</span>
                    </a>
                </div>
            </div>
        </div>
        @if (\Session::has('success'))
        <div class="alert alert-danger">
            <ul>
                <li>{!! \Session::get('success') !!}</li>
            </ul>
        </div>
        @endif
        <div class="row" id="search-result">
            <div class="col">
                @if($booking->count() <= 0) <div>
                    Nothing here
            </div>
            @else
            <table class="table table-hover table-bordered" id="booking-table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">ID</th>
                        <th scope="col">Room</th>
                        <th scope="col">Status</th>
                        <th scope="col">Requested by</th>
                        <th scope="col">Faculty</th>
                        <th scope="col">Event name</th>
                        <th scope="col">Event type</th>
                        <th scope="col">Book time</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($booking as $key=>$item)
                    @php 
                    if($item->approvalStatus == 'accept')
                        $status = '<span class="badge badge-success">Accepted</span>';
                    elseif($item->approvalStatus == 'cancel')
                        $status = '<span class="badge badge-danger">Cancelled</span>';
                    elseif($item->approvalStatus == 'decline')
                        $status = '<span class="badge badge-danger">Declined</span>';
                    elseif($item->approvalStatus == 'pending')
                            $status = '<span class="badge badge-info">Pending</span>';
                    elseif($item->approvalStatus == 'revise')
                        $status = '<span class="badge badge-warning">Need revision</span>';
                    @endphp
                    <tr>
                        <th>{{$key+1}}</th>
                        <td>{{$item->id}}</td>
                        <th scope="row">{{$item->facilities->facilId}}</th>
                        <td>{!!$status!!}</td>
                        <td>{{$item->requestorName}}</td>
                        <td>{{$item->requestorFacility}}</td>
                        <td>{{ empty($item->eventName) ? '-' : $item->eventName  }} </td>
                        <td>{{ empty($item->eventType) ? '-' : $item->eventType  }} </td>
                        <td>{{$item->bookDate->format('d M Y')}} from {{$item->bookTime}} for {{$hours = intdiv($item->bookDuration, 60).' hours and '. ($item->bookDuration % 60).' minutes'}}</td>
                        @if($item->approvalStatus == 'pending')
                            <td>
                                <a href="#accept" class="btn btn-success btn-sm approval-btn accept-btn" data-toggle="modal"
                                    data-target="#approvalModal-{{$item->id}}"><i class="far fa-check-circle"></i>
                                    Approve</a>
                                <!-- Approval Modal -->
                                <div class="modal fade" id="approvalModal-{{$item->id}}" tabindex="-1" role="dialog"
                                    aria-labelledby="approvalModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-lg" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div id="bookInfo-{{$item->id}}">
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
                                                    <hr />
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
                                                        <strong>Booked room capacity:</strong>
                                                        {{floor($item->facilities->capacity*$dataMasterConfigs['facilityCapacity']->configValue/100)}}
                                                    </p>
                                                    <p>
                                                        <strong>Booking time:</strong>
                                                        {{$item->bookDate->format('d M Y')}} at {{$item->bookTime}} <b>until</b> {{date('d M Y',strtotime($item->bookEnd))}} at {{date('H:i',strtotime($item->bookEnd))}} <b>for</b> {{$hours = intdiv($item->bookDuration, 60).' hours and '. ($item->bookDuration % 60).' minutes'}}
                                                    </p>
                                                    <p>
                                                    <strong>Booking End:</strong>
                                                    {{date('d M Y H:i',strtotime($item->bookEnd))}}
                                                    </p>
                                                    <p>
                                                        <strong>Equipments:</strong>
                                                        @if(!is_null($item->equipments) && $item->equipments !== 'null')
                                                        @php
                                                        $equipments = json_decode($item->equipments);
                                                        $equipments = !is_null($equipments) ? implode(', ', $equipments) : '-';
                                                        @endphp
                                                        {{$equipments}}
                                                        @else
                                                        -
                                                        @endif
                                                    </p>
                                                    <p>
                                                        <strong>Additional requests:</strong>
                                                        {{$item->bookReason}}
                                                    </p>
                                                    <p>
                                                        <strong>Requestor Capacity:</strong>
                                                        {{$item->requestorCapacity}}
                                                    </p>
                                                    <hr />
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
                                                    <hr />
                                                    <p>
                                                        <strong>Requested on:</strong>
                                                        {{$item->created_at}}
                                                    </p>
                                                </div>

                                                <div><strong>Approver's note:</strong></div>
                                                <form method="post" action="{{route('home.booking.accept')}}">
                                                    @csrf
                                                    <div class="form-row my-2">
                                                        <div class="col">
                                                            <div class="input-group">
                                                                <input type="hidden" name="id" value="{{$item->id}}">
                                                                <textarea class="form-control"
                                                                    placeholder="Reason (if necessary...)" name="reason"
                                                                    rows="5"></textarea>
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

                                <a href="#decline" class="btn btn-danger btn-sm approval-btn decline-btn"
                                    id="form-{{$item->id}}" data-toggle="modal" data-target="#declineModal-{{$item->id}}"><i
                                        class="far fa-times-circle"></i> Decline</a>
                                <!-- Decline Modal -->
                                <div class="modal fade" id="declineModal-{{$item->id}}" tabindex="-1" role="dialog"
                                    aria-labelledby="declineModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-lg" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
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
                                                <hr />
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
                                                    <strong>Booked room capacity:</strong>
                                                    {{floor($item->facilities->capacity*$dataMasterConfigs['facilityCapacity']->configValue/100)}}
                                                </p>
                                                <p>
                                                    <strong>Booking time:</strong>
                                                    {{$item->bookDate->format('d M Y')}} at {{$item->bookTime}} <b>until</b> {{date('d M Y',strtotime($item->bookEnd))}} at {{date('H:i',strtotime($item->bookEnd))}} <b>for</b> {{$hours = intdiv($item->bookDuration, 60).' hours and '. ($item->bookDuration % 60).' minutes'}}
                                                </p>
                                                <p>
                                                <strong>Booking End:</strong>
                                                {{date('d M Y H:i',strtotime($item->bookEnd))}}
                                                </p>
                                                <p>
                                                    <strong>Equipments:</strong>
                                                    @if(!is_null($item->equipments) && $item->equipments !== 'null')
                                                    @php
                                                    $equipments = json_decode($item->equipments);
                                                    $equipments = !is_null($equipments) ? implode(', ', $equipments) : '-';
                                                    @endphp
                                                    {{$equipments}}
                                                    @else
                                                    -
                                                    @endif
                                                </p>
                                                <p>
                                                    <strong>Additional requests:</strong>
                                                    {{$item->bookReason}}
                                                </p>
                                                <p>
                                                    <strong>Requestor Capacity:</strong>
                                                    {{$item->requestorCapacity}}
                                                </p>
                                                <hr />
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
                                                <hr />
                                                <p>
                                                    <strong>Requested on:</strong>
                                                    {{$item->created_at}}
                                                </p>
                                                <div><strong>Approver's note:</strong></div>
                                                <form method="post" action="{{route('home.booking.decline')}}">
                                                    @csrf
                                                    <div class="form-row my-2">
                                                        <div class="col">
                                                            <div class="input-group">
                                                                <input type="hidden" name="id" value="{{$item->id}}">
                                                                <textarea class="form-control"
                                                                    placeholder="Reason (if necessary...)" name="reason"
                                                                    rows="5"></textarea>
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
                                <a href="{{ route("home.booking.file", [ 'id' => $item->id ]) }}" aria-prompt="{{ $item->id }}"
                                    class="btn btn-info btn-sm pdf-btn" data-toggle="modal" data-target="#pdfModal"><i
                                        class="fas fa-file-alt"></i> Check file</a>
                            </td>
                        @elseif($item->approvalStatus == 'accept')
                            <td>
                                <a href="#cancel" class="btn btn-danger btn-sm"
                                    id="form-{{$item->id}}" data-toggle="modal" data-target="#cancelModal-{{$item->id}}"><i
                                        class="far fa-times-circle"></i> Cancel</a>
                                <!-- Decline Modal -->
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
                                                <hr />
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
                                                    <strong>Booked room capacity:</strong>
                                                    {{floor($item->facilities->capacity*$dataMasterConfigs['facilityCapacity']->configValue/100)}}
                                                </p>
                                                <p>
                                                    <strong>Booking time:</strong>
                                                    {{$item->bookDate->format('d M Y')}} at {{$item->bookTime}} <b>until</b> {{date('d M Y',strtotime($item->bookEnd))}} at {{date('H:i',strtotime($item->bookEnd))}} <b>for</b> {{$hours = intdiv($item->bookDuration, 60).' hours and '. ($item->bookDuration % 60).' minutes'}}
                                                </p>
                                                <p>
                                                <strong>Booking End:</strong>
                                                {{date('d M Y H:i',strtotime($item->bookEnd))}}
                                                </p>
                                                <p>
                                                    <strong>Equipments:</strong>
                                                    @if(!is_null($item->equipments) && $item->equipments !== 'null')
                                                    @php
                                                    $equipments = json_decode($item->equipments);
                                                    $equipments = !is_null($equipments) ? implode(', ', $equipments) : '-';
                                                    @endphp
                                                    {{$equipments}}
                                                    @else
                                                    -
                                                    @endif
                                                </p>
                                                <p>
                                                    <strong>Additional requests:</strong>
                                                    {{$item->bookReason}}
                                                </p>
                                                <p>
                                                    <strong>Requestor Capacity:</strong>
                                                    {{$item->requestorCapacity}}
                                                </p>
                                                <hr />
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
                                                <hr />
                                                <p>
                                                    <strong>Requested on:</strong>
                                                    {{$item->created_at}}
                                                </p>
                                                <div><strong>Approver's note:</strong></div>
                                                <form method="post" action="{{route('home.booking.cancel')}}">
                                                    @csrf
                                                    <div class="form-row my-2">
                                                        <div class="col">
                                                            <div class="input-group">
                                                                <input type="hidden" name="id" value="{{$item->id}}">
                                                                <textarea class="form-control"
                                                                    placeholder="Reason (if necessary...)" name="reason"
                                                                    rows="5"></textarea>
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
                                <a href="{{ route("home.booking.file", [ 'id' => $item->id ]) }}" aria-prompt="{{ $item->id }}"
                                    class="btn btn-info btn-sm pdf-btn" data-toggle="modal" data-target="#pdfModal"><i
                                        class="fas fa-file-alt"></i> Check file</a>
                            </td>
                        @else
                            <td>
                                <a href="#info" class="btn btn-info btn-sm" data-toggle="modal"
                                    data-target="#infoModal-{{$item->id}}"><i class="fas fa-info-circle"></i> Details</a>
                                <!-- INfo Modal -->
                                <div class="modal fade" id="infoModal-{{$item->id}}" tabindex="-1" role="dialog"
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
                                                <hr />
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
                                                    <strong>Booked room capacity:</strong>
                                                    {{floor($item->facilities->capacity*$dataMasterConfigs['facilityCapacity']->configValue/100)}}
                                                </p>
                                                <p>
                                                    <strong>Booking time:</strong>
                                                    {{$item->bookDate->format('d M Y')}} at {{$item->bookTime}} <b>until</b> {{date('d M Y',strtotime($item->bookEnd))}} at {{date('H:i',strtotime($item->bookEnd))}} <b>for</b> {{$hours = intdiv($item->bookDuration, 60).' hours and '. ($item->bookDuration % 60).' minutes'}}
                                                </p>
                                                <p>
                                                <strong>Booking End:</strong>
                                                {{date('d M Y H:i',strtotime($item->bookEnd))}}
                                                </p>
                                                <p>
                                                    <strong>Equipments:</strong>
                                                    @if(!is_null($item->equipments) && $item->equipments !== 'null')
                                                    @php
                                                    $equipments = json_decode($item->equipments);
                                                    $equipments = !is_null($equipments) ? implode(', ', $equipments) : '-';
                                                    @endphp
                                                    {{$equipments}}
                                                    @else
                                                    -
                                                    @endif
                                                </p>
                                                <p>
                                                    <strong>Additional requests:</strong>
                                                    {{$item->bookReason}}
                                                </p>
                                                <p>
                                                    <strong>Requestor Capacity:</strong>
                                                    {{$item->requestorCapacity}}
                                                </p>
                                                <hr />
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
                                                <hr />
                                                <p>
                                                    <strong>Requested on:</strong>
                                                    {{$item->created_at}}
                                                </p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            @if($item->approvalStatus == 'accept')
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
                        @endif
                    </tr>
                    @endforeach
                </tbody>
            </table>


            <!-- PDF Modal -->
            <div class="modal fade" id="pdfModal" tabindex="-1" role="dialog" aria-labelledby="pdfModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div id="book-info"></div>
                            <div id="modal-pdf"></div>
                            <strong>Revision note:</strong>
                            <form method="post" action="{{route('home.booking.prompt')}}">
                                @csrf
                                <div class="form-row my-2">
                                    <div class="col">
                                        <div class="input-group">
                                            <input type="hidden" name="id" id="prompt-id" value="{{$item->id}}">
                                            <textarea class="form-control"
                                                placeholder="Reason (if necessary...)" name="reason"
                                                rows="5"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <input type="submit" class="btn btn-warning" value="Prompt revision">
                            </form>

                        </div>
                        <div class="modal-footer">
                            {{-- <a href="#" class="btn btn-warning" id="prompt">Prompt revision</a> --}}
                        </div>
                    </div>
                </div>
            </div>



            @endif
        </div>

    </div>

    </div>

    </div>
    <script>
        $('.pdf-btn').click(function () {
            var path = $(this).attr('href');
            var embed = '<embed src="' + path + '" height="450" width="100%" id="pdf-embed">';
            // var embed = '<iframe src="' + path + '" title="description"></iframe>';
            $('#pdf-embed').remove();
            $('#modal-pdf').append(embed);
            var prompt = $(this).attr('aria-prompt');
            $('#prompt-id').val(prompt);
            var info = $('#bookInfo-'+prompt).html();
            $('#book-info').html(info);

            console.log('#bookInfo-'+prompt);
            console.log(info);
        })

        $('.accept-btn').click(function () {
            var id = $(this).attr('id');
            var action = "{{ route("home.booking.accept") }}";
            $('#reason-' + id).attr('action', action);
            $('#reason-' + id).submit();
        });


        $('.decline-btn').click(function () {
            var id = $(this).attr('id');
            var action = "{{ route("home.booking.decline") }}";
            $('#reason-' + id).attr('action', action);
            $('#reason-' + id).submit();
        });

    </script>
</body>
@endsection
