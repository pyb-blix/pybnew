<?php

use Illuminate\Database\Seeder;

class AdminTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('admin')->delete();
        DB::table('role_user')->delete();
        DB::table('roles')->delete();
        DB::table('permission_role')->delete();

        //admin table seeder
        DB::table('admin')->insert([
        	array('id' => '1','username' => 'admin','email' => 'admin@trioangle.com','password' => '$2y$10$IzcIdUEq9arWkv7ASfC3huBwAXWuIpaE18rW0ThBnxLmzCdl1Ix8.','remember_token' => NULL,'status' => 'Active','created_at' => '2016-04-17 05:30:00','updated_at' => NULL),
            array('id' => '2','username' => 'subadmin','email' => 'subadmin@trioangle.com','password' => '$2y$10$gRH0KTG1rq18thHPriBI6uajMW/uGlyCwi2TU4IF/68x02TcKxIRy','remember_token' => NULL,'status' => 'Active','created_at' => '2016-04-17 05:30:00','updated_at' => NULL),
            array('id' => '3','username' => 'accountant','email' => 'accountant@trioangle.com','password' => '$2y$10$ih76FCSfMmNyiPblzhv00.zrVS0ZYnRyUfL2NMICTvTCVBHcKnP1W','remember_token' => NULL,'status' => 'Active','created_at' => '2016-04-17 05:30:00','updated_at' => NULL)
        ]);


        //role table seeded
        DB::table('roles')->insert([
            array('id' => '1','name' => 'admin','display_name' => 'Admin','description' => 'Admin User','created_at' => '2016-04-17 05:30:00','updated_at' => '2016-04-17 05:30:00'),
            array('id' => '2','name' => 'subadmin','display_name' => 'subadmin','description' => 'subadmin','created_at' => '2016-04-17 05:40:00','updated_at' => '2016-04-17 05:30:00'),
            array('id' => '3','name' => 'accountant','display_name' => 'accountant','description' => 'accountant','created_at' => '2016-04-17 05:40:00','updated_at' => '2016-04-17 05:30:00')
        ]);

        //role user table seeder
        DB::table('role_user')->insert([
            array('user_id' => '1','role_id' => '1'),
            array('user_id' => '2','role_id' => '2'),
            array('user_id' => '3','role_id' => '3')
        ]);

        
        $permissions = DB::table('permissions')->get();
        $subadmin_permissions = array(1, 2, 3, 9, 14);
        $accountant_permissions = array(8, 10, 14, 17);

        $permissions_data = [];

        // Admin Permissions
        foreach ($permissions as $key => $value) {
            $permissions_data[] = array('permission_id' => $value->id, 'role_id' => '1');
        }

        // Subadmin Permissions
        foreach ($permissions->whereIn('id',$subadmin_permissions) as $key => $value) {
            $permissions_data[] = array('permission_id' => $value->id, 'role_id' => '2');
        }

        // Subadmin Permissions
        foreach ($permissions->whereIn('id',$accountant_permissions) as $key => $value) {
            $permissions_data[] = array('permission_id' => $value->id, 'role_id' => '3');
        }

        DB::table('permission_role')->insert($permissions_data);
    }
}
