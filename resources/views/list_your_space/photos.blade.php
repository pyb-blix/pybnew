<div class="col-12 manage-listing-container d-flex" id="js-manage-listing-content-container" ng-cloak>
  <div class="manage-listing-content col-12 col-lg-7" id="js-manage-listing-content">
    <div class="content-heading my-4">
      <h3>
        {{ trans('messages.lys.photos_title') }}
      </h3>
      <p>
        {{ trans('messages.lys.photos_desc') }}
      </p>
    </div>

    <div id="js-photos-grid" class="photos-info mb-3 pb-3 d-flex align-items-center justify-content-between">
      <div class="add-photos-button">
        <input type="file" class="d-none" name="photos[]" multiple="true" id="upload_photos" accept="image/*" onchange="angular.element(this).scope().uploadPhotos(this)">
        <button id="photo-uploader" class="btn d-flex align-items-center my-2" onclick="$('#upload_photos').trigger('click');">
          <i class="icon icon-upload mr-2"></i>
          {{ trans_choice('messages.lys.add_photo',2) }}
        </button>
      </div>

      <div id="photo_count" ng-show="photos_list.length > 0" ng-cloak>
        <span>
          @{{ photos_list.length }} {{ trans_choice('messages.lys.photo',1) }}<span ng-show="photos_list.length > 1">s</span>
        </span>
      </div>
    </div>

    <div id="js-first-photo-text" class="my-2">
      <span> {{ trans('messages.lys.drag_image_to_set_feature') }} </span>
    </div>

    <div class="row sortable_image_view">
      <ul id="js-photo-grid" class="photo-grid d-flex flex-wrap w-100">
        <li ng-repeat="item in photos_list" class="col-lg-4 col-md-6 photo_drag_item" data-id="@{{ item.id }}" id="photo_li_@{{ item.id }}">
          <div class="panel photo-item">
            <input type='hidden' class="image_order_list" value="@{{ item.id }}">
            <a class="media-photo media-photo-block text-center photo-size" href="#">
              <img alt="" class="img-responsive-height" ng-src="@{{ item.name }}" >
            </a>
            <button data-photo-id="@{{ item.id }}" ng-click="delete_photo(item,'{{ trans('messages.lys.delete') }}','{{ trans('messages.lys.delete_descrip') }}')" class="delete-photo-btn overlay-btn js-delete-photo-btn">
              <i class="icon icon-trash"></i>
            </button>
            <div class="panel-body panel-condensed">
              <textarea name="@{{ item.id }}" ng-model="item.highlights" ng-keyup="keyup_highlights(item.id, item.highlights)" rows="3" placeholder="@lang('messages.lys.highlights_photo')" class="input-large highlights" tabindex="1"></textarea>
            </div>
          </div>
        </li>
      </ul>
    </div>

  <div class="d-flex align-items-center justify-content-between my-4 progress-buttons">
    <div class="prev_step next_step">
      <a data-prevent-default="" href="{{ url('manage-listing/'.$room_id.'/amenities') }}" class="back-section-button">
        {{ trans('messages.lys.back') }}
      </a>
    </div>
    <div class="next_step">
      @if($result->status == NULL)
      <a data-prevent-default="" href="{{ url('manage-listing/'.$room_id.'/video') }}" class="btn btn-primary next-section-button">
        {{ trans('messages.lys.next') }}
      </a>
      @endif
      @if($result->status != NULL)
      <a data-prevent-default="" href="{{ url('manage-listing/'.$room_id.'/video') }}" class="btn btn-primary next-section-button">
        {{ trans('messages.lys.next') }}
      </a>
      @endif
    </div>
  </div>
</div>

<div class="manage-listing-help photos-help-wrap text-center mt-5 col-lg-5 d-none d-lg-block" id="js-manage-listing-help">
  <div class="help-icon">
    {!! Html::image('images/lightbulb2x.png', '') !!}
  </div>
  <div class="help-content mb-5">
    <h4>
      {{ trans('messages.lys.guests_love_photos') }}
    </h4>
    <p>
      {{ trans('messages.lys.include_well_lit_photos') }}
    </p>
    <p>
      {{ trans('messages.lys.phone_photos_find') }}
    </p>
  </div>
</div>
</div>

