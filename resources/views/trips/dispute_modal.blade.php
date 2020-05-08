<div class="modal fade" role="dialog" id="dispute_modal">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
            <h5 class="text-center"> {{ trans('messages.disputes.create_a_dispute') }} </h5>
            <button type="button" class="close icon" data-dismiss="modal"></button>
        </div>
        <div class="modal-body">
            <div id="dipute_form_content" >
                {!! Form::open(['url' => url('disputes/create'), 'id' => 'create_dispute_form', 'method' => 'Post', 'name' => 'create_dispute_form']) !!}
                <div class="panel-body">
                    <div class="control-group">
                        <label class="font-weight-bold" for="dispute_subject"> {{ trans('messages.disputes.dispute_reason') }} </label>
                        <div class="d-flex align-items-center justify-content-center">
                            {!! Form::text('subject', '', ['id' => 'dispute_subject', 'ng-model' => 'dispute_reservation_data.subject']) !!}
                        </div>
                        <p class="text-danger">@{{dispute_form_errors.subject[0]}}</p>
                    </div>

                    <div class="control-group">
                        <label class="font-weight-bold" for="dispute_description"> {{ trans('messages.disputes.dispute_description') }} </label>
                        <div class="d-flex align-items-center justify-content-center">
                            {!! Form::textarea('dispute_description', '', ['rows' => '5','id' => 'dispute_description', 'ng-model' => 'dispute_reservation_data.description']) !!}
                        </div>
                        <p class="text-danger">@{{dispute_form_errors.description[0]}}</p>
                    </div>

                    <div class="control-group">
                        <label class="font-weight-bold" for="dispute_amount"> {{ trans('messages.account.amount') }} </label>
                        <div class="d-flex align-items-center justify-content-center input-addon">
                            <span class="input-prefix">@{{dispute_reservation_data.currency_code}} </span>
                            {!! Form::text('amount', '', ['id' => 'dispute_amount', 'ng-model' => 'dispute_reservation_data.amount', 'class' => 'input-stem']) !!}
                        </div>
                        <p class="text-danger">@{{dispute_form_errors.amount[0]}}</p>
                    </div>

                    <div class="control-group">
                        <label class="font-weight-bold" for="dispute_documents"> {{ trans('messages.disputes.documents') }} </label>
                        <div class="d-flex align-items-center justify-content-center">
                            {!! Form::file('documents',['id' => 'dispute_documents', 'file' => 'dispute_reservation_data.documents', 'multiple']) !!}
                        </div>
                        <p class="text-danger">@{{dispute_form_errors.documents[0]}}</p>
                    </div>
                </div>
                <div class="panel-footer mt-4">
                    <input type="hidden" name="decision" value="decline">
                    <input class="btn btn-primary" id="dispute_submit" name="commit" type="button" ng-click="submit_create_dispute()" value="{{ trans('messages.disputes.create_a_dispute') }}">
                </div>
                {!! Form::close() !!}
            </div>
      </div>
    </div>
  </div>
</div>