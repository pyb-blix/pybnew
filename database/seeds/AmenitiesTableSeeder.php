<?php

use Illuminate\Database\Seeder;

class AmenitiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('amenities')->delete();
    	
        DB::table('amenities')->insert([
            array('id' => '1','type_id' => '1','name' => 'Essentials','description' => 'Essentials','icon' => 'essentials.png','mobile_icon' => 'j','status' => 'Active'),
            array('id' => '2','type_id' => '1','name' => 'TV','description' => '','icon' => 'tv.png','mobile_icon' => 'z','status' => 'Active'),
            array('id' => '3','type_id' => '1','name' => 'Cable TV','description' => '','icon' => 'cabletv.png','mobile_icon' => 'f','status' => 'Active'),
            array('id' => '4','type_id' => '1','name' => 'Air Conditioning ','description' => '','icon' => 'ac.png','mobile_icon' => 'b','status' => 'Active'),
            array('id' => '5','type_id' => '1','name' => 'Heating','description' => 'Heating','icon' => 'heating1.png','mobile_icon' => 'o','status' => 'Active'),
            array('id' => '6','type_id' => '1','name' => 'Kitchen','description' => 'Kitchen','icon' => 'kitchen.png','mobile_icon' => 's','status' => 'Active'),
            array('id' => '7','type_id' => '1','name' => 'Internet','description' => 'Internet','icon' => 'internetwired.png','mobile_icon' => 'r','status' => 'Active'),
            array('id' => '8','type_id' => '1','name' => 'Wireless Internet','description' => 'Wireless Internet','icon' => 'wireless.jpeg','mobile_icon' => 'B','status' => 'Active'),
            array('id' => '9','type_id' => '2','name' => 'Hot Tub','description' => '','icon' => 'hottub.png','mobile_icon' => 'p','status' => 'Active'),
            array('id' => '10','type_id' => '2','name' => 'Washer','description' => 'Washer','icon' => 'washer.png','mobile_icon' => 'A','status' => 'Active'),
            array('id' => '11','type_id' => '2','name' => 'Pool','description' => 'Pool','icon' => 'pool.png','mobile_icon' => 'w','status' => 'Active'),
            array('id' => '12','type_id' => '2','name' => 'Dryer','description' => 'Dryer','icon' => 'dryerorg.png','mobile_icon' => 'n','status' => 'Active'),
            array('id' => '13','type_id' => '2','name' => 'Breakfast','description' => 'Breakfast','icon' => 'breakfast1.png','mobile_icon' => 'e','status' => 'Active'),
            array('id' => '14','type_id' => '2','name' => 'Free Parking on Premises','description' => '','icon' => 'parking.png','mobile_icon' => 'u','status' => 'Active'),
            array('id' => '15','type_id' => '2','name' => 'Gym','description' => 'Gym','icon' => 'gym.png','mobile_icon' => 'm','status' => 'Active'),
            array('id' => '16','type_id' => '2','name' => 'Elevator in Building','description' => '','icon' => 'elevator.png','mobile_icon' => 'i','status' => 'Active'),
            array('id' => '17','type_id' => '2','name' => 'Indoor Fireplace','description' => '','icon' => 'fireplace.png','mobile_icon' => 'l','status' => 'Active'),
            array('id' => '18','type_id' => '2','name' => 'Buzzer/Wireless Intercom','description' => '','icon' => 'buzzer.png','mobile_icon' => 'q','status' => 'Active'),
            array('id' => '19','type_id' => '2','name' => 'Doorman','description' => '','icon' => 'doorman.png','mobile_icon' => 'g','status' => 'Active'),
            array('id' => '20','type_id' => '2','name' => 'Shampoo','description' => '','icon' => 'shampoo.png','mobile_icon' => 'x','status' => 'Active'),
            array('id' => '21','type_id' => '3','name' => 'Family/Kid Friendly','description' => 'Family/Kid Friendly','icon' => 'family.jpeg','mobile_icon' => 'k','status' => 'Active'),
            array('id' => '22','type_id' => '3','name' => 'Smoking Allowed','description' => '','icon' => 'smoking.png','mobile_icon' => 'y','status' => 'Active'),
            array('id' => '23','type_id' => '3','name' => 'Suitable for Events','description' => 'Suitable for Events','icon' => 'events.png','mobile_icon' => 'c','status' => 'Active'),
            array('id' => '24','type_id' => '3','name' => 'Pets Allowed','description' => '','icon' => 'petsallowed.png','mobile_icon' => 'v','status' => 'Active'),
            array('id' => '25','type_id' => '3','name' => 'Pets live on this property','description' => '','icon' => 'petslive.png','mobile_icon' => 't','status' => 'Active'),
            array('id' => '26','type_id' => '3','name' => 'Wheelchair Accessible','description' => 'Wheelchair Accessible','icon' => 'wheelchair.png','mobile_icon' => 'a','status' => 'Active'),
            array('id' => '27','type_id' => '4','name' => 'Smoke Detector','description' => 'Smoke Detector','icon' => 'smokedet.jpeg','mobile_icon' => 't','status' => 'Active'),
            array('id' => '28','type_id' => '4','name' => 'Carbon Monoxide Detector','description' => 'Carbon Monoxide Detector','icon' => 'codetect.png','mobile_icon' => 't','status' => 'Active'),
            array('id' => '29','type_id' => '4','name' => 'First Aid Kit','description' => '','icon' => 'firstaidkit.jpeg','mobile_icon' => 't','status' => 'Active'),
            array('id' => '30','type_id' => '4','name' => 'Safety Card','description' => 'Safety Card','icon' => 'safety.png','mobile_icon' => 't','status' => 'Active'),
            array('id' => '31','type_id' => '4','name' => 'Fire Extinguisher','description' => 'Essentials','icon' => 'fire.png','mobile_icon' => 't','status' => 'Active')
        	]);
    }
}
