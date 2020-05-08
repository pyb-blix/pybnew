<div class="manage-listing-footer d-md-flex align-items-center" id="js-manage-listing-footer" data-role="footer">
  <div class="col-12 col-md-2 col-foot text-center text-md-left">
    <span>
      © {{ $site_name }}, Inc.
    </span>
  </div>
  <div class="col-12 col-md-8 mt-2 mb-3 my-md-0 col-foot site-list text-center">
    @php $i = 0 @endphp
    @foreach($company_pages as $company_page)
    <a href="{{ url($company_page->url) }}">
      {{ $company_page->name }}
    </a> 
    {{ ($i+1 == $company_pages->count()) ? '|' : '|' }}
    @php $i++ @endphp
    @endforeach
    <a href="{{ url('contact') }}">
      {{ trans('messages.contactus.contactus') }}
    </a>
  </div>
  <div class="col-12 col-md-2 col-foot">
    <div class="language-curr-picker">
      <div class="select">
        <label id="language-selector-label" class="screen-reader-only">
          {{ trans('messages.footer.change_language') }}
        </label>
        {!! Form::select('language',$language, (Session::get('language')) ? Session::get('language') : $default_language[0]->value, ['class' => 'language-selector', 'aria-labelledby' => 'language-selector-label', 'id' => 'language_footer']) !!}
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
  var map_key = "{!! $map_key !!}";
</script>