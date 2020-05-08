<?php

/**
 * Rooms Photos Model
 *
 * @package     Makent
 * @subpackage  Model
 * @category    Rooms Photos
 * @author      Trioangle Product Team
 * @version     2.0
 * @link        http://trioangle.com
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomsPhotos extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'rooms_photos';

    public $timestamps = false;

    protected $appends = ['steps_count','original_name'];

    // Get Ordered Photos List
    public function scopeOrdered($query)
    {
        return $query->orderBy('order_id','asc');
    }

    // Get steps_count using sum of rooms_steps_status
    public function getStepsCountAttribute()
    {
        $result = RoomsStepsStatus::find($this->attributes['room_id']);

        if ($result!=null) {
            return 6 - ($result->basics + $result->description + $result->location + $result->photos + $result->pricing + $result->calendar);
        }else{
            return 6;
        }
        
    }

    public function getOriginalNameAttribute(){
        return @$this->attributes['name'];
    }

    // Get Name Attribute
    public function getNameAttribute(){
        $site_settings_url = @SiteSettings::where('name' , 'site_url')->first()->value;
        $url = \App::runningInConsole() ? $site_settings_url : url('/');
        $photo_src=explode('.',$this->attributes['name']);
        if(count($photo_src)>1)
        {
            $photo_details = pathinfo($this->attributes['name']); 
            if(@$photo_details['extension']=='gif')
            {
                $name = @$photo_details['filename'].'.'.@$photo_details['extension'];
            }
            else
            {
                $name = @$photo_details['filename'].'_450x250.'.@$photo_details['extension'];
            }
            return $url.'/images/rooms/'.$this->attributes['room_id'].'/'.$name;
        }
        else
        {
            $options['secure']=TRUE;
            $options['width']=450;
            $options['height']=250;
            $options['crop']='fill';
            return $src=\Cloudder::show($this->attributes['name'],$options);
        }

    }
    // Get Name Attribute
    public function getOriginalImageAttribute(){
        $site_settings_url = @SiteSettings::where('name' , 'site_url')->first()->value;
        $url = \App::runningInConsole() ? $site_settings_url : url('/');
        $photo_src=explode('.',$this->attributes['name']);
        if(count($photo_src)>1)
        {
            $photo_details = pathinfo($this->attributes['name']); 
            $name = @$photo_details['filename'].'.'.@$photo_details['extension'];
            
            return $url.'/images/rooms/'.$this->attributes['room_id'].'/'.$name;
        }
        else
        {
            $options['secure']=TRUE;
            $options['crop']='fill';
            return $src=\Cloudder::show($this->attributes['name'],$options);
        }

    }

    // Get Slider Image Name Attribute
    public function getSliderImageNameAttribute(){
        $photo_src=explode('.',$this->attributes['name']);
        if(count($photo_src)>1)
        {
            $photo_details = pathinfo($this->attributes['name']); 
            if(@$photo_details['extension']=='gif')
            {
                $name = @$photo_details['filename'].'.'.@$photo_details['extension'];
            }
            else
            {
                $name = @$photo_details['filename'].'_1440x960.'.@$photo_details['extension'];
            }
            return url('/').'/images/rooms/'.$this->attributes['room_id'].'/'.$name;
        }
        else
        {
            $options['secure']=TRUE;
            $options['width']=1440;
            $options['height']=960;
            $options['crop']='fill';
            return $src=\Cloudder::show($this->attributes['name'],$options);
        }
        
    }
    // Get Banner Image Name Attribute
    public function getBannerImageNameAttribute(){
        $photo_src=explode('.',$this->attributes['name']);
        if(count($photo_src)>1)
        {
            $photo_details = pathinfo($this->attributes['name']); 
            if(@$photo_details['extension']=='gif')
            {
                $name = @$photo_details['filename'].'.'.@$photo_details['extension'];
            }
            else
            {
                $name = @$photo_details['filename'].'_1349x402.'.@$photo_details['extension'];
            }
            return url('/').'/images/rooms/'.$this->attributes['room_id'].'/'.$name;
        }
        else
        {
            $options['secure']=TRUE;
            $options['width']=1349;
            $options['height']=402;
            $options['crop']='fill';
            return $src=\Cloudder::show($this->attributes['name'],$options);
        }
    }
}
