@extends('template')
@section('main')
<main role="main" id="site-content">
<div class="container">
  <div class="py-4 py-md-5 text-wrap">
    {!! $content !!}
  </div>
</div>
</main>
@push('scripts')
<script type="text/javascript">
$( document ).ready(function() {
 
 var base_url = '{!! url("/") !!}';
 var user_token = '{!! Session::get('get_token') !!}';

 if(user_token!='')
 {

  $('a[href*="'+base_url+'"]').attr('href' , 'javascript:void(0)');
 
 }

});

</script>
@endpush
@stop