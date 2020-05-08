@extends('template')
@section('main')
<main id="site-content" role="main" ng-controller="help">
  <div class="help_page_banner" style="background-image:url({{$help_page_cover_image}})">
    <div class="container">
      <div class="d-flex align-items-center justify-content-center help_page_search">
        <div class="col-md-10 col-12 help_search_block">
          <h1>
            {{ trans('messages.help.welcome') }}
          </h1>
          <form class="search-input-container" id="help-search-container">
            <i class="icon icon-search"></i>
            <input class="search-input" type="text" name="q" autocomplete="off" maxlength="1024" value="" placeholder="{{ trans('messages.help.search_anything') }}" id="help_search">
          </form>
        </div>
      </div>
    </div>
  </div>
  <div class="help-nav">
    <div class="subnav">
      <div class="container">
        <ul class="subnav-list">
          <li>
            <a class="subnav-item" href="{{ route('help_home') }}">
              {{ trans('messages.help.help_center') }}
            </a>
          </li>
          @if ((@$is_subcategory != 'no' || Route::current()->uri() != 'help/topic/{id}/{category}') && (@$is_subcategory != 'no' || Route::current()->uri() != 'help/article/{id}/{question}') && Route::current()->uri() != 'help')
          <li>
            <a class="subnav-item" href="#" data-node-id="0" aria-selected="true">
              {{ @$result[0]->category_name }}
            </a>
          </li>
          @endif
        </ul>
      </div>
    </div>
    <div class="help_container py-md-5">
      <div class="container">
        <div class="row">
          <div class="col-md-3 col-12 left-menu">
            <div class="sub_menu_header d-md-none">
              <h2> @lang('messages.help.help_center')</h2>
              <i class="fa fa-bars"></i>
            </div>
            <div class="navtree">
              <ul class="navtree-list" id="navtree">
                @for($i=0; $i < count($category); $i++)
                @if($category[$i]->category->status=="Active")
                <li class="sidenav-item new_tree sidenav-item-{{ $category[$i]->category_id }} {{ ((Route::current()->uri() == 'help/topic/{id}/{category}' || Route::current()->uri() == 'help/article/{id}/{question}') && ($category[$i]->category->id == @$result[0]->category_id) || request()->segment(2) == '' || $is_subcategory == 'no') ? '' : 'd-none' }}">
                  <a href="{{ (count($category[$i]->subcategory)) ? 'javascript:void(0);' : url('help/topic/'.$category[$i]->category_id.'/'.str_slug($category[$i]->category_name,'-')) }}" class="{{ (count($category[$i]->subcategory)) ? 'navtree-next' : '' }} {{ ((Route::current()->uri() == 'help/topic/{id}/{category}' || Route::current()->uri() == 'help/article/{id}/{question}') && ($category[$i]->category->id == @$result[0]->category_id) && $is_subcategory != 'no') ? 'd-none selected' : '' }}" data-id="{{ $category[$i]->category_id }}" data-name="{{ $category[$i]->category_name }}" aria-selected="{{ ((Route::current()->uri() == 'help/topic/{id}/{category}' || Route::current()->uri() == 'help/article/{id}/{question}') && ($category[$i]->category->id == @$result[0]->category_id)) ? 'true' : 'false' }}"> {{ $category[$i]->category_name }}
                    <span class="help-arrow"><i class="icon icon-chevron-right"></i></span>
                  </a>
                  @if(count($category[$i]->subcategory))
                  <ul class="navtree-list new_view" id="navtree-{{ $category[$i]->category_id }}" style="{{ (Route::current()->uri() == 'help/topic/{id}/{category}' || Route::current()->uri() == 'help/article/{id}/{question}') ? ((@$result[0]->category_id == $category[$i]->category->id) ? 'display:block;' : '') : '' }}">
                    <li class="sidenav-item">
                      <a href="javascript:void(0);" class="navtree-back" data-id="{{ $category[$i]->category_id }}" data-name="{{ $category[$i]->category->name }}">
                        <i class="icon icon-arrow-left"></i>
                        {{ trans('messages.lys.back') }}
                      </a>
                    </li>
                    @for($j=0; $j < count($category[$i]->subcategory); $j++)
                    @if($category[$i]->subcategory[$j]->status=="Active")
                    @if($category[$i]->subcategory_($category[$i]->subcategory[$j]->id)->count())
                    <li class="sub_tree sidenav-item">
                      <a href="{{ url('help/topic/'.$category[$i]->subcategory[$j]->id.'/'.str_slug($category[$i]->subcategory[$j]->name,'-')) }}" aria-selected="{{ (@$result[0]->subcategory_id == $category[$i]->subcategory[$j]->id && Route::current()->uri() != 'help') ? 'true' : 'false' }}">
                        {{ $category[$i]->subcategory[$j]->name_lang }}
                      </a>
                    </li>
                    @endif
                    @endif
                    @endfor
                  </ul>
                  @endif
                </li>
                @endif
                @endfor
              </ul>
            </div>
          </div>
          @if (Route::current()->uri() == 'help/topic/{id}/{category}')
          <div class="col-12 col-md-9">
            <h3>
              {{ (@$subcategory_count == 0) ? @$result[0]->category_name : @$result[0]->subcategory_name }}
            </h3>
            @foreach($result as $row)
            <div class="homepage-articles-list inner_articles">
              <a href="{{ url('help/article/'.$row->id.'/'.str_slug($row->question,'-')) }}" class="article-link  d-flex align-items-center">
                <div class="article-link-left">
                  <i class="icon icon-description"></i>
                </div>
                <div class="article-link-right">
                  {{ str_replace('SITE_NAME', $site_name, $row->question) }}
                </div>
              </a>
            </div>
            @endforeach
          </div>
          @elseif (Route::current()->uri() == 'help/article/{id}/{question}')
          <div class="col-12 col-md-9 navtree-content">
            <h2>
              {{ str_replace('SITE_NAME', $site_name, $result[0]->question) }}
            </h2>
            <p>
              {!! str_replace('SITE_NAME', $site_name, $result[0]->answer) !!}
            </p>
          </div>
          @else
          <div class="col-md-9 col-12 mb-4 mb-md-0">
            <div class="popular-topics">
              <h2> @lang('messages.help.suggested_helps') </h2>
              @foreach($result as $row)
              <div class="homepage-articles-list">
                <a href="{{ url('help/article/'.$row->id.'/'.str_slug($row->question,'-')) }}" class="article-link  d-flex align-items-center">
                  <div class="article-link-left">
                    <i class="icon icon-description"></i>
                  </div>
                  <div class="article-link-right">
                    {{ str_replace('SITE_NAME', $site_name, $row->question) }}
                  </div>
                </a>
              </div>
              @endforeach
              @if($result->count() == 0)
              {{ trans('messages.help.no_suggested_helps') }}
              @endif
            </div>
          </div>
          @endif
        </div>
      </div>
    </div>
  </div>
  <div class="help_nav">
  </div>
</main>
@stop