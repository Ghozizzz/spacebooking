@extends('layout')
@section('content')
<body>
  <div class="container-fluid">

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
          <!-- Card Body -->
          <form action="{{route('home.master.query')}}" method="post">
            @csrf
            <div class="card-body">
              <div class="info-area px-1" style="overflow-y:auto">
                  <div class="form-row">
                    <div class="col-xs-12 col-md-6">
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <div class="input-group-text">
                            <i class="far fa-building"></i>
                          </div>
                        </div>
                        <select class="custom-select" name="building">
                          <option value="" selected>Choose a room type</option>
                          @foreach($building as $item)
                            <option value="{{$item->building}}">{{$item->building}}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>

                    <div class="col-xs-12 col-md-6">
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <div class="input-group-text">
                            <i class="far fa-user"></i>
                          </div>
                        </div>
                        <input type="number" class="form-control" placeholder="Capacity"  name="capacity">
                      </div>
                    </div>

                  </div>

                  <div class="form-row my-2">
                    <div class="col-xs-12 col-md-6">
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <div class="input-group-text">
                            <i class="far fa-calendar"></i>
                          </div>
                        </div>
                      {{-- <input type="date" class="form-control" placeholder="dd/mm/yy" name="bookDate" value="{{old('bookDate')}}" min="{{$beginDate}}" max="{{$endDate}}" required> --}}
                        <input type="text" class="form-control" name="bookDate" value="{{old('bookDate')}}" />
                      </div>
                    </div>

                    <div class="col-xs-12 col-md-6">
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
@section('script')
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
@endsection