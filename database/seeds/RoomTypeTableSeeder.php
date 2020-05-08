<?php

use Illuminate\Database\Seeder;

class RoomTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('room_type')->delete();
    	
        DB::table('room_type')->insert([
            ['id' => '1','name' => 'Entire home/apt','description' => 'Guests will rent the entire place. Includes in-law units.','status' => 'Active', 'is_shared' => 'No','icon' => 'entirehome.png'],
            ['id' => '2','name' => 'Private room','description' => 'Guests share some spaces but they’ll have their own private room for sleeping.','status' => 'Active', 'is_shared' => 'No','icon' => 'private.png'],
            ['id' => '3','name' => 'Shared room','description' => 'Guests don’t have a room to themselves.','status' => 'Active', 'is_shared' => 'Yes','icon' => 'shared.png']
        ]);
    }
}
