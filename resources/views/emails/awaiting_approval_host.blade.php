@extends('emails.template')

@section('emails.main')
<div style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;margin-top:1em">
<div style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;margin-top:1em">
   <!-- {{ trans('messages.email.your_recent_listing',[], null, $locale) }} <a href="{{ $url.('rooms/'.$room_id) }}" style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;color:#ff5a5f;text-decoration:none" target="_blank"> {{ $result['name'] }}</a> {{ trans('messages.email.awaiting_approval_host_content',[], null, $locale) }}  -->
   {{ trans('messages.email.awaiting_approval_host_message',['site_name'=>SITE_NAME], null, $locale) }}
</div>
</div>
@stop