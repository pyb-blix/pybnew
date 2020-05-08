<?php

/*
|--------------------------------------------------------------------------
| Host Experiences Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
 */
Route::group(['middleware' => 'web'], function () {
	/**
	 * No Authentication required routes
	 */

	// Api payment
	Route::post('experiences/{host_experience_id}/book/complete_payment', 'HostExperiencePaymentController@complete_payment');
	Route::get('experiences/{host_experience_id}/book/payment_success', 'HostExperiencePaymentController@payment_success');
	Route::get('experiences/{host_experience_id}/book/payment_cancel', 'HostExperiencePaymentController@payment_cancel');
	Route::post('experiences/{host_experience_id}/book/update_payment_data', 'HostExperiencePaymentController@update_payment_data');

	/**
	 * No Authentication required routes
	 */
	Route::group(['middleware' => ['locale', 'session_check']], function () {
		Route::match(['get', 'post'], 'searchexperienceResult', 'SearchController@searchexperienceResult');
		Route::post('host_experience_photos', 'SearchController@host_experience_photos');
		Route::post('get_wishlists_experience', 'WishlistController@get_wishlists_experience');
	});

	/**
	 * Access only with authentication
	 */
	Route::group(['middleware' => ['locale', 'auth:user', 'session_check','protection']], function () {
			
		Route::match(['get', 'post'], 'host_experience_reviews/edit/{id}', 'UserController@host_experience_reviews_edit')->where('id', '[0-9]+');
		Route::post('save_wishlist_experience', 'WishlistController@save_wishlist_experience');
		
		Route::group(['prefix' => 'host'], function () {
			Route::get('delete_host_experience/{host_experience_id}', 'HostExperiencesController@delete_host_experience')->where('host_experience_id', '[0-9]+');
			Route::match(['get', 'post'], 'experiences/new', 'HostExperiencesController@new_host_experience');
			Route::group(['middleware' => 'manage_experience_auth'],function () {
				Route::get('review_experience/{host_experience_id}', 'HostExperiencesController@review_experience')->where('host_experience_id', '[0-9]+');
				Route::post('ajax_review_experience/{host_experience_id}', 'HostExperiencesController@ajax_review_experience')->where('host_experience_id', '[0-9]+');
				Route::get('manage_experience/{host_experience_id}', 'HostExperiencesController@manage_experience')->where('host_experience_id', '[0-9]+');
				Route::post('ajax_manage_experience/{host_experience_id}', 'HostExperiencesController@ajax_manage_experience')->where('host_experience_id', '[0-9]+');
				Route::post('manage_experience/{host_experience_id}/update_experience', 'HostExperiencesController@update_experience')->where('host_experience_id', '[0-9]+');
				Route::post('manage_experience/{host_experience_id}/upload_photo', 'HostExperiencesController@upload_photo')->where('host_experience_id', '[0-9]+');
				Route::post('manage_experience/{host_experience_id}/delete_photo', 'HostExperiencesController@delete_photo')->where('host_experience_id', '[0-9]+');
				Route::post('manage_experience/{host_experience_id}/refresh_calendar', 'HostExperiencesController@refresh_calendar')->where('host_experience_id', '[0-9]+');
				Route::post('manage_experience/{host_experience_id}/update_calendar', 'HostExperiencesController@update_calendar')->where('host_experience_id', '[0-9]+');
			});
		});

		Route::get('experiences/{host_experience_id}/book/{tab}', 'HostExperiencePaymentController@index');
	});

	/**
	 * No Authentication required routes
	 */
	Route::group(['middleware' => ['locale', 'session_check','protection']], function () {

		Route::get('host/experiences', 'HostExperiencesController@index');
		Route::get('host/experiences/set_city', 'HostExperiencesController@set_city');
		Route::get('experiences/{host_experience_id}', 'HostExperiencesController@experience_detail')->where('host_experience_id', '[0-9]+');
		Route::match(['get', 'post'], 'experiences/{host_experience_id}/get_available_dates', 'HostExperiencesController@get_available_dates')->where('host_experience_id', '[0-9]+');
		Route::match(['get', 'post'], 'experiences/{host_experience_id}/choose_date', 'HostExperiencesController@choose_date')->where('host_experience_id', '[0-9]+');
		Route::match(['get', 'post'], 'experiences/{host_experience_id}/get_all_reviews', 'HostExperiencesController@get_all_reviews')->where('host_experience_id', '[0-9]+');
		Route::post('experiences/{host_experience_id}/contact_host', 'HostExperiencesController@contact_host')->where('host_experience_id', '[0-9]+');
	});
});

/*
|--------------------------------------------------------------------------
| Host Experience API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
 */
Route::group(['middleware' => ['jwt.verify', 'api','disable_user']], function () {

	Route::get('experiences/book/{host_experience_id?}', 'Api\HostExperiencePaymentController@index');

	Route::group(['prefix' => 'api'],function () {

		Route::get('host_experience_categories', 'Api\HostExperiencesController@host_experience_categories');
		Route::get('explore_experiences', 'Api\SearchController@explore_experiences');
		Route::get('experience', 'Api\HostExperiencesController@experience_details');
		Route::get('experience_review_detail', 'Api\HostExperiencesController@experience_review_detail');

		Route::group(['middleware' => ['jwt.auth', 'disable_user']],function () {
			Route::get('choose_date', 'Api\HostExperiencesController@choose_date');
			Route::get('add_guest_details', 'Api\HostExperiencesController@add_guest_details');
			Route::get('experience_pre_payment', 'Api\HostExperiencePaymentController@experience_pre_payment');

			Route::get('experience_payment', 'Api\HostExperiencePaymentController@book_now');
			Route::get('experiences/{host_experience_id}/book/update_payment_data', 'Api\HostExperiencePaymentController@update_payment_data');
			Route::get('experiences/contact_host', 'Api\HostExperiencesController@contact_host');
		});
	});
});