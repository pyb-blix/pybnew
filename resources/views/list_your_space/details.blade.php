<div class="col-12 manage-listing-container d-flex" id="js-manage-listing-content-container">
  <div class="manage-listing-content col-12 col-lg-7" id="js-manage-listing-content">
    <div class="content-heading my-4">
      <h3>
        {{ trans('messages.lys.details_title') }}
      </h3>
      <p>
        {{ trans('messages.lys.details_desc') }}
      </p>
    </div>

    <div class="js-section" id="js-section-details">
      <div class="js-saving-progress saving-progress help-panel-saving description2" style="display: none;">
        <h5>
          {{ trans('messages.lys.saving') }}...
        </h5>
      </div>
      <h4>
        {{ trans('messages.lys.the_trip') }}
      </h4>

      <div class="my-3" id="help-panel-space">
        <label>
          {{ trans('messages.lys.the_space') }}
        </label>
        <textarea name="space" placeholder="{{ trans('messages.lys.space_placeholder') }}" data-saving="description2">{{ $result->rooms_description->space }}</textarea>
      </div>

      <div class="my-3" id="help-panel-access">
        <label>
          {{ trans('messages.lys.guest_access') }}
        </label>
        <textarea name="access" rows="4" placeholder="{{ trans('messages.lys.guest_access_placeholder') }}" data-saving="description2">{{ $result->rooms_description->access }}</textarea>
      </div>

      <div class="my-3" id="help-panel-interaction">
        <label>
          {{ trans('messages.lys.interaction_with_guests') }}
        </label>
        <textarea name="interaction" rows="4" placeholder="{{ trans('messages.lys.interaction_with_guests_placeholder') }}" data-saving="description2">{{ $result->rooms_description->interaction }}</textarea>
      </div>

      <div class="my-3" id="help-panel-notes">
        <label>
          {{ trans('messages.lys.other_things_note') }}
        </label>
        <textarea class="input-large textarea-resize-vertical" name="notes" rows="4" placeholder="{{ trans('messages.lys.other_things_note_placeholder') }}" data-saving="description2">{{ $result->rooms_description->notes }}</textarea>
      </div>

      <div class="my-3" id="help-panel-house-rules">
        <label>
          {{ trans('messages.lys.house_rules') }}
        </label>
        <textarea class="textarea-resize-vertical" name="house_rules" rows="4" placeholder="{{ trans('messages.lys.house_rules_placeholder') }}" data-saving="description2">{{ $result->rooms_description->house_rules }}</textarea>
      </div>
    </div>

    <div class="js-section">
      <div class="js-saving-progress saving-progress help-panel-neigh-saving description3" style="display: none;">
        <h5>
          {{ trans('messages.lys.saving') }}...
        </h5>
      </div>
      <h4>
        {{ trans('messages.lys.the_neighborhood') }}
      </h4>
      <div class="my-3" id="help-panel-neighborhood">
        <label>
          {{ trans('messages.lys.overview') }}
        </label>
        <textarea name="neighborhood_overview" rows="4" placeholder="{{ trans('messages.lys.overview_placeholder') }}" data-saving="description3">{{ $result->rooms_description->neighborhood_overview }}</textarea>
      </div>
      <div id="help-panel-transit">
        <label>
          {{ trans('messages.lys.getting_around') }}
        </label>
        <textarea name="transit" rows="4" placeholder="{{ trans('messages.lys.getting_around_placeholder') }}" data-saving="description3">{{ $result->rooms_description->transit }}</textarea>
      </div>
    </div>

    <div class="d-flex align-items-center justify-content-between my-4 progress-buttons">
      <div class="prev_step next_step">
        <a data-prevent-default="" href="{{ url('manage-listing/'.$room_id.'/video') }}" class="back-section-button">
          {{ trans('messages.lys.back') }}
        </a>
      </div>
      <div class="next_step">
        <a class="btn btn-primary next-section-button" data-prevent-default="" href="{{ url('manage-listing/'.$room_id.'/terms') }}">
          {{ trans('messages.lys.next') }}
        </a>
      </div>
    </div>
  </div>

  <div class="manage-listing-help mt-5 col-lg-5 d-none d-lg-block" id="js-manage-listing-help">
    <div class="help-icon">
      {!! Html::image('images/lightbulb2x.png', '') !!}
    </div>
    <div class="help-content mb-5">
      <h4 class="text-center">
        {{ trans('messages.lys.guest_access') }}
      </h4>
      <p>
        {{ trans('messages.lys.guest_access_desc') }}
      </p>
    </div>
  </div>
</div>
