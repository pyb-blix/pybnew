<?php

/**
 * Rooms Model
 *
 * @package     Makent
 * @subpackage  Model
 * @category    Rooms
 * @author      Trioangle Product Team
 * @version     2.0
 * @link        http://trioangle.com
 */

namespace App\Models;

use App\Models\RoomType;
use App\Models\User;
use App\Models\Messages;
use Auth;
use Config;
use DateTime;
use DateTimeZone;
use Illuminate\Database\Eloquent\Model;
use JWTAuth;
use Request;
use Session;
use DB;
use App\Http\Start\Helpers;

class Rooms extends Model
{
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'rooms';

	protected $fillable = ['summary', 'name'];

	protected $appends = ['steps_count', 'property_type_name', 'room_type_name', 'bed_type_name', 'photo_name', 'host_name', 'reviews_count', 'overall_star_rating', 'reviews_count_lang', 'bed_lang','popup_price','all_photos', 'link','beds','room_status','original_image'];

	protected $dates = ['deleted_at'];

	public function setRoomTypeAttribute($input)
	{
		$room_type = RoomType::where('id', $input)->first();
		$is_shared = @$room_type->is_shared == 'Yes' ? 'Yes' : 'No';
		$this->attributes['room_type'] = $input;
		$this->attributes['is_shared'] = $is_shared;
	}

	// Check rooms table user_id is equal to current logged in user id
	public static function check_user($id)
	{
		return Rooms::where(['id' => $id, 'user_id' => Auth::user()->id])->first();
	}

	// Join with rooms_address table
	public function rooms_address()
	{
		return $this->belongsTo('App\Models\RoomsAddress', 'id', 'room_id');
	}

	// Join with rooms_price table
	public function rooms_price()
	{
		return $this->belongsTo('App\Models\RoomsPrice', 'id', 'room_id');
	}

	// Join with rooms_price table
	public function room_type_data()
	{
		return $this->belongsTo('App\Models\RoomType', 'room_type', 'id');
	}

	// Join with rooms_description table
	public function rooms_description()
	{
		return $this->belongsTo('App\Models\RoomsDescription', 'id', 'room_id');
	}

	// Join with saved_wishlists table
	public function saved_wishlists()
	{
		return $this->belongsTo('App\Models\SavedWishlists', 'id', 'room_id');
	}

	// Join with rooms_bed table
	public function rooms_beds()
	{
		return $this->hasMany('App\Models\RoomsBeds', 'room_id', 'id');
	}

	// Join with reviews table
	public function reviews()
	{
		return $this->hasMany('App\Models\Reviews', 'room_id', 'id')->where('user_to', $this->attributes['user_id'])->where('list_type', 'Rooms');
	}

	// Reviews Count
	public function getReviewsCountAttribute()
	{
		$reviews = Reviews::where('room_id', $this->attributes['id'])->where('user_to', $this->attributes['user_id'])->where('list_type', 'Rooms');

		return $reviews->count();
	}

	// Bed Count
	public function getBedLangAttribute()
	{
		return ucfirst(trans_choice('messages.lys.bed', @$this->beds));
	}

	// Reviews Count
	public function getReviewsCountLangAttribute()
	{
		return ucfirst(trans_choice('messages.header.review', $this->getReviewsCountAttribute()));
	}

	protected function getRatingResult($type)
	{
		$valid_types = array('rating', 'accuracy', 'location' ,'communication', 'checkin', 'cleanliness', 'value');
		if(!isset($this->attributes['id']) || !in_array($type, $valid_types)) {
			return '';
		}

		$reviews = Reviews::where('room_id', $this->attributes['id'])->where('user_to', $this->attributes['user_id'])->where('list_type', 'Rooms');

		if (request()->segment(1) == 'api') {

			$result['rating_value'] = '0';
			$result['is_wishlist']  = "No";

			if ($reviews->count() > 0) {
				$rating_value = roundHalfInteger($reviews->sum($type) / $reviews->count());
				$result['rating_value'] = strval($rating_value);
			}
			
			if(request()->token) {
				$user_details = JWTAuth::parseToken()->authenticate();
				$result_wishlist = SavedWishlists::with('wishlists')->where('room_id', $this->attributes['id'])->where('user_id', $user_details->id)->count();

				if ($result_wishlist > 0) {
					$result['is_wishlist'] = "Yes";
				}
			}
			return $result;
		}

		$rating_html = '';		

		if ($reviews->count() > 0) {
			$rating_html = '<div class="star-rating"> <div class="foreground">';
			$average = $reviews->sum($type) / $reviews->count();

			$whole = floor($average);
			$fraction = $average - $whole;

			for ($i = 0; $i < $whole; $i++) {
				$rating_html .= ' <i class="icon icon-star"></i>';
			}

			if ($fraction >= 0.5) {
				$rating_html .= ' <i class="icon icon-star-half"></i>';
			}

			$rating_html .= ' </div> <div class="star-bg background mb_blck">';
			$rating_html .= '<i class="icon icon-star"></i> <i class="icon icon-star"></i> <i class="icon icon-star"></i> <i class="icon icon-star"></i> <i class="icon icon-star"></i>';
			$rating_html .= ' </div> </div>';
			return $rating_html;
		}
		return $rating_html;
	}

	// Overall Reviews Star Rating
	public function getOverallStarRatingAttribute()
	{
		return $this->getRatingResult('rating');
	}

	// Accuracy Reviews Star Rating
	public function getAccuracyStarRatingAttribute()
	{
		return $this->getRatingResult('accuracy');
	}

	// Location Reviews Star Rating
	public function getLocationStarRatingAttribute()
	{
		return $this->getRatingResult('location');
	}

	// Communication Reviews Star Rating
	public function getCommunicationStarRatingAttribute()
	{
		return $this->getRatingResult('communication');
	}

	// Checkin Reviews Star Rating
	public function getCheckinStarRatingAttribute()
	{
		return $this->getRatingResult('checkin');
	}

	// Cleanliness Reviews Star Rating
	public function getCleanlinessStarRatingAttribute()
	{
		return $this->getRatingResult('cleanliness');
	}

	// Value Reviews Star Rating
	public function getValueStarRatingAttribute()
	{
		return $this->getRatingResult('value');
	}

	public function getBedsAttribute()
	{
		$beds_count = RoomsBeds::where('room_id',$this->attributes['id'])->where('count','>',0)->get()->sum('count');
		return $beds_count;
	}

	//Get rooms photo all
	public function rooms_photos()
	{
		return $this->hasMany('App\Models\RoomsPhotos', 'room_id', 'id')->ordered();
	}

	// Get All rooms all_photos
	public function getAllPhotosAttribute()
	{
		return $this->rooms_photos;
	}

	// Get rooms featured photo_name URL
	public function getPhotoNameAttribute()
	{
		$result = RoomsPhotos::where('room_id', $this->attributes['id'])->ordered();

		if ($result->count() == 0) {
			return asset('images/default_image.png');
		}
		return $result->first()->name;
	}

	// Get rooms featured photo_name URL
	public function getOriginalImageAttribute()
	{
		$result = RoomsPhotos::where('room_id', $this->attributes['id'])->ordered();

		if ($result->count() == 0) {
			return asset('images/default_image.png');
		} else {
			return $result->first()->original_image;
		}

	}

	// Get rooms featured photo_name URL
	public function getPopupPriceAttribute()
	{
		$result = RoomsPrice::where('room_id', $this->attributes['id']);

		return @$result->first()->night;
	}

	// Get rooms featured photo_name URL
	public function getSrcAttribute()
	{
		$result = RoomsPhotos::where('room_id', $this->attributes['id'])->ordered();

		if ($result->count() == 0) {
			return asset('images/default_image.png');
		} else {
			return $result->first()->name;
		}

	}

	// Get rooms featured photo_name URL
	public function getBannerPhotoNameAttribute()
	{
		$result = RoomsPhotos::where('room_id', $this->attributes['id'])->ordered();

		if ($result->count() == 0) {
			return asset('images/default_image.png');
		}
		return $result->first()->banner_image_name;
	}

	// Get steps_count using sum of rooms_steps_status
	public function getStepsCountAttribute()
	{
		$result = RoomsStepsStatus::find($this->attributes['id']);

		return 6 - (@$result->basics+@$result->description+@$result->location+@$result->photos+@$result->pricing+@$result->calendar);
	}

	// Join with users table
	public function users()
	{
		return $this->belongsTo('App\Models\User', 'user_id', 'id');
	}

	// Join with calendar table
	public function calendar()
	{
		// return $this->hasMany('App\Models\Calendar','room_id','id');
		return $this->hasMany('App\Models\Calendar', 'room_id', 'id')
		->where('status', 'Not available');
	}

	public function calendar_data()
	{
		return $this->hasMany('App\Models\Calendar', 'room_id', 'id');
	}

	// Get property_type_name from property_type table
	public function getPropertyTypeNameAttribute()
	{
		return PropertyType::find($this->attributes['property_type'])->name;
	}

	// Get room_type_name from room_type table
	public function getRoomTypeNameAttribute()
	{
		return RoomType::find($this->attributes['room_type'])->name;
	}

	// Get host name from users table
	public function getHostNameAttribute()
	{
		return User::find($this->attributes['user_id'])->first_name;
	}

	// Get bed_type_name from bed_type table
	public function getBedTypeNameAttribute()
	{
		if ($this->attributes['bed_type'] != NULL) {
			return BedType::find($this->attributes['bed_type'])->name;
		} else {
			return $this->attributes['bed_type'];
		}

	}

	public function getLinkAttribute()
	{
		$site_settings_url = @SiteSettings::where('name', 'site_url')->first()->value;
		$url = \App::runningInConsole() ? $site_settings_url : url('/');
		$this_link = $url . '/rooms/' . $this->id;
		return $this_link;
	}

	// Get host user data
	public function scopeUser($query)
	{
		return $query->where('user_id', Auth::user()->id);
	}

	// Get The Listed Room
	public function scopeListed($query)
	{
		return $query->where('status', 'Listed');
	}

	// Get The Verified Room
	public function scopeVerified($query)
	{
		return $query->where('verified', 'Approved');
	}

	// Get The Verified Listed Room
	public function scopeProfilePage($query)
	{
		return $query->listed()->verified();
	}

	// Get Created at Time for Rooms Listed
	public function getCreatedTimeAttribute()
	{
		$new_str = new DateTime($this->attributes['updated_at'], new DateTimeZone(Config::get('app.timezone')));
		if (request()->segment(1) == ADMIN_URL) {
			$timezone = User::find($this->attributes['user_id'])->timezone;
		}else{
			$timezone = Auth::user()->timezone;
		}
		$new_str->setTimeZone(new DateTimeZone($timezone));

		return date(PHP_DATE_FORMAT, strtotime($this->attributes['updated_at'])) . ' at ' . $new_str->format('h:i A');
	}

	// delete for rooms relationship data (for all table) $this->attributes['id']
	public function Delete_All_Room_Relationship()
	{
		if ($this->attributes['id'] != '') {
			$RoomsPrice = RoomsPrice::find($this->attributes['id']);if ($RoomsPrice != '') {$RoomsPrice->delete();};
			$RoomsBeds = RoomsBeds::where('room_id', $this->attributes['id']);if ($RoomsBeds != '') {$RoomsBeds->delete();};
			$RoomsAddress = RoomsAddress::find($this->attributes['id']);if ($RoomsAddress != '') {$RoomsAddress->delete();};

			/*Delete room photos start*/
				$helper = new Helpers;
				$RoomsPhotos = RoomsPhotos::where('room_id', $this->attributes['id'])->get();
	            $compress_images = ['_450x250.','_1440x960.','_1349x402.'];
				foreach($RoomsPhotos as $RoomsPhoto){
	            	$helper->remove_image_file($RoomsPhoto->original_name,"images/rooms/".$this->attributes['id'],$compress_images);
	            }
	            $RoomsPhotos = RoomsPhotos::where('room_id', $this->attributes['id'])->delete();

	            $path = public_path("images/rooms/".$this->attributes['id']);
	            $files = glob($path.'/*'); //get all file names
				foreach($files as $file){
				    if(is_file($file))
				    unlink($file); //delete file
				}
				if (is_dir($path)) {
			        rmdir($path);
			    }
				
			/*Delete room photos end*/

			$RoomsDescription = RoomsDescription::find($this->attributes['id']);if ($RoomsDescription != '') {$RoomsDescription->delete();};
			$RoomsStepsStatus = RoomsStepsStatus::find($this->attributes['id']);if ($RoomsStepsStatus != '') {$RoomsStepsStatus->delete();};
			$SavedWishlists = SavedWishlists::where('room_id', $this->attributes['id']);if ($SavedWishlists != '') {$SavedWishlists->delete();};
			$RoomsDescriptionLang = RoomsDescriptionLang::where('room_id', $this->attributes['id']);if ($RoomsDescriptionLang != '') {$RoomsDescriptionLang->delete();};
			$ImportedIcal = ImportedIcal::where('room_id', $this->attributes['id'])->delete();
			$Calendar = Calendar::where('room_id', $this->attributes['id'])->delete();
			Messages::where('room_id', $this->attributes['id'])->delete();
			SavedWishlists::where('room_id', $this->attributes['id'])->delete();
			RoomsPriceRules::where('room_id', $this->attributes['id'])->delete();
			RoomsAvailabilityRules::where('room_id', $this->attributes['id'])->delete();
			Rooms::find($this->attributes['id'])->delete();
			return true;
		}

	}

	public function getNameAttribute()
    {
        return $this->getTranslatedValue('name');
    }

    public function getSummaryAttribute()
    {
        return $this->getTranslatedValue('summary');
    }

	public function getRoomCreatedAtAttribute()
	{

		return date(PHP_DATE_FORMAT, strtotime($this->attributes['room_created_at']));
	}

	public function getRoomUpdatedAtAttribute()
	{
		return date(PHP_DATE_FORMAT, strtotime($this->attributes['room_updated_at']));
	}

	public function price_rules()
	{
		return $this->hasMany('App\Models\RoomsPriceRules', 'room_id', 'id');
	}

	public function length_of_stay_rules()
	{
		return $this->price_rules()->type('length_of_stay');
	}

	public function early_bird_rules()
	{
		return $this->price_rules()->type('early_bird');
	}

	public function last_min_rules()
	{
		return $this->price_rules()->type('last_min');
	}

	public function availability_rules()
	{
		return $this->hasMany('App\Models\RoomsAvailabilityRules', 'room_id', 'id');
	}

	public static function getLenghtOfStayOptions($keep_keys = false) {
		$nights = Request::segment(1) == ADMIN_URL ? 'nights' : trans_choice('messages.rooms.night', 2);
		$weekly = Request::segment(1) == ADMIN_URL ? 'Weekly' : trans('messages.lys.weekly');
		$monthly = Request::segment(1) == ADMIN_URL ? 'Monthly' : trans('messages.lys.monthly');

		$length_of_stay_options = [
			2 => [
				'nights' => 2,
				'text' => '2 ' . $nights,
			],
			3 => [
				'nights' => 3,
				'text' => '3 ' . $nights,
			],
			4 => [
				'nights' => 4,
				'text' => '4 ' . $nights,
			],
			5 => [
				'nights' => 5,
				'text' => '5 ' . $nights,
			],
			6 => [
				'nights' => 6,
				'text' => '6 ' . $nights,
			],
			7 => [
				'nights' => 7,
				'text' => $weekly,
			],
			14 => [
				'nights' => 14,
				'text' => '14 ' . $nights,
			],
			28 => [
				'nights' => 28,
				'text' => $monthly,
			],
		];
		if (!$keep_keys) {
			$length_of_stay_options = array_values($length_of_stay_options);
		}
		return $length_of_stay_options;
	}

	public static function getAvailabilityRulesMonthsOptions()
	{
		$month = date('m');
		$year = date('Y');
		$this_time = $start_time = mktime(12, 0, 0, $month, 1, $year);
		$end_time = mktime(12, 0, 0, $month, 1, $year + 1);

		$format = PHP_DATE_FORMAT;
		if (request()->segment(1) == 'api') {
			$format = ('d-m-Y');
		}

		$availability_rules_months_options = collect();
		$i = 1;
		while ($this_time < $end_time) {
			$loop_time = mktime(12, 0, 0, $month + ($i * 3), 0, $year);
			$start_month = date('F', $this_time);
			$end_month = date('F', $loop_time);
			$start_year = date('Y', $this_time);
			$end_year = date('Y', $loop_time);
			$start_month = trans('messages.lys.'.$start_month);
			$end_month = trans('messages.lys.'.$end_month);
			$start_year_month = $start_month.' '.$start_year;
			$end_year_month = $end_month.' '.$end_year;
			$availability_rules_months_options[] = [
				'text' => $start_year_month . ' - ' . $end_year_month,
				'start_date' => date($format, $this_time),
				'end_date' => date($format, $loop_time),
			];
			$this_time = strtotime('+1 day', $loop_time);
			$i++;
		}
		return $availability_rules_months_options;
	}
	
	// get_single_bed_type
	public function getGetSingleBedTypeAttribute()
	{
		$return = [];
		$val = BedType::where('status','Active')->limit(4)->get();
		foreach ($val as $key => $value) {
			$val[$key]->count = 0;
			$url_array = array('id' => $value->id, 'name'=> $value->name,'count'=> 0,'icon'=> $value->icon); 
			$return[]= $url_array;
		}
		return $return;
	}
    // get_first_bed_type
	public function getGetFirstBedTypeAttribute()
	{
		$bed_types       = DB::table('bed_type')->where('status','Active')->select('id')->get()->pluck('id');
        // bed_room_no
		$bed_room_count = $this->attributes['bedrooms'];
		$return=[];
		if($bed_room_count > 0){ 
			for ($i=1; $i < $bed_room_count+1; $i++) { 
				$return[$i] = [];
				$get = RoomsBeds::where('room_id',$this->attributes['id'])->where('bed_room_no',$i)->whereIn('bed_id',$bed_types)->get();

				if($get->count() > 0){ 
					foreach ($get as $key => $value) {
						$val = BedType::where('id',$value->bed_id)->where('status','Active')->first();
						if($val->count()){
							$url_array = array('id' => $val->id, 'name'=> $val->name,'count'=> $value->count,'icon'=> $val->icon);
							$return[$i][]= $url_array;
						}
					}
				}
				else{
					$val = BedType::where('status','Active')->limit(4)->get();
					foreach ($val as $key => $abc) {
						$val[$key]->count = 0;
						$url_array =array('id' => $abc->id, 'name'=> $abc->name,'count'=>0,'icon'=> $abc->icon); 
						$return[$i][]=@$url_array;
					}
				}
			}
			return @$return;

		}
		else
		{
			$bed_room_count = $bed_room_count>0?$bed_room_count:1;
			$val = BedType::where('status','Active')->limit(4)->get();
			for ($i=1; $i < $bed_room_count+1; $i++) { 
				$return[$i] = [];
				foreach ($val as $key => $value) {
					$val[$key]->count = 0;
					$url_array =array('id' => $value->id, 'name'=> $value->name,'count'=>0,'icon'=> $value->icon);
					$return[$i][]= $url_array;
				}
			}
		}

		return @$return;
	}

	// get bedroom beds
	public function getBedroomBedTypeAttribute()
	{
		$bed_types       = DB::table('bed_type')->where('status','Active')->select('id')->get()->pluck('id');
        // bed_room_no
		$bed_room_count = $this->attributes['bedrooms'];
		$return=[];
		if($bed_room_count > 0){ 
			for ($i=1; $i < $bed_room_count+1; $i++) { 
				$return[$i] = [];
				$get = RoomsBeds::where('room_id',$this->attributes['id'])->where('bed_room_no',$i)->whereIn('bed_id',$bed_types)->where('count','>',0)->get();

				if($get->count() > 0){ 
					foreach ($get as $key => $value) {
						$val = BedType::where('id',$value->bed_id)->where('status','Active')->first();
						if($val->count()){
							$url_array = array('id' => $val->id, 'name'=> $val->name,'count'=> $value->count,'icon'=> $val->icon);
							$return[$i][]= $url_array;
						}
					}
				}
			}
		}
		return @$return;
	}

	// get common room beds
	public function getCommonroomBedTypeAttribute()
	{
		$return = [];
		$bed_types       = DB::table('bed_type')->where('status','Active')->select('id')->get()->pluck('id');
		$get = RoomsBeds::where('room_id',$this->attributes['id'])->where('bed_room_no','common')->whereIn('bed_id',$bed_types)->where('count','>',0)->get();

		if($get->count() > 0){ 
			foreach ($get as $key => $value) {
				$val = BedType::where('id',@$value->bed_id)->where('status', 'Active')->first();
				$url_array =array('id' => @$val->id, 'name'=>@$val->name,'count'=>@$value->count,'icon'=>@$val->icon); 
				$return[]= $url_array;
			}
		}
		
		return $return;
	}

    // get_common_bed_type
	public function getGetCommonBedTypeAttribute()
	{
		$return = [];
		$bed_types       = DB::table('bed_type')->where('status','Active')->select('id')->get()->pluck('id');
		$get = RoomsBeds::where('room_id',$this->attributes['id'])->where('bed_room_no','common')->whereIn('bed_id',$bed_types)->get();

		if($get->count() > 0){ 
			foreach ($get as $key => $value) {
				$val = BedType::where('id',@$value->bed_id)->where('status', 'Active')->first();
				$url_array =array('id' => @$val->id, 'name'=>@$val->name,'count'=>@$value->count,'icon'=>@$val->icon); 
				$return[]= $url_array;
			}
		}
		else{
			$val = BedType::where('status','Active')->limit(4)->get();
			foreach ($val as $key => $abc) {
				$url_array =array('id' => @$abc->id, 'name'=>@$abc->name,'count'=>0,'icon'=>@$abc->icon); 
				$return[]= $url_array;
			}
		}
		return $return;
	}

	public function getRoomsBedsCountAttribute()
	{
		return RoomsBeds::where('room_id',$this->attributes['id'])->where('count','>',0)->get()->count();
	}
	public static function searcharray($value, $key, $array) 
	{
		foreach ($array as $k => $val) {
			if ($val[$key] > 0) {
				return $val[$key];
			}
		}
		return null;
	}

    // get_bathrooms
	public function getGetBathroomsAttribute()
	{
		// bed_room_no
		$bed_room_count = $this->attributes['bedrooms'];
		$return = [];
		if ($bed_room_count > 0) {
			for ($i = 1; $i < $bed_room_count + 1; $i++) {
				$get = RoomsBeds::where('room_id', $this->attributes['id'])->where('bed_room_no', $i)->get();

				if ($get->count() > 0) {
					foreach ($get as $key => $value) {
						$val = BedType::where('id', $value->bed_id)->first();
						if($val){
							if ($val->count()) {
								$url_array = array('bathrooms' => @$batrooms_value);
								$return[@$i][@$val->id] = @$url_array;
							}
						}
					}
				}
				else {
					$val = BedType::where('status', 'Active')->limit(4)->get();
					foreach ($val as $key => $abc) {
						$val[$key]->count = 0;
						$url_array = array('bathrooms' => @$batrooms_value);
						$return[@$i][@$abc->id] = @$url_array;
					}
				}
			}
			return $return;

		}
		else {
			$bed_room_count = $bed_room_count > 0 ? $bed_room_count : 1;
			$val = BedType::where('status', 'Active')->limit(4)->get();
			$batrooms_value = 'No';
			for ($i = 1; $i < $bed_room_count + 1; $i++) {
				foreach ($val as $key => $value) {
					$val[$key]->count = 0;
					$url_array = array('bathrooms' => $batrooms_value);
					$return[$i][$value->id] = $url_array;
				}
			}
		}

		return $return;
	}

	// get_common_bathrooms
	public function getGetCommonBathroomsAttribute()
	{
		$get = RoomsBeds::where('room_id', $this->attributes['id'])->where('bed_room_no', 'common')->get();
		
		if ($get->count() > 0) {
			foreach ($get as $key => $value) {
				$val = BedType::where('id', @$value->bed_id)->first();
				if ($val != '') {
					$url_array = array('bathrooms' => @$batrooms_value);
					$return[@$val->id] = @$url_array;
				}
			}
		}
		else {
			$val = BedType::where('status', 'Active')->limit(4)->get();
			foreach ($val as $key => $abc) {
				$url_array = array('bathrooms' => @$batrooms_value);
				$return[$abc->id] = $url_array;
			}
		}
		return $return;
	}

	public function getRoomStatusAttribute()
	{
		$status = $this->attributes['status'];
		$steps_count = $this->steps_count;
		if($status==null || $status == 'Pending' || ($status == 'Unlisted' && $steps_count != 0)){
			if($steps_count>0){
				$status = 'steps_remaining';
			}elseif($steps_count==0){
				$status = 'pending';
			}
		}elseif($status!=null && $status != 'Pending' && $steps_count == 0){
			if($status == 'Resubmit'){
				$status = 'resubmit';
			}elseif($status == 'Listed'){
				$status = 'listed';
			}elseif($status == 'Unlisted'){
				$status = 'unlisted';
			}
		}
		return $status;
	}

	// Get Translated value of given column
    protected function getTranslatedValue($field)
    {
        if(!isset($this->attributes[$field])) {
            return '';
        }
        $value = $this->attributes[$field];

        if(request()->segment(1) == 'manage-listing' || request()->segment(1) == ADMIN_URL) {
            return $value;
        }

        $lang_code = getLangCode();
        if ($lang_code == 'en') {
            return $value;
        }
        $trans_value = @RoomsDescriptionLang::where('room_id', $this->attributes['id'])->where('lang_code', $lang_code)->first()->$field;
        if ($trans_value) {
            return $trans_value;
        }
        return $value;
    }
}
