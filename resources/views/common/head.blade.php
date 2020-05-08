<!DOCTYPE html>
<html dir="{{ (((Session::get('language')) ? Session::get('language') : $default_language[0]->value) == 'ar') ? 'rtl' : '' }}" lang="{{ (Session::get('language')) ? Session::get('language') : $default_language[0]->value }}"  xmlns:fb="http://ogp.me/ns/fb#">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta name="viewport" content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width, height=device-height">
  <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=0">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
  <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0' >
  <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
  <meta name = "viewport" content = "user-scalable=no, width=device-width">
  <meta name="daterangepicker_format" content = "{{ $daterangepicker_format  }}">
  <meta name="datepicker_format" content = "{{$datepicker_format }}"> 
  <meta name="datedisplay_format" content = "{{ strtolower(DISPLAY_DATE_FORMAT) }}"> 
  <meta name="php_date_format" content = "{{ PHP_DATE_FORMAT }}"> 

  <link rel="dns-prefetch" href="https://maps.googleapis.com/">
  <link rel="dns-prefetch" href="https://maps.gstatic.com/">
  <link rel="dns-prefetch" href="https://mts0.googleapis.com/">
  <link rel="dns-prefetch" href="https://mts1.googleapis.com/">
  <link rel="shortcut icon" href="{{ $favicon }}">

  <!--[if IE]><![endif]-->
  <meta charset="utf-8">
  <!--[if IE 8]>
    {!! Html::style('css/common_ie8.css?v='.$version) !!}
    <![endif]-->
  <!--[if !(IE 8)]><!-->

  {!! Html::style('css/common.css?v='.$version) !!}
    
  @if (isset($exception))
    @if ($exception->getStatusCode()  == '404')
      {!! Html::style('css/error_pages_pretzel.css?v='.$version) !!}
    @endif
  @endif

  @if (!isset($exception))

    @if (Route::current()->uri() == 'signup_action')
      {!! Html::style('css/signinup.css?v='.$version) !!}
    @endif

    @if (Route::current()->uri() == 'z/q/{id}')
      {!! Html::style('css/tooltip.css?v='.$version) !!}
    @endif

    @if (Route::current()->uri() == 'messaging/qt_with/{id}')
      {!! Html::style('css/responsive_calendar.css?v='.$version) !!}
    @endif

    @if (Route::current()->uri() == 'dispute_details/{id}')
      {!! Html::style('css/slider/nivo-lightbox.css?v='.$version) !!}
      {!! Html::style('css/slider/default.css?v='.$version) !!}
    @endif

    @if (Route::current()->uri() == 'rooms')
      {!! Html::style('css/unlist_modal.css?v='.$version) !!}
      {!! Html::style('css/dashboard.css?v='.$version) !!}
    @endif
    
    @if (Route::current()->uri() == 'reviews/edit/{id}' || Route::current()->uri() == 'host_experience_reviews/edit/{id}')
      <!-- {!! Html::style('css/reviews.css?v='.$version) !!} -->
    @endif

    @if (Route::current()->uri() == 'help' || Route::current()->uri() == 'help/topic/{id}/{category}' || Route::current()->uri() == 'help/article/{id}/{question}')
      {!! Html::style('css/jquery-ui.css?v='.$version) !!}
    @endif

    @if(Route::currentRouteName() == 'search_page')
      {!! Html::style('css/nouislider.min.css') !!}
    @endif

  @endif

  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">   
  <meta name="keywords" content="{{ Helpers::meta((!isset($exception)) ? Route::current()->uri() : '', 'keywords') }}">   
  <meta name="twitter:widgets:csp" content="on">

  @if (!isset($exception))
    @if (Route::current()->uri() == 'rooms/{id}')
    <meta property="og:image" content="{{ $result->photo_name }}">
    <meta itemprop="image" src="{{ $result->photo_name }}">
    <link rel="image_src" href="#" src="{{ $result->photo_name }}">
    @endif

    @if (Route::current()->uri() == 'experiences/{host_experience_id}')
      <title>{{ @$result->title.' - '.$site_name }}</title>
      <meta name="description" content="{{ @$result->city_details->name }} - {{ @$result->tagline }} - {{ @$result->about_you}}">
      <meta name="twitter:widgets:csp" content="on">
      <meta property="og:url" content="{{ $result->link }}">
      <meta property="og:type" content="website" />
      <meta property="og:title" content="{{ @$result->title }}">
      <meta property="og:description" content="{{ @$result->city_details->name }} - {{ @$result->tagline }} - {{ @$result->about_you}}">
      <meta property="og:image" content="{{ @$result->host_experience_photos[0]->og_image }}">
      <meta property="og:image:height" content="1280">
      <meta property="og:image:width" content="853">
      <meta itemprop="image" src="{{ @$result->photo_name }}">
      <link rel="image_src" href="#" src="{{ @$result->photo_name }}">
      <meta name="twitter:title" content="{{ @$result->title }}">
      <meta name="twitter:site" content="{{ SITE_NAME }}">
      <meta name="twitter:url" content="{{ $result->link }}">
    @endif

    @if (Route::current()->uri() == 'wishlists/{id}')
      <meta property="og:image" content="{{@$result[0]->saved_wishlists[0]->photo_name}}">
      <meta itemprop="image" src="{{@$result[0]->saved_wishlists[0]->photo_name}}">
      <link rel="image_src" href="#" src="{{ @$result[0]->saved_wishlists[0]->photo_name }}">
    @endif
  @endif

  <link rel="search" type="application/opensearchdescription+xml" href="#" title="">
  
  <title>
    {{ $title ?? Helpers::meta((!isset($exception)) ? Route::current()->uri() : '', 'title') }} {{ $additional_title ?? '' }}
  </title>

  <meta name="description" content="{{ Helpers::meta((!isset($exception)) ? Route::current()->uri() : '', 'description') }}">
  <meta name="mobile-web-app-capable" content="yes">
  <meta name="theme-color" content="#f5f5f5">
</head>

<body class="{{ (!isset($exception)) ? (Route::current()->uri() == '/' ? 'home-page' : '') : '' }} {{(!isset($exception)) ? (@Route::current()->uri() == 's' ? 'search-page' : '') : ''}} {{(!isset($exception)) ? (Route::current()->uri() == 'help' ? 'help-page' : '') : ''}}  {{(!isset($exception)) ? (Route::current()->uri() == 'experiences/{host_experience_id}' ? 'host-detail-page' : '') : '' }}
  {{(!isset($exception)) ? (Route::current()->uri() == 'rooms/{id}' ? 'room-detail-page' : '') : '' }}" ng-app="App" ng-cloak>