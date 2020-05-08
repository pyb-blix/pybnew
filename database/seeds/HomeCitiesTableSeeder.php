<?php

use Illuminate\Database\Seeder;

class HomeCitiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('home_cities')->delete();
        DB::table('home_cities')->insert([
            array('id' => '1','name' => 'New York, NY, USA','display_name' => 'New York','latitude' => '40.7127753','longitude' => '-74.0059728','image' => 'home_city_1569501592.jpeg','source' => 'Local'),
            array('id' => '2','name' => 'Sydney NSW, Australia','display_name' => 'Sydney','latitude' => '-33.8688197','longitude' => '151.20929550000005','image' => 'home_city_1569501876.jpeg','source' => 'Local'),
            array('id' => '3','name' => 'Hawaii','display_name' => 'Hawaii','latitude' => '33.8314045','longitude' => '-118.07284240000001','image' => 'home_city_1569502184.jpeg','source' => 'Local'),
            array('id' => '4','name' => 'Paris, France','display_name' => 'Paris','latitude' => '48.85661400000001','longitude' => '2.3522219000000177','image' => 'home_city_1569504637.jpeg','source' => 'Local'),
            array('id' => '5','name' => 'Barcelona, Spain','display_name' => 'Barcelona','latitude' => '41.38506389999999','longitude' => '2.1734034999999494','image' => 'home_city_1569503962.jpeg','source' => 'Local'),
            array('id' => '6','name' => 'Bangkok, Thailand','display_name' => 'Thailand','latitude' => '13.7563309','longitude' => '100.50176510000006','image' => 'home_city_1569579022.jpeg','source' => 'Local'),
            array('id' => '7','name' => 'London, UK','display_name' => 'London','latitude' => '51.5073509','longitude' => '-0.12775829999998223','image' => 'home_city_1569579115.jpeg','source' => 'Local'),
            array('id' => '8','name' => 'San Francisco, CA, USA','display_name' => 'San Francisco','latitude' => '37.7749295','longitude' => '-122.41941550000001','image' => 'home_city_1569505132.jpeg','source' => 'Local'),
            array('id' => '9','name' => 'Berlin, Germany','display_name' => 'Berlin','latitude' => '52.52000659999999','longitude' => '13.404953999999975','image' => 'home_city_1569505210.jpeg','source' => 'Local'),
            array('id' => '10','name' => 'Budapest, Hungary','display_name' => 'Budapest','latitude' => '47.497912','longitude' => '19.04023499999994','image' => 'home_city_1566565360.jpg','source' => 'Local')
        ]);
    }
}
