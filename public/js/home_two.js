var daterangepicker_format = $('meta[name="daterangepicker_format"]').attr('content');
var datepicker_format = $('meta[name="datepicker_format"]').attr('content');

$('.burger--sm').click(function() {
  $('.header--sm .nav--sm').css('visibility', 'visible');
  $('.makent-header .header--sm .nav-content--sm').addClass('right-content');
  $('.arrow-icon').toggleClass('fa-angle-down');
  $('.arrow-icon').toggleClass('fa-angle-up');
  $('.arrow-icon1').toggleClass('fa-bars');
  $('.arrow-icon1').toggleClass('fa-bars-up');
  $("body").addClass("pos-fix");
  $("body").addClass("remove-pos-fix pos-fix");
  $('.makent-header .header--sm .title--sm').toggleClass('hide');
});

$('.nav-mask--sm').click(function() {
  $('.header--sm .nav--sm').css('visibility', 'hidden');
  $('.makent-header .header--sm .nav-content--sm').removeClass('right-content');
  $('.arrow-icon').toggleClass('fa-angle-down');
  $('.arrow-icon').toggleClass('fa-angle-up');
  $('.arrow-icon1').toggleClass('fa-bars');
  $('.arrow-icon1').toggleClass('fa-bars-up');
  $("body").removeClass("remove-pos-fix pos-fix");
  $('.makent-header .header--sm .title--sm').toggleClass('hide');
});

$('.foryou').click(function() {
  $('.foryou').addClass('current');
  $('.homes').removeClass('current');
  $('.experiences').removeClass('current');
});

$('.homes').click(function() {
  $('.foryou').removeClass('current');
  $('.homes').addClass('current');
  $('.experiences').removeClass('current');
});

$('.home-menu .experiences').click(function() {
  $('.foryou').removeClass('current');
  $('.homes').removeClass('current');
  $('.experiences').addClass('current');
});

$('body').click(function() {
  $('.tooltip.tooltip-top-right.dropdown-menu.drop-down-menu-login').removeClass('show');
  $('.panel-drop-down').addClass('hide-drop-down');
});

$('.button-sm-search').click(function(e) {
  e.stopPropagation();
  $('#search-modal--sm').removeClass('hide');
  $('#search-modal--sm').attr('aria-hidden', 'false');
});

$('.arrow-button').click(function(e) {
  e.stopPropagation();
  $('.panel-drop-down').toggleClass('hide-drop-down');
});

$('.home-bx-slider .bxslider:not(.host_experience_bxslider)').bxSlider({
  infiniteLoop: false,
  hideControlOnEnd: true,
  minSlides: 1,
  maxSlides: 3,
  slideWidth: 320,
  slideMargin: 20,
  moveSlides: 1,
  onSliderLoad: function() {
    setTimeout(function() {
      $("#lazy_load_slider").removeClass('lazy-load');
    }, 2000);
  }
});

start = moment();
$('.webcot-lg-datepicker button').daterangepicker({
  startDate: start,
  minDate: start,
  dateLimitMin:{
    "days": 1
  },
  autoApply: true,
  autoUpdateInput: false,
  locale: {
    format: daterangepicker_format
  },
});

$('.webcot-lg-datepicker button').on('show.daterangepicker', function(ev, picker) {
  $(this).css('opacity', 0);
});

$('.webcot-lg-datepicker button').on('apply.daterangepicker', function(ev, picker) {
  startDateInput = $('[name="checkin"]');
  endDateInput = $('[name="checkout"]');

  startDate = picker.startDate;
  endDate = picker.endDate;

  startDateInput.val(startDate.format(daterangepicker_format));
  startDateInput.next().html(startDate.format(daterangepicker_format));
  endDateInput.val(endDate.format(daterangepicker_format));
  endDateInput.next().html(endDate.format(daterangepicker_format));
});

$(".webcot-lg-datepicker button").on('hide.daterangepicker', function(ev, picker) {
  if (!picker.startDate || !picker.endDate) {
   $(this).css('opacity', 1);
  }
});

// bx slider modified
$(document).ready(function() {
   $('.home-bx-slider .bxslider').each(function(i, slider) {
    var a = $(slider).children('li').length;
    li_width = 320/*li width static*/ + 30 /*li margin right*/;
    slider_width = (a*li_width);
    $(slider).css('width', slider_width+'px');
  });

});