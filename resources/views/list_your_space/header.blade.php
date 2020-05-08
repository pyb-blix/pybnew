<div class="manage-listing-header" id="js-manage-listing-header">
  <ul class="listing-nav d-none d-md-flex align-items-center">
    <li id="collapsed-nav" class="d-none">
      <a href="javascript:void(0)" data-prevent-default="" class="listing-nav-item show-collapsed-nav-link d-flex align-items-center" id="price-id">
        <i class="icon icon-reorder mr-2"></i>
        <span>
          {{ trans('messages.lys.pricing_listing_details') }}…
        </span>
      </a>
    </li>
    <li>
      <span id="listing-name">
        <span ng-hide="name">
          {{ ($result->name == '') ? $result->sub_name : $result->name }}
        </span>
        <span ng-show="name">
          <span ng-bind="name"></span>
        </span>
      </span>
      <span class="see-all-listings-link ml-1">
        (<a href="{{ url('rooms') }}">
        {{ trans('messages.lys.see_all_listings') }}
      </a>)
    </span>
  </li>
  <li class="ml-auto">
    <a href="{{ url('rooms/'.$room_id.'?preview') }}" data-track="preview" class="listing-nav-item d-flex align-items-center" id="preview-btn" title="Preview your listing as it will appear when active." target="_blank">
      <i class="icon icon-eye mr-2"></i>
      <span>
        {{ ($result->status == NULL) ? trans('messages.lys.preview') : trans('messages.lys.view') }}
      </span>
    </a>
  </li>
</ul>
<ul class="listing-nav has-collapsed-nav d-md-none">
  <li class="show-if-collapsed-nav" id="collapsed-nav">
    <a href="javascript:void(0)" data-prevent-default="" class="listing-nav-item show-collapsed-nav-link d-flex align-items-center">
      <i class="icon icon-reorder mr-2"></i>
      <span>
        {{ trans('messages.lys.pricing_listing_details') }}…
      </span>
    </a>
  </li>
</ul>
</div>
