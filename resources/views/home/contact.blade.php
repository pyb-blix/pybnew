@extends('template')
@section('main')
<main id="site-content" role="main">
  @if( Auth::check())
  @include('common.subheader')
  @endif
  <div class="container pt-1">
    <div class="col-md-7 my-4 my-md-5 mx-auto p-0 card">     
      <div class="card-header">
        <h3>
         {{ trans('messages.contactus.contactus') }}
       </h3>
     </div>
     <div class="card-body">
       {!! Form::open(['action' => 'HomeController@contact_create','accept-charset' => 'UTF-8' , 'novalidate' => 'true']) !!}
       <div class="row">
        <label class="col-md-3">
          {{ trans('messages.contactus.name') }}
          <em class="text-danger">*</em>
        </label>
        <div class="col-md-9">
          {!! Form::text('name', '', ['class' =>  $errors->has('name') ? '' : 'focus', 'placeholder' => trans('messages.contactus.name')]) !!}
          @if ($errors->has('name')) 
          <span class="text-danger">
            {{ $errors->first('name') }}
          </span>
          @endif  
        </div>
      </div>
      <div class="row mt-3">
        <label class="col-md-3">
          {{ trans('messages.contactus.email') }}
          <em class="text-danger">*</em>
        </label>
        <div class="col-md-9">    
          {!! Form::email('email', '', ['class' => $errors->has('email') ? '' : 'focus', 'placeholder' => trans('messages.contactus.email_address')]) !!}
          @if ($errors->has('email'))  
          <span class="text-danger">
            {{ $errors->first('email') }}
          </span>
          @endif
        </div>
      </div>
      <div class="row mt-3">
        <label class="col-md-3">
          {{ trans('messages.contactus.feedback') }}
          <em class="text-danger">*</em>
        </label>
        <div class="col-md-9">
         {!! Form::textarea('feedback', '', ['class' => $errors->has('feedback') ? '' : 'focus', 'placeholder' => trans('messages.contactus.feedback')]) !!}
         @if ($errors->has('feedback'))  
         <span class="text-danger">
           {{ $errors->first('feedback') }}
         </span> 
         @endif
       </div>
     </div>
     <div class="row mt-3">
      <label class="col-md-3"></label>
      <div class="col-md-9 text-right">
        {!! Form::submit( trans('messages.contactus.send') , ['class' => 'contact_submit btn btn-primary w-auto'])  !!}
        {!! Form::close() !!}
      </div>
    </div>
  </div>
</div>
</div>
</main>
@stop