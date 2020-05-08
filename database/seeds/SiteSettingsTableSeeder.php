<?php

use Illuminate\Database\Seeder;

class SiteSettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('site_settings')->delete();

        DB::table('site_settings')->insert([
          array('id' => '1','name' => 'site_name','value' => 'Makent'),
          array('id' => '2','name' => 'head_code','value' => ''),
          array('id' => '3','name' => 'logo','value' => 'Makent/inkmdhxylz5kuev3u11k'),
          array('id' => '4','name' => 'home_logo','value' => 'MakentDefault/y8e9eobkqcxmk0obg0cv'),
          array('id' => '5','name' => 'home_video','value' => 'MakentDefault/ljhzwaolgl0kirrcbwvv'),
          array('id' => '6','name' => 'favicon','value' => 'Makent/x7sod6jxn30rsmcirczw'),
          array('id' => '7','name' => 'currency_provider','value' => ''),
          array('id' => '8','name' => 'email_logo','value' => 'Makent/b8t9jyumcujvodglnnqz'),
          array('id' => '9','name' => 'home_video_webm','value' => 'MakentDefault/gfjxdx0xvln69kh4uqqj'),
          array('id' => '10','name' => 'footer_cover_image','value' => 'footer_cover_image.png'),
          array('id' => '11','name' => 'help_page_cover_image','value' => 'Makent/d2zapzifrkhtd65fq6ng'),
          array('id' => '12','name' => 'site_date_format','value' => '2'),
          array('id' => '13','name' => 'paypal_currency','value' => 'EUR'),
          array('id' => '14','name' => 'home_page_header_media','value' => 'Slider'),
          array('id' => '15','name' => 'site_url','value' => ''),
          array('id' => '16','name' => 'default_home','value' => 'home_two'),
          array('id' => '17','name' => 'version','value' => '2.0'),
          array('id' => '18','name' => 'admin_prefix','value' => 'admin'),
          array('id' => '19','name' => 'upload_driver','value' => 'cloudinary'),
          array('id' => '20','name' => 'minimum_amount','value' => '10'),
          array('id' => '21','name' => 'maximum_amount','value' => '750'),
          array('id' => '22','name' => 'support_number','value' => '000 800 4405 103'),
          array('id' => '23','name' => 'home_page_stay_image','value' => 'Makent/a4zdb9m86p0pwat2u55i'),
          array('id' => '24','name' => 'home_page_experience_image','value' => 'Makent/isolqjioqzjejkx8iajg')
        ]);
    }
}
