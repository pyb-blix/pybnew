@extends('admin.template')

@section('main')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Edit {{ $main_title }}
    </h1>
    <ol class="breadcrumb">
      <li><a href="{{url('admin/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="{{url('admin/home_cities')}}">{{ $main_title }}</a></li>
      <li class="active">Edit</li>
    </ol>
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="row">
      <!-- right column -->
      <div class="col-md-8 col-sm-offset-2">
        <!-- Horizontal Form -->
        <div class="box box-info">
          <div class="box-header with-border">
            <h3 class="box-title">Edit {{ $main_title }} Form</h3>
          </div>
          <!-- /.box-header -->
          <!-- form start -->
          {!! Form::open(['url' => route('home_cities.update',['id' => $result->id]), 'class' => 'form-horizontal', 'files' => true,'id'=>'form']) !!}
          @method('PUT')
          <div class="box-body">
            <span class="text-danger">(*)Fields are Mandatory</span>

            <div class="multiple_lang">

              <div class="form-group">
                <label for="input_status" class="col-sm-3 control-label">Language<em class="text-danger">*</em></label>
                <div class="col-sm-6">
                 @foreach($language as $lang)
                  @php
                    $val[$lang->value]= $lang->name;
                  @endphp 
                 @endforeach
                 {!! Form::select('lang_code[]', $val, 'en', ['class' => 'form-control go','id'=>'lang_1']) !!}
               </div>
             </div>

             <div class="form-group">
                <label for="input_display_name" class="col-sm-3 control-label"> Display Name <em class="text-danger">*</em> </label>
                <div class="col-sm-6">
                  {!! Form::text('display_name', $result->display_name, ['class' => 'form-control name-input', 'id' => 'input_display_name', 'placeholder' => 'City Address','required']) !!}
                  {!! Form::hidden('latitude',$result->latitude,['id' => 'input_latitude']) !!}
                  {!! Form::hidden('longitude',$result->longitude,['id' => 'input_longitude']) !!}
                  <span class="text-danger">{{ $errors->first('display_name') }}</span>
                </div>
              </div>

             <div class="form-group">
              <label for="input_name" class="col-sm-3 control-label">City Name<em class="text-danger">*</em></label>
              <div class="col-sm-6">
                {!! Form::text('name[]', $result->name, ['class' => 'form-control', 'id' => 'input_name', 'placeholder' => 'City Name','required']) !!}
                <span class="text-danger">{{ $errors->first('name') }}</span>
              </div>
            </div>

          </div>

          <div class="multiple_lang_add">

            @php $i=2 @endphp 
            @foreach($langresult as $langs)

            <div class="multiple_lang">  
              <input type="hidden" value="{{ $langs->id }}" id ="property_type_id" name="lang_id[]"">

              <div class="form-group">
                <label for="input_status" class="col-sm-3 control-label">Language<em class="text-danger">*</em></label>
                <div class="col-sm-6">
                 @foreach($language as $lang)
                 @php $val[$lang->value]= $lang->name; @endphp 
                 @endforeach

                 {!! Form::select('lang_code[]', $val, $langs->lang_code, ['class' => 'form-control go','id'=>'lang_'.$i]) !!}
               </div>
             </div>

             <button type="button" class="btn btn-danger remove_lang" style="float:right;">Remove</button>
             <div class="form-group">
              <label for="input_name" class="col-sm-3 control-label">City Name<em class="text-danger">*</em></label>
              <div class="col-sm-6">
                {!! Form::text('name[]',$langs->name, ['class' => 'form-control name-input', 'id' => 'input_name_'.$i, 'placeholder' => 'Name','required']) !!}  

              </div>
            </div>

          </div>
          @php $i++; @endphp 
          @endforeach

        </div>
        <input type="hidden" id="increment" value="{{ count($langresult) + 1 }}"> 
        <div class="form-group" style="float:right;margin-right: 10px;">
          <button type="button" class="btn btn-primary add_lang_city" >Add</button>
        </div>

        <div class="form-group">
          <label for="input_description" class="col-sm-3 control-label">Image<em class="text-danger">*</em></label>
          <div class="col-sm-6">
            {!! Form::file('images', ['class' => 'form-control', 'id' => 'input_image']) !!}
            <span class="text-danger">{{ $errors->first('images') }}</span><br>
            <img src="{{ $result->image_url }}" height="100" width="200">
          </div>
        </div>
      </div>
      <!-- /.box-body -->
      <div class="box-footer">
        <button type="submit" class="btn btn-info pull-right" name="submit" value="submit">Submit</button>
        <a href="{{ route('home_cities') }}" class="btn btn-default pull-left"> Cancel </a>
      </div>
      <!-- /.box-footer -->
      {!! Form::close() !!}
    </div>
    <!-- /.box -->
  </div>
  <!--/.col (right) -->
</div>
<!-- /.row -->
</section>
<!-- /.content -->
</div>
<!-- /.content-wrapper -->
@push('scripts')
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key={{ $map_key }}&sensor=false&libraries=places"></script>
<script type="text/javascript">
  var latitude = $("#input_latitude").val();
  var longitude = $("#input_longitude").val();
  //when we click enter in city Address list box -- form auto load
  var input = document.getElementById('input_name');
  google.maps.event.addDomListener(input, 'keydown', function(event) { 
    if (event.keyCode === 13) { 
      event.preventDefault(); 
    }
  }); 

  function initAutocomplete()
  {
    autocomplete_elem = document.getElementById('input_name');
    autocomplete = new google.maps.places.Autocomplete(autocomplete_elem, { types: ['(cities)']});
    autocomplete.addListener('place_changed', function(){
      place = autocomplete.getPlace();
      latitude = place.geometry.location.lat();
      longitude = place.geometry.location.lng();

      $("#input_latitude").val(latitude);
      $("#input_longitude").val(longitude);
      //validate_lat_long();
    });
  }
  initAutocomplete();
  
</script>
@endpush
@stop