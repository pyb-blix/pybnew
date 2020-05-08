<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::match(['get', 'post'], 'api_payments/book/{id?}', 'PaymentPageController@index')->name('api_payments.book');
Route::post('api_payments/pre_accept', 'PaymentPageController@pre_accept');
Route::post('api_payments/create_booking', 'PaymentPageController@create_booking');
Route::get('api_payments/success', 'PaymentPageController@success');
Route::get('api_payments/cancel', 'PaymentPageController@cancel');
Route::post('api_payments/apply_coupon', 'PaymentPageController@apply_coupon');
Route::post('api_payments/remove_coupon', 'PaymentPageController@remove_coupon');
Route::get('experiences/book/{host_experience_id?}', 'HostExperiencePaymentController@index');


Route::group(['prefix' => 'api','middleware' => ['jwt.verify', 'disable_user']],function () {

	Route::post('common_data', 'TokenAuthController@common_data');
	Route::get('login', 'TokenAuthController@login');
	Route::get('signup', 'TokenAuthController@signup');
	Route::post('apple_callback', 'TokenAuthController@apple_callback');
	Route::get('emailvalidation', 'TokenAuthController@emailvalidation');
	Route::get('forgotpassword', 'TokenAuthController@forgotpassword');
	Route::get('logout', 'TokenAuthController@logout');
	Route::match(['get', 'post'], 'add_payout_perference', 'PaymentController@add_payout_perference');

	Route::get('explore', 'SearchController@explore_details');
	Route::get('home', 'SearchController@homePage');
	Route::get('rooms', 'RoomsController@rooms_detail');

	Route::get('room_property_type', 'RoomsController@room_property_type');
	Route::get('amenities_list', 'HomeController@amenities_list');
	Route::get('currency_list', 'HomeController@currency_list');
	Route::get('calendar_availability_status', 'RoomsController@calendar_availability_status');
	Route::get('user_profile_details', 'UserController@user_profile_details');
	Route::get('review_detail', 'RoomsController@review_detail');

	Route::get('host_experience_categories', 'HostExperiencesController@host_experience_categories');
	Route::get('explore_experiences', 'SearchController@explore_experiences');
	Route::get('experience', 'HostExperiencesController@experience_details');
	Route::get('experience_review_detail', 'HostExperiencesController@experience_review_detail');
	Route::get('language','UserController@language');
	Route::get('user_details', 'UserController@user_details');
	Route::get('home_cities', 'UserController@home_cities');
	Route::get('payout_details', 'UserController@payout_details');
	Route::get('payout_changes', 'UserController@payout_changes');
	Route::get('maps', 'RoomsController@maps');
	Route::get('calendar_availability', 'RoomsController@calendar_availability');
	Route::get('country_list', 'HomeController@country_list');
	Route::get('stripe_supported_country_list', 'HomeController@stripe_supported_country_list');
	Route::get('pre_payment', 'PaymentController@pre_payment');
	Route::get('after_payment', 'PaymentController@after_payment');
	Route::get('payment_methods', 'PaymentController@payment_methods');
	Route::get('apply_coupon', 'PaymentController@apply_coupon');
	Route::get('house_rules', 'RoomsController@house_rules');
	Route::get('add_rooms_price', 'RoomsController@add_rooms_price');
	Route::get('update_Long_term_prices', 'RoomsController@update_Long_term_prices');
	Route::get('update_room_currency', 'RoomsController@update_room_currency');
	Route::get('update_location', 'RoomsController@update_location');
	Route::get('disable_listing ', 'RoomsController@disable_listing');
	Route::get('update_price_rule ', 'RoomsController@update_price_rule');
	Route::get('delete_price_rule ', 'RoomsController@delete_price_rule');
	Route::get('update_availability_rule ', 'RoomsController@update_availability_rule');
	Route::get('delete_availability_rule ', 'RoomsController@delete_availability_rule');
	Route::get('get_price_rules_list ', 'RoomsController@get_price_rules_list');
	Route::get('get_availability_rules_list ', 'RoomsController@get_availability_rules_list');
	Route::get('update_minimum_maximum_stay ', 'RoomsController@update_minimum_maximum_stay');
	Route::get('currency_change', 'PaymentController@currency_change');

	Route::get('update_house_rules', 'HomeController@update_house_rules');
	Route::get('update_description', 'HomeController@update_description');
	Route::get('update_title_description', 'RoomsController@update_title_description');
	Route::get('update_amenities', 'HomeController@update_amenities');
	Route::get('add_whishlist', 'HomeController@add_whishlist');
	Route::get('update_calendar', 'HomeController@update_calendar');
	Route::get('home_pending_request', 'HomeController@home_pending_request');
	Route::get('rooms_list_calendar', 'HomeController@rooms_list_calendar');
	Route::match(array('GET', 'POST'),'new_add_room', 'RoomsController@new_add_room');
	Route::get('listing', 'RoomsController@listing_rooms');
	Route::get('room_bed_details', 'RoomsController@room_bed_details');
	Route::get('listing_rooms_beds', 'RoomsController@listing_rooms_beds');
	Route::post('update_bed_detail', 'RoomsController@update_bed_detail');
	Route::get('send_message', 'MessagesController@send_message');
	Route::get('inbox_reservation', 'ReservationController@inbox_reservation');
	Route::get('view_profile', 'UserController@view_profile');
	Route::get('edit_profile', 'UserController@edit_profile');
	Route::get('conversation_list', 'ReservationController@conversation_list');
	Route::get('trips_type', 'TripsController@trips_type');
	Route::get('trips_details', 'TripsController@trips_details');
	Route::get('add_wishlists', 'WishlistsController@add_wishlists');
	Route::get('get_whishlist', 'WishlistsController@get_whishlist');
	Route::get('get_particular_wishlist', 'WishlistsController@get_particular_wishlist');
	Route::get('delete_wishlist', 'WishlistsController@delete_wishlist');
	Route::get('edit_wishlist', 'WishlistsController@edit_wishlist');
	Route::get('book_now', 'PaymentController@book_now');
	Route::get('pay_now', 'PaymentController@pay_now');
	Route::get('payment_success', 'PaymentController@payment_success');
	
	Route::get('guest_cancel_pending_reservation', 'TripsController@guest_cancel_pending_reservation');
	Route::get('guest_cancel_reservation', 'TripsController@guest_cancel_reservation');
	Route::get('reservation_list', 'ReservationController@reservation_list');
	Route::get('update_booking_type', 'RoomsController@update_booking_type');
	Route::get('contact_request', 'RoomsController@contact_request');
	Route::get('update_policy', 'RoomsController@update_policy');
	Route::get('remove_uploaded_image', 'RoomsController@remove_uploaded_image');
	Route::get('host_cancel_reservation', 'ReservationController@host_cancel_reservation');
	Route::get('pre_approve', 'ReservationController@pre_approve');
	Route::get('pre_accept', 'PaymentController@pre_accept');
	Route::get('accept', 'PaymentController@accept');
	Route::get('decline', 'PaymentController@decline');
	Route::get('new_update_calendar', 'HomeController@new_update_calendar');

	/*HostExperiencePHPCommentStart*/
	Route::get('choose_date', 'HostExperiencesController@choose_date');
	Route::get('add_guest_details', 'HostExperiencesController@add_guest_details');
	Route::get('experience_pre_payment', 'HostExperiencePaymentController@experience_pre_payment');
	Route::get('experience_payment', 'HostExperiencePaymentController@book_now');
	Route::get('experiences/{host_experience_id}/book/update_payment_data', 'HostExperiencePaymentController@update_payment_data');
	Route::get('experiences/contact_host', 'HostExperiencesController@contact_host');
	/*HostExperiencePHPCommentEnd*/

	Route::match(array('GET', 'POST'), 'upload_profile_image', 'UserController@upload_profile_image');
	Route::match(array('GET', 'POST'), 'upload_profile_images', 'UserController@upload_profile_images');
	Route::match(array('GET', 'POST'), 'room_image_upload', 'RoomsController@room_image_upload');
	Route::match(array('GET', 'POST'), 'room_image_uploads', 'RoomsController@room_image_uploads');
});