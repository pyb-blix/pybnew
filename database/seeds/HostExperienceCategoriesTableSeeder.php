<?php

use Illuminate\Database\Seeder;

class HostExperienceCategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('host_experience_categories')->delete();
    	
        DB::table('host_experience_categories')->insert([
            array('id' => '1','name' => 'Arts & Design','image' => 'host_experience_category_1570599505.jpeg','status' => 'Active','is_featured' => 'No','created_at' => NULL,'updated_at' => '2019-10-09 11:08:25'),
            array('id' => '2','name' => 'Fashion','image' => 'host_experience_category_1570599975.jpeg','status' => 'Active','is_featured' => 'No','created_at' => NULL,'updated_at' => '2019-10-09 11:16:15'),
            array('id' => '3','name' => 'Entertainment','image' => 'host_experience_category_1570599434.jpeg','status' => 'Active','is_featured' => 'No','created_at' => NULL,'updated_at' => '2019-10-09 11:07:14'),
            array('id' => '4','name' => 'Sports','image' => 'host_experience_category_1570599387.jpeg','status' => 'Active','is_featured' => 'No','created_at' => NULL,'updated_at' => '2019-10-09 11:06:27'),
            array('id' => '5','name' => 'Wellness','image' => 'host_experience_category_1570600310.jpeg','status' => 'Active','is_featured' => 'No','created_at' => NULL,'updated_at' => '2019-10-09 11:21:50'),
            array('id' => '6','name' => 'Nature','image' => 'host_experience_category_1570602694.jpeg','status' => 'Active','is_featured' => 'No','created_at' => NULL,'updated_at' => '2019-10-09 12:01:34'),
            array('id' => '7','name' => 'Food & Drink','image' => 'host_experience_category_1570599399.jpeg','status' => 'Active','is_featured' => 'No','created_at' => NULL,'updated_at' => '2019-10-09 11:06:39'),
            array('id' => '8','name' => 'Lifestyle','image' => 'host_experience_category_1570602118.jpeg','status' => 'Active','is_featured' => 'No','created_at' => NULL,'updated_at' => '2019-10-09 11:51:58'),
            array('id' => '9','name' => 'History','image' => 'host_experience_category_1570601000.jpeg','status' => 'Active','is_featured' => 'No','created_at' => NULL,'updated_at' => '2019-10-09 11:33:20'),
            array('id' => '10','name' => 'Music','image' => 'host_experience_category_1570600857.jpeg','status' => 'Active','is_featured' => 'No','created_at' => NULL,'updated_at' => '2019-10-09 11:30:57'),
            array('id' => '11','name' => 'Business','image' => 'host_experience_category_1570601928.jpeg','status' => 'Active','is_featured' => 'No','created_at' => NULL,'updated_at' => '2019-10-09 11:48:48'),
            array('id' => '12','name' => 'Nightlife','image' => 'host_experience_category_1570601949.jpeg','status' => 'Active','is_featured' => 'No','created_at' => NULL,'updated_at' => '2019-10-09 11:49:09')
        ]);

        DB::table('permissions')->insert([
            ['name' => 'manage_host_experience_categories', 'display_name' => 'Manage Host Experience Categories', 'description' => 'Manage Host Experience Categories'],
            ['name' => 'manage_host_experience_provide_items', 'display_name' => 'Manage Host Experience Provide Items', 'description' => 'Manage Host Experience Provide Items'],
            ['name' => 'manage_host_experience_cities', 'display_name' => 'Manage Host Experience Cities', 'description' => 'Manage Host Experience Cities'],
            ['name' => 'manage_host_experiences', 'display_name' => 'View Host Experiences', 'description' => 'View Host Experiences'],
            ['name' => 'add_host_experiences', 'display_name' => 'Add Host Experiences', 'description' => 'Add Host Experiences'],
            ['name' => 'edit_host_experiences', 'display_name' => 'Edit Host Experiences', 'description' => 'Edit Host Experiences'],
            ['name' => 'delete_host_experiences', 'display_name' => 'Delete Host Experiences', 'description' => 'Delete Host Experiences'],
            ['name' => 'manage_host_experiences_reservation', 'display_name' => 'Manage Host Experiences Reservation', 'description' => 'Manage Host Experiences Reservation'],
            ['name' => 'manage_host_experiences_reviews', 'display_name' => 'Manage Host Experiences Reviews', 'description' => 'Manage Host Experiences Reviews'],
        ]);
    }
}
