<?php

/**
 * Home Cities Model
 *
 * @package     Makent
 * @subpackage  Model
 * @category    Home Cities
 * @author      Trioangle Product Team
 * @version     2.0
 * @link        http://trioangle.com
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomeCities extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'home_cities';

    public $timestamps = false;

    public $appends = ['image_url','search_url','average_price'];

    // Get Translated value of given column
    protected function getTranslatedValue($field)
    {
        if(!isset($this->attributes[$field])) {
            return '';
        }
        $value = $this->attributes[$field];

        if(request()->segment(1) == ADMIN_URL) {
            return $value;
        }

        $lang_code = getLangCode();
        if ($lang_code == 'en') {
            return $value;
        }
        $trans_value = @HomeCitiesLang::where('home_cities_id', $this->attributes['id'])->where('lang_code', $lang)->first()->$field;
        if ($trans_value) {
            return $trans_value;
        }
        return $value;
    }

    public function getImageUrlAttribute()
    {
        $photo_src=explode('.',$this->attributes['image']);
        if(count($photo_src)>1) {
            return asset('images/home_cities/'.$this->attributes['image']);
        }

        $options['secure']  = TRUE;
        $options['crop']    = 'fill';
        return $src=\Cloudder::show($this->attributes['image'],$options);
    }

    public function getSearchUrlAttribute()
    {
        return url('/s?location='.$this->attributes['name'].'&source=ds');
    }

    public function getNameAttribute()
    {
        return $this->getTranslatedValue('name');
    }

    public function getAveragePriceAttribute()
    {

        $address      = str_replace([" ","%2C"], ["+",","], $this->attributes['name']);
        $geocode      = @file_get_contents('https://maps.google.com/maps/api/geocode/json?key='. view()->shared('map_server_key').'&address='.$address.'&sensor=false&libraries=places');

        $json         = json_decode($geocode);
        
        if(@$json->{'results'}){
            $minLat = $json->{'results'}[0]->{'geometry'}->{'viewport'}->{'southwest'}->{'lat'};
            $maxLat = $json->{'results'}[0]->{'geometry'}->{'viewport'}->{'northeast'}->{'lat'};
            $minLong = $json->{'results'}[0]->{'geometry'}->{'viewport'}->{'southwest'}->{'lng'};
            $maxLong = $json->{'results'}[0]->{'geometry'}->{'viewport'}->{'northeast'}->{'lng'};
        }
        else{
            $minLat = -1000;
            $maxLat = 1000;
            $minLong = -1000;
            $maxLong = 1000;
        }

        $rooms = Rooms::listed()->verified()
        ->with('rooms_price')
        ->whereHas('users', function($query)  {
            $query->where('users.status','Active');
        })
        ->whereHas('rooms_address', function($query) use($minLat, $maxLat, $minLong, $maxLong) {
            $query->whereRaw("latitude between $minLat and $maxLat and longitude between $minLong and $maxLong");
        })
        ->get();

        $total_rooms = $rooms->count();
        $total_price = $rooms->sum(function ($room) {
            return $room->rooms_price->night;
        });
        
        if($total_rooms > 0) {
            $average_price = round($total_price / $total_rooms);
            return session('symbol').''.$average_price.' / '.trans_choice('messages.rooms.night',1).' '.trans('messages.home.average');
        }
        return '';
    }
}