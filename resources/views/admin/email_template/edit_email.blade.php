@extends('admin.template')

@section('main')
  <div class="content-wrapper">
    <section class="content-header">
      <h1>Send Email</h1>
      <ol class="breadcrumb">
        <li><a href="dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="send_email">Email</a></li>
        <li class="active">Send</li>
      </ol>
    </section>

    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Send Email Form</h3>
            </div>
              {!! Form::open(['url' => ADMIN_URL.'/edit_template/'.$result->id, 'class' => 'form-horizontal']) !!}
              <div class="box-body">
              <span class="text-danger">(*)Fields are Mandatory</span>
               <div class="form-group">
                  <label for="input_language" class="col-sm-3 control-label">Language<em class="text-danger">*</em></label>
                  <div class="col-sm-6">
                    {!! Form::select('language', $languages,$result->language, ['class' => 'form-control', 'id' => 'input_language']) !!}
                  </div>
                </div>  

                <div class="form-group">
                  <label for="input_name" class="col-sm-3 control-label">Name<em class="text-danger">*</em></label>
                  <div class="col-sm-6">
                    {!! Form::text('name', $result->name, ['class' => 'form-control', 'id' => 'input_name', 'disabled' =>'disabled']) !!}
                  </div>
                </div>

               <div class="form-group">
                  <label for="input_content" class="col-sm-3 control-label">Content<em class="text-danger">*</em></label>
                  <div class="col-sm-6">
                    <textarea id="txtEditor" name="txtEditor"></textarea>
                    {!! Form::textarea('content', $result->content, ['id' => 'content', 'hidden' => 'true']) !!}
                    <span class="text-danger">{{ $errors->first('content') }}</span>
                  </div>
                </div>

              </div>
              <div class="box-footer">
                <button type="submit" class="btn btn-info pull-right" id="edt_btn" name="submit" value="submit">Submit</button>
                 <button type="submit" class="btn btn-default pull-left" name="cancel" value="cancel">Cancel</button>
              </div>
            {!! Form::close() !!}
          </div>
        </div>
      </div>
    </section>
  </div>
@stop
@push('scripts')
<script type="text/javascript">
$("#txtEditor").Editor(); 
$('.Editor-editor').html($('#content').val())
</script>
@endpush