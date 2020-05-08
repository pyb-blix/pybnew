<?php

use Illuminate\Database\Seeder;

class SliderTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('slider')->delete();
    	
        DB::table('slider')->insert([
            array('id' => '1','image' => 'slider_1570172783.jpeg','order' => '3','status' => 'Active','created_at' => NULL,'updated_at' => '2019-10-04 12:36:24'),
            array('id' => '2','image' => 'slider_1570182215.jpeg','order' => '1','status' => 'Active','created_at' => NULL,'updated_at' => '2019-10-04 15:13:35'),
            array('id' => '3','image' => 'slider_1570173059.jpeg','order' => '2','status' => 'Active','created_at' => NULL,'updated_at' => '2019-10-04 12:40:59')
        ]);
    }
}
