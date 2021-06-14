@extends('layout')
@section('css')
<link rel="stylesheet" href="{{ asset('/css/lightbox.min.css') }}">
@endsection
@section('content')
<body>
    <div class="container-fluid py-3">
        <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
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
		        <a href="{{route('home.master.facility.image.delete', ['id' => $dataMasterFacilities->id, 'img' => $image['id']])}}" class="btn btn-block btn-danger my-1">Delete</a>
              </div>
              @endforeach
              </div>
            </div>
            @endforeach
          </div>
          <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
          </a>
          <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
          </a>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row my-3">
            <div class="col-md-12">
                <form action="{{ route('home.master.facility.image') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <label for="scheduleFile">Upload new image</label>
                    <input type="hidden" name="id" value="{{$dataMasterFacilities->id}}">
                    <input type="file" name="file" class="form-control-file" required>
                    <button class="btn btn-primary btn-block my-2">Submit</button>
                    {{-- <a class="btn btn-warning" href="{{ route('MasterFacility.export') }}">Export User Data</a>
                    --}}
                </form>
            </div>
            @if($errors->has('file'))
                <div class="alert alert-warning" role="alert">
                    File Yang di upload terlalu besar atau tidak sesuai
                </div>
            @endif
        </div>

        <div class="row">
            <div class="col-md-9">
                <div class="d-sm-flex align-items-center justify-content-between mb-2 px-1">
                    <h1 class="h3 mb-0 mt-3 text-gray-800">{{$dataMasterFacilities->facilId}}</h1>
                </div>
                <div class="d-flex flex-row bd-highlight mb-2">
                    @if($dataMasterFacilities->status == 'A')
                        <div class="p-2 bd-highlight mr-3"><i class="far fa-check-circle" style="color:green"></i> Available</div>
                    @else
                        <div class="p-2 bd-highlight mr-3"><i class="far fa-times-circle" style="color:red"></i> Not Available</div>
                    @endif
                    <div class="p-2 bd-highlight mr-3"><i class="far fa-building"></i> {{$dataMasterFacilities->type}}
                    </div>
                    <div class="p-2 bd-highlight mr-3"><i class="far fa-user"></i> {{$dataMasterFacilities->capacity}}
                        seats</div>
                </div>
            </div>
            <div class="col-md-3">
                {{-- {{ route('booking.confirm', ['code' => $dataMasterFacilities->qrcode]) }} --}}
                @if(!is_null($dataMasterFacilities->qrcode))
                {!! QrCode::size(100)->generate(route('booking.confirm', ['code' => $dataMasterFacilities->qrcode])); !!}
                @endif
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <hr />
                <form action="{{ route('home.master.facility.update') }}" method="post">
                  @csrf
                <div class="mb-2">
                    <h4 class="mb-0 my-2 text-gray-800">Description</h4>
                    <input type="hidden" name="id" value="{{$dataMasterFacilities->id}}">
                    <textarea class="form-control" name="room_desc" rows="5"
                        placeholder="Room description">{{$dataMasterFacilities->room_desc}}</textarea>
                </div>

                <hr />

                <div class="mb-2">
                    <h4 class="mb-0 my-2 text-gray-800">Day & Time Availability</h4>
                    <div class="form-row">
                        <div class="col">
                          @php
                            $days = json_decode($dataMasterFacilities->days);
                            $days = is_null($days) ? array() : $days;
                          @endphp
                            <div class="form-check">
                                <input class="form-check-input checkbox-days" type="checkbox" value="monday" name="days[]" id="monday" {{ in_array('monday', $days) ? 'checked' : ''}}>
                                <label class="form-check-label" for="monday">
                                    Monday
                                </label>
                            </div>

                            <div class="form-check">
                                <input class="form-check-input checkbox-days" type="checkbox" value="tuesday" name="days[]"
                                    id="tuesday" {{ in_array('tuesday', $days) ? 'checked' : ''}}>
                                <label class="form-check-label" for="tuesday">
                                    Tuesday
                                </label>
                            </div>
                        </div>

                        <div class="col">
                            <div class="form-check">
                                <input class="form-check-input checkbox-days" type="checkbox" value="wednesday" name="days[]"
                                    id="wednesday" {{ in_array('wednesday', $days) ? 'checked' : ''}}>
                                <label class="form-check-label" for="wednesday">
                                    Wednesday
                                </label>
                            </div>

                            <div class="form-check">
                                <input class="form-check-input checkbox-days" type="checkbox" value="thursday" name="days[]"
                                    id="thursday" {{ in_array('thursday', $days) ? 'checked' : ''}}>
                                <label class="form-check-label" for="thursday">
                                    Thursday
                                </label>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-check">
                                <input class="form-check-input checkbox-days" type="checkbox" value="friday" name="days[]" id="friday" {{ in_array('friday', $days) ? 'checked' : ''}}>
                                <label class="form-check-label" for="friday">
                                    Friday
                                </label>
                            </div>

                            <div class="form-check">
                                <input class="form-check-input checkbox-days" type="checkbox" value="saturday" name="days[]"
                                    id="saturday" {{ in_array('saturday', $days) ? 'checked' : ''}}>
                                <label class="form-check-label" for="saturday">
                                    Saturday
                                </label>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-check">
                                <input class="form-check-input checkbox-days" type="checkbox" value="sunday" name="days[]" id="sunday" {{ in_array('sunday', $days) ? 'checked' : ''}}>
                                <label class="form-check-label" for="sunday">
                                    Sunday
                                </label>
                            </div>

                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="all" name="allday" id="all">
                                <label class="form-check-label" for="all">
                                    All day
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <hr />

                <div class="mb-2">
                    <div class="form-row">
                        <div class="col">
                            <label>Allow user to book this room from:</label>
                            <input type="text" class="form-control" name="start_time" placeholder="00:00" value="{{$dataMasterFacilities->start_time}}">
                        </div>
                        <div class="col">
                            <label>Allow user to book this room until:</label>
                            <input type="text" class="form-control" name="end_time" placeholder="23:00" value="{{$dataMasterFacilities->end_time}}">
                        </div>
                    </div>
                </div>

                <div class="mb-2">
                  <input type="submit" value="Save" class="btn btn-block btn-success my-3">
                </div>
              </form>

            </div>
        </div>

    </div>
    <script src="{{asset('js/lightbox.min.js')}}"></script>
</body>
@endsection
