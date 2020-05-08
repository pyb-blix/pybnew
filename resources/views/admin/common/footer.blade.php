<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">Confirm Delete</h4>
                </div>
                <div class="modal-body">
                    <p>You are about to delete one track, this procedure is irreversible.</p>
                    <p>Do you want to proceed?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default confirm-delete_cancel" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-danger btn-ok confirm-delete">Delete</a>
                </div>
            </div>
        </div>
</div>
<div class="modal fade" id="resubmit_listing" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="resubmit_listing close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Resubmit Listing</h4>
            </div>
            <div class="modal-body">
                <div class="col-sm-12" align="right" style="color: #82888a">
                    <span ng-bind="500 - summary.length"> 500 </span> characters left
                </div>
                <div class="col-sm-12">
                    {!! Form::textarea('resubmit_msg', '', ['class' => 'form-control', 'id' => 'resubmit_msg', 'placeholder' => 'Please enter the reason here', 'rows' => 5, 'maxlength'=>500,'ng-model'=>'summary']) !!}
                      <p class="text-danger resubmit_err_msg hide">Resubmit reason is required</p>
                </div>
                
                <input type="hidden" id="resubmit_room_id" name="resubmit_room_id">
                <input type="hidden" id="resubmit_prev_val" name="resubmit_prev_val">                           
              
            </div>
            <div class="modal-footer" style="border-top: 0px solid #e5e5e5;">
                <button type="button" id="resubmit_cancel" name="resubmit_cancel" class="btn btn-default resubmit_listing" data-dismiss="modal">Cancel</button>
                <a class="btn btn-danger btn-ok resubmit">Submit</a>
            </div>
        </div>
    </div>
</div>
<footer class="main-footer">
    <div class="pull-right hidden-xs">
    </div>
    <strong>Copyright &copy; 2017 <a href="">Trioangle Technologies</a>.</strong> All rights
    reserved.
</footer>