  <div class="form-group">
    <label for="input_image" class="col-sm-3 control-label">Image</label>
    <div class="col-sm-6">
      {!! Form::file('image', ['class' => 'form-control', 'id' => 'input_image', 'accept' => 'image/*']) !!}
      <p class="note_text">(Note: Upload size minimum 1360px * 600px)</p>
      <span class="text-danger">{{ $errors->first('image') }}</span>
      @isset($result->image_url)
        <img src="{{ $result->image_url }}" height="100" width="200">
      @endisset
    </div>
  </div>
  <div class="form-group">
    <label for="input_position" class="col-sm-3 control-label">Order<em class="text-danger">*</em></label>
    <div class="col-sm-6">
      {!! Form::text('order', @$result->order, ['class' => 'form-control', 'id' => 'input_position']) !!}
      <span class="text-danger">{{ $errors->first('order') }}</span>
    </div>
  </div>

  <div class="form-group">
    <label for="input_name" class="col-sm-3 control-label"> Name <em class="text-danger">*</em> </label>
    <div class="col-sm-6">
      {!! Form::text('name', @$result->name, ['class' => 'form-control', 'id' => 'input_name']) !!}
      <span class="text-danger">{{ $errors->first('name') }}</span>
    </div>
  </div>

  <div class="form-group">
    <label for="input_description" class="col-sm-3 control-label"> Description Address <em class="text-danger">*</em> </label>
    <div class="col-sm-6">
      {!! Form::text('description', @$result->description, ['class' => 'form-control', 'id' => 'input_description', 'rows' => '5', 'cols' => '100']) !!}
      <span class="text-danger">{{ $errors->first('description') }}</span>
    </div>
  </div>

  <div class="form-group">
    <label for="input_status" class="col-sm-3 control-label">Status<em class="text-danger">*</em></label>
    <div class="col-sm-6">
      {!! Form::select('status', ['Active' => 'Active', 'Inactive' => 'Inactive'], @$result->status, ['class' => 'form-control', 'id' => 'input_status']) !!}
      <span class="text-danger">{{ $errors->first('status') }}</span>
    </div>
  </div>