<?php

use Illuminate\Database\Seeder;

class OurCommunityBannersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       	DB::table('our_community_banners')->delete();
        
        DB::table('our_community_banners')->insert([
            array('id' => '1','title' => 'Garry & Lianne','description' => 'Across an ocean or across town, garry & Lianne are always in search in local experiences  Learn more about travel on Makent','image' => 'our_community_banners_1569494372.jpeg','link' => 'http://makent.trioangle.com/terms_of_service'),
            array('id' => '2','title' => 'Makent for Business','description' => 'Feel at home. wherever your work takes you  Get your team on Makent','image' => 'our_community_banners_1569494388.jpeg','link' => 'http://makent.trioangle.com/privacy_policy'),
            array('id' => '3','title' => 'Patricia','description' => 'A professional photographer, patricia loves helping guests explore Shanghai\'s arts scene.  Learn more about hosting on Makent','image' => 'our_community_banners_1569494499.jpeg','link' => 'http://makent.trioangle.com/host_guarantee')
        ]);
    }
}
