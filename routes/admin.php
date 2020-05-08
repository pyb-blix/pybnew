<?php

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register admin routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['middleware' => 'guest:admin'], function () {
    Route::get('login', 'AdminController@login')->name('admin_login');
    Route::get('get_sliders', 'AdminController@get_sliders');
});

Route::group(['middleware' => ['auth:admin','protection']], function () {
    Route::get('/', function () {
        return redirect()->route('admin_dashboard');
    });
    Route::get('logout', 'AdminController@logout')->name('admin_logout');

    // Dashboard
    Route::get('dashboard', 'AdminController@index')->name('admin_dashboard');

    // Manage Admin Users , Roles & Permissions
    Route::group(['middleware' => 'admin_can:manage_admin'], function () {
        Route::get('admin_users', 'AdminusersController@index')->name('admin_users');
        Route::match(array('GET', 'POST'), 'add_admin_user', 'AdminusersController@add');
        Route::match(array('GET', 'POST'), 'edit_admin_user/{id}', 'AdminusersController@update')->where('id', '[0-9]+');
        Route::get('delete_admin_user/{id}', 'AdminusersController@delete')->where('id', '[0-9]+');
        Route::get('roles', 'RolesController@index')->name('roles');;
        Route::match(array('GET', 'POST'), 'add_role', 'RolesController@add');
        Route::match(array('GET', 'POST'), 'edit_role/{id}', 'RolesController@update')->where('id', '[0-9]+');
        Route::get('delete_role/{id}', 'RolesController@delete')->where('id', '[0-9]+');
        Route::match(array('GET', 'POST'), 'add_permission', 'PermissionsController@add');
        Route::match(array('GET', 'POST'), 'edit_permission/{id}', 'PermissionsController@update')->where('id', '[0-9]+');
        Route::get('delete_permission/{id}', 'PermissionsController@delete')->where('id', '[0-9]+');
    });

    // Manage Users
    Route::get('users', 'UsersController@index')->middleware('admin_can:users')->name('users');
    Route::match(array('GET', 'POST'), 'add_user', 'UsersController@add')->middleware('admin_can:add_user');
    Route::match(array('GET', 'POST'), 'edit_user/{id}', 'UsersController@update')->where('id', '[0-9]+')->middleware('admin_can:edit_user');
    Route::get('delete_user/{id}', 'UsersController@delete')->where('id', '[0-9]+')->middleware('admin_can:delete_user');

    // Manage Rooms
    Route::get('rooms', 'RoomsController@index')->middleware('admin_can:rooms')->name('rooms');
    Route::match(array('GET', 'POST'), 'add_room', 'RoomsController@add')->middleware('admin_can:add_room')->name('admin.add_room');
    Route::post('admin_update_rooms', 'RoomsController@update_video');
    Route::match(array('GET', 'POST'), 'edit_room/{id}', 'RoomsController@update')->where('id', '[0-9]+')->middleware('admin_can:edit_room')->name('admin.edit_room');
    Route::get('delete_room/{id}', 'RoomsController@delete')->where('id', '[0-9]+')->middleware('admin_can:delete_room');
    Route::get('popular_room/{id}', 'RoomsController@popular')->where('id', '[0-9]+')->where('id', '[0-9]+');
    Route::get('recommended_room/{id}', 'RoomsController@recommended')->where('id', '[0-9]+')->where('id', '[0-9]+');
    Route::post('ajax_calendar/{id}', 'RoomsController@ajax_calendar')->where('id', '[0-9]+');
    Route::post('resubmit_listing','RoomsController@resubmit_listing');
    Route::post('delete_photo', 'RoomsController@delete_photo');
    Route::post('admin_pricelist', 'RoomsController@update_price');
    Route::post('featured_image', 'RoomsController@featured_image');
    Route::post('photo_highlights', 'RoomsController@photo_highlights');
    Route::get('rooms/users_list', 'RoomsController@users_list');
    Route::post('rooms/delete_price_rule/{id}', 'RoomsController@delete_price_rule')->where('id', '[0-9]+');
    Route::post('rooms/delete_availability_rule/{id}', 'RoomsController@delete_availability_rule')->where('id', '[0-9]+');
    Route::get('update_room_status/{id}/{type}/{option}', 'RoomsController@update_room_status');

    /*HostExperiencePHPCommentStart*/

    // Manage Host Experiences
    Route::get('host_experiences', 'HostExperiencesController@index')->middleware('admin_can:manage_host_experiences')->name('host_experiences');
    Route::match(array('GET', 'POST'), 'host_experiences/add', 'HostExperiencesController@add');
    Route::match(array('GET', 'POST'), 'host_experiences/edit/{id}', 'HostExperiencesController@update')->where('id', '[0-9]+');
    Route::get('host_experiences/delete/{id}', 'HostExperiencesController@delete')->where('id', '[0-9]+');
    Route::post('host_experiences/photo_delete/{id}', 'HostExperiencesController@photo_delete')->where('id', '[0-9]+');
    Route::post('host_experiences/provide_item_delete/{id}', 'HostExperiencesController@provide_item_delete')->where('id', '[0-9]+');
    Route::post('host_experiences/packing_list_delete/{id}', 'HostExperiencesController@packing_list_delete')->where('id', '[0-9]+');
    Route::post('host_experiences/refresh_calendar/{id}', 'HostExperiencesController@refresh_calendar')->where('id', '[0-9]+');
    Route::post('update_hostexperience_status', 'HostExperiencesController@update_hostexperience_status');
    Route::get('feature_experience/{id}', 'HostExperiencesController@feature')->where('id', '[0-9]+')->where('id', '[0-9]+');

    // Manage Host Experience Cities
    Route::group(['middleware' => 'admin_can:manage_host_experience_cities'], function () {
        Route::get('host_experience_cities', 'HostExperienceCitiesController@index')->name('host_experience_cities');
        Route::match(array('GET', 'POST'), 'host_experience_cities/add', 'HostExperienceCitiesController@add');
        Route::match(array('GET', 'POST'), 'host_experience_cities/edit/{id}', 'HostExperienceCitiesController@update')->where('id', '[0-9]+');
        Route::get('host_experience_cities/delete/{id}', 'HostExperienceCitiesController@delete')->where('id', '[0-9]+');
    });

    // Manage Host Experience Categories
    Route::group(['middleware' => 'admin_can:manage_host_experience_categories'], function () {
        Route::get('host_experience_categories', 'HostExperienceCategoriesController@index')->name('host_experience_categories');
        Route::match(array('GET', 'POST'), 'host_experience_categories/add', 'HostExperienceCategoriesController@add');
        Route::match(array('GET', 'POST'), 'host_experience_categories/edit/{id}', 'HostExperienceCategoriesController@update')->where('id', '[0-9]+');
        Route::get('host_experience_categories/delete/{id}', 'HostExperienceCategoriesController@delete')->where('id', '[0-9]+');
        Route::get('feature_experience_categories/{id}', 'HostExperienceCategoriesController@feature')->where('id', '[0-9]+')->where('id', '[0-9]+');
    });

    // Manage Host Experience Provide Items
    Route::group(['middleware' => 'admin_can:manage_host_experience_provide_items'], function () {
        Route::get('host_experience_provide_items', 'HostExperienceProvideItemsController@index')->name('host_experience_provide_items');
        Route::match(array('GET', 'POST'), 'host_experience_provide_items/add', 'HostExperienceProvideItemsController@add');
        Route::match(array('GET', 'POST'), 'host_experience_provide_items/edit/{id}', 'HostExperienceProvideItemsController@update')->where('id', '[0-9]+');
        Route::get('host_experience_provide_items/delete/{id}', 'HostExperienceProvideItemsController@delete')->where('id', '[0-9]+');
    });

     // Host Experience Reservation Routes
    Route::group(['middleware' => 'admin_can:manage_host_experiences_reservation'], function () {
        Route::get('host_experiences_reservation', 'ReservationsController@host_experiences')->name('host_experiences_reservation');
        Route::get('host_experiences_reservation/detail/{id}', 'ReservationsController@host_experience_detail')->where('id', '[0-9]+')->name('experience_reservation_details');
        Route::get('host_experiences_inquiries', 'HostExperienceCategoriesController@host_experiences_inquiries')->name('host_experiences_inquiries');
    });

    // Host Experience Reviews
    Route::match(array('GET', 'POST'), 'host_experiences_reviews', 'ReviewsController@host_experiences_reviews')->middleware(['admin_can:manage_host_experiences_reviews'])->name('host_experiences_reviews');
    Route::match(array('GET', 'POST'), 'exp_edit_review/{id}', 'ReviewsController@exp_update')->where('id', '[0-9]+')->middleware(['admin_can:manage_host_experiences_reviews']);

    /*HostExperiencePHPCommentEnd*/

    // Manage Reservations
    Route::group(['middleware' => 'admin_can:reservations'], function() {
        Route::get('reservations', 'ReservationsController@index')->name('reservations');
        Route::get('reservation/detail/{id}', 'ReservationsController@detail')->where('id', '[0-9]+');
        Route::get('reservation/conversation/{id}', 'ReservationsController@conversation')->where('id', '[0-9]+');
        Route::get('reservation/need_payout_info/{id}/{type}/{list_type?}', 'ReservationsController@need_payout_info');
        Route::post('reservation/payout', 'ReservationsController@payout');
        Route::get('host_penalty', 'HostPenaltyController@index')->name('host_penalty');
    });

    // Manage Disputes
    Route::group(['middleware' => 'admin_can:manage_disputes'], function () {
        Route::get('disputes', 'DisputesController@index')->name('disputes');
        Route::get('dispute/details/{id}', 'DisputesController@details');
        Route::get('dispute/close/{id}', 'DisputesController@close');
        Route::post('dispute_admin_message/{id}', 'DisputesController@admin_message');
        Route::get('dispute_confirm_amount/{id}', 'DisputesController@confirm_amount');
    });

    // Manage Email Settings & Send email
    Route::match(array('GET', 'POST'), 'email_settings', 'EmailController@index')->middleware(['admin_can:email_settings'])->name('email_settings');
    Route::match(array('GET', 'POST'), 'send_email', 'EmailController@send_email')->middleware(['admin_can:send_email'])->name('send_email');
   
    Route::match(array('GET', 'POST'), 'email_template', 'EmailController@email_template')->middleware(['admin_can:send_email'])->name('email_template');
    Route::match(array('GET', 'POST'), 'edit_template/{id}', 'EmailController@edit_template')->where('id', '[0-9]+')->name('edit_template');

    // Manage Reviews
    Route::group(['middleware' => 'admin_can:manage_reviews'], function () {
        Route::match(array('GET', 'POST'), 'reviews', 'ReviewsController@index')->name('reviews');
        Route::match(array('GET', 'POST'), 'edit_review/{id}', 'ReviewsController@update')->where('id', '[0-9]+');
    });

     // Manage Referrals Routes
    Route::group(['middleware' => 'admin_can:manage_referrals'], function () {
        Route::get('referrals', 'ReferralsController@index')->name('referrals');
        Route::get('referral_details/{id}', 'ReferralsController@details')->where('id', '[0-9]+');
    });

    // Manage Wishlists
    Route::group(['middleware' => 'admin_can:manage_wishlists'], function () {
    Route::match(array('GET', 'POST'), 'wishlists', 'WishlistController@index')->name('wishlists');
    Route::match(array('GET', 'POST'), 'pick_wishlist/{id}', 'WishlistController@pick')->where('id', '[0-9]+')->name('pick_wishlist');
    });

    // Manage Coupon Code
    Route::group(['middleware' => 'admin_can:manage_coupon_code'], function () {
        Route::get('coupon_code', 'CouponCodeController@index')->name('coupon_code');
        Route::match(array('GET', 'POST'), 'add_coupon_code', 'CouponCodeController@add');
        Route::match(array('GET', 'POST'), 'edit_coupon_code/{id}', 'CouponCodeController@update')->where('id', '[0-9]+');
        Route::get('delete_coupon_code/{id}', 'CouponCodeController@delete');
    });

    // Manage Reports
    Route::group(['middleware' => 'admin_can:reports'], function () {
        Route::match(['GET', 'POST'], 'reports', 'ReportsController@index')->name('reports');
        Route::get('reports/export/{from}/{to}/{category}', 'ReportsController@export');
    });

    // Manage Home Cities
    Route::group(['middleware' => 'admin_can:manage_home_cities'], function () {
        Route::resource('home_cities','HomeCitiesController')->except(['destroy','edit'])->names(['index' => 'home_cities','create' => 'home_cities.add','store' => 'home_cities.store','update' => 'home_cities.update']);
        Route::get('edit_home_city/{id}', 'HomeCitiesController@edit')->name('home_cities.edit');
        Route::get('delete_home_cities/{id}', 'HomeCitiesController@destroy')->where('id', '[0-9]+')->name('home_cities.delete');
    });

    // Manage Login Slider
    Route::group(['middleware' => 'admin_can:manage_login_sliders'],function () {
        Route::get('slider', 'SliderController@index')->name('slider');
        Route::match(array('GET', 'POST'), 'add_slider', 'SliderController@add');
        Route::match(array('GET', 'POST'), 'edit_slider/{id}', 'SliderController@update')->where('id', '[0-9]+');
        Route::get('delete_slider/{id}', 'SliderController@delete')->where('id', '[0-9]+');
    });

    // Manage Home Page Sliders
    Route::group(['middleware' => 'admin_can:manage_home_sliders'], function () {
        Route::resource('homepage_sliders','HomePageSlidersController')->except(['destroy','edit'])->names(['index' => 'homepage_sliders','create' => 'homepage_sliders.add','store' => 'homepage_sliders.store', 'update' => 'homepage_sliders.update']);
        Route::get('edit_home_slider/{id}', 'HomePageSlidersController@edit')->name('homepage_sliders.edit');
        Route::get('delete_home_slider/{id}', 'HomePageSlidersController@destroy')->where('id', '[0-9]+')->name('homepage_sliders.delete');
    });

    // Manage Our Community Banner
    Route::group(['middleware' => 'admin_can:manage_our_community_banners'],function () {
        Route::get('our_community_banners', 'OurCommunityBannersController@index')->name('our_community_banners');
        Route::match(array('GET', 'POST'), 'add_our_community_banners', 'OurCommunityBannersController@add');
        Route::match(array('GET', 'POST'), 'edit_our_community_banners/{id}', 'OurCommunityBannersController@update')->where('id', '[0-9]+');
        Route::get('delete_our_community_banners/{id}', 'OurCommunityBannersController@delete')->where('id', '[0-9]+');
    });

    // Manage Help
    Route::group(['middleware' => 'admin_can:manage_help'], function () {
        Route::get('help_category', 'HelpCategoryController@index')->name('help_category');
        Route::match(array('GET', 'POST'), 'add_help_category', 'HelpCategoryController@add');
        Route::match(array('GET', 'POST'), 'edit_help_category/{id}', 'HelpCategoryController@update')->where('id', '[0-9]+');
        Route::get('delete_help_category/{id}', 'HelpCategoryController@delete')->where('id', '[0-9]+');
        Route::get('help_subcategory', 'HelpSubCategoryController@index')->name('help_subcategory');
        Route::match(array('GET', 'POST'), 'add_help_subcategory', 'HelpSubCategoryController@add');
        Route::match(array('GET', 'POST'), 'edit_help_subcategory/{id}', 'HelpSubCategoryController@update')->where('id', '[0-9]+');
        Route::get('delete_help_subcategory/{id}', 'HelpSubCategoryController@delete')->where('id', '[0-9]+');
        Route::get('help', 'HelpController@index')->name('help');
        Route::match(array('GET', 'POST'), 'add_help', 'HelpController@add')->name('add_help');
        Route::match(array('GET', 'POST'), 'edit_help/{id}', 'HelpController@update')->where('id', '[0-9]+')->name('edit_help');
        Route::get('delete_help/{id}', 'HelpController@delete')->where('id', '[0-9]+')->name('delete_help');
        Route::post('ajax_help_subcategory/{id}', 'HelpController@ajax_help_subcategory')->where('id', '[0-9]+')->name('ajax_help_subcategory');
    });

    // Manage Amenities
    Route::group(['middleware' => 'admin_can:manage_amenities'], function () {
        Route::get('amenities', 'AmenitiesController@index')->name('amenities');
        Route::match(array('GET', 'POST'), 'add_amenity', 'AmenitiesController@add');
        Route::match(array('GET', 'POST'), 'edit_amenity/{id}', 'AmenitiesController@update')->where('id', '[0-9]+');
        Route::get('delete_amenity/{id}', 'AmenitiesController@delete')->where('id', '[0-9]+');
        Route::get('amenities_type', 'AmenitiesTypeController@index')->name('amenities_type');
        Route::match(array('GET', 'POST'), 'add_amenities_type', 'AmenitiesTypeController@add');
        Route::match(array('GET', 'POST'), 'edit_amenities_type/{id}', 'AmenitiesTypeController@update')->where('id', '[0-9]+');
        Route::get('delete_amenities_type/{id}', 'AmenitiesTypeController@delete')->where('id', '[0-9]+');
    });

    // Manage Property Type
    Route::group(['middleware' => 'admin_can:manage_property_type'], function () {
        Route::get('property_type', 'PropertyTypeController@index')->name('property_type');
        Route::match(array('GET', 'POST'), 'add_property_type', 'PropertyTypeController@add');
        Route::match(array('GET', 'POST'), 'edit_property_type/{id}', 'PropertyTypeController@update')->where('id', '[0-9]+');
        Route::get('delete_property_type/{id}', 'PropertyTypeController@delete')->where('id', '[0-9]+');
    });

    // Manage Room Type
    Route::group(['middleware' => 'admin_can:manage_room_type'],function () {
        Route::get('room_type', 'RoomTypeController@index')->name('room_type');
        Route::match(array('GET', 'POST'), 'add_room_type', 'RoomTypeController@add');
        Route::match(array('GET', 'POST'), 'edit_room_type/{id}', 'RoomTypeController@update')->where('id', '[0-9]+');
        Route::match(array('GET', 'POST'), 'status_check/{id}', 'RoomTypeController@chck_status')->where('id', '[0-9]+');
        Route::match(array('GET', 'POST'), 'bed_status_check/{id}', 'BedTypeController@chck_status')->where('id', '[0-9]+');
        Route::get('delete_room_type/{id}', 'RoomTypeController@delete')->where('id', '[0-9]+');
    });

    // Manage Bed Type
    Route::group(['middleware' => 'admin_can:manage_bed_type'], function () {
        Route::get('bed_type', 'BedTypeController@index')->name('bed_type');
        Route::match(array('GET', 'POST'), 'add_bed_type', 'BedTypeController@add');
        Route::match(array('GET', 'POST'), 'edit_bed_type/{id}', 'BedTypeController@update')->where('id', '[0-9]+');
        Route::get('delete_bed_type/{id}', 'BedTypeController@delete')->where('id', '[0-9]+');
    });

    // Manage Pages
    Route::group(['middleware' => 'admin_can:manage_pages'], function () {
        Route::get('pages', 'PagesController@index')->name('pages');
        Route::match(array('GET', 'POST'), 'add_page', 'PagesController@add')->name('add_page');
        Route::match(array('GET', 'POST'), 'edit_page/{id}', 'PagesController@update')->where('id', '[0-9]+')->name('edit_page');
        Route::match(array('GET', 'POST'), 'page_status_check/{id}', 'PagesController@chck_status')->where('id', '[0-9]+');
        Route::get('delete_page/{id}', 'PagesController@delete')->where('id', '[0-9]+')->name('delete_page');;
    });

    // Manage Currency
    Route::group(['middleware' => 'admin_can:manage_currency'], function () {
        Route::get('currency', 'CurrencyController@index')->name('currency');
        Route::match(array('GET', 'POST'), 'add_currency', 'CurrencyController@add');
        Route::match(array('GET', 'POST'), 'edit_currency/{id}', 'CurrencyController@update')->where('id', '[0-9]+');
        Route::get('delete_currency/{id}', 'CurrencyController@delete')->where('id', '[0-9]+');
    });

    // Manage Language
    Route::group(['middleware' => 'admin_can:manage_language'], function () {
        Route::get('language', 'LanguageController@index')->name('language');
        Route::match(array('GET', 'POST'), 'add_language', 'LanguageController@add');
        Route::match(array('GET', 'POST'), 'edit_language/{id}', 'LanguageController@update')->where('id', '[0-9]+');
        Route::get('delete_language/{id}', 'LanguageController@delete')->where('id', '[0-9]+');
    });

    // Manage Country
    Route::group(['middleware' => 'admin_can:manage_country'], function () {
        Route::get('country', 'CountryController@index')->name('country');
        Route::match(array('GET', 'POST'), 'add_country', 'CountryController@add');
        Route::match(array('GET', 'POST'), 'edit_country/{id}', 'CountryController@update')->where('id', '[0-9]+');
        Route::get('delete_country/{id}', 'CountryController@delete')->where('id', '[0-9]+');
    });

    // Manage Referral Settings
    Route::match(array('GET', 'POST'), 'referral_settings', 'ReferralSettingsController@index')->middleware(['admin_can:manage_referral_settings'])->name('referral_settings');

    // Manage Fees
    Route::group(['middleware' => 'admin_can:manage_fees'], function () {
        Route::match(array('GET', 'POST'), 'fees', 'FeesController@index')->name('fees');
        Route::match(array('GET', 'POST'), 'host_service_fees', 'FeesController@host_service_fees');
        Route::match(array('GET', 'POST'), 'fees/host_penalty_fees', 'FeesController@host_penalty_fees');
    });

    // Manage Metas
    Route::group(['middleware' => 'admin_can:manage_metas'], function () {
        Route::match(array('GET', 'POST'), 'metas', 'MetasController@index')->name('metas');
        Route::match(array('GET', 'POST'), 'edit_meta/{id}', 'MetasController@update')->where('id', '[0-9]+');
    });

    // Manage API Credentials
    Route::match(array('GET', 'POST'), 'api_credentials', 'ApiCredentialsController@index')->middleware(['admin_can:api_credentials'])->name('admin.api_credentials');

    // Manage Payment Gateway
    Route::match(array('GET', 'POST'), 'payment_gateway', 'PaymentGatewayController@index')->middleware(['admin_can:payment_gateway'])->name('payment_gateway');

    // Manage Join Us
    Route::match(array('GET', 'POST'), 'join_us', 'JoinUsController@index')->middleware(['admin_can:join_us'])->name('join_us');

    // Manage Site Settings
    Route::match(array('GET', 'POST'), 'site_settings', 'SiteSettingsController@index')->middleware(['admin_can:site_settings'])->name('site_settings');
});

Route::post('authenticate', 'AdminController@authenticate')->name('admin_authenticate');