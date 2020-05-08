@extends('template')
@section('main')
<main id="site-content" role="main" ng-controller="transaction_history">      
  @include('common.subheader')
  <div class="trasaction-content my-4 my-md-5">
    <div class="container">
      <div class="row">
        <div class="col-md-3 side-nav">
          @include('common.sidenav')
        </div>
        <div class="col-md-9 mt-4 mt-md-0" ng-cloak>
          <div class="card custom-tabs" id="transaction-history">
            <ul role="tablist" class="card-header tabs pb-0" role="tablist" ng-init="trans_lang = {{json_encode(['loading' => trans('messages.account.loading'), 'no_transactions' => trans('messages.account.no_transactions')])}}">
              <li>
                <a href="javascript:void(0);" class="tab-item" role="tab" aria-controls="completed-transactions" aria-selected="true">
                  {{ trans('messages.account.completed_transactions') }}
                </a>
              </li>
              <li>
                <a href="javascript:void(0);" class="tab-item" role="tab" aria-controls="future-transactions" aria-selected="false">
                  {{ trans('messages.account.future_transactions') }}
                </a>
              </li>
            </ul>
            <div id="completed-transactions" class="card-body tab-panel completed-transaction transaction-tab" role="tabpanel" aria-hidden="false">
              <div class="row mt-2">
                <div class="col-7" ng-show="result_show">
                  <h3 class="payout-amount pyamt">
                    <span>
                      {{ trans('messages.account.paid_out') }}: 
                    </span>
                    <span ng-bind-html="paid_out"></span>
                  </h3> 
                </div>
                <div class="col-5 text-right">
                  <a href="{{ url('/') }}/transaction_history/csv/{{ Auth::user()->id }}?@{{ completed_csv_param }}" class="export-csv-link theme-link" ng-show="result_show">
                    {{ trans('messages.account.export_to_csv') }}
                  </a>
                </div>
              </div>
              <input type="hidden" id="pagin_next" value= "{{ trans('messages.pagination.pagi_next') }} ">
              <input type="hidden" id="pagin_prev" value= "{{ trans('messages.pagination.pagi_prev') }} ">
              <div class="payout-filters d-md-flex" ng-init="payout_startMonth=1;payout_endMonth=12;payout_year='{{date('Y')}}'">
                <div class="select col-md-3 p-0">
                  {!! Form::select('payout_method', $payout_methods, '', ['class'=>'payout-method', 'placeholder'=>trans('messages.account.all_payout_methods'), 'ng-model' => 'payout_method','ng-change' => 'pagination_result("completed-transactions",1)']) !!}
                </div>
                <div class="select">
                  {!! Form::select('listing', $lists, '', ['class'=>'payout-listing', 'placeholder'=>trans('messages.account.all_listings'), 'ng-model' => 'payout_listing','ng-change' => 'pagination_result("completed-transactions",1)']) !!}
                </div>
                <div class="select">
                  {!! Form::select('payout_year', $payout_year, '', ['class'=>'payout-year', 'ng-model' => 'payout_year','ng-change' => 'pagination_result("completed-transactions",1)']) !!}
                </div>
                <div class="select">
                  {!! Form::select('start_month', $from_month, 1, ['class'=>'payout-start-month', 'ng-model' => 'payout_startMonth','ng-change' => 'pagination_result("completed-transactions",1)']) !!}
                </div>
                <div class="select" ng-init="payout_endMonth=12">                  
                  <select name="end_month" ng-model="payout_endMonth" class="payout-end-month" ng-change='pagination_result("completed-transactions",1)' ng-init="to_month={{json_encode($to_month)}}">
                    <option  ng-repeat="(key, value) in to_month" value="@{{ key+1 }}" ng-selected="(key+1) == 12" ng-disabled="(key+1)<payout_startMonth">@{{value}}</option>
                  </select>
                </div>
              </div>
              <div class="col-12 p-0 mt-4">
                <table class="table transaction-table mb-4">
                  <thead>
                    <tr>
                      <th>{{ trans('messages.account.date') }}</th>
                      <th>{{ trans('messages.account.type') }}</th>
                      <th>{{ trans('messages.your_reservations.details') }}</th>
                      <th>{{ trans('messages.account.amount') }}</th>
                    </tr>
                  </thead>
                  <tbody ng-show="result_show">
                    <tr ng-repeat="item in result">
                      <td>
                        @{{ item.date }}
                      </td>
                      <td>
                        <span ng-show="@{{ item.user_type == 'guest' }}">{{ trans('messages.account.refund') }}</span>
                        <span ng-show="@{{ item.user_type == 'host' }}">{{ trans('messages.account.payout') }}</span>
                      </td>
                      <td>
                        <span ng-if="item.account != ''">
                          {{ trans('messages.account.transfer_to') }} @{{ item.account }}
                        </span>
                      </td>
                      <td>  
                        <b>
                          <span ng-bind-html="item.currency_symbol"></span>
                          @{{ item.amount }}
                        </b>
                      </td>
                    </tr>
                  </tbody>
                </table>
                <posts-pagination-transaction></posts-pagination-transaction>
              </div>
            </div>
            <div id="future-transactions" class="card-body tab-panel transaction-tab" role="tabpanel" aria-hidden="true">
              <div class="row mt-2">
                <div class="col-7" ng-show="result_show">
                  <h3 class="payout-amount pyamt">
                    <span>
                      {{ trans('messages.account.future_payout') }}/{{ trans('messages.account.refund') }}:
                    </span> 
                    <span ng-bind-html="paid_out"></span>
                  </h3>
                </div>
                <div class="col-5 text-right">
                  <a href="{{ url('/') }}/transaction_history/csv/{{ Auth::user()->id }}?@{{ future_csv_param }}" class="export-csv-link theme-link" ng-show="result_show">
                    {{ trans('messages.account.export_to_csv') }}
                  </a>
                </div>
              </div>
              <div class="payout-filters">
                <div class="select d-none">
                  {!! Form::select('payout_method', $payout_methods, '', ['class'=>'payout-method', 'placeholder'=>trans('messages.account.all_payout_methods'),'ng-model' => 'payout_method', 'ng-change' => 'pagination_result("future-transactions",1)']) !!}
                </div>
                <div class="select col-12 col-md-6 col-lg-5 p-0 my-4">
                  {!! Form::select('listing', $lists, '', ['class'=>'payout-listing', 'placeholder'=>trans('messages.account.all_listings'), 'ng-model' => 'payout_listing','ng-change' => 'pagination_result("future-transactions",1)']) !!}
                </div>
              </div>
              <div class="col-12 p-0">
                <table class="table transaction-table mb-3">
                  <thead>
                    <tr>
                      <th>{{ trans('messages.account.date') }}</th>
                      <th>{{ trans('messages.account.type') }}</th>
                      <th>{{ trans('messages.account.amount') }}</th>
                    </tr>
                  </thead>
                  <tbody ng-show="result_show">
                    <tr ng-repeat="item in result">
                      <td>
                        @{{ item.date }}
                      </td>
                      <td>
                        <span ng-show="@{{ item.user_type == 'guest' }}">
                          {{ trans('messages.account.refund') }}
                        </span>
                        <span ng-show="@{{ item.user_type == 'host' }}">
                          {{ trans('messages.account.payout') }}
                        </span>
                      </td>
                      <td>
                        <b>
                          <span ng-bind-html="item.currency_symbol"></span>
                          @{{ item.amount }}
                        </b>
                      </td>
                    </tr>
                  </tbody>
                </table>
                <posts-pagination-transaction></posts-pagination-transaction>
              </div>
            </div>
          </div>
          <div id="payout-eta-modal"></div>
        </div>
      </div>
    </div>
  </div>
</main>
@stop