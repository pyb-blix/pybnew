<?php

/**
 * HostExperienceCategories Model
 *
 * @package     Makent
 * @subpackage  Model
 * @category    HostExperienceCategories
 * @author      Trioangle Product Team
 * @version     2.0
 * @link        http://trioangle.com
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HostExperienceCategories extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'host_experience_categories';

    protected $appends = ['all_host_experiences_count'];

    public $timestamps = true;

    public function scopeActive($query)
    {
    	$query = $query->where('status', 'Active');
    	return $query;
    }

    public function scopeFeatured($query) {
        return $query->where('is_featured', 'Yes');
    }

    public function scopeHomePage($query) {
        $query = $query->active()->featured()
                ->with(['host_experiences' => function($query) {
                    $query->with('currency')->homePageFeatured();
                }, 'host_experiences_secondary' => function($query) {
                    $query->with('currency')->homePage();
                }])
                ->where(function($query) {
                    $query->whereHas('host_experiences', function ($query) {
                        $query->with('currency')->homePageFeatured();
                    })
                    ->orWhereHas('host_experiences_secondary', function ($query) {
                        $query->with('currency')->homePage();
                    });
                });

        return $query;
    }

    public function host_experiences() {
        return $this->hasMany('App\Models\HostExperiences', 'category', 'id');
    }

    public function host_experiences_secondary() {
        return $this->hasMany('App\Models\HostExperiences', 'secondary_category', 'id');
    }

    public function allHostExperiences() {
        return $this->host_experiences->merge($this->host_experiences_secondary);
    }

    public function getAllHostExperiencesCountAttribute() {
        return $this->host_experiences->count();
    }
    public function getImageUrlAttribute()
    {
        $url = '';
        if($this->attributes['image'])
        {
            $photo_src=explode('.',$this->attributes['image']);
            if(count($photo_src)>1)
            {
                $url = url('images/host_experiences/categories/'.$this->attributes['image']);
            }
            else
            {
                $options['secure']=TRUE;
                $url =\Cloudder::show($this->attributes['image'],$options);
            }
        }
        return $url;
    }
}
