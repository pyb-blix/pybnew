<?php

use Illuminate\Database\Seeder;

class ApiCredentialsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('api_credentials')->delete();
        
        DB::table('api_credentials')->insert([
            array('id' => '1','name' => 'client_id','value' => '433021387036498','site' => 'Facebook'),
            array('id' => '2','name' => 'client_secret','value' => '84aa1d0248e91c0e6ed2f6562ed44bec','site' => 'Facebook'),
            array('id' => '3','name' => 'client_id','value' => '1020008011841-dssket4ctilaupugcv6abcha2kl65a66.apps.googleusercontent.com','site' => 'Google'),
            array('id' => '4','name' => 'client_secret','value' => 'Sg3sgEU3giYxdWFWr0jEpOmg','site' => 'Google'),
            array('id' => '5','name' => 'client_id','value' => '814qxyvczj5t7z','site' => 'LinkedIn'),
            array('id' => '6','name' => 'client_secret','value' => 'mkuRNAxW9TSp22Zf','site' => 'LinkedIn'),
            array('id' => '7','name' => 'key','value' => 'AIzaSyB6lCQnISdsSUVFdcQYxaHxXXjvKDn9wcs','site' => 'GoogleMap'),
            array('id' => '8','name' => 'server_key','value' => 'AIzaSyB6lCQnISdsSUVFdcQYxaHxXXjvKDn9wcs','site' => 'GoogleMap'),
            array('id' => '9','name' => 'key','value' => 'd7b78816','site' => 'Nexmo'),
            array('id' => '10','name' => 'secret','value' => '99a1dde9a6079c4a','site' => 'Nexmo'),
            array('id' => '11','name' => 'from','value' => 'Nexmo','site' => 'Nexmo'),
            array('id' => '12','name' => 'cloudinary_name','value' => 'makent-trioangle','site' => 'Cloudinary'),
            array('id' => '13','name' => 'cloudinary_key','value' => '322711781143127','site' => 'Cloudinary'),
            array('id' => '14','name' => 'cloudinary_secret','value' => 'jMtGS4ksf4ne_JasBAI_-3BissA','site' => 'Cloudinary'),
            array('id' => '15','name' => 'cloud_base_url','value' => 'http://res.cloudinary.com/makent-trioangle','site' => 'Cloudinary'),
            array('id' => '16','name' => 'cloud_secure_url','value' => 'https://res.cloudinary.com/makent-trioangle','site' => 'Cloudinary'),
            array('id' => '17','name' => 'cloud_api_url','value' => 'https://api.cloudinary.com/v1_1/makent-trioangle','site' => 'Cloudinary'),
            array('id' => '18','name' => 'service_id', 'value' => 'com.trioangle.makent.serviceid', 'site' => 'Apple'),
            array('id' => '19','name' => 'team_id', 'value' => 'W89HL6566S', 'site' => 'Apple'),
            array('id' => '20','name' => 'key_id', 'value' => '59V8S6B98L', 'site' => 'Apple'),
            array('id' => '21','name' => 'key_file', 'value' => 'key.txt', 'site' => 'Apple'),
        ]);
    }
}
