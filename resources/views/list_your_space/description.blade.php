<div class="col-12 manage-listing-container d-flex" id="js-manage-listing-content-container">
  <div class="manage-listing-content col-12 col-lg-7" id="js-manage-listing-content">
    <div class="row my-4 language-tabs-container">
      <div class="col-12 col-md-8 description_heading">
        <ul class="description-tabs" id="multiple_description" role="tablist">
          <li style="display:none;" class="tab-pager prev-tab-page" role="tab">
            <a href="#" class="tab-item">
              <i class="icon icon-arrow-left"></i>
            </a>
          </li>
          <li style="display:none;" class="tab-pager next-tab-page" role="tab">
            <a href="#" class="tab-item">
              <i class="icon icon-arrow-right"></i>
            </a>
          </li>
          <input type="hidden" id="current_tab_code" value="en">
          <li>
            <a href="javascript:void(0);" class="tab-item" role="tab" id="en" aria-controls="tab-pane-0"  aria-selected="true" ng-click="getdescription('en')">
              English
            </a>
          </li>
          <li ng-repeat="lan_row in lan_description">  
            <a href="javascript:void(0);" class="tab-item" role="tab" id="@{{ lan_row.lang_code }}" aria-controls="tab-pane-0"  aria-selected="false" ng-click="getdescription(lan_row.lang_code)" ng-cloak>
              @{{ lan_row.language.name }}
            </a>
          </li>
        </ul>
      </div>

      <div class="col-12 col-md-4 add-language mt-3 pl-0 mt-md-0 d-flex flex-wrap justify-content-end align-items-end">
        <a href="javascript:void(0)" class="d-flex align-items-center theme-color" id="add_language" title="{{trans('messages.lys.write_title_and_description')}}">
          <i class="icon icon-add mr-2"></i>
          {{trans('messages.lys.add_language')}}
        </a>
        <a id="delete_language" href="javascript:void(0)" style="display:none">
          <i class="icon icon-trash ml-2 green-color mb-0"></i>
        </a>
      </div>
    </div>

    <div class="description_form mb-4">
      <div class="content-heading my-4">
        <h3>
          {{ trans('messages.lys.amenities_title') }}
        </h3>   
        <p>
          {{ trans('messages.lys.amenities_desc',['site_name'=>$site_name]) }}
        </p>
      </div>

      <form name="overview">
        <div class="js-section" ng-init='name="{{ @$result->name_original }}";summary="{{ @$result->summary_original }}"; space="{{ @$result->rooms_description->space }}"; access="{{ @$result->rooms_description->access }}"; interaction="{{ @$result->rooms_description->interaction }}" ;other_notes="{{ @$result->rooms_description->notes }}"; house_rules="{{ @$result->rooms_description->house_rules }}" ;neighborhood_overview="{{ @$result->rooms_description->neighborhood_overview }}" ; transit="{{ @$result->rooms_description->transit }}"'>
          <div class="js-saving-progress saving-progress description1" style="display: none;">
            <h5>
              {{ trans('messages.lys.saving') }}...
            </h5>
          </div>

          <div class="mt-2 mb-4" id="help-panel-name">
            <div class="row">
              <div class="col-6">
                <label>
                  {{ trans('messages.lys.listing_name') }}
                </label>
              </div>
              <div class="col-6">
                <div id="js-name-count" class="text-right">
                  <span ng-bind="35 - name.length">35</span> 
                  {{ trans('messages.lys.characters_left') }}
                </div>
              </div>
            </div>

            <input type="text" name="name" value="{{ @$result->name }}" class="overview-title name_required" placeholder="{{ trans('messages.lys.name_placeholder') }}" maxlength="35" ng-model="name" data-saving="description1">
            <p class="d-none error-too-long mt-1">
              {{ trans('messages.lys.shorten_to_save_changes') }}
            </p>

            <p class="d-none error-msg mt-1 name_required_msg">
              {{ trans('messages.lys.value_is_required') }}
            </p>
          </div>

          <div id="help-summary">
            <div class="row">
              <div class="col-6 text_heading">
                <label>
                  {{ trans('messages.lys.summary') }}
                </label>
              </div>
              <div id="js-summary-count" class="col-6 text_sub_heading text-right">
                <span ng-bind="500 - summary.length">500</span> 
                {{ trans('messages.lys.characters_left') }}
              </div>
            </div>
            <textarea class="overview-summary summary_required" name="summary" rows="6" placeholder="{{ trans('messages.lys.summary_placeholder') }}" maxlength="500" ng-model="summary" data-saving="description1">
              {{ @$result->summary }}
            </textarea>
          </div>

          <p class="d-none error-too-long mt-1">
            {{ trans('messages.lys.shorten_to_save_changes') }}
          </p>

          <p class="d-none error-msg mt-1 summary_required_msg">
            {{ trans('messages.lys.value_is_required') }}
          </p>
        </div>
      </form>
    </div>

    <div id="add_language_des" class="add_language_info mb-4 text-center" style="display: none;">
      <i class="icon icon-globe green-color mb-2"></i>
      <h3>
        {{trans('messages.lys.write_description_other_language')}}
      </h3>
      <p>
        {{trans('messages.lys.site_provide_your_own_version', ['site_name' => $site_name])}}
      </p>
      <div class="select-language d-flex justify-content-center">
        <div class="col-7 p-0 select">
          <select id="language-select">
            <option disabled="" selected="">
              {{trans('messages.footer.choose_language')}}...
            </option>
            <option value="@{{ lan_row.value }}" ng-repeat="lan_row in all_language">
              @{{ lan_row.name }}
            </option>
          </select>
        </div>
        <button class="btn d-flex ml-3 align-items-center" disabled id="write-description-button" ng-click="addlanguageRow()">
          <i class="icon icon-add mr-2"></i>
          {{trans('messages.lys.add')}}
        </button>
      </div>
    </div>

    <p class="my-3 not-post-listed write_more_p">
      {{ trans('messages.lys.you_can_add_more') }} 
      <a href="javascript:void(0);" id="js-write-more" class="theme-color">
        {{ trans('messages.lys.details') }}
      </a> 
      {{ trans('messages.lys.tell_travelers_about_your_space') }}
    </p>

    <div class="js-section" id="js-section-details" style="display:none;">
      <div class="js-saving-progress saving-progress help-panel-saving description2" style="display: none;">
        <h5>
          {{ trans('messages.lys.saving') }}...
        </h5>
      </div>

      <h4>
        {{ trans('messages.lys.the_trip') }}
      </h4>

      <div class="mt-2 mb-3" id="help-panel-space">
        <label>
          {{ trans('messages.lys.the_space') }}
        </label>
        <textarea name="space" rows="4" ng-model="space" placeholder="{{ trans('messages.lys.space_placeholder') }}" data-saving="description2">
          {{ @$result->rooms_description->space }}
        </textarea>
      </div>

      <div class="my-3" id="help-panel-access">
        <label>
          {{ trans('messages.lys.guest_access') }}
        </label>
        <textarea name="access" ng-model="access" rows="4" placeholder="{{ trans('messages.lys.guest_access_placeholder') }}" data-saving="description2">
          {{ @$result->rooms_description->access }}
        </textarea>
      </div>

      <div class="row-space-2" id="help-panel-interaction">
        <label>
          {{ trans('messages.lys.interaction_with_guests') }}
        </label>
        <textarea name="interaction" ng-model="interaction" rows="4" placeholder="{{ trans('messages.lys.interaction_with_guests_placeholder') }}" data-saving="description2">
          {{ @$result->rooms_description->interaction }}
        </textarea>
      </div>

      <div class="my-3" id="help-panel-notes">
        <label>
          {{ trans('messages.lys.other_things_note') }}
        </label>
        <textarea name="notes" ng-model="other_notes" rows="4" placeholder="{{ trans('messages.lys.other_things_note_placeholder') }}" data-saving="description2">
          {{ @$result->rooms_description->notes }}
        </textarea>
      </div>

      <div class="my-3" id="help-panel-house-rules">
        <label>
          {{ trans('messages.lys.house_rules') }}
        </label>
        <textarea name="house_rules" ng-model="house_rules" rows="4" placeholder="{{ trans('messages.lys.house_rules_placeholder') }}" data-saving="description2">
          {{ @$result->rooms_description->house_rules }}
        </textarea>
      </div>
    </div>

    <div class="js-section" id="js-section-details_2" style="display:none;">
      <div class="js-saving-progress saving-progress help-panel-neigh-saving description3" style="display: none;">
        <h5>
          {{ trans('messages.lys.saving') }}...
        </h5>
      </div>

      <h4>
        {{ trans('messages.lys.the_neighborhood') }}
      </h4>

      <div class="mt-2 mb-3" id="help-panel-neighborhood">
        <label>
          {{ trans('messages.lys.overview') }}
        </label>
        <textarea name="neighborhood_overview" ng-model="neighborhood_overview" rows="4" placeholder="{{ trans('messages.lys.overview_placeholder') }}" data-saving="description3">
          {{ @$result->rooms_description->neighborhood_overview }}
        </textarea>
      </div>

      <div id="help-panel-transit">
        <label>
          {{ trans('messages.lys.getting_around') }}
        </label>
        <textarea name="transit" ng-model="transit" rows="4" placeholder="{{ trans('messages.lys.getting_around_placeholder') }}" data-saving="description3">
          {{ @$result->rooms_description->transit }}
        </textarea>
      </div>
    </div>

    <div class="d-flex align-items-center justify-content-between my-4 progress-buttons">
      <div class="prev_step next_step">
        <a class="back-section-button" href="{{ url('manage-listing/'.$room_id.'/basics') }}" data-prevent-default="">
          {{ trans('messages.lys.back') }}
        </a>
      </div>
      <div class="next_step">
        <a class="btn btn-primary next-section-button" href="{{ url('manage-listing/'.$room_id.'/location') }}" data-prevent-default="">
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
        {{ trans('messages.lys.listing_name') }}
      </h4>
      <div class="help-row">
        <p>
          {{ trans('messages.lys.listing_name_desc') }}
        </p>
        <p>
          {{ trans('messages.lys.example_name') }}
        </p>
      </div>
    </div>
  </div>
</div>