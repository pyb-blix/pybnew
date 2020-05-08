<?php

use Illuminate\Database\Seeder;

class BedTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('bed_type')->delete();
    	
        DB::table('bed_type')->insert([
            ['id'=>1,'name' => 'King','icon'=>'king.png'],
            ['id'=>2,'name' => 'Queen','icon'=>'queen.png'],
            ['id'=>3,'name' => 'Water bed','icon'=>'water-bed.png'],
            ['id'=>4,'name' => 'Single','icon'=>'single.png'],
            ['id'=>5,'name' => 'Hammock','icon'=>'hammock.png'],
            ['id'=>6,'name' => 'Air mattress','icon'=>'air-mattress.png'],
            ['id'=>7,'name' => 'Sofa Bed','icon'=>'sofa-bed.png'],
            // ['name' => 'Twin','icon'=>'.png'],
            ['id'=>8,'name' => 'Double','icon'=>'double.png'],
            // ['name' => 'Matrimony','icon'=>'.png'],
            // ['name' => 'Bunk Bed Matrimony','icon'=>'.png'],
            // ['name' => 'Bunk Bed Singles','icon'=>'.png'],
            // ['name' => 'Rool Away','icon'=>'.png'],
            ['id'=>9,'name' => 'Bunk Bed','icon'=>'bunk-bed.png'],
            ['id'=>10,'name' => 'Crib','icon'=>'crib.png'],
            ['id'=>11,'name' => 'Toddler Bed','icon'=>'toddler-bed.png'],
            ['id'=>12,'name' => 'Small double','icon'=>'small-double.png'],
            ['id'=>13,'name' => 'Couch','icon'=>'couch.png'],
            ['id'=>14,'name' => 'Floor mattress','icon'=>'floor-mattress.png'],
        	]);
    }
}