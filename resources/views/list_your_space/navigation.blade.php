<div class="col-lg-2 col-md-3 manage-nav p-0" id="js-manage-listing-nav">
  <div class="nav-sections">
    @if($result->status != NULL)
    <div class="section-header pre-listed">
      <h3>
        {{ trans('messages.lys.hosting') }}
      </h3>
    </div>
    <ul class="list-unstyled list-nav-link">      
      <li class="nav-item nav-calendar pre-listed" data-track="calendar" ng-class="(step == 'calendar') ? 'nav-active' : ''">
        <a href="{{ ($room_step != 'calendar') ? url('manage-listing/'.$room_id.'/calendar') : '' }}" id="href_calendar">
          <span class="text-truncate">
            {{ trans('messages.lys.calendar') }}
          </span>
        </a>
      </li>

      <li class="nav-item nav-pricing pre-listed" data-track="pricing" ng-class="(step == 'pricing') ? 'nav-active' : ''">
        <a href="{{ ($room_step != 'pricing') ? url('manage-listing/'.$room_id.'/pricing') : '' }}" id="href_pricing">
          <span class="text-truncate">
            {{ trans('messages.lys.pricing') }}
          </span>
          @if($result->status == NULL)
          <div class="js-new-section-icon not-post-listed transition {{ ($rooms_status->pricing == 1) ? 'd-none' : 'visible' }}">
            <i class="icon icon-add"></i>
          </div>
          <div class="success-icon">
            <i class="icon icon-ok-alt not-post-listed {{ ($rooms_status->pricing == 1) ? '' : 'd-none' }}"></i>
            <i class="dot dot-success d-none"></i>
          </div>
          @endif
        </a>
      </li>

      <li data-track="how-guests-book" class="nav-item pre-listed" ng-class="(step == 'booking') ? 'nav-active' : ''">
        <a href="{{ ($room_step != 'booking') ? url('manage-listing/'.$room_id.'/booking') : '' }}" id="href_booking">
          <span class="text-truncate">
            {{ trans('messages.lys.booking') }}
          </span>
          @if($result->status == NULL)
          <div class="js-new-section-icon not-post-listed">
            <i class="icon icon-add"></i>
          </div>
          <div>
            <i aria-hidden="true" class="icon nav-icon-checked not-post-listed icon-ok-alt {{ ($rooms_status->booking == 1) ? '' : 'd-none' }}">
            </i>
            <i class="dot dot-success d-none"></i>
          </div>
          @endif
        </a>
      </li>
    </ul>
    @endif

    <div class="section-header pre-listed">
      <h3>
        {{ trans('messages.lys.listing') }}
      </h3>
    </div>
    <ul class="list-unstyled list-nav-link">
      <li class="nav-item nav-basics pre-listed" data-track="basics" ng-class="(step == 'basics') ? 'nav-active' : ''">
        <a href="{{ ($room_step != 'basics') ? url('manage-listing/'.$room_id.'/basics') : '' }}" id="href_basics">
          <span class="text-truncate">
            {{ trans('messages.lys.basics') }}
          </span>
          @if($result->status == NULL)
          <div class="js-new-section-icon not-post-listed transition {{ ($rooms_status->basics == 1) ? 'd-none' : 'visible' }}">
            <i class="icon icon-add"></i>
          </div>
          <div class="success-icon">
            <i class="icon icon-ok-alt not-post-listed {{ ($rooms_status->basics == 1) ? '' : 'd-none' }}"></i>
            <i class="dot dot-success d-none"></i>
          </div>
          @endif
        </a>
      </li>

      <li class="nav-item nav-description pre-listed" data-track="description" ng-class="(step == 'description') ? 'nav-active' : ''">
        <a href="{{ ($room_step != 'description') ? url('manage-listing/'.$room_id.'/description') : '' }}" id="href_description">
          <span class="text-truncate">
            {{ trans('messages.lys.description') }}
          </span>
          @if($result->status == NULL)
          <div class="js-new-section-icon not-post-listed transition {{ ($rooms_status->description == 1) ? 'd-none' : 'visible' }}">
            <i class="icon icon-add"></i>
          </div>
          <div class="success-icon">
            <i class="icon icon-ok-alt not-post-listed {{ ($rooms_status->description == 1) ? '' : 'd-none' }}"></i>
            <i class="dot dot-success d-none"></i>
          </div>
          @endif
        </a>
      </li>

      <li class="nav-item nav-location pre-listed" data-track="location" ng-class="(step == 'location') ? 'nav-active' : ''">
        <a href="{{ ($room_step != 'location') ? url('manage-listing/'.$room_id.'/location') : '' }}" id="href_location">
          <span class="text-truncate">
            {{ trans('messages.your_trips.location') }}
          </span>
          @if($result->status == NULL)
          <div class="js-new-section-icon not-post-listed transition {{ ($rooms_status->location == 1) ? 'd-none' : 'visible' }}">
            <i class="icon icon-add"></i>
          </div>
          <div class="success-icon">
            <i class="icon icon-ok-alt not-post-listed {{ ($rooms_status->location == 1) ? '' : 'd-none' }}"></i>
            <i class="dot dot-success d-none"></i>
          </div>
          @endif
        </a>
      </li>

      <li class="nav-item nav-amenities pre-listed" data-track="amenities" ng-class="(step == 'amenities') ? 'nav-active' : ''">
        <a ng-click="reloadRoute()" href="{{ ($room_step != 'amenities') ? url('manage-listing/'.$room_id.'/amenities') : '' }}" id="href_amenities">
          <span class="text-truncate">
            {{ trans('messages.lys.amenities') }}
          </span>
        </a>
      </li>

      <li class="nav-item nav-photos pre-listed" data-track="photos" ng-class="(step == 'photos') ? 'nav-active' : ''">
        <a href="{{ ($room_step != 'photos') ? url('manage-listing/'.$room_id.'/photos') : '' }}" id="href_photos">
          <span class="text-truncate">
            {{ trans('messages.lys.photos') }}
          </span>
          @if($result->status == NULL)
          <div class="js-new-section-icon not-post-listed transition {{ ($rooms_status->photos == 1) ? 'd-none' : 'visible' }}">
            <i class="icon icon-add"></i>
          </div>
          <div class="success-icon">
            <i class="icon icon-ok-alt not-post-listed {{ ($rooms_status->photos == 1) ? '' : 'd-none' }}"></i>
            <i class="dot dot-success d-none"></i>
          </div>
          @endif
        </a>
      </li>

      <li class="nav-item nav-video pre-listed" data-track="video" ng-class="(step == 'video') ? 'nav-active' : ''">
        <a href="{{ ($room_step != 'video') ? url('manage-listing/'.$room_id.'/video') : '' }}" id="href_video">
          <span class="text-truncate">
            {{ trans('messages.lys.video') }}
          </span>
        </a>
      </li>      
    </ul>
    
    @if($result->status != NULL) 
    <div class="section-header pre-listed">
      <h3>
        {{ trans('messages.lys.guest_resources') }}
      </h3>
    </div>
    <ul class="list-unstyled list-nav-link">
      <li class="nav-item nav-details pre-listed" data-track="details" ng-class="(step == 'details') ? 'nav-active' : ''">
        <a href="{{ ($room_step != 'details') ? url('manage-listing/'.$room_id.'/details') : '' }}" id="href_details">
          <span class="text-truncate">
            {{ trans('messages.lys.detailed_description') }}
          </span>
        </a>
      </li>

      <li class="nav-item nav-terms pre-listed" data-track="terms" ng-class="(step == 'terms') ? 'nav-active' : ''">
        <a href="{{ ($room_step != 'terms') ? url('manage-listing/'.$room_id.'/terms') : '' }}" id="href_terms">
          <span class="text-truncate">
            {{ trans('messages.lys.terms') }}
          </span>
        </a>
      </li>
    </ul>
    @endif

    @if($result->status == NULL)
    <div class="section-header pre-listed">
      <h3>
        {{ trans('messages.lys.hosting') }}
      </h3>
    </div>
    <ul class="list-unstyled list-nav-link">
      <li class="nav-item nav-pricing pre-listed" data-track="pricing" ng-class="(step == 'pricing') ? 'nav-active' : ''">
        <a href="{{ ($room_step != 'pricing') ? url('manage-listing/'.$room_id.'/pricing') : '' }}" id="href_pricing">
          <span class="text-truncate">
            {{ trans('messages.lys.pricing') }}
          </span>
          <div class="js-new-section-icon not-post-listed transition {{ ($rooms_status->pricing == 1) ? 'd-none' : 'visible' }}">
            <i class="icon icon-add"></i>
          </div>
          <div class="success-icon">
            <i class="icon icon-ok-alt not-post-listed {{ ($rooms_status->pricing == 1) ? '' : 'd-none' }}"></i>
            <i class="dot dot-success d-none"></i>
          </div>
        </a>
      </li>

      <li class="nav-item nav-calendar pre-listed" id="remove-manage" data-track="calendar" ng-class="(step == 'calendar') ? 'nav-active' : ''">
        <a href="{{ ($room_step != 'calendar') ? url('manage-listing/'.$room_id.'/calendar') : '' }}" id="href_calendar">
          <span class="text-truncate">
            {{ trans('messages.lys.calendar') }}
          </span>
        </a>
      </li>
    </ul>
    @endif
  </div>

  <div class="publish-actions text-center" ng-init="steps_count={{ $result->steps_count }};status='{{ $result->status }}'">
    <div id="user-suspended"></div>
    <div id="availability-dropdown" class="availability-dropdown-wrap d-flex align-items-center justify-content-center" ng-if="status && status != 'Pending' && steps_count == 0">
      <span data-href="complete" ng-show="status == 'Resubmit'" class="green-color" id="js-list-space-button1">
        {{ trans('messages.profile.Resubmit') }}
      </span>
      <div class="d-flex align-items-center justify-content-center" ng-if="status != 'Resubmit'">
        <i class="dot" ng-class="status != 'Listed' ? 'dot-danger' : 'dot-success'" ng-if="status != 'Resubmit'"></i>
        <div class="select ml-2 flex-grow-1" ng-if="status != 'Resubmit'">
          <select class="availability_dropdown">
            <option value="Listed" {{ ($result->status == 'Listed') ? 'selected' : '' }}>
              {{ trans('messages.your_listing.listed') }}
            </option>
            <option value="Unlisted" {{ ($result->status == 'Unlisted') ? 'selected' : '' }}>
              {{ trans('messages.your_listing.unlisted') }}
            </option>
          </select>
        </div>
      </div>
    </div>

    <div id="js-publish-button" ng-if="(! status) || status == 'Pending' || (status == 'Unlisted' && steps_count != 0)">
      <div class="not-post-listed text-center">
        <div class="steps-remaining js-steps-remaining" style="opacity: 1;" ng-show="steps_count>0">
          {{ trans('messages.lys.complete') }} 
          <strong class="text-highlight"> 
            <span id="steps_count">
              @{{steps_count}}
            </span> 
            {{ trans('messages.lys.steps') }} 
          </strong> 
          {{ trans('messages.lys.to_list_your_space') }}
        </div>
        <span data-href="complete" class="green-color" id="js-list-space-button1" data-track="list_space_button_left_nav" ng-show="steps_count==0">
          {{ trans('messages.your_listing.pending') }}
        </span>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
  SAVING = "{{ trans('messages.lys.saving') }}..."
  SAVED = "{{ trans('messages.lys.saved') }}!"
  REMOVING = "{{ trans('messages.lys.removing') }}..."
  REMOVED = "{{ trans('messages.lys.removed') }}!"
</script>