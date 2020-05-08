<div class="col-12 manage-listing-container d-flex" id="js-manage-listing-content-container">
  <div class="manage-listing-content col-12 col-lg-7" id="js-manage-listing-content">
    <div class="content-heading my-4">
      <h3>
        {{ trans('messages.lys.amenities_title') }}
      </h3>
      <p>
        {{ trans('messages.lys.amenities_desc',['site_name'=>$site_name]) }}
      </p>
    </div>

    @foreach($amenities_type as $row_type)
    <div class="js-section">
      <div class="js-saving-progress saving-progress {{ $row_type->id }}" style="display:none;">
        <h5>
          {{ trans('messages.lys.saving') }}...
        </h5>
      </div>
      <h4>
        {{ $row_type->name }}
      </h4>
      @if($row_type->description != '')
      <p>
        {{ $row_type->description }}
      </p>
      @endif
      <ul class="amenity-list mt-3">
        @foreach($amenities as $row_amenities)
        @if($row_amenities->type_id == $row_type->id)
        <li>
          <label>
            <input type="checkbox" value="{{ $row_amenities->id }}" name="amenities" data-saving="{{ $row_type->id }}" {{ in_array($row_amenities->id, $prev_amenities) ? 'checked' : '' }}>
            <span>
              {{ $row_amenities->name }}
            </span>
          </label>
          @if($row_amenities->description != '')
          <div class="custom-tooltip ml-2">
            <i class="icon icon-question" id="amenity-tooltip-{{ $row_amenities->id }}" data-id="{{ $row_amenities->id }}"></i>
            <div class="tooltip-wrap tool-arrow-bottom" id="ame-tooltip-{{ $row_amenities->id }}" role="tooltip">
              <div class="tooltip-info">
               <span>
                 {{ $row_amenities->description }}
               </span>
             </div>
           </div>
         </div>
         @endif
       </li>
       @endif
       @endforeach
     </ul>
   </div>
   @endforeach

   <div class="d-flex align-items-center justify-content-between my-4 progress-buttons">
    <div class="prev_step next_step">
      <a data-prevent-default="true" href="{{ url('manage-listing/'.$room_id.'/location') }}" class="back-section-button">
        {{ trans('messages.lys.back') }}
      </a>
    </div>
    <div class="next_step">
      <a data-prevent-default="true" href="{{ url('manage-listing/'.$room_id.'/photos') }}" class="btn btn-primary next-section-button">
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
      {{ trans('messages.lys.amenities') }}
    </h4>
    <p>
      {{ trans('messages.lys.edit_amenities_desc1') }}
    </p>
    <p>
      {{ trans('messages.lys.edit_amenities_desc2') }}
    </p>
  </div>
</div>
</div>