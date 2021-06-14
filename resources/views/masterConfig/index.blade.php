@extends('layout')
@section('script')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
@endsection
@section('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
@endsection
@section('content')
<body>
    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 mt-4 text-gray-800">Configuration</h1>
        </div>

        <div class="card shadow mb-4">
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form action="{{route('home.config.store')}}" method="post">
                    @csrf
                    @foreach($configs as $config)
                    @php
                        if($config->configName == 'days'){
                            $days = json_decode($config->configValue);
                            $days = is_null($days) ? array() : $days;
                            continue;
                        }elseif($config->configName == 'activeTerm'){
                            continue;
                        }
                    @endphp
                    <div class="form-group">
                      <label>{{ $config->configDesc }}</label>
                        <textarea class="form-control" name="{{$config->configName}}">{{$config->configValue}}</textarea>
                        <small class="form-text text-muted float-right">{{$config->configName}}</small>
                    </div>
                    @endforeach

                    <div class="form-group">
                        <label>Active Term</label>
                        <select class="form-control" id="activeTerm" name="activeTerm">
                            <option {{ is_null($activeTerm) ? 'selected': ''}} value="">Select active term</option>
                            @foreach($terms as $term)
                                <option value="{{$term->term}}" {{ $term->term == $activeTerm ? 'selected': ''}}>
                                    {{$term->term}} {{$term->description}} ({{$term->beginDate}} s/d {{$term->endDate}})
                                </option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted float-right">activeTerm</small>
                    </div>
  

                    <div class="mb-2">
                        <label>Day Availability</label>

                        <div class="form-row">
                            <div class="col">
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

                    <input type="submit" class="btn btn-primary" value="Submit">
                  </form>
            </div>
        </div>
    </div>
    <script>
         $(document).ready(function() {
          $('#activeTerm').select2();
        });
    </script>
</body>
@endsection
