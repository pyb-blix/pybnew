<div class="col-12 manage-listing-container d-flex" id="js-manage-listing-content-container">
  <div class="manage-listing-content col-12 col-lg-7" id="js-manage-listing-content">
    <div class="content-heading my-4">
      <h3>
        {{ trans('messages.lys.video_title') }}
      </h3>
      <p>
        {{ trans('messages.lys.video_desc') }}
      </p>
    </div>
    <form name="overview">
      <div class="js-section video-wrap">
        <div class="js-saving-progress saving-progress" style="display: none;">
          <h5>
            {{ trans('messages.lys.saving') }}...
          </h5>
        </div>
        <div class="js-saving-progress error-msg my-1 float-right" id="video_error" style="display: none;">
          <h5>
            {{ trans('messages.lys.video_error_msg') }}
          </h5>
        </div>
        <div id="help-panel-video" ng-init="video='{{ $result->video }}'">
          <label>
            {{ trans('messages.lys.youtube') }}
          </label>
          <input type="text" name="video" id='video' value="{!! $result->video !!}" placeholder="{{ trans('messages.lys.youtube') }}" ng-model="video">
        </div>
        <div class="video-wrap mt-3 @if($result->video == '') d-none @endif">
          <a class="remove_rooms_video" id="remove_rooms_video" data-saving="video_saving" href="javascript:void(0);">
            <i class="icon icon-trash"></i>
          </a>
          <iframe src="{{$result->video}}?showinfo=0" style="width:100%; height:250px;" id="rooms_video_preview"  allowfullscreen="allowfullscreen" mozallowfullscreen="mozallowfullscreen" msallowfullscreen="msallowfullscreen" oallowfullscreen="oallowfullscreen" webkitallowfullscreen="webkitallowfullscreen">
          </iframe>
        </div>
      </div>
    </form>

    <div class="d-flex align-items-center justify-content-between my-4 progress-buttons">
      <div class="prev_step next_step">
        @if($result->status == NULL)
        <a data-prevent-default="" href="{{ url('manage-listing/'.$room_id.'/photos') }}" class="back-section-button">
          {{ trans('messages.lys.back') }}
        </a>
        @endif

        @if($result->status != NULL)
        <a data-prevent-default="" href="{{ url('manage-listing/'.$room_id.'/photos') }}" class="back-section-button">
          {{ trans('messages.lys.back') }}
        </a>
        @endif
      </div>
      <div class="next_step">
        @if($result->status == NULL)
        <a data-prevent-default="" href="{{ url('manage-listing/'.$room_id.'/pricing') }}" class="btn btn-primary next-section-button">
          {{ trans('messages.lys.next') }}
        </a>
        @endif

        @if($result->status != NULL)
        <a data-prevent-default="" href="{{ url('manage-listing/'.$room_id.'/details') }}" class="btn btn-primary next-section-button">
          {{ trans('messages.lys.next') }}
        </a>
        @endif
      </div>
    </div>
  </div>

  <div class="manage-listing-help video-help-wrap text-center mt-5 col-lg-5 d-none d-lg-block" id="js-manage-listing-help">
    <div class="help-icon">
      {!! Html::image('images/lightbulb2x.png', '') !!}
    </div>
    <div class="help-content mb-5">
      <h4>
        {{ trans('messages.lys.guests_love_video') }}
      </h4>
      <p>
        {{ trans('messages.lys.phone_video_fine') }}
      </p>
    </div>
  </div>
</div>

