<?php

/**
 * Search Controller
 *
 * @package     Makent
 * @subpackage  Controller
 * @category    Search
 * @author      Trioangle Product Team
 * @version     2.0
 * @link        http://trioangle.com
 */

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Helper\PaymentHelper;
use App\Http\Start\Helpers;
use App\Models\Amenities;
use App\Models\Calendar;
use App\Models\Currency;
use App\Models\HostExperienceCalendar;
use App\Models\HostExperiences;
use App\Models\HostExperienceCategories;
use App\Models\Reservation;
use App\Models\Rooms;
use App\Models\User;
use Auth;
use DB;
use Illuminate\Http\Request;
use JWTAuth;
use Session;
use Validator;

class SearchController extends Controller {
	protected $payment_helper; // Global variable for Helpers instance
	protected $helper; // Global variable for Helpers instance

	/**
	 * Constructor to Set PaymentHelper instance in Global variable
	 *
	 * @param array $payment   Instance of PaymentHelper
	 */
	public function __construct(PaymentHelper $payment) {
		$this->payment_helper = $payment;
		$this->helper = new Helpers;
		$this->map_server_key = view()->shared('map_server_key');
		DB::enableQueryLog();
	}

	/**
	 * Display a listing of the resource
	 *
	 * @return Response in Json
	 */
	function explore_details(Request $request) {

		if (isset($request->page)) {

			if (isset($request->checkin) && isset($request->checkin)) {

				$rules = array(

					'page' => 'required|integer|min:1',

					'checkin' => 'required|date_format:d-m-Y',

					'checkout' => 'required|date_format:d-m-Y|after:today|after:checkin',

				);

			} elseif (isset($request->location)) {
				$rules = array(

					'page' => 'required|integer|min:1',

					'location' => 'required',

				);

			} elseif (isset($request->guests)) {
				$rules = array(

					'page' => 'required|integer|min:1',

					'guests' => 'required|integer|between:1,16',

				);

			} elseif (isset($request->instant_book)) {
				$rules = array(

					'page' => 'required|integer|min:1',

					'instant_book' => 'required|integer|between:0,1',

				);

			} elseif (isset($request->min_price) || isset($request->max_price)) {
				if (!isset($request->min_price) || (!isset($request->max_price))) {
					$rules = array(

						'page' => 'required|integer|min:1',

						'min_price' => 'required|numeric',

						'max_price' => 'required|numeric',

					);
				}

				$rules = array(

					'page' => 'required|integer|min:1',

					'min_price' => 'required|numeric',

					'max_price' => 'required|numeric',

				);

			} elseif (isset($request->beds)) {
				$rules = array(

					'page' => 'required|integer|min:1',

					'beds' => 'required|integer|min:1|max:16',

				);

			} elseif (isset($request->bedrooms)) {
				$rules = array(

					'page' => 'required|integer|min:1',

					'bedrooms' => 'required|integer|min:0|max:10',

				);

			} elseif (isset($request->amenities)) {
				$len = strlen($request->amenities);
				if ($len == 1) {
					$rules = array(

						'page' => 'required|integer|min:1',

						'amenities' => 'required|numeric|min:1',

					);

				} elseif ($len > 1) {
					$data = explode(',', $request->amenities);

					if (in_array("", $data)) {

						return response()->json([

							'success_message' => 'Invalid Amenities Format',

							'status_code' => '0',

						]);
					}
					if (max($data) > 31) {

						return response()->json([

							'success_message' => 'Select Amenities between 1 to 31',

							'status_code' => '0',

						]);
					}

					$rules = array(

						'page' => 'required|integer|min:1',

						'amenities' => 'required|regex:/[^[0-9]+[,]?[0-9]{1,2}$]*/',

					);

				} else {
					$rules = array(

						'page' => 'required|integer|min:1',

						'amenities' => 'required',

					);
				}

			} elseif (isset($request->room_type)) {
				$rules = array(

					'page' => 'required|integer|min:1',

					'room_type' => 'required',

				);

			} elseif (isset($request->latitude) && isset($request->longitude)) {
				$rules = array(

					'page' => 'required|integer|min:1',

					'latitude' => 'required',

					'longitude' => 'required',

				);

			} elseif (isset($request->map_details)) {

				$rules = array(

					'page' => 'required|integer|min:1',

					'map_details' => 'required',

				);

			} else {
				$rules = array('page' => 'required|integer|min:1');

			}
		} else {
			return response()->json([

				'success_message' => 'Page number requird',

				'status_code' => '0',

			]);

		}

		$messages = array(

			'required' => ':attribute is required.',

			'instant_book.integer' => ':The instant book must be 0 or 1.',

			'instant_book.between' => ':The instant book must be 0 or 1.',

			'page.integer' => ':The page no allowed only Integer value',

			'guests.between' => ':The guests may not be greater than 16.',

			'beds.integer' => ':The beds allowed only Integer value',

			'bedrooms.integer' => ':The bedrooms allowed only Integer value',

		);

		$validator = Validator::make($request->all(), $rules, $messages);

		if ($validator->fails()) {
			$error = $validator->messages()->toArray();

			foreach ($error as $er) {
				$error_msg[] = array($er);

			}

			return response()->json([

				'success_message' => $error_msg['0']['0']['0'],

				'status_code' => '0',

			]);
		}
		$currency_code =  $this->helper->get_user_currency_code();
		//get currency details
		$currency_symbol = @Currency::where('code', $currency_code)->first();
		//set user token for remove header and footer
		Session::put('get_token', $request->token);

		if ($request->page != '' && $request->page != '0') {
			$full_address = $request->input('location');

			$checkin = $request->input('checkin');

			$checkout = $request->input('checkout');

			$guest = $request->input('guests');

			$bathrooms = $request->input('bathrooms');

			$bedrooms = $request->input('bedrooms');

			$beds = $request->input('beds');

			$property_type = $request->input('property_type');

			$room_type = $request->input('room_type');

			$amenities = $request->input('amenities');

			$min_price = $request->input('min_price');

			$max_price = $request->input('max_price');

			$map_details = $request->input('map_details');

			$instant_book = $request->input('instant_book');

			$data['viewport'] = '';

			// convert minimum price based on user currency
			$default_min_price = $this->payment_helper->currency_convert(DEFAULT_CURRENCY, $currency_code, MINIMUM_AMOUNT);

			//convert maxmimum price based on user currency
			$default_max_price = $this->payment_helper->currency_convert(DEFAULT_CURRENCY, $currency_code, MAXIMUM_AMOUNT);

			if (!$min_price) {
				$min_price = $this->payment_helper->currency_convert(DEFAULT_CURRENCY, $currency_code, 0);
				$max_price = $this->payment_helper->currency_convert(DEFAULT_CURRENCY, $currency_code, MAXIMUM_AMOUNT);

			}

			if ($min_price != '' && $max_price == '') {
				$max_price = $this->payment_helper->currency_convert(DEFAULT_CURRENCY, $currency_code, MAXIMUM_AMOUNT);
			}

			if (!is_array($room_type)) {
				if ($room_type != '') {
					$room_type = explode(',', $room_type);
				} else {
					$room_type = [];
				}

			}

			if (!is_array($property_type)) {
				if ($property_type != '') {
					$property_type = explode(',', $property_type);
				} else {
					$property_type = [];
				}

			}

			if (!is_array($amenities)) {
				if ($amenities != '') {
					$amenities = explode(',', $amenities);
				} else {
					$amenities = [];
				}

			}

			$property_type_val = [];
			$rooms_whereIn = [];
			$room_type_val = [];
			$rooms_address_where = [];

			$address = str_replace([" ", "%2C"], ["+", ","], "$full_address");
			$geocode = @file_get_contents('https://maps.google.com/maps/api/geocode/json?key=' . $this->map_server_key . '&address=' . $address . '&sensor=false&libraries=places');
			$json = json_decode($geocode);

			if (@$json->results) {
				foreach ($json->results as $result) {
					foreach ($result->address_components as $addressPart) {
						if ((in_array('locality', $addressPart->types)) && (in_array('political', $addressPart->types))) {
							$city1 = $addressPart->long_name;
							$rooms_address_where['rooms_address.city'] = $city1;
						}
						if ((in_array('administrative_area_level_1', $addressPart->types)) && (in_array('political', $addressPart->types))) {
							$state = $addressPart->long_name;
							$rooms_address_where['rooms_address.state'] = $state;
						}
						if ((in_array('country', $addressPart->types)) && (in_array('political', $addressPart->types))) {
							$country = $addressPart->short_name;
							$rooms_address_where['rooms_address.country'] = $country;
						}
					}
				}
			}

			if ($map_details != '') {
				$map_detail = explode('~', $map_details);
				$zoom = $map_detail[0];
				$bounds = $map_detail[1];
				$minLat = $map_detail[2];
				$minLong = $map_detail[3];
				$maxLat = $map_detail[4];
				$maxLong = $map_detail[5];
				$cLat = $map_detail[6];
				$cLong = $map_detail[7];
			} else {
				if (@$json->{'results'}) {
					$data['viewport'] = $json->{'results'}[0]->{'geometry'}->{'viewport'};

					$minLat = $json->{'results'}[0]->{'geometry'}->{'viewport'}->{'southwest'}->{'lat'};
					$maxLat = $json->{'results'}[0]->{'geometry'}->{'viewport'}->{'northeast'}->{'lat'};
					$minLong = $json->{'results'}[0]->{'geometry'}->{'viewport'}->{'southwest'}->{'lng'};
					$maxLong = $json->{'results'}[0]->{'geometry'}->{'viewport'}->{'northeast'}->{'lng'};
				} else {
					$data['lat'] = 0;
					$data['long'] = 0;

					$minLat = -1000;
					$maxLat = 1000;
					$minLong = -1000;
					$maxLong = 1000;
				}
			}
			$users_where['users.status'] = 'Active';

			$checkin = date('Y-m-d', strtotime($checkin));

			$checkout = date('Y-m-d', strtotime($checkout));

			$days = $this->get_days($checkin, $checkout);

			unset($days[count($days) - 1]);

			$total_nights = count($days);
			$total_weekends = 0;
			foreach ($days as $day) {
				$weekday = date('N', strtotime($day));
				if (in_array($weekday, [5, 6])) {
					$total_weekends++;
				}
			}
			$from = new \DateTime($checkin);
			$today = new \DateTime(date('Y-m-d'));
			$period = $from->diff($today)->format("%a") + 1;
			$total_guests = intval($guest);
			$dates = implode(',', $days);

			$calendar_where['date'] = $days;

			$rooms_where['rooms.accommodates'] = $guest;

			$rooms_where['rooms.status'] = 'Listed';

			if ($bathrooms) {
				$rooms_where['rooms.bathrooms'] = $bathrooms;
			}

			if ($bedrooms) {
				$rooms_where['rooms.bedrooms'] = $bedrooms;
			}

			/*if ($beds) {
				$rooms_where['rooms.beds'] = $beds;
			}*/

			if (count($property_type)) {
				foreach ($property_type as $property_value) {
					array_push($property_type_val, $property_value);
				}

				$rooms_whereIn['rooms.property_type'] = $property_type_val;
			}

			if (count($room_type)) {
				foreach ($room_type as $room_value) {
					array_push($room_type_val, $room_value);
				}

				$rooms_whereIn['rooms.room_type'] = $room_type_val;
			}

			if ($instant_book == 1) {
				$rooms_where['rooms.booking_type'] = 'instant_book';
			}
			$currency_code_change = $currency_code != null ? $currency_code : DEFAULT_CURRENCY;
			$currency_rate = Currency::where('code', $currency_code_change)->first()->rate;

			$max_price_check = $this->payment_helper->currency_convert($currency_code, DEFAULT_CURRENCY, $max_price);

			$dates_available = ($request->input('checkin') != '' && $request->input('checkout') != '');

			$not_available_room_ids = [];

			// Availability Filters Start
			$not_available_room_ids = Calendar::daysNotAvailable($days, $total_guests)->distinct()->pluck('room_id')->toArray();
			if ($dates_available) {
				// Create virtual Table for rooms availability rules with given dates
				$availability_rules_virtual_table = DB::table('rooms_availability_rules')
					->select('minimum_stay as rule_minimum_stay', 'maximum_stay as rule_maximum_stay', 'room_id', 'id as rule_id')
					->whereRaw("start_date <= '" . $checkin . "'")
					->whereRaw("end_date >= '" . $checkin . "'")
					->orderBy('type', 'ASC')
					->orderBy('rooms_availability_rules.id', 'DESC')
					->limit(1)
					->toSql();
				// Query to get the prioritized rule minimum stay
				$rule_minimum_stay_query = DB::table('rooms_availability_rules')
					->select('minimum_stay')
					->whereRaw("start_date <= '" . $checkin . "'")
					->whereRaw("end_date >= '" . $checkin . "'")
					->orderBy('type', 'ASC')
					->orderBy('rooms_availability_rules.id', 'DESC')
					->whereRaw('room_id = rooms.id')
					->limit(1)
					->toSql();
				// Query to get the prioritized rule maximum stay
				$rule_maximum_stay_query = DB::table('rooms_availability_rules')
					->select('maximum_stay')
					->whereRaw("start_date <= '" . $checkin . "'")
					->whereRaw("end_date >= '" . $checkin . "'")
					->orderBy('type', 'ASC')
					->orderBy('rooms_availability_rules.id', 'DESC')
					->whereRaw('room_id = rooms.id')
					->limit(1)
					->toSql();
				// Query to get the prioritized rule id
				$rule_id_query = DB::table('rooms_availability_rules')
					->select('id')
					->whereRaw("start_date <= '" . $checkin . "'")
					->whereRaw("end_date >= '" . $checkin . "'")
					->orderBy('type', 'ASC')
					->orderBy('rooms_availability_rules.id', 'DESC')
					->whereRaw('room_id = rooms.id')
					->limit(1)
					->toSql();
				// select availability rules virttual table with rooms table and select minimum and maximum stay values
				$rooms_availability_rules = DB::table('rooms')
					->select('rooms.id', 'rooms_price.minimum_stay', 'rooms_price.maximum_stay')
					->selectRaw("(" . $rule_minimum_stay_query . ") as rule_minimum_stay")
					->selectRaw("(" . $rule_maximum_stay_query . ") as rule_maximum_stay")
					->selectRaw("(" . $rule_id_query . ") as rule_id")
					->selectRaw('( SELECT IF(rule_id >0,(IFNULL(rule_minimum_stay, null)),(IFNULL(minimum_stay, null))) ) as check_minimum_stay')
					->selectRaw('( SELECT IF(rule_id >0,(IFNULL(rule_maximum_stay, null)),(IFNULL(maximum_stay, null))) ) as check_maximum_stay')
				// ->leftJoin(DB::raw("(".$availability_rules_virtual_table.") as availability_rule"),
				//     function($join) {
				//         $join->on('rooms.id','=','availability_rule.room_id');
				//     })
					->join('rooms_price', 'rooms_price.room_id', '=', 'rooms.id')
					->whereNotIn('rooms.id', $not_available_room_ids);
				// Compare the minimum stay and maximum stay value with the total nights to get the unavailable room_ids
				$availability_rules_missed_rooms = $rooms_availability_rules
					->havingRaw('(check_minimum_stay IS NOT NULL and check_minimum_stay > ' . $total_nights . ')')
					->orHavingRaw('(check_maximum_stay IS NOT NULL and check_maximum_stay < ' . $total_nights . ')')
					->pluck('id')->toArray();

				$not_available_room_ids = array_merge($not_available_room_ids, $availability_rules_missed_rooms);
			}
			// Availability Filters End
			// Basic Filters Start

			$rooms = Rooms::with(['rooms_address',
					'users' => function ($query) {
						$query->with('profile_picture');
					}])
					->whereHas('users', function ($query) use ($users_where) {
						$query->where($users_where);
					});

			if(isset($request->latitude) && isset($request->longitude)){
				$rooms = $rooms->
					whereHas('rooms_address', function ($query) use ($request) {
						$query->select(DB::raw('*, ( 3959 * acos( cos( radians('.$request->latitude.') ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians('.$request->longitude.') ) + sin( radians('.$request->latitude.') ) * sin( radians( latitude ) ) ) ) as distance'))->having('distance', '<=', 5);
					});
			}else{
				$rooms = $rooms->
					whereHas('rooms_address', function ($query) use ($minLat, $maxLat, $minLong, $maxLong) {
						$query->whereRaw("latitude between $minLat and $maxLat and longitude between $minLong and $maxLong");
					});
			}

			if ($rooms_where) {
				foreach ($rooms_where as $row => $value) {
					if ($row == 'rooms.accommodates' || $row == 'rooms.bathrooms' || $row == 'rooms.bedrooms') {
						$operator = '>=';
					} else {
						$operator = '=';
					}

					if ($value == '') {
						$value = 0;
					}

					$rooms = $rooms->where($row, $operator, $value);
				}
			}

			if ($rooms_whereIn) {
				foreach ($rooms_whereIn as $row_rooms_whereIn => $value_rooms_whereIn) {
					$rooms = $rooms->whereIn($row_rooms_whereIn, array_values($value_rooms_whereIn));
				}

			}

			if (count($amenities)) {
				foreach ($amenities as $amenities_value) {
					$rooms = $rooms->whereRaw('find_in_set(' . $amenities_value . ', amenities)');
				}

			}

			$rooms->whereNotIn('id', $not_available_room_ids);
			// Basic Filters End
			// Price Filter Start
			// Query to get sum of calendar price for rooms in a given dates
			$calendar_price_total_query = DB::table("calendar")
				->selectRaw('sum(price)')
				->whereRaw('calendar.room_id = rooms.id')
				->whereRaw('FIND_IN_SET(calendar.date, "' . $dates . '")')
				->toSql();
			// Query to count the total calendar result for rooms as special nights
			$calendar_special_nights_query = DB::table("calendar")
				->selectRaw("count('*')")
				->whereRaw('calendar.room_id = rooms.id')
				->whereRaw('FIND_IN_SET(calendar.date, "' . $dates . '")')
				->toSql();
			// Query to count the total weekend calendar result for rooms as special weekends
			$calendar_special_weekends_query = DB::table("calendar")
				->selectRaw("count('*')")
				->whereRaw('calendar.room_id = rooms.id')
				->whereRaw('FIND_IN_SET(calendar.date, "' . $dates . '")')
				->whereRaw('( WEEKDAY(date) = 4 OR WEEKDAY(date) = 5 )')
				->toSql();
			// Query to get rooms price rules minimum period for last min booking
			$min_price_rule_period_query = DB::table('rooms_price_rules')
				->selectRaw('min(period)')
				->whereRaw('room_id = rooms.id')
				->whereRaw('period>=' . $period)
				->whereRaw("type = 'last_min'")
				->toSql();
			// Query to get rooms price rules maximum period for early bird booking
			$max_price_rule_period_query = DB::table('rooms_price_rules')
				->selectRaw('max(period)')
				->whereRaw('room_id = rooms.id')
				->whereRaw('period<=' . $period)
				->whereRaw("type = 'early_bird'")
				->toSql();
			// Query to find the booking period discount based on the dates
			$booked_period_discount_query = DB::table('rooms_price_rules')
				->select('discount')
				->whereRaw('room_id = rooms.id')
				->where(function ($query) use ($period) {
					$query->where(function ($sub_query) use ($period) {
						$sub_query->whereRaw('period >= ' . $period)
							->whereRaw("type = 'last_min'")
							->whereRaw('period= min_price_rule_period');
					});
					$query->orWhere(function ($sub_query) use ($period) {
						$sub_query->whereRaw('period <= ' . $period)
							->whereRaw("type = 'early_bird'")
							->whereRaw('period= max_price_rule_period');
					});
				})
				->toSql();
			// Query to find the appropriate period for the length of stay based on total nights
			$length_of_stay_period_query = DB::table('rooms_price_rules')
				->selectRaw('max(period)')
				->whereRaw('room_id = rooms.id')
				->whereRaw('period<=' . $total_nights)
				->whereRaw("type = 'length_of_stay'")
				->toSql();
			// Query to get the length of stay discount from price rules based on total nights
			$length_of_stay_discount_query = DB::table('rooms_price_rules')
				->select('discount')
				->whereRaw('room_id = rooms.id')
				->whereRaw('period<=' . $total_nights)
				->whereRaw("type = 'length_of_stay'")
				->whereRaw("period = length_of_stay_period")
				->toSql();
			// Create a rooms price details virtual table with all the possible prices applied
			$rooms_price_details_virtual_table = DB::table('rooms')
				->select('rooms.id as room_id')
				->selectRaw("(" . $calendar_price_total_query . ") as calendar_total")
				->selectRaw("(" . $calendar_special_nights_query . ") as special_nights")
				->selectRaw("(" . $calendar_special_weekends_query . ") as special_weekends")

				->selectRaw("(SELECT " . $total_weekends . "-special_weekends) as normal_weekends")
				->selectRaw("(SELECT " . $total_nights . "-special_nights-normal_weekends) as normal_nights")
				->selectRaw("(SELECT (rooms_price.night * normal_nights) + ( IF (rooms_price.weekend >0 , rooms_price.weekend , rooms_price.night) * normal_weekends)) as price_total")

				->selectRaw("(SELECT IFNULL(price_total, 0)+ IFNULL(calendar_total, 0)) as base_total")

				->selectRaw("(" . $min_price_rule_period_query . ") as min_price_rule_period")
				->selectRaw("(" . $max_price_rule_period_query . ") as max_price_rule_period")
				->selectRaw("(" . $booked_period_discount_query . ") as booked_period_discount")

				->selectRaw("(" . $length_of_stay_period_query . ") as length_of_stay_period")
				->selectRaw("(" . $length_of_stay_discount_query . ") as length_of_stay_discount")

				->selectRaw("(SELECT Round(base_total*(booked_period_discount/100)) ) as booked_period_discount_price")
				->selectRaw("(SELECT ROUND(base_total-IFNULL(booked_period_discount_price, 0))) as booked_period_base_total")

				->selectRaw("(SELECT Round(booked_period_base_total*(length_of_stay_discount/100)) ) as length_of_stay_discount_price")
				->selectRaw("(SELECT ROUND(booked_period_base_total - IFNULL(length_of_stay_discount_price, 0))) as discounted_base_total")

				->selectRaw("(SELECT case when (" . $total_guests . "-rooms_price.guests) > 0 THEN (" . $total_guests . "-rooms_price.guests) else 0 end ) as extra_guests")

				->selectRaw("(SELECT ROUND(IFNULL(discounted_base_total, 0) + rooms_price.cleaning + rooms_price.security + (extra_guests * rooms_price.additional_guest) ) ) as total")
				->selectRaw("(SELECT ROUND(total/" . $total_nights . ")) as avg_price")
				->selectRaw("(SELECT ROUND(total/" . $total_nights . ")) as night")
				->selectRaw("( SELECT ROUND(((avg_price / currency.rate) * " . $currency_rate . "))) as session_night")

				->join('calendar', 'calendar.room_id', '=', 'rooms.id', 'LEFT OUTER')
				->join('rooms_price', 'rooms_price.room_id', '=', 'rooms.id', 'LEFT')
				->leftJoin('currency', 'currency.code', '=', 'rooms_price.currency_code')
				->groupBy('rooms.id')
				->toSql();
			// Join the rooms price details virtual table with the rooms price
			$rooms = $rooms->with([
				'rooms_price' => function ($query) use ($rooms_price_details_virtual_table, $currency_rate, $min_price, $max_price, $max_price_check, $dates_available) {
					$query->select('*');
					if ($dates_available) {
						$query->leftJoin(DB::raw("(" . $rooms_price_details_virtual_table . ") as rooms_price_details"), function ($join) {
							$join->on('rooms_price.room_id', '=', 'rooms_price_details.room_id');
						});
					}
					$query->with('currency');
				},
			]);

			// $rooms          = $rooms->join('rooms_price', 'rooms_price.room_id', '=', 'rooms.id');

			if ($dates_available) {
				$rooms = $rooms->leftJoin(DB::raw("(" . $rooms_price_details_virtual_table . ") as rooms_price_details"), function ($join) {
					$join->on('rooms.id', '=', 'rooms_price_details.room_id');
				});
				// Compare the session night price with the given min price and max price
				if ($max_price_check >= MAXIMUM_AMOUNT) {
					$rooms->havingRaw('session_night >= ' . $min_price);
				} else {
					$rooms->havingRaw('session_night >= ' . $min_price . ' and session_night <= ' . $max_price);
				}
			} else {
				$rooms->whereHas('rooms_price', function ($query) use ($currency_rate, $min_price, $max_price, $max_price_check) {
					$query->join('currency', 'currency.code', '=', 'rooms_price.currency_code');
					if ($max_price_check >= MAXIMUM_AMOUNT) {
						$query->whereRaw('ROUND(((night / currency.rate) * ' . $currency_rate . ')) >= ' . $min_price);
					} else {
						$query->whereRaw('ROUND(((night / currency.rate) * ' . $currency_rate . ')) >= ' . $min_price . ' and ROUND(((night / currency.rate) * ' . $currency_rate . ')) <= ' . $max_price);
					}
				});
			}
			// Price Filter End

			// Beds Filter Started
				$beds = ($beds==null)?0:$beds;
				$bed_type = DB::table('bed_type')->select('id')->where('status','Active')->pluck('id');
		        $rooms = $rooms->whereHas('rooms_beds',function($q) use ($bed_type,$beds){
		            $q->whereIn('bed_id',$bed_type)
		            ->havingRaw('SUM(count) >= ?', [$beds]);
		        });
		    // Beds Filter End

			$rooms = $rooms->orderByRaw('RAND(1234)')->get()->paginate(10)->toJson();

			$data = array(

				'success_message' => 'Rooms Details Listed Successfully',

				'status_code' => '1',

			);

			$data_success = json_encode($data);

			$totalcount = json_decode($rooms);

			if ($totalcount->total == 0 || empty($totalcount->data)) {
				return response()->json([

					'success_message' => 'No Data Found',

					'status_code' => '0',

				]);
			} else {

				$data_result = json_decode($rooms, true);
				$data_result['data'] = array_values($data_result['data']);
				$count = count($data_result['data']);

				for ($i = 0; $i < $count; $i++) {

					@$result_value[] = array(

						'room_id' => $data_result['data'][$i]['id'],

						'room_type' => $data_result['data'][$i]['room_type_name'],

						'bed_count' => $data_result['data'][$i]['beds'],

						'room_price' => $data_result['data'][$i]

						['rooms_price']['night'],

						'room_name' => $data_result['data'][$i]['name'],

						'photo_name' => $data_result['data'][$i]['photo_name'],

						'rating' => $data_result['data'][$i]

						['overall_star_rating']['rating_value'] != null

						? (string) $data_result['data'][$i]

						['overall_star_rating']['rating_value']

						: '0',

						'reviews_count' => $data_result['data'][$i]

						['reviews_count'] != null

						? $data_result['data'][$i]

						['reviews_count'] : 0,

						'instant_book' => $data_result['data'][$i]

						['booking_type'] == 'instant_book'

						? 'Yes' : 'No',

						'latitude' => (string) $data_result['data'][$i]

						['rooms_address']['latitude'],

						'longitude' => (string) $data_result['data'][$i]

						['rooms_address']['longitude'],

						'is_wishlist' => $data_result['data'][$i]

						['overall_star_rating']['is_wishlist'] != null

						? $data_result['data'][$i]

						['overall_star_rating']['is_wishlist'] : 'No',

						'country_name' => $data_result['data'][$i]

						['rooms_address']['country_name'],

						'city_name' => $data_result['data'][$i]['rooms_address']['city'] != ''

						? $data_result['data'][$i]['rooms_address']['city']

						: $data_result['data'][$i]['rooms_address']['country_name'],

						'currency_code' => $currency_code != ''

						? $currency_code : DEFAULT_CURRENCY,

						'currency_symbol' => html_entity_decode($currency_symbol->original_symbol != null? $currency_symbol->original_symbol : '&#36;'),
					);
				}

				$result = array(
					'total_page' => $data_result['last_page'],

					'min_price' => $default_min_price,

					'max_price' => $default_max_price,

					'data' => $result_value,
				);

				$data = json_encode($result);

				return json_encode(array_merge(json_decode($data_success, true), json_decode($data, true)), JSON_UNESCAPED_SLASHES);
			}
		} else {
			return response()->json([

				'success_message' => 'Undefind Page No',

				'status_code' => '0',

			]);

		}
	}

	public function explore_experiences(Request $request) {

		$currency_code =  $this->helper->get_user_currency_code();
		$rules = [
			'page' => 'required|integer|min:1',
			'checkin' => 'nullable|date_format:d-m-Y|after:yesterday',
			'checkout' => 'required_with:checkin|date_format:d-m-Y|after:today|after:checkin',
			'guests' => 'nullable|integer|min:1',
			'location' => 'nullable',
		];

		$validator = Validator::make($request->all(), $rules);
		if ($validator->fails()) {
			return response()->json(
				[
					'success_message' => $validator->messages()->first(),
					'status_code' => '0',
				]
			);
		}

		$location = $request->location;
		$checkin = $request->checkin;
		$checkout = $request->checkout;
		$guest = ($request->guests > 0)?$request->guests:1;
		if ($request->category) {
			$host_experience_category = explode(',', $request->category);
		} else {
			$host_experience_category = array();
		}

		$map_details = $request->map_details;

		$category_val = [];

		$address = str_replace([" ", "%2C"], ["+", ","], "$location");
		$geocode = @file_get_contents('https://maps.google.com/maps/api/geocode/json?key=' . $this->map_server_key . '&address=' . $address . '&sensor=false&libraries=places');
		$json = json_decode($geocode);

		if ($map_details != '') {
			$map_detail = explode('~', $map_details);
			$zoom = $map_detail[0];
			$bounds = $map_detail[1];
			$minLat = $map_detail[2];
			$minLong = $map_detail[3];
			$maxLat = $map_detail[4];
			$maxLong = $map_detail[5];
			$cLat = $map_detail[6];
			$cLong = $map_detail[7];

			if ($minLong > $maxLong) {
				if ($maxLong > 0) {
					$maxLong = $minLong;
					$minLong = "-180";
				} else {
					$maxLong = "180";
				}
			}
			// dump($zoom,$bounds,$minLat,$maxLat,$minLong,$maxLong,$cLat,$cLong);
		} else {
			if (@$json->{'results'}) {
				// $data['lat'] = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lat'};
				// $data['long'] = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lng'};
				$data['viewport'] = $json->{'results'}[0]->{'geometry'}->{'viewport'};

				$minLat = $json->{'results'}[0]->{'geometry'}->{'viewport'}->{'southwest'}->{'lat'};
				$maxLat = $json->{'results'}[0]->{'geometry'}->{'viewport'}->{'northeast'}->{'lat'};
				$minLong = $json->{'results'}[0]->{'geometry'}->{'viewport'}->{'southwest'}->{'lng'};
				$maxLong = $json->{'results'}[0]->{'geometry'}->{'viewport'}->{'northeast'}->{'lng'};
			} else {
				$data['lat'] = 0;
				$data['long'] = 0;

				$minLat = -1000;
				$maxLat = 1000;
				$minLong = -1000;
				$maxLong = 1000;
			}
		}

		$users_where['users.status'] = 'Active';

		if ($checkin) {
			$checkin = date('Y-m-d', $this->helper->custom_strtotime($checkin, 'd-m-Y'));
			$checkout = date('Y-m-d', $this->helper->custom_strtotime($checkout, 'd-m-Y'));

			$days = $this->get_days($checkin, $checkout);
			$calendar_where['date'] = $days;
			$not_available_room_ids = HostExperienceCalendar::whereIn('date', $days)->whereStatus('Not available')->distinct()->pluck('host_experience_id');
		} else {
			$days = [];
		}

		$rooms_where['host_experiences.number_of_guests'] = $guest;

		$rooms_where['host_experiences.status'] = 'Listed';
		$rooms_where['host_experiences.admin_status'] = 'Approved';

		if (count($host_experience_category)) {
			foreach ($host_experience_category as $category_value) {
				array_push($category_val, $category_value);
			}

		}

		$currency_rate = Currency::where('code', Currency::first()->session_code)->first()->rate;

		$rooms = HostExperiences::with(['host_experience_location' => function ($query) use ($minLat, $maxLat, $minLong, $maxLong) {},
			'currency' => function ($query) {},
			'category_details' => function ($query) {},
			'user' => function ($query) use ($users_where) {
				$query->with('profile_picture')
					->where($users_where);
			},
			'saved_wishlists' => function ($query) {
				$query->where('user_id', @Auth::user()->id);
			}])
			->whereHas('user', function ($query) use ($users_where) {
				$query->where($users_where);
			})
			->daysAvailable($days, $guest);


		if(isset($request->latitude) && isset($request->longitude)){
			$rooms = $rooms
				->whereHas('host_experience_location', function ($query) use ($request) {
					$query->select(DB::raw('*, ( 3959 * acos( cos( radians('.$request->latitude.') ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians('.$request->longitude.') ) + sin( radians('.$request->latitude.') ) * sin( radians( latitude ) ) ) ) as distance'))->having('distance', '<=', 5);
				});
		}else{
			$rooms = $rooms
				->whereHas('host_experience_location', function ($query) use ($minLat, $maxLat, $minLong, $maxLong) {
					$query->whereRaw("latitude between $minLat and $maxLat and longitude between $minLong and $maxLong");
				});
		}

		if ($rooms_where) {
			foreach ($rooms_where as $row => $value) {
				if ($row == 'host_experiences.number_of_guests') {
					$operator = '>=';
				} else {
					$operator = '=';
				}

				if ($value == '') {
					$value = 0;
				}

				$rooms = $rooms->where($row, $operator, $value);
			}
		}
		if (count($host_experience_category)) {
			$rooms = $rooms->where(function ($query) use ($category_val) {
				$query->whereIn('category', $category_val);
				$query->orwhereIn('secondary_category', $category_val);
			});
		}
		$rooms = $rooms->orderByRaw('RAND(1234)')->paginate(10);

		if ($rooms->count() == 0) {
			return response()->json(
				[
					'success_message' => trans('messages.api.no_data_found'),
					'status_code' => '0',
				]
			);
		}
		$rooms_lists = $rooms->map(
			function ($experience) use ($currency_code) {
				return [
					'experience_id' => $experience->id,
					'experience_price' => $experience->session_price,
					'experience_name' => $experience->title,
					'photo_name' => $experience->photo_resize_name,
					'experience_category' => $experience->category_details->name,
					'rating' => $experience->overall_star_rating['rating_value'] ? (string) $experience->overall_star_rating['rating_value'] : '0',
					'reviews_count' => $experience->reviews_count,
					'latitude' => $experience->host_experience_location->latitude,
					'longitude' => $experience->host_experience_location->longitude,
					'is_wishlist' => $experience->overall_star_rating['is_wishlist'],
					'country_name' => $experience->host_experience_location->country_name,
					'city_name' => $experience->host_experience_location->city ?: $experience->host_experience_location->country_name,
					'currency_code' => $currency_code,
					'currency_symbol' => Currency::original_symbol($currency_code),
				];
			}
		)->toArray();

		$Experiences = $rooms->map(
			function ($experience) use ($currency_code) {
				return [
					'id' => $experience->id,
					'price' => $experience->session_price,
					'name' => $experience->title,
					'photo_name' => $experience->photo_resize_name,
					'category_name' => $experience->category_details->name,
					'rating' => $experience->overall_star_rating['rating_value'] ? (string) $experience->overall_star_rating['rating_value'] : '0',
					'reviews_count' => $experience->reviews_count,
					'latitude' => $experience->host_experience_location->latitude,
					'longitude' => $experience->host_experience_location->longitude,
					'is_wishlist' => $experience->overall_star_rating['is_wishlist'],
					'country_name' => $experience->host_experience_location->country_name,
					'city_name' => $experience->host_experience_location->city ?: $experience->host_experience_location->country_name,
					'currency_code' => $currency_code,
					'currency_symbol' => Currency::original_symbol($currency_code),
				];
			}
		)->toArray();

		return response()->json(
			[
				'success_message' => trans('messages.api.experience_listed'),
				'status_code' => '1',
				'total_page' => $rooms->lastPage(),
				'data' => $rooms_lists,
				'Experiences' => [ ['Experiences'=> $Experiences] ],
			]
		);
	}

	/**
	 * Get days between two dates
	 *
	 * @param date $sStartDate  Start Date
	 * @param date $sEndDate    End Date
	 * @return array $days      Between two dates
	 */
	public function get_days($sStartDate, $sEndDate) {
		$aDays[] = $sStartDate;
		$sCurrentDate = $sStartDate;

		while ($sCurrentDate < $sEndDate) {
			$sCurrentDate = gmdate("Y-m-d", strtotime("+1 day", strtotime($sCurrentDate)));
			$aDays[] = $sCurrentDate;
		}

		return $aDays;
	}

	/**
	 * Get Room Details with or without filter
	 *
	 * @return Rooms
	 */

	 public function rooms()
    {
    	$request = request();

		if($request)
		{
		    $filter = $this->filter(request());
		}

    	$rooms = Rooms::with(['rooms_price' => function ($query) {
		$query->with(['currency'=>function($query1){
		$query1->select('code','symbol');
		}]);
		}])->whereHas('users', function ($query) {
		$query->where('status', 'Active');
		});

		if(isset($request->location))
		{

		    $minLat=$filter['minLat'];
		    $maxLat=$filter['maxLat'];
		    $minLong=$filter['minLong'];
		    $maxLong=$filter['maxLong'];

		    $rooms = $rooms->whereHas('rooms_address', function ($query) use ($minLat, $maxLat, $minLong, $maxLong) {
		    $query->whereRaw("latitude between $minLat and $maxLat and longitude between $minLong and $maxLong");
		})    ;

		}
		if(isset($request->guests))
		{
		    $rooms = $rooms->where('accommodates','>=',$request->guests);
		}
		if(isset($request->checkin))
		{
		   $rooms =  $rooms->whereNotIn('id', $filter['not_available_room_ids']);
		}

		return $rooms;
    }

     /**
     * Recommented     
     */

    public function getRecommented() 
    {
        $recommented = $this->rooms();
        $recommented = $recommented->orderBy('id', 'desc')
        ->where('recommended', 'Yes')
        ->where('status', 'Listed')
        ->groupBy('id');
			if(request()->list=="Recommended")
			{
			  $recommented = $recommented->paginate(10);
			  $total_page = $recommented->lastPage();
			}
			else{
			 	$recommented = $recommented->limit(4)->get();
			 	$total_page = 0;
			}


       $recommented =  $this->commonMapFunction($recommented);
       return ['recommented'=>$recommented,'total_page'=>$total_page];
    }

    /**
     * View count     
     */
    public function getViewCount() 
    {
		$views_count = $this->rooms();
		$views_count = $views_count->orderBy('views_count', 'desc')
		->where('status', 'Listed')
		->groupBy('id');

	        if(request()->list=="MostViewed")
	        {
	           $views_count = $views_count->paginate(10);
	           $total_page = $views_count->lastPage();
	        }
	        else{
	        	$views_count = $views_count->limit(4)->get();
	        	$total_page = 0;
	        }

        $views_count =  $this->commonMapFunction($views_count);
        return ['views_count'=>$views_count,'total_page'=>$total_page];
    }
	/**
     * Listing details in home    
     */
    public function homePage() 
    {
        $host_experiences = [];
        $reservation=[];
        $view_count =[];
        $explore_makent =[];

        if(!request()->search_type || request()->search_type=="stay" || request()->search_type=="all"){
        	$explore_makent[] = [ 'name'=>trans('messages.home.stays'),'key'=>'Homes','image' => view()->shared('home_page_stay_image')];
        }

        if(!request()->search_type || request()->search_type=="experience" || request()->search_type=="all"){
        	$explore_makent[] = ['name'=>trans_choice('messages.home.experience',2),'key'=>'Experiences','image' => view()->shared('home_page_experience_image')];
        }

             $data = [];
             $total_page = 0;
             if(isset(request()->list))
             {     
             	    if(request()->list=="Experiences"){
             	    	$experiences = $this->getExperiences();
						if(count($experiences['host_experiences'])>0){
             				$total_page = $experiences['total_page'];
						   	$data[] = ['Title' =>trans('messages.api.host_experience'), 'Key' =>'Experiences','Details' => $experiences['host_experiences']];
						}
					}
					if(request()->list=="MostViewed"){
						$view_detail = $this->getViewCount();
						if(count($view_detail['views_count']) >0){
             				$total_page = $view_detail['total_page'];
				     		$data[] = ['Title' => trans('messages.api.most_viewed'),'Key' =>'MostViewed','Details' =>$view_detail['views_count']];
						}
					}
					if(request()->list=="Reservation"){
						$reservation_detail = $this->getReservation();
					 	if(count($reservation_detail['reservation'])>0){
             				$total_page = $reservation_detail['total_page'];
					    	$data[]   =['Title' => trans('messages.header.justbooked'),'Key' =>'Reservation','Details' =>$reservation_detail['reservation']];
					 	}
					}
					
             }
             else
             {
             	if(!request()->search_type || request()->search_type=="experience" || request()->search_type=="all"){
	             	$experiences = $this->getExperiences();
	               	if(count($experiences['host_experiences'])>0){
		            	$data[] = ['Title' =>trans('messages.api.host_experience'),'Key' =>'Experiences','Details' => $experiences['host_experiences']];
	               	}
	            }

	            if(!request()->search_type || request()->search_type=="stay" || request()->search_type=="all"){
	               	$view_detail = $this->getViewCount();
	             	$reservation_detail = $this->getReservation();
	               	if(count($view_detail['views_count']) >0) {
		             	$data[] = ['Title' => trans('messages.api.most_viewed'),'Key' =>'MostViewed','Details' =>$view_detail['views_count']];
	               	}
	               	if(count($reservation_detail['reservation'])>0) {
		              	$data[]   =['Title' => trans('messages.header.justbooked'),'Key' =>'Reservation','Details' =>$reservation_detail['reservation']];
	               	}	
	            }            
            }

			return response()->json(
			[
				'success_message' => trans('messages.api.success'),
				'status_code' => '1',
				'total_page' => $total_page,
				'Explore list'    => $explore_makent,    
				'Lists' =>  $data 
				]
			);
        
    }

  /**
     * Host experiences     
     */
    public function getExperiences() 
    {
             /*HostExperiencePHPCommentStart*/
             $request = request();

			$host_experiences = HostExperiences::where('status','listed')->whereHas('user', function ($query) {
				$query->where('status','Active');
			});

		if(isset($request->latitude) && isset($request->longitude))
		{
			$host_experiences = $host_experiences->whereHas('host_experience_location', function ($query) use ($request) {
				$query->select(DB::raw('*, ( 3959 * acos( cos( radians('.$request->latitude.') ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians('.$request->longitude.') ) + sin( radians('.$request->latitude.') ) * sin( radians( latitude ) ) ) ) as distance'))->having('distance', '<=', 5);
			});
		}elseif(isset($request->location)){
			$location = urldecode($request->location);
		    $location =  $this->findLocation($location);

		    $minLat=$location['minLat'];
            $maxLat=$location['maxLat'];
            $minLong=$location['minLong'];
            $maxLong=$location['maxLong'];

			$host_experiences = $host_experiences->whereHas('host_experience_location', function ($query) use ($minLat, $maxLat, $minLong, $maxLong) {
				$query->whereRaw("latitude between $minLat and $maxLat and longitude between $minLong and $maxLong");
			});
		}

	    $checkin = $request->checkin;
		$checkout = $request->checkout;

		$guest = isset($request->guests) > 0 ? $request->guests:1;		

		if ($checkin) {
			$checkin = date('Y-m-d', $this->helper->custom_strtotime($checkin, 'd-m-Y'));
			$checkout = date('Y-m-d', $this->helper->custom_strtotime($checkout, 'd-m-Y'));

			$days = $this->get_days($checkin, $checkout);

				$host_experiences = $host_experiences->daysAvailable($days,$guest);

		} else {

			$days = [];
		}
			
			if(isset($request->guests))
			$host_experiences = $host_experiences->where("number_of_guests",'>=',$guest);

			$host_experiences = $host_experiences->homePageFeatured()->latest();

			if(request()->list=="Experiences")
			{
					$host_experiences = $host_experiences->paginate(10);
					$total_page = $host_experiences->lastPage();
			}
			else{
				$host_experiences = $host_experiences->limit(4)->get();
				$total_page = 0;
			}

		

		$host_experiences =	$host_experiences->map(function ($min_listing) {
				return [ 

					'id' => $min_listing->id ,				
					'category' => $min_listing->category,
					'name' => $min_listing->title,
					'category_name' => $min_listing->category_details->name,
					'photo_name' =>$min_listing->photo_name ,
					'rating' =>$min_listing->overall_star_rating['rating_value'],
					'is_wishlist' =>$min_listing->overall_star_rating['is_wishlist'],
				    'reviews_count' =>$min_listing->reviews_count,
					'price' => $min_listing->session_price ,
					'currency_code' => $min_listing->currency->session_code,
				    'currency_symbol' => html_entity_decode($min_listing->currency->symbol),
					'city_name' => $min_listing->host_experience_location->city ?: $min_listing->host_experience_location->country_name,
					'country_name' =>$min_listing->host_experience_location->country_name,
                'latitude' =>$min_listing->host_experience_location->latitude,
                'longitude' =>$min_listing->host_experience_location->longitude,
                'type' =>'Experiences',
                'instant_book' => $min_listing->booking_type=='instant_book'? 'Yes' : 'No'

					
				];
			});

			return ['host_experiences'=>$host_experiences,'total_page'=>$total_page];

			/*HostExperiencePHPCommentEnd*/
    }

    /**
     * Reservation     
     */
        public function getReservation(){

        	$request = request();

            $reservation = Reservation::with([
            'rooms' => function ($query) {
            $query->with(['rooms_price']);
            }, 
            ])
            ->where('list_type', 'Rooms')          
            ->whereHas('host_users', function ($query) {
            $query->where('status', 'Active');
            });

         $guests = $request->guests;
         $not_available_room_ids = [];
     
	        if(isset($request->checkin))
	        {
				$checkin = date('Y-m-d', strtotime($request->checkin));

				$checkout = date('Y-m-d', strtotime($request->checkout));

				$days = $this->get_days($checkin, $checkout);

				$not_available_room_ids = Calendar::daysNotAvailable($days, $request->guests)->distinct()->pluck('room_id')->toArray();

	        }

			$reservation = $reservation->whereHas('rooms', function ($query) use ($guests,$request,$not_available_room_ids)  {
				$query->where('status',"Listed");

			if(isset($request->latitude) && isset($request->longitude)){
				$query->whereHas('rooms_address', function ($query) use ($request) {
					$query->select(DB::raw('*, ( 3959 * acos( cos( radians('.$request->latitude.') ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians('.$request->longitude.') ) + sin( radians('.$request->latitude.') ) * sin( radians( latitude ) ) ) ) as distance'))->having('distance', '<=', 5);
				});
			}elseif(isset($request->location)){
				$location = urldecode($request->location);
				$location =  $this->findLocation($location);
				$minLat=$location['minLat'];
				$maxLat=$location['maxLat'];
				$minLong=$location['minLong'];
				$maxLong=$location['maxLong'];
				$query->whereHas('rooms_address', function ($query) use ($minLat, $maxLat, $minLong, $maxLong,$not_available_room_ids) {
				$query->whereRaw("latitude between $minLat and $maxLat and longitude between $minLong and $maxLong");
				});
			}

			if(isset($request->guests))
			{
				$query->where('accommodates',$guests);
			}
			if(isset($request->checkin))
			{			
			     $query-> whereNotIn('id', $not_available_room_ids);
			}

			});


	         $reservation = $reservation->orderBy('id', 'desc')
	        ->where('status', 'Accepted')
	        ->groupBy('room_id');

	        if(request()->list=="Reservation")
	        {
	           $reservation = $reservation->paginate(10);
	           $total_page = $reservation->lastPage();
	        }
	        else{
	        	$reservation = $reservation->limit(4)->get();
	        	$total_page = 0;
	        }


            $reservation = $reservation->map(function ($min_listing) {

                return [  
                'id' => $min_listing->room_id ,                
                'name' => $min_listing->rooms->name ,    
                'category_name' => $min_listing->rooms->room_type_name ,                
                'bed_count' => $min_listing->rooms->beds ,                
                'photo_name' =>$min_listing->rooms->photo_name ,
                'rating' =>$min_listing->rooms->overall_star_rating['rating_value'],
                'is_wishlist' =>$min_listing->rooms->overall_star_rating['is_wishlist'],
                'reviews_count' =>$min_listing->rooms->reviews_count,
                'price' => $min_listing->rooms->rooms_price->night ,
                'currency_code' => $min_listing->rooms->rooms_price->code,
                'currency_symbol' => html_entity_decode($min_listing->rooms->rooms_price->currency->symbol),
                'country_name' =>$min_listing->rooms->rooms_address->country_name,
                'latitude' =>$min_listing->rooms->rooms_address->latitude,
                'longitude' =>$min_listing->rooms->rooms_address->longitude,
                'type' =>'Rooms',
                'instant_book' => $min_listing->rooms->booking_type=='instant_book'? 'Yes' : 'No',
                'city_name' => $min_listing->rooms->rooms_address->city!=''?$min_listing->rooms->rooms_address->city:$min_listing->rooms->rooms_address->country_name
                
                ];
            });

            return ['reservation'=>$reservation,'total_page'=>$total_page];

              }

    /**
     * Filter details   
     */
    public function filter($request) 
    {
       if(isset($request->location))
        {
				$location = urldecode($request->location);
               	$location =  $this->findLocation($location);
        }

           $not_available_room_ids = [];
           	

           	if (isset($request->checkin) && isset($request->checkin)) {

                $checkin = date('Y-m-d', strtotime($request->checkin));

                $checkout = date('Y-m-d', strtotime($request->checkout));
                
                $days = $this->get_days($checkin, $checkout);

                $not_available_room_ids = Calendar::daysNotAvailable($days, $request->guests)->distinct()->pluck('room_id')->toArray();

            }
                return [ 'not_available_room_ids' => $not_available_room_ids,
                         'minLat' =>@$location['minLat'],
                         'maxLat' =>@$location['maxLat'],
                         'minLong' =>@$location['minLong'],
                         'maxLong' =>@$location['maxLong']
                    ];

                }

     /**
     * Common map function  
     */

    public function commonMapFunction($common){

            return $common = $common->map(function ($min_listing) {

                return [  
                'id' => $min_listing->id ,                
                'name' => $min_listing->name ,        
                'category_name' => $min_listing->room_type_name ,                        
                'bed_count' => $min_listing->beds ,                        
                'photo_name' =>$min_listing->photo_name ,
                'rating' =>(string) $min_listing->overall_star_rating['rating_value'],
                'is_wishlist' =>$min_listing->overall_star_rating['is_wishlist'],
                'reviews_count' =>$min_listing->reviews_count,
                'price' => $min_listing->rooms_price->night ,
                'currency_code' => $min_listing->rooms_price->code,
                'currency_symbol' => html_entity_decode($min_listing->rooms_price->currency->symbol),
                'country_name' =>$min_listing->rooms_address->country_name,
                'latitude' =>$min_listing->rooms_address->latitude,
                'longitude' =>$min_listing->rooms_address->longitude,
                'type' =>'Rooms',
                'instant_book' => $min_listing->booking_type=='instant_book'? 'Yes' : 'No',
                'city_name' => $min_listing->rooms_address->city!=''?$min_listing->rooms_address->city:$min_listing->rooms_address->country_name
                ];
           });

    }

   public function findLocation($location){
    
			$address = str_replace([" ", "%2C"], ["+", ","], "$location");
			$geocode = @file_get_contents('https://maps.google.com/maps/api/geocode/json?key=' . $this->map_server_key . '&address=' . $address . '&sensor=false&libraries=places');

			$json = json_decode($geocode);


			if (@$json->{'results'}) {

			$data['viewport'] = $json->{'results'}[0]->{'geometry'}->{'viewport'};
			$minLat = $json->{'results'}[0]->{'geometry'}->{'viewport'}->{'southwest'}->{'lat'};
			$maxLat = $json->{'results'}[0]->{'geometry'}->{'viewport'}->{'northeast'}->{'lat'};
			$minLong = $json->{'results'}[0]->{'geometry'}->{'viewport'}->{'southwest'}->{'lng'};
			$maxLong = $json->{'results'}[0]->{'geometry'}->{'viewport'}->{'northeast'}->{'lng'};
			} 
			else
			{
				$minLat = -1000;
				$maxLat = 1000;
				$minLong = -1000;
				$maxLong = 1000;
			}

			 return [   'minLat' =>@$minLat,
                         'maxLat' =>@$maxLat,
                         'minLong' =>@$minLong,
                         'maxLong' =>@$maxLong
                    ];
 }


}
