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
        <li><a href="{{ route('admin_dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{ route('homepage_sliders') }}">{{ $main_title }}</a></li>
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
            {!! Form::open(['url' => route('homepage_sliders.update',['id' => $result->id]), 'class' => 'form-horizontal', 'files' => true]) !!}
              @method('PUT')
             <div class="box-body">
                <span class="text-danger">(*)Fields are Mandatory</span>
                @include('admin.home_page_sliders.form')
              </div>
              <div class="box-footer">
                <button type="submit" class="btn btn-info pull-right" name="submit" value="submit">Submit</button>
                <a href="{{ route('homepage_sliders') }}" class="btn btn-default pull-left"> Cancel </a>
              </div>
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
@stop