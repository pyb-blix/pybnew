<?php

use Illuminate\Database\Seeder;

class HomePageSlidersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('home_page_sliders')->delete();
        DB::table('home_page_sliders')->insert([
            array('id' => '1','image' => 'home_page_slider_1569578869.jpg','order' => '3','name' => 'Crescent lake, Oregon','description' => 'Crescent Lake room rental','source' => 'Local','status' => 'Active','created_at' => NULL,'updated_at' => NULL),
            array('id' => '2','image' => 'home_page_slider_1569578860.jpg','order' => '2','name' => 'Bigfork, Montana','description' => 'Restful and Clean Guest Studio','source' => 'Local','status' => 'Active','created_at' => NULL,'updated_at' => NULL),
            array('id' => '3','image' => 'home_page_slider_1569578840.jpg','order' => '1','name' => 'Hood River, Oregon','description' => 'Cozy Studio in Hood River','source' => 'Local','status' => 'Active','created_at' => NULL,'updated_at' => NULL),
            array('id' => '4','image' => 'banner5.jpg','order' => '4','name' => 'Hood River, Oregon','description' => 'Cozy Studio in Hood River','source' => 'Local','status' => 'Inactive','created_at' => NULL,'updated_at' => NULL),
            array('id' => '5','image' => 'banner6.jpg','order' => '5','name' => 'Bigfork, Montana','description' => 'Restful and Clean Guest Studio','source' => 'Local','status' => 'Inactive','created_at' => NULL,'updated_at' => NULL)
        ]);
    }
}
