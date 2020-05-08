<?php

use Illuminate\Database\Seeder;

class AmenitiesTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('amenities_type')->delete();
    	
        DB::table('amenities_type')->insert([
            array('id' => '1','name' => 'Common Amenities','description' => '','status' => 'Active'),
            array('id' => '2','name' => 'Additional Amenities','description' => '','status' => 'Active'),
            array('id' => '3','name' => 'Special Features','description' => 'Features of your listing for guests with specific needs.','status' => 'Active'),
            array('id' => '4','name' => 'Home Safety','description' => 'Smoke and carbon monoxide detectors are strongly encouraged for all listings.','status' => 'Active')
        ]);
    }
}
