@extends('emails.template')

@section('emails.main')
<div style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;margin-top:1em">
	<div style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;margin-top:1em">
		
		{{ trans('messages.email.congratulations',[], null, $locale) }}! <a href="{{ $url.('rooms/'.$room_id) }}" style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;color:#ff5a5f;text-decoration:none" target="_blank"> {{ trans('messages.email.rooms_for',[], null, $locale) }} {{ $room_name }}</a> {{ trans('messages.email.was_approved_by_admin',['site_name'=>$site_name],null, $locale) }}.

	</div>
	<div style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;margin-top:1em">
		
		{{ trans('messages.email.not_ready_for_guests',[], null, $locale) }} <a href="{{ $url }}manage-listing/{{ $room_id }}/calendar" style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;color:#ff5a5f;text-decoration:none" target="_blank"> {{ trans('messages.email.your_calendar',[], null, $locale) }}</a>, {{ trans('messages.email.pervent_people_booking',[], null, $locale) }} <strong style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">{{ trans('messages.email.not_available',[], null, $locale) }}</strong> {{ trans('messages.email.and_click',[], null, $locale) }} <strong style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">{{ trans('messages.profile.save',[], null, $locale) }}</strong>.

	</div>
	<div style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;margin-top:1em">
		
		{{ trans('messages.email.please_unlist_it',[], null, $locale) }}
	</div>
</div>
@stop