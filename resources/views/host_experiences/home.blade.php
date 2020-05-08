@extends('template')
@section('main')
<main id="site-content" role="main">
  @if($header_class == 'exp_mak')
  <div class="experience-info-wrap d-flex align-items-center" ng-controller="host_experiences">
    <div class="container">
      <div class="experience-info">
        <div class="container">
          <h1>
            {{trans('experiences.home.share_your_experience')}}
          </h1>
          <p>
            {{trans('experiences.home.create_unique_experience_and_earn_money')}}
          </p>
          <button class="btn btn-primary" href="javascript:void(0)" ng-click="new_experience_navigate()">
            {{trans('experiences.home.create_an_experience')}}
          </button>
        </div>
      </div>
    </div>
  </div>
  @endif

  <div ng-controller="host_experiences">
    <div class="host-banner-info">
      <div class="container">
        @if($header_class != 'exp_mak')
        <div class="welcome-info py-4 py-md-5">
          <div class="row">
            <div class="col-md-8">
              <h5>
                {{trans('experiences.home.welcome_back')}}
              </h5>
              <p>
                {{trans('experiences.home.keep_track_and_edit_happy_hosting')}}
              </p>
            </div>
            <div class="col-md-4 mt-3 mt-md-0 text-md-right">
              <a class="btn btn-host-secondary" href="javascript:void(0)" ng-click="new_experience_navigate();">{{trans('experiences.home.new_experience')}}
              </a>
            </div>
          </div>
        </div>

        @if($host_experiences->count() > 0)
        <div class="box-wrap d-flex overflow-auto">
          @foreach($host_experiences as $host_experience)
          <div class="box-item d-flex flex-column">
            <a class="title-link" href="{{url('experiences/'.$host_experience->id)}}">
              {{$host_experience->title ? $host_experience->title : trans('experiences.home.experience')}}
            </a>
            <p>
              {{@$host_experience->city_details->name}} {{-- · 1 {{strtolower(trans('experiences.home.experience'))}} --}}
            </p>
            <p> 
              @if($host_experience->is_completed)
              @if($host_experience->admin_status == 'Pending')
              <i class="fa fa-circle text-warning"></i>
              {{trans('experiences.home.your_experience_submitted_expect_hear_back_in_weeks')}}
              @elseif($host_experience->admin_status == 'Approved')
              <i class="fa fa-circle text-success"></i>
              {{trans('experiences.home.experience_approved')}}
              @else
              <i class="fa fa-circle text-danger"></i>
              {{trans('experiences.home.experience_rejected')}}
              @endif
              @else
              {{trans('experiences.home.you_still_have_to_complete_some_steps')}}
              @endif
            </p>   
            <a class="btn btn-host mt-auto align-self-start" href="{{url('host/manage_experience/'.$host_experience->id)}}">
              {{trans('experiences.home.edit_experience')}}
            </a>
            <div class="tooltip_cover">
              <i class="icon icon-trash js-delete-photo-btn" ng-click="delete_experience('{{ trans('messages.lys.delete') }}','{{ trans('messages.lys.delete_experience') }}')" class="js-delete-photo-btn" id="{{ $host_experience->id}}"></i>
              <div class="tooltip">
                <a href="javascript:void(0)"> 
                  {{trans('experiences.home.delete')}} 
                </a>
              </div>
            </div>
          </div>
          @endforeach
        </div>
        @endif
        @endif

        <div class="my-5 pt-md-4 pt-lg-0">
          <p class="text-center">
            {{trans('experiences.home.hosts_who_love_sharing_community_experiences')}}
          </p>
        </div>
        <div class="host-banner" data-id="pop1" style="background: url('{{url('images/host_experiences/video_static_img12.jpg')}}') no-repeat center / cover;">
          <!-- <div class="trans1">
            <i class="icon icon2-play-button video_ico"></i>
          </div> -->
        </div>
        <h4>
          {{trans('experiences.home.sample_of_great_hosts_do')}}
        </h4>
      </div>
    </div>

    <div class="host-slider-wrap">
      <div class="container">
        <div class="host-slider owl-carousel">
          <div class="item">
            <img src="{{url('images/host_experiences/great_host_slider1.jpg')}}" class="img-responsive">
            <div class="slide-info my-4 d-md-flex">
              <div class="col-md-4 p-0">
                <h4>
                  {{trans('experiences.home.give_insider_access')}}
                </h4>
              </div>
              <div class="col-md-8 p-0">
                <p>
                  {{trans('experiences.home.dawn_takes_aquarium_research_programs_marine')}}
                </p>
              </div>
            </div>
          </div>
          <div class="item">
            <img src="{{url('images/host_experiences/great_host_slider2.jpg')}}" class="img-responsive">
            <div class="slide-info my-4 d-md-flex">
              <div class="col-md-4 p-0">
                <h4>
                  {{trans('experiences.home.encourage_participation')}}
                </h4>
              </div>
              <div class="col-md-8 p-0">
                <p>
                  {{trans('experiences.home.ron_makes_sunflower_planting_learning_about_compose')}}
                </p>
              </div>
            </div>
          </div>
          <div class="item">
            <img src="{{url('images/host_experiences/great_host_slider3.jpg')}}" class="img-responsive">
            <div class="slide-info my-4 d-md-flex">
              <div class="col-md-4 p-0">
                <h4>
                  {{trans('experiences.home.offer_unique_perspective')}}
                </h4>
              </div>
              <div class="col-md-8 p-0">
                <p>
                  {{trans('experiences.home.virgo_shows_unveiling_music_virtual_reality')}}
                </p>
              </div>
            </div>
          </div>
        </div> 
      </div>
    </div>

    <div class="why-host">
      <div class="container">
        <div class="why-host-content">
          <div class="row d-none">
            <div class="col-md-6">
              <h4>
                {{trans('experiences.home.want_to_host_for_social_cause')}}
              </h4>
              <p>
                {{trans('experiences.home.if_you_are_member_nonprofit_organization_bring_people')}}
              </p>
              <a href="javascript:void(0)" class="theme-link">
                {{trans('experiences.home.learn_more')}} > 
              </a>
            </div>
            <div class="col-md-6">
              <img src="{{url('images/host_experiences/makent_ph.png')}}" class="img-responsive">
            </div>
          </div>  
          <h4>
            {{trans('experiences.home.why_host_on_makent', ['site_name' => SITE_NAME])}}
          </h4>
          <div class="background-cover" style="background: url('{{url('images/host_experiences/why_host.jpg')}}') no-repeat center / cover;">
          </div>
          <div class="row">
            <div class="col-md-4">
              <h5>
                {{trans('experiences.home.get_more_exposure')}}
              </h5>
              <p>
                {{trans('experiences.home.millions_of_guest_book_instant')}}
              </p>
            </div>
            <div class="col-md-4">
              <h5>
                {{trans('experiences.home.promote_your_brand')}}
              </h5>
              <p>
                {{trans('experiences.home.get_sharable_source_of_experience')}}
              </p>
            </div>
            <div class="col-md-4">
              <h5>
                {{trans('experiences.home.meet_locals_like_you')}}
              </h5>
              <p>
                {{trans('experiences.home.join_global_hosts_community_meetups_and_event')}}
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="get-started">
      <div class="container">
        <h4>
          {{trans('experiences.home.how_to_get_started')}}
        </h4>
        <ul class="d-flex flex-wrap row my-4">
          <li class="col-12 col-md-6 col-lg-3">
            <span>
              1
            </span>
            <h4>
              {{trans('experiences.home.get_inspired_to_host')}} 
            </h4>
            <p>
              {!!trans('experiences.home.review_quality_standards_to_know_what_community_expects', ['quality_standards_link' => '<a href="'.url('quality_standards').'"> '.trans('experiences.manage.quality_standards').'</a>'] ) !!}
            </p>
          </li>
          <li class="col-12 col-md-6 col-lg-3">
            <span>
              2
            </span>
            <h4>
              {{trans('experiences.home.design_and_submit')}}
            </h4>
            <p>
              {{trans('experiences.home.build_your_experience_and_submit_our_team_will_reach_you')}}
            </p>
          </li>
          <li class="col-12 col-md-6 col-lg-3">
            <span>
              3
            </span>
            <h4>
              {{trans('experiences.home.hot_on_your_terms')}}
            </h4>
            <p>
              {{trans('experiences.home.once_host_published_free_to_host_when_you_want')}}
            </p>
          </li>
          <li class="col-12 col-md-6 col-lg-3">
            <span>
              4
            </span>
            <h4>
              {{trans('experiences.home.manage_on_the_fly')}}
            </h4>
            <p>
              {{trans('experiences.home.keep_track_of_bookings_message_and_update_from_app')}}
            </p>
          </li>
        </ul>
      </div>
    </div>

    <div class="create-experience" id="create_host_experience_div">
      <div class="container">
        <div class="create-wrap col-12 col-md-9 col-lg-7 p-0">
          <div class="create-info">
            <h5>
              {{trans('experiences.home.ready_to_host_in_your_city')}}
            </h5>
            <p>
              {{trans('experiences.home.start_host_experience_short_desc')}}
            </p>
          </div>
          <div class="experience-select mt-4 mt-md-5">
            <label>
              {{trans('experiences.home.where')}}
            </label>
            {!! Form::open(['url' => 'host/experiences/new', 'id' => 'new_host_experience', 'accept-charset' => 'UTF-8' , 'name' => 'new_host_experience', 'method' => 'post', 'class' => 'd-md-flex']) !!}
            <div class="host-select flex-grow-1 mr-md-3">
              <i class="icon-chevron-down" ng-init="city = ''"></i>
              {!! Form::select('city', $host_experience_cities, '', ['placeholder' => trans('experiences.home.select_city'), 'ng-model' => 'city', 'id' => 'input_city']) !!}
            </div>
            <button class="btn btn-host mt-3 mt-md-0" ng-disabled="city == ''" type="submit">
              {{trans('experiences.home.create_an_experience')}}
            </button>
            {!! Form::close() !!}
          </div>
        </div>
      </div>
    </div>

    <div id="js-error" class="modal show" aria-hidden="true" style="" tabindex="-1">
     <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title"></h4>
        </div>
        <div class="modal-body py-4">
          <p></p>
        </div>
        <div class="modal-footer">
         <button type="button" data-dismiss="modal" class="btn">
          {{ trans('messages.home.close') }}
        </button>
        <button class="btn btn-primary js-delete-photo-confirm" ng-value="id" data-id="">
          {{ trans('messages.lys.delete') }}
        </button>
      </div>
    </div>
  </div>
</div>
</div>
</main>
@stop