<?php

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->delete();

        DB::table('roles')->insert([
            array('name' => 'admin','display_name' => 'Admin','description' => 'Admin User'),
            array('name' => 'subadmin','display_name' => 'subadmin','description' => 'subadmin'),
            array('name' => 'accountant','display_name' => 'accountant','description' => 'accountant')
        ]);
    }
}
