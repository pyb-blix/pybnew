@extends('emails.template')

@section('emails.main')
@php
 $link = '<a href="'.$url.'admin/rooms" style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;color:#ff5a5f;text-decoration:none" target="_blank"> '.$result['name'].' </a>'
@endphp
<div style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;margin-top:1em">
<div style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;margin-top:1em">
{!! trans('messages.email.awaiting_approval_admin_message',['listing_name'=>$link], null, $locale) !!} 
</div>
</div>
@stop

