@extends('layout')
@section('content')
<body>
  <div class="container-fluid pb-5">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
      <h1 class="h3 mb-0 mt-4 text-gray-800">Find Class</h1>
    </div>

    <!-- Content Row -->

    <div class="row">

      <!-- Area Chart -->
      <div class="col-xl-12 col-lg-12">
        <div class="card shadow mb-4">
          <!-- Card Header - Dropdown -->
          <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Search</h6>
          </div>

          @if (\Session::has('warning'))
            <div class="alert alert-danger">
                <ul class="my-0">
                    <li>{!! \Session::get('warning') !!}</li>
                </ul>
            </div>
          @endif
          <!-- Card Body -->
          <form action="{{route('home.master.query')}}" method="post">
            @csrf
            <div class="card-body">
              <div class="info-area px-1" style="overflow-y:auto">
                  <div class="form-row">
                    <div class="col-xs-12 col-md-4">
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <div class="input-group-text">
                            <i class="far fa-building"></i>
                          </div>
                        </div>
                        <select class="custom-select" name="building" required>
                          <option value="" selected>Choose a room type</option>
                          @foreach($building as $item)
                            <option value="{{$item->building}}" {{ (old("building") == $item->building ? "selected":"") }}>{{$item->building}}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>

                    <div class="col-xs-12 col-md-4">
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <div class="input-group-text">
                            <i class="far fa-user"></i>
                          </div>
                        </div>
                        <input type="number" class="form-control" placeholder="Capacity"  name="capacity" value="{{ old("capacity") }}" required>
                      </div>
                    </div>

                    <div class="col-xs-12 col-md-4">
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <div class="input-group-text">
                            <i class="far fa-check-circle"></i>
                          </div>
                        </div>
                        <select class="custom-select"  name="availability">
                          <option value="" selected>All</option>
                          <option value="A">Available</option>
                          <option value="I">Not available</option>
                        </select>
                      </div>
                    </div>

                  </div>

                  <div class="form-row my-2">
                    <div class="col-xs-12 col-md-3">
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <div class="input-group-text">
                            <i class="far fa-calendar"></i>
                            &nbsp;
                            Start
                          </div>
                        </div>
                        <input type="date" class="form-control" placeholder="dd/mm/yy" name="bookStartDate" value="{{old('bookStartDate')}}" min="{{$beginDate}}" max="{{$endDate}}" required>
                        {{-- <input type="text" class="form-control" name="bookDate" value="{{old('bookDate')}}" /> --}}
                      </div>
                    </div>

                    <div class="col-xs-12 col-md-3">
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <div class="input-group-text">
                            <i class="fas fa-hourglass-half"></i>
                          </div>
                        </div>

                        <select class="custom-select" name="bookStartHour" required>
                          <option value="" {{ (old("bookStartHour") == "" ? "selected":"") }}>Hour</option>
                          @php
                            $start = strtotime($dataMasterConfigs['bookStart']->configValue);
                            $end = strtotime($dataMasterConfigs['bookEnd']->configValue);
                            $bookDuration = $dataMasterConfigs['timeSlotDuration']->configValue;
                            $duration = $bookDuration * 60;
                          @endphp

                          @for($time = $start ; $time < $end ; $time += $duration)
                          <option value="{{date("H", $time)}}" {{ (old("bookStartHour") == date("H", $time) ? "selected":"") }}>{{date("H", $time)}}</option>
                          @endfor
                        </select>
                        <select class="custom-select" name="bookStartMinute" required>
                          <option value="" {{ (old("bookStartMinute") == "" ? "selected":"") }}>Minute</option>
                          <option value="00" {{ (old("bookStartMinute") == '00' ? "selected":"") }}>00</option>
                          <option value="30" {{ (old("bookStartMinute") == '30' ? "selected":"") }}>30</option>
                        </select>
                      </div>
                    </div>

                    <div class="col-xs-12 col-md-3">
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <div class="input-group-text">
                            <i class="far fa-calendar"></i> 
                            &nbsp;
                            End
                          </div>
                        </div>
                        <input type="date" class="form-control" placeholder="dd/mm/yy" name="bookEndDate" value="{{old('bookEndDate')}}" min="{{$beginDate}}" max="{{$endDate}}" required>
                      </div>
                    </div>

                    <div class="col-xs-12 col-md-3">
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <div class="input-group-text">
                            <i class="fas fa-hourglass-half"></i>
                          </div>
                        </div>

                        <select class="custom-select" name="bookEndHour" required>
                          <option value="" {{ (old("bookEndHour") == "" ? "selected":"") }}>Hour</option>
                          @php
                            $start = strtotime($dataMasterConfigs['bookStart']->configValue);
                            $end = strtotime($dataMasterConfigs['bookEnd']->configValue);
                            $bookDuration = $dataMasterConfigs['timeSlotDuration']->configValue;
                            $duration = $bookDuration * 60;
                          @endphp

                          @for($time = $start ; $time < $end ; $time += $duration)
                          <option value="{{date("H", $time)}}" {{ (old("bookEndHour") == date("H", $time) ? "selected":"") }}>{{date("H", $time)}}</option>
                          @endfor
                        </select>
                        <select class="custom-select" name="bookEndMinute" required>
                          <option value="" {{ (old("bookEndMinute") == "" ? "selected":"") }}>Minute</option>
                          <option value="00" {{ (old("bookEndMinute") == '00' ? "selected":"") }}>00</option>
                          <option value="30" {{ (old("bookEndMinute") == '30' ? "selected":"") }}>30</option>
                        </select>
                      </div>
                    </div>
                  </div>
              </div>
            </div>
            <div class="card-footer">
              <input type="submit" class="btn btn-primary btn-block" id="search" value="Search">
            </div>
          </form>
        </div>
      </div>
    </div>

    <div class="row" id="search-result">
      <div class="col">
        <div class="list-group">
          @foreach($dataMasterFacilities as $item)
          <div class="list-group-item list-group-item-action">
            <div class="d-flex w-100 justify-content-between">
              <h5 class="mb-1">Room {{$item->facilId}}</h5>
              @if($item->status == 'A')
                <a href="{{url('/master-facility/catalogue')}}/{{$item->id}}"  class="btn btn-success">Book</a>
              @endif
            </div>
            <div class="row">
              <div class="col-2">
                <i class="far fa-building"></i> {{$item->type}}
              </div>
              <div class="col-2">
                <i class="far fa-user"></i> {{ floor($item->capacity*$dataMasterConfigs['facilityCapacity']->configValue/100) }} seats
              </div>
              <div class="col-2">
                @if($item->status == 'A')
                <i class="far fa-check-circle"></i> Available
                @else
                <i class="far fa-times-circle"></i> Not Available
                @endif
              </div>
            </div>
          </div>
          @endforeach
        </div>
      </div>
    </div>

  </div>

</body>
@endsection
{{-- @section('script')
<script>
$(function() {
  $('input[name="bookDate"]').daterangepicker({
    timePicker: true,
    timePicker24Hour: true,
    timePickerIncrement: 30,
    locale: {
      format: 'DD/MM/YYYY . HH:00'
    }
  });
});
</script>
@endsection --}}