<?php

/**
 * Room Type Model
 *
 * @package     Makent
 * @subpackage  Model
 * @category    Room Type
 * @author      Trioangle Product Team
 * @version     2.0
 * @link        http://trioangle.com
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Session;
use Request;
class RoomType extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'room_type';

    public $timestamps = false;

    protected $appends = ['image_name'];

    // Get all Active status records
    public static function active_all()
    {
    	return RoomType::whereStatus('Active')->get();
    }

    // Get all Active status records in lists type
    public static function dropdown()
    {
        //return RoomType::whereStatus('Active')->pluck('name','id');
        $data=RoomType::whereStatus('Active')->get();
        return $data->pluck('name','id');
    }

    // Get single field data by using id and field name
    public static function single_field($id, $field)
    {
        return RoomType::whereId($id)->first()->$field;
    }

    public function getNameAttribute()
    {
        return $this->getTranslatedValue('name');
    }

    public function getDescriptionAttribute()
    {
        return $this->getTranslatedValue('description');
    }

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
        $trans_value = @RoomTypeLang::where('room_type_id', $this->attributes['id'])->where('lang_code', $lang_code)->first()->$field;
        if ($trans_value) {
            return $trans_value;
        }
        return $value;
    }
   
    // Get Image Name Attribute
    public function getImageNameAttribute() {
        $site_settings_url = @SiteSettings::where('name', 'site_url')->first()->value;
        $url = \App::runningInConsole() ? $site_settings_url : url('/');
        $photo_src = explode('.', $this->attributes['icon']);

        if (count($photo_src) > 1) {
            $name = $this->attributes['icon'];
            return $url . '/images/room_type/' . $name;
        }
        $options['secure'] = TRUE;
        $options['width'] = 100;
        $options['height'] = 100;
        $options['crop'] = 'fill';
        return $src = \Cloudder::show($this->attributes['icon'], $options);
    }
}