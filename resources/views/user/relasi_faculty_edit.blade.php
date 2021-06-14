@extends('layout')
@section('css')
<link rel="stylesheet" href="{{ asset('/css/lightbox.min.css') }}">
@endsection
@section('content')
<body>
  <!-- Page Heading -->
  @if($errors->any())
  {!! implode('', $errors->all('
    <div class="alert alert-danger my-2 text-center" role="alert">
      :message
    </div>
  ')) !!}
  @endif
    <div class="container-fluid mt-5">
        <div class="row">
          <div class="col-md-12">
            <div class="d-sm-flex align-items-center justify-content-between mb-2 px-1">
              <h1 class="h3 mb-0 mt-3 text-gray-800">Detail User</h1>
            </div>
            <div class="d-flex flex-row bd-highlight mb-2">
              <div class="p-2 bd-highlight mr-3"><i class="far fa-user" style="color:green"></i> Email : {{$dataUser->email}}</div>
              <div class="p-2 bd-highlight mr-3"><i class="far fa-building"></i> Name : {{$dataUser->name}}</div>
              <div class="p-2 bd-highlight mr-3"><i class="far fa-user"></i> Phone : {{$dataUser->phone}}</div>
            </div>
          </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <hr />
                <form action="{{ route('home.user.faculty.update') }}" method="post">
                  @csrf
                  <input type="hidden" id="id" name="id" value="{{ Request::segment(3) }}">
                <div class="mb-2">
                    <h4 class="mb-0 my-2 text-gray-800">Faculty Role</h4>
                    <select class="js-example-basic-multiple" name="faculty[]" multiple="multiple" style="width: 100%;">
                      @php
                      $faculty_value = [];
                      if(empty($dataUser->faculty_value)){
                        $faculty_value = [];
                      }else{
                        $faculty_value = explode(";", $dataUser->faculty_value);
                      }
                      $faculties = explode(";", $dataMasterConfigs['masterFaculty']->configValue);
                      @endphp
                      @foreach($faculties as $faculty)
                        <option value="{{$faculty}}"  
                            <?php 
                            if(in_array($faculty, $faculty_value)){
                                echo 'selected';
                            }
                            ?>
                        >{{$faculty}}</option>
                      @endforeach
                    </select>
                </div>

                <hr />

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
@section('script')
<script type="text/javascript">
$(document).ready(function() {
    $('.js-example-basic-multiple').select2();
});
</script>
@endsection