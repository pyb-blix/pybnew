<?php

use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('permissions')->delete();

        DB::table('permissions')->insert([
              ['name' => 'manage_admin', 'display_name' => 'Manage Admin', 'description' => 'Manage Admin Users'],
              ['name' => 'users', 'display_name' => 'View Users', 'description' => 'View Users'],
              ['name' => 'add_user', 'display_name' => 'Add User', 'description' => 'Add User'],
              ['name' => 'edit_user', 'display_name' => 'Edit User', 'description' => 'Edit User'],
              ['name' => 'delete_user', 'display_name' => 'Delete User', 'description' => 'Delete User'],
              ['name' => 'manage_amenities', 'display_name' => 'Manage Amenities', 'description' => 'Manage Amenities'],
              ['name' => 'manage_property_type', 'display_name' => 'Manage Property Type', 'description' => 'Manage Property Type'],
              ['name' => 'manage_room_type', 'display_name' => 'Manage Room Type', 'description' => 'Manage Room Type'],
              ['name' => 'manage_bed_type', 'display_name' => 'Manage Bed Type', 'description' => 'Manage Bed Type'],
              ['name' => 'manage_currency', 'display_name' => 'Manage Currency', 'description' => 'Manage Currency'],
              ['name' => 'manage_language', 'display_name' => 'Manage Language', 'description' => 'Manage Language'],
              ['name' => 'manage_country', 'display_name' => 'Manage Country', 'description' => 'Manage Country'],
              ['name' => 'api_credentials', 'display_name' => 'Api Credentials', 'description' => 'Api Credentials'],
              ['name' => 'payment_gateway', 'display_name' => 'Payment Gateway', 'description' => 'Payment Gateway'],
              ['name' => 'email_settings', 'display_name' => 'Email Settings', 'description' => 'Email Settings'],
              ['name' => 'site_settings', 'display_name' => 'Site Settings', 'description' => 'Site Settings'],
              ['name' => 'reservations', 'display_name' => 'Reservations', 'description' => 'Reservations'],
              ['name' => 'rooms', 'display_name' => 'View Rooms', 'description' => 'View Rooms'],
              ['name' => 'add_room', 'display_name' => 'Add Room', 'description' => 'Add Room'],
              ['name' => 'edit_room', 'display_name' => 'Edit Room', 'description' => 'Edit Room'],
              ['name' => 'delete_room', 'display_name' => 'Delete Room', 'description' => 'Delete Room'],
              ['name' => 'manage_pages', 'display_name' => 'Manage Pages', 'description' => 'Manage Pages'],
              ['name' => 'manage_fees', 'display_name' => 'Manage Fees', 'description' => 'Manage Fees'],
              ['name' => 'join_us', 'display_name' => 'Join Us', 'description' => 'Join Us'],
              ['name' => 'manage_metas', 'display_name' => 'Manage Metas', 'description' => 'Manage Metas'],
              ['name' => 'reports', 'display_name' => 'Reports', 'description' => 'Reports'],
              ['name' => 'manage_home_cities', 'display_name' => 'Manage Home Page Cities', 'description' => 'Manage Home Page Cities'],
              ['name' => 'manage_reviews', 'display_name' => 'Manage Reviews', 'description' => 'Manage Reviews'],
              ['name' => 'send_email', 'display_name' => 'Send Email', 'description' => 'Send Email'],
              ['name' => 'manage_help', 'display_name' => 'Manage Help', 'description' => 'Manage Help'],
              ['name' => 'manage_coupon_code', 'display_name' => 'Manage Coupon Code', 'description' => 'Manage Coupon Code'],
              ['name' => 'manage_referral_settings', 'display_name' => 'Manage Referrals Settings', 'description' => 'Manage Referrals Settings'],
              ['name' => 'manage_wishlists', 'display_name' => 'Manage Wish Lists', 'description' => 'Manage Wish Lists'],
              ['name' => 'manage_login_sliders', 'display_name' => 'Manage Login Slider', 'description' => 'Manage Login Slider'],
              ['name' => 'manage_home_sliders', 'display_name' => 'Manage HomePage Slider', 'description' => 'Manage HomePage Slider'],
              ['name' => 'manage_our_community_banners', 'display_name' => 'Manage Our Community', 'description' => 'Manage Our Communtiy'],
              ['name' => 'manage_disputes', 'display_name' => 'Manage Disputes', 'description' => 'Manage Disputes'],
              ['name' => 'manage_referrals', 'display_name' => 'Manage Referrals', 'description' => 'Manage Referrals'],
        	]);
    }
}
