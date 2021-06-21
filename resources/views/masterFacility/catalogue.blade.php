@extends('layout')
@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js" integrity="sha256-4iQZ6BVL4qNKlQ27TExEhBN1HFPvAvAMbFavKKosSWQ=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
<script>
  $(document).ready(function () {
    $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    var calendar = $('#calendar').fullCalendar({
        events: "{{ route('index.calender', ['id' => $dataMasterFacilities->id]) }}",
        displayEventTime: true,
        editable: false,
        eventBackgroundColor: 'red',
        eventTextColor: 'white',
        eventRender: function (event, element, view) {
            if (event.allDay === 'true') {
                event.allDay = true;
            } else {
                event.allDay = false;
            }

//	    if(event.start.isAfter(event.beginDate) && event.end.isBefore(event.endDate)){
//		return true;
//	    }else{
//		return false;
//	   }
        },
        selectable: true,
        selectHelper: true,
        timeFormat: 'H:mm',
        displayEventEnd : true,
        validRange: {
          start: '{{$beginDate}}',
          end: '{{$endDate}}'
        }
      })
    })
</script>
@endsection
@section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.css" />
<link rel="stylesheet" href="{{ asset('/css/lightbox.min.css') }}">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />

<style>
    .fc-day-grid-event > .fc-content {
        white-space: normal;
    }
</style>
@endsection
@section('content')
<body>
  <!-- Page Heading -->
  @if($errors->any())
    <div class="alert alert-danger alert-dismissible m-2 text-center" role="alert">
  {!! implode('', $errors->all('
      :message
  <br/>')) !!}
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
    </div>
  @endif
    <div class="container-fluid py-3">
        <div id="galleryControls" class="carousel slide" data-interval="false">
          <div class="carousel-inner">
            @php 
              $galleries = array_chunk($dataFacilityImages->toArray(), 3);
            @endphp

            @foreach($galleries as $key => $gallery)
            <div class="carousel-item {{$key == 0 ? 'active' : ''}}">
              <div class="row">
              @foreach($gallery as $image)
              <div class="col-md-4">
                <a href="{{asset($image['image'])}}" data-lightbox="gallery">
                <img class="img-fluid rounded-lg" src="{{asset($image['image'])}}">
                </a>
              </div>
              @endforeach
              </div>
            </div>
            @endforeach
          </div>
          <a class="carousel-control-prev" href="#galleryControls" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
          </a>
          <a class="carousel-control-next" href="#galleryControls" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
          </a>
        </div>
        
    </div>
    <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="d-sm-flex align-items-center justify-content-between mb-2 px-1">
              <h1 class="h3 mb-0 mt-3 text-gray-800">{{$dataMasterFacilities->description}}</h1>
            </div>
            <div class="d-flex flex-row bd-highlight mb-2">
              <div class="p-2 bd-highlight mr-3"><i class="far fa-check-circle" style="color:green"></i> Available
              </div>
              <div class="p-2 bd-highlight mr-3"><i class="far fa-building"></i> {{$dataMasterFacilities->type}}</div>
              <div class="p-2 bd-highlight mr-3"><i class="far fa-user"></i> {{floor($dataMasterFacilities->capacity*$dataMasterConfigs['facilityCapacity']->configValue/100)}} seats</div>
            </div>
            <div class="d-flex flex-row bd-highlight mb-2">
              <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#dateModal">
                <i class="fas fa-calendar-alt"></i> See available dates
              </a>
            </div>

            <hr />

            <div class="mb-2">
              <h4 class="mb-0 my-2 text-gray-800">Description</h4>
              <p class="lead">
                @if(is_null($dataMasterFacilities->room_desc))
                The classroom is set up with learner in mind, it is full of teacher made activities and
                bulletin boards where the learner could activity demonstrate their knowledge about any subject matter
                by answering the questions posted on the boards. The boards are also displayed with historical,
                grammatical, and scientific facts.
                @else
                {{$dataMasterFacilities->room_desc}}
                @endif
              </p>
            </div>

            <hr />

            @if (\Session::has('warning'))
              <div class="alert alert-danger">
                  <ul class="my-0">
                      <li>{!! \Session::get('warning') !!}</li>
                  </ul>
              </div>
            @endif
            <form action="{{route('home.master.book')}}" method="post" enctype="multipart/form-data" id="bookingForm">
              @csrf
              <div class="form-row mb-2">
                <div class="col">
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <div class="input-group-text">
                        <i class="far fa-calendar"></i>
                      </div>
                    </div>
                    <input type="text" class="form-control" name="bookDate" id="bookDate" value="{{old('bookDate')}}" />
                    <input type="hidden" name="masterFacilityId" value="{{$dataMasterFacilities->id}}">
                  </div>
                </div>

                <div class="col">
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <div class="input-group-text">
                        <i class="fas fa-file-upload"></i>
                      </div>
                    </div>
                    <div class="custom-file">
                      <input type="file" class="custom-file-input" id="file" name="file" accept="application/pdf,application/zip" required>
                      <label class="custom-file-label" for="file" id="fileLabel">Upload file</label>
                    </div>
                  </div>
                </div>

              </div>

              <div class="form-row mb-2">
                <div class="col">
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <div class="input-group-text">
                        <i class="fas fa-chalkboard"></i>
                      </div>
                    </div>
                    <input type="text" class="form-control" placeholder="Event name" name="eventName" id="eventName" required>
                  </div>
                </div>

                <div class="col">
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <div class="input-group-text">
                        <i class="fas fa-chalkboard-teacher"></i>
                      </div>
                    </div>
                    <select class="custom-select" name="eventType" id="eventType" required>
                      <option selected value="">Event type</option>
                      @php
                      $events = explode(";", $dataMasterConfigs['masterEvent']->configValue);
                      @endphp
                      @foreach($events as $event)
                      <option value="{{$event}}">{{$event}}</option>
                      @endforeach
                    </select>
                  </div>
                </div>

                <div class="col">
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <div class="input-group-text">
                        <i class="fas fa-phone"></i>
                      </div>
                    </div>
                    <input type="text" class="form-control" placeholder="Phone number" name="requestorPhone" id="requestorPhone" required>
                  </div>
                </div>

                <div class="col">
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <div class="input-group-text">
                        <i class="fas fa-graduation-cap"></i>
                      </div>
                    </div>
                    <select class="custom-select" name="requestorFacility" id="requestorFacility" required>
                      <option selected value="">Faculty/Unit</option>
                      @php
                      $faculties = explode(";", $dataMasterConfigs['masterFaculty']->configValue);
                      @endphp
                      @foreach($faculties as $faculty)
                      <option value="{{$faculty}}">{{$faculty}}</option>
                      @endforeach
                    </select>
                  </div>
                </div>

              </div>
              <div class="form-row my-2">
                <div class="col">
                  <div class="input-group">
                    Select equipments:
                    <select class="equipments-multiple" name="equipments[]" multiple="multiple" style="width:100%" id="equipments">
                      @foreach($dataMasterEquipments as $equip)
                        <option>{{$equip->descr}}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
              </div>

              <div class="form-row my-2">
                <div class="col">
                  <div class="input-group">
                    <textarea class="form-control" placeholder="Additional request..." name="bookReason" id="bookReason"></textarea>
                  </div>
                </div>
              </div>

              <div class="form-row">
                <div class="col">
                  <div class="input-group">
                    <a href="#" class="btn btn-primary btn-block" id="summaryBtn">Confirm booking</a>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="dateModal" tabindex="-1" role="dialog" aria-labelledby="dateModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="dateModalLabel">Calendar view</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="calendar shadow bg-white p-5 my-2">
                  <div id='calendar'></div>  
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              </div>
            </div>
          </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="summaryModal" tabindex="-1" role="dialog" aria-labelledby="summaryModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="summaryModalLabel">Booking Summary</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <p>
                  <strong>Booked room:</strong> <span id="summaryRoom">{{$dataMasterFacilities->description}}</span>
                </p>
                <p>
                  <strong>Booked time:</strong> <span id="summaryDatetime"></span>
                </p>
                <p>
                  <strong>Requested equipment(s):</strong> <span id="summaryEquipment"></span>
                </p>
                <p>
                  <strong>Additional request(s):</strong> <span id="summaryRequest"></span>
                </p>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" id="bookBtn">Book</button>
              </div>
            </div>
          </div>
        </div>
      </div>
      <script src="{{asset('js/lightbox.min.js')}}"></script>
      
      <script>
        $(function() {
          $('input[name="bookDate"]').daterangepicker({
            timePicker: true,
            timePicker24Hour: true,
            timePickerIncrement: 60,
            locale: {
              format: 'DD/MM/YYYY . HH:00'
            }
          });
        });

        $("#file").on('change', function() {
         var fullPath = this.value;
         var filename = fullPath.replace(/^.*[\\\/]/, '')
         $("#fileLabel").text(filename);
        });

        $(document).ready(function() {
          $('.equipments-multiple').select2();
        });

        $('#summaryBtn').click(function(){
          if($('#eventName').val()==''){
            alert('Please Fill Event Name');
          }else if($('#eventType').val()==''){
            alert('Please Choose Event Type');
          }else if($('#requestorPhone').val()==''){
            alert('Please Fill Phone Number');
          }else if($('#requestorFacility').val()==''){
            alert('Please Choose Facult/Unit');
          }else if($('#bookDate').val()==''){
            alert('Please Fill Date');
          }else{
            var date = $('#bookDate').val();

            var bD = date.split('-');

            var bstart = bD[0].split('.');
            var bend = bD[1].split('.');

            var bs = bstart[0];
            var bookStart = bs+bstart[1].replaceAll(' ','');

            var be = bend[0];
            var bookEnd = be+bend[1].replaceAll(' ','');
            var ms = moment(bookEnd,"DD/MM/YYYY HH:mm").diff(moment(bookStart,"DD/MM/YYYY HH:mm"));
            var d = moment.duration(ms);
            var s = Math.floor(d.asHours()) + moment.utc(ms).format(":mm");

            var datetime = bs+", at "+bstart[1]+" for "+s+" hours";
            $('#summaryDatetime').text(datetime);

            var equipments = $('#equipments').val().join(", ");
            $('#summaryEquipment').text(equipments);

            var request = $('#bookReason').val();
            $('#summaryRequest').text(request);

            $('#summaryModal').modal('show');
          }
        });

        $('#bookBtn').click(function(){
          $("#bookingForm").submit();
        });
      </script>
</body>
@endsection
