var daterangepicker_format = $('meta[name="daterangepicker_format"]').attr('content');
var datepicker_format = $('meta[name="datepicker_format"]').attr('content');

app.directive('postsPagination', function() {
    return {
        restrict: 'E',
        template: '<ul class="pagination" ng-show="disputes_result.total">' +
        '<li ng-show="disputes_result.current_page != 1"><a href="javascript:void(0)" ng-click="get_disputes_result(1)">&laquo;</a></li>' +
        '<li ng-show="disputes_result.current_page != 1"><a href="javascript:void(0)" ng-click="get_disputes_result(disputes_result.current_page-1)">&lsaquo; ' + $('#pagin_prev').val() + ' </a></li>' +
        '<li ng-repeat="i in [] | range:disputes_result.last_page:1" ng-class="{active : disputes_result.current_page == i}">' +
        '<a href="javascript:void(0)" ng-click="get_disputes_result(i)">{{i}}</a>' +
        '</li>' +
        '<li ng-show="disputes_result.current_page != disputes_result.last_page"><a href="javascript:void(0)" ng-click="get_disputes_result(disputes_result.current_page+1)"> ' + $('#pagin_next').val() + ' &rsaquo;</a></li>' +
        '<li ng-show="disputes_result.current_page != disputes_result.last_page"><a href="javascript:void(0)" ng-click="get_disputes_result(disputes_result.last_page)">&raquo;</a></li>' +
        '</ul>'
    };
}).controller('disputes', ['$scope', '$http', function($scope, $http) {
    $scope.today = new Date();
    $scope.pageNumber = 1;

    $scope.get_disputes_result = function(pageNumber) {
        $("#threads").addClass('loading');
        if (pageNumber === undefined) {
            pageNumber = $scope.pageNumber;
        }
        $scope.pageNumber = pageNumber;

        var status = $scope.disputes_status;

        $http.post(APP_URL+'/get_disputes?page=' + pageNumber, {status: status}).then(function(response) {
            $scope.disputes_result = response.data.disputes_result;
            $scope.disputes_count = response.data.disputes_count;
            $("#threads").removeClass('loading');
        });
    };

    $(document).ready(function(){
        $scope.get_disputes_result();
    });
}]).filter('range', function() {
  return function(input, total, from) {
    if(from == undefined)
    {
        from = 0;
    }
    total = parseInt(total);

    for (var i=from; i<=total; i++) {
      input.push(i);
  }

  return input;
};
}).controller('dispute_details',['$scope', '$http', function($scope, $http) {

    $scope.detail_slider = function() {
        $('#dispute-gallery').lightSlider({
            gallery: false,
            item:1,
            loop: true,
            pager: false,
            thumbItem:9,
            slideMargin:0,
            enableDrag: false,
            enableTouch:false,
            currentPagerPosition:'left',
            onSliderLoad: function(el) {
                el.lightGallery({
                    selector: '#dispute-gallery .lslide',
                    subHtmlSelectorRelative:true,
                    mode: 'lg-fade',
                    closable:true,
                    autoWidth:true,
                    mousewheel:false,
                    enableDrag:true,
                    enableSwipe:true,
                    loop: true,
                    hideControlOnEnd:true,
                    slideEndAnimatoin:false,
                    thumbItem: 5,
                    thumbnail:true,
                    animateThumb: true,
                });
            }
        });
    };

    $(document).ready(function() {
        $('.bx-prev').addClass('icon icon-chevron-left icon-gray icon-size-2 ');
        $('.bx-prev').text('');
        $('.bx-next').addClass('icon icon-chevron-right icon-gray icon-size-2 ');
        $('.bx-next').text('');
        $scope.detail_slider();
    });

    $('.tabs li > .tab-item').click(function() {
        tab_target = $(this).attr('data-target');
        $('.tabs .tab-item').attr('aria-selected', "false");
        $('.tabs-content div').attr('aria-hidden', "true");

        $(this).attr('aria-selected', "true");
        $('.tabs-content div[data-tab_content="'+tab_target+'"]').attr('aria-hidden', "false");
    });

    $scope.dispute_message_form_errors = [];
    $scope.keep_talking = function() {
        $("#dispute_controls_area").addClass('loading');
        dispute_id = $('#dispute_id').val();
        $http.post(APP_URL+'/dispute_keep_talking/'+dispute_id, $scope.dispute_message).then(function(response) {
            if(response.data.status == 'danger') {
                window.location.reload();
            }
            else if(response.data.status == 'error') {
                $scope.dispute_message_form_errors = response.data.errors;
            }
            else if(response.data.status == 'success') {
                $('#thread-list').prepend(response.data.content);
                if(parseInt($scope.dispute_message.amount) > 0)
                {
                    $('.dispute_amount_accept_panel').addClass('d-none');
                }
                $scope.dispute_message = {'message':'', 'amount': ''};
                $scope.dispute_message_form_errors = [];
            }
            $("#dispute_controls_area").removeClass('loading');
        })
    };

    $scope.involve_site_form_errors = [];
    $scope.involve_site = function() {
        $("#dispute_controls_area").addClass('loading');
        dispute_id = $('#dispute_id').val();
        $http.post(APP_URL+'/dispute_involve_site/'+dispute_id, $scope.involve_site_data).then(function(response) {
            if(response.data.status == 'danger') {
                window.location.reload();
            }
            else if(response.data.status == 'error') {
                $scope.involve_site_form_errors = response.data.errors;
            }
            else if(response.data.status == 'success') {
                $('#thread-list').prepend(response.data.content);
                $scope.involve_site_data = {'message':'', 'amount': ''};
                $scope.involve_site_form_errors = [];
            }
            $("#dispute_controls_area").removeClass('loading');
        })
    };

    $("#dispute_documents").on('change', function(){
        $("#dispute_documents_form").submit();
    });

    $scope.accept_amount_form_errors = [];
    $scope.accept_amount = function() {
        $(".dispute_amount_accept_panel").addClass('loading');
        dispute_id = $('#dispute_id').val();
        $http.post(APP_URL+'/dispute_accept_amount/'+dispute_id, $scope.accept_amount_data).then(function(response) {
            if(response.data.status == 'danger') {
                window.location.reload();
            }
            else if(response.data.status == 'error') {
                $scope.accept_amount_form_errors = response.data.errors;
                $(".dispute_amount_accept_panel").removeClass('loading');
            }
            else if(response.data.status == 'success') {
                window.location.reload();
            }
            else if(response.data.status == 'show_popup') {
                $scope.dispute_payment_data.message = $scope.accept_amount_data.message;
                $("#"+response.data.target).modal('show');
                $(".dispute_amount_accept_panel").removeClass('loading');
            }
        });
    };

    $scope.dispute_payment_data = {'message': ''};
    $scope.dispute_form_errors = [];

    $scope.pay_dispute_amount = function() {
        $("#dispute_payment_popup .modal-content").addClass('loading');
        dispute_id = $('#dispute_id').val();
        $http.post(APP_URL+'/dispute_pay_amount/'+dispute_id, $scope.dispute_payment_data).then(function(response){
            if(response.data.status == 'danger') {
                window.location.reload();
            }
            else if(response.data.status == 'error') {
                $scope.dispute_form_errors = response.data.errors;
                $("#dispute_payment_popup .modal-content").removeClass('loading');
            }
            else if(response.data.status == 'success') {
                window.location.reload();
            }
            else if(response.data.status == 'requires_action') {
                $scope.handleServerResponse(response.data.payment_intent_client_secret);
            }
            else if(response.data.status == 'redirect') {
                window.location.href=response.data.redirect_to;
            }

        });
    };

    $scope.handleServerResponse = function (payment_intent_client_secret) {
        var stripe = Stripe(STRIPE_PUBLISH_KEY);
        stripe.handleCardAction(payment_intent_client_secret)
        .then(function(result) {
          if (result.error) {
            $("#dispute_payment_popup .modal-content").removeClass('loading');
          }
          else {
            // The card action has been handled & The PaymentIntent can be confirmed again on the server
            $scope.dispute_payment_data.payment_intent_id = result.paymentIntent.id;
            $scope.pay_dispute_amount();
          }
        });
    };

    $scope.delete_document = function() {
        var document_id = $scope.id;
        $('#delete_document-popup .modal-content').addClass('loading');

        $http.post('dispute_delete_document', {
            document_id: document_id
        }).then(function(response) {
            if (response.data.success == 'true') {
                window.location.reload();
            } 
        }, function(response) {
            if (response.status == '300')
                window.location = APP_URL + '/login';
        });
    };
}]);