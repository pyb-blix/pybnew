<div class="col-12 manage-listing-container d-flex" id="js-manage-listing-content-container">
  <div class="manage-listing-content col-12 col-lg-7" id="js-manage-listing-content">
    <div class="content-heading my-4">
      <h3>
        {{ trans('messages.lys.terms') }}
      </h3>
      <p>
        {{ trans('messages.lys.terms_desc') }}
      </p>
    </div>

    <div class="js-section">
      <div class="js-saving-progress saving-progress" style="display: none;">
        <h5>
          {{ trans('messages.lys.saving') }}...
        </h5>
      </div>
      
      <div class="my-2">
        <label>
          {{ trans('messages.payments.cancellation_policy') }}
        </label>
        <div id="cancellation-policy-select" class="my-2">
          <div class="select">
            <select name="cancel_policy" id="select-cancel_policy" data-saving="saving-progress">
              <option value="Flexible" {{ ($result->cancel_policy == 'Flexible') ? 'selected' : '' }}>{{ trans('messages.lys.flexible_desc') }}</option>
              <option value="Moderate" {{ ($result->cancel_policy == 'Moderate') ? 'selected' : '' }}>{{ trans('messages.lys.moderate_desc') }}</option>
              <option value="Strict" {{ ($result->cancel_policy == 'Strict') ? 'selected' : '' }}>{{ trans('messages.lys.strict_desc') }}</option>
            </select>
          </div>
        </div>
      </div>
    </div>

    <div class="d-flex align-items-center justify-content-between my-4 progress-buttons">
      <div class="prev_step next_step">
        <a data-prevent-default="" href="{{ url('manage-listing/'.$room_id.'/details') }}" class="back-section-button">
          {{ trans('messages.lys.back') }}
        </a>
      </div>
    </div>
  </div>
</div>
